<?php
/******************************************
****AuThor:rubbish.boy@163.com
****Title :用户
*******************************************/
namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

//使用User模型
use App\Http\Model\User;
use DB;
use Cache;
//使用URL生成地址
use URL;
use Hash;
class UserController extends PublicController
{
	/******************************************
	****AuThor:rubbish.boy@163.com
	****Title :列表
	*******************************************/
	public function index()  
	{
		$website=$this->website;
		$website['cursitename']=trans('admin.website_navigation_user');
		$website['apiurl_list']=URL::action('Admin\UserController@api_list');
		$website['apiurl_one_action']=URL::action('Admin\OneactionapiController@api_one_action');
		$website['link_set']=route('get.admin.user.set').'/';
		$website['way']='username';
		$wayoption[]=array('text'=>trans('admin.fieldname_item_username'),'value'=>'username');
		$wayoption[]=array('text'=>trans('admin.fieldname_item_email'),'value'=>'email');
		$wayoption[]=array('text'=>trans('admin.fieldname_item_mobile'),'value'=>'mobile');
		$wayoption[]=array('text'=>trans('admin.fieldname_item_nick'),'value'=>'nick');
		$website['wayoption']=json_encode($wayoption);
		$website['modelname']=getCurrentControllerName();

		return view('admin/user/index')->with('website',$website);
	}
	/******************************************
	****AuThor:rubbish.boy@163.com
	****Title :列表接口
	*******************************************/
	public function api_list(Request $request)  
	{
		$search_field=$request->get('way')?$request->get('way'):'title';
		$keyword=$request->get('keyword');
		if($keyword)
		{
			$list=User::join('userinfos', 'userinfos.user_id', '=', 'users.id')->where('users.id', '>', 0)->where($search_field, 'like', '%'.$keyword.'%')->paginate($this->pagesize);
			//分页传参数
			$list->appends(['keyword' => $keyword,'way' =>$search_field])->links();
		}
		else
		{
			$list=User::join('userinfos', 'userinfos.user_id', '=', 'users.id')->where('users.id', '>', 0)->paginate($this->pagesize);
			
		}

		if($list)
		{
			$msg_array['status']='1';
			$msg_array['info']=trans('admin.message_get_success');
			$msg_array['is_reload']=0;
			$msg_array['curl']='';
			$msg_array['resource']=$list;
			$msg_array['param_way']=$search_field;
			$msg_array['param_keyword']=$keyword;
		}
		else
		{
			$msg_array['status']='1';
			$msg_array['info']=trans('admin.message_get_empty');
			$msg_array['is_reload']=0;
			$msg_array['curl']='';
			$msg_array['resource']="";
			$msg_array['param_way']=$search_field;
			$msg_array['param_keyword']=$keyword;
		}
        return response()->json($msg_array);
	}
	/******************************************
	****AuThor:rubbish.boy@163.com
	****Title :详情接口
	*******************************************/
	public function api_info(Request $request)  
	{

		$condition['user_id']=$request->get('id');
		$info=DB::table('userinfos')->where($condition)->first();
		if($info)
		{
			$msg_array['status']='1';
			$msg_array['info']=trans('admin.message_get_success');
			$msg_array['is_reload']=0;
			$msg_array['curl']='';
			$msg_array['resource']=$info;
			$msg_array['param_way']='';
			$msg_array['param_keyword']='';
		}
		else
		{
			$msg_array['status']='0';
			$msg_array['info']=trans('admin.message_get_empty');
			$msg_array['is_reload']=0;
			$msg_array['curl']='';
			$msg_array['resource']="";
			$msg_array['param_way']='';
			$msg_array['param_keyword']='';
		}
        return response()->json($msg_array);
	}
	/******************************************
	****@AuThor : rubbish.boy@163.com
	****@Title  : 更新数据接口
	****@return : Response
	*******************************************/
	public function api_edit(Request $request)
	{
		$condition['user_id']=$request->get('id');

		$params['name']=$request->get('name');
		$params['nick']=$request->get('nick');
		$params['sex']=$request->get('sex');
		$params['birthday']=$request->get('birthday');
		$params['qq']=$request->get('qq');
		$params['area_pid']=$request->get('area_pid');
		$params['area_cid']=$request->get('area_cid');
		$params['area_xid']=$request->get('area_xid');

		//图片上传处理接口
		$attachment='attachment';
		$data_image=$request->get($attachment);
		if($data_image)
		{
			//上传文件归类：获取控制器名称
			$classname=getCurrentControllerName();
			$params['attachment']=$this->uploads_action($classname,$data_image);
			$params['isattach']=1;
		}


		$info=DB::table('userinfos')->where($condition)->update($params);
		if ($info) 
		{
			$cache_userinfo='userinfo_'.$this->user['id'];
			$userinfo=User::find($this->user['id'])->hasOneUserinfo;
			$minutes=3600;
			Cache::store('file')->put($cache_userinfo, $userinfo, $minutes);

			$msg_array['status']='1';
			$msg_array['info']=trans('admin.message_save_success');
			$msg_array['is_reload']=0;
			$msg_array['curl']=URL::action('Admin\UserController@index');
			$msg_array['resource']='';
			$msg_array['param_way']='';
			$msg_array['param_keyword']='';
		} 
		else 
		{
			$msg_array['status']='0';
			$msg_array['info']=trans('admin.message_save_exit');
			$msg_array['is_reload']=0;
			$msg_array['curl']='';
			$msg_array['resource']="";
			$msg_array['param_way']='';
			$msg_array['param_keyword']='';	
		}
		return response()->json($msg_array);
	}
	/******************************************
	****@AuThor : rubbish.boy@163.com
	****@Title  : 修改密码接口
	****@return : Response
	*******************************************/
	public function api_edit_pwd(Request $request)
	{
		$condition['id']=$request->get('id');
		$user = User::findOrFail($request->get('id'));
		$oldpwd  = $request->get('oldpwd');
		$newpwd  = $request->get('newpwd');
		$surepwd = $request->get('surepwd');
		
		$check=Hash::check($oldpwd, $user->password);
		if($check)
		{
			if($newpwd!=$surepwd)
			{
				$msg_array['status']='0';
				$msg_array['info']=trans('admin.option_editpwd_surefailure');
				$msg_array['is_reload']=0;
				$msg_array['curl']='';
				$msg_array['resource']="";
				$msg_array['param_way']='';
				$msg_array['param_keyword']='';	
			}
			else if (strlen($newpwd)<6) 
			{
				$msg_array['status']='0';
				$msg_array['info']=trans('admin.option_editpwd_failurelength');
				$msg_array['is_reload']=0;
				$msg_array['curl']='';
				$msg_array['resource']="";
				$msg_array['param_way']='';
				$msg_array['param_keyword']='';
			}
			else
			{
				$params['password']=Hash::make($newpwd);
				$params['remember_token']="";

				$result=$user->fill($params)->save();
				
				if ($result) 
				{
					$msg_array['status']='1';
					$msg_array['info']=trans('admin.message_edit_success');
					$msg_array['is_reload']=0;
					$msg_array['curl']=URL::action('User\LoginController@logout');
					$msg_array['resource']='';
					$msg_array['param_way']='';
					$msg_array['param_keyword']='';
				} 
				else 
				{
					$msg_array['status']='0';
					$msg_array['info']=trans('admin.message_edit_failure');
					$msg_array['is_reload']=0;
					$msg_array['curl']='';
					$msg_array['resource']="";
					$msg_array['param_way']='';
					$msg_array['param_keyword']='';	
				}
			}
		}
		else
		{
			$msg_array['status']='0';
			$msg_array['info']=trans('admin.option_editpwd_failure');
			$msg_array['is_reload']=0;
			$msg_array['curl']='';
			$msg_array['resource']='';
			$msg_array['param_way']='';
			$msg_array['param_keyword']='';	
		}

		return response()->json($msg_array);
	}
	/******************************************
	****AuThor : rubbish.boy@163.com
	****Title  : 设置
	*******************************************/
	public function set($id)  
	{
		$website=$this->website;
		$website['cursitename']=trans('admin.website_action_set_role');

		$website['apiurl_list']=URL::action('Admin\UserroleController@api_list_related');
		$website['apiurl_get']=URL::action('Admin\UserroleController@api_get_role');
		$website['apiurl_cancel']=URL::action('Admin\UserroleController@api_cancel_role');
		$website['way']='name';
		$wayoption[]=array('text'=>trans('admin.fieldname_item_keyval'),'value'=>'name');
		$wayoption[]=array('text'=>trans('admin.fieldname_item_name'),'value'=>'display_name');
		$wayoption[]=array('text'=>trans('admin.fieldname_item_description'),'value'=>'description');
		$website['wayoption']=json_encode($wayoption);
		$website['id']=$id;
		$condition['user_id']=$id;
		$info=object_array(DB::table('userinfos')->where($condition)->first());
		$website['info']=$info;
		return view('admin/user/set')->with('website',$website);
	}
	/******************************************
	****AuThor:rubbish.boy@163.com
	****Title :用户资料
	*******************************************/
	public function userinfo()  
	{
		$website=$this->website;
		$website['modelname']=getCurrentControllerName();
		$website['cursitename']=trans('admin.website_navigation_userinfo');
		$website['apiurl_info']=URL::action('Admin\UserController@api_info');
		$website['apiurl_edit']=URL::action('Admin\UserController@api_edit');
		$website['apiurl_area']=URL::action('Admin\DistrictController@api_area');
		$website['apiurl_del_image']=URL::action('Admin\DeleteapiController@api_del_image');
		$area_data_p[]=array('id'=>0,'name'=>trans('admin.option_select_p'),'alias'=>trans('admin.option_select_p'));
		$area_data_c[]=array('id'=>0,'name'=>trans('admin.option_select_c'),'alias'=>trans('admin.option_select_c'));
		$area_data_x[]=array('id'=>0,'name'=>trans('admin.option_select_x'),'alias'=>trans('admin.option_select_x'));
		$website['area_data_p']=json_encode($area_data_p);
		$website['area_data_c']=json_encode($area_data_c);
		$website['area_data_x']=json_encode($area_data_x);

		$website['id']=$this->user['id'];

		return view('admin/user/userinfo')->with('website',$website);
	}
	/******************************************
	****AuThor:rubbish.boy@163.com
	****Title :修改密码
	*******************************************/
	public function edit_pwd()  
	{
		$website=$this->website;
		$website['cursitename']=trans('admin.define_model_user_editpwd');
		$website['apiurl_edit_pwd']=URL::action('Admin\UserController@api_edit_pwd');
		$website['id']=$this->user['id'];
		
		return view('admin/user/edit_pwd')->with('website',$website);
	}
}


# laravelcms V1.5.0
基于 laravel5.2 的PHP内容管理系统  后台基于AdminLTE主题  前端组件Vue.js 集成基础的微信相关功能。是一个可以快速上手，项目开发的首选工具。

作者:rubbish.boy@163.com
QQ	:471416739

#	使用了laravel哪些扩展包？ composer.json
1. zizaco/entrust 权限验证
2. predis/predis redis-php扩展包
3. overtrue/laravel-lang laravel多个国家的语言包
4. gregwar/captcha 验证码类库
5. barryvdh/laravel-debugbar 调式Debug插件
6. intervention/image 图片处理类库
7. yuanchao/laravel-5-markdown-editor 文本编辑器
8. stevenyangecho/laravel-u-editor 百度富文本编辑器
9. workerman/phpsocket.io,workerman/workerman  目前开发用的是win版本, 压缩包是linux版本
10. overtrue/wechat: ~3.1	微信扩展包

#	laravel使用了哪些前端组件？ bower.json

	前端组件默认安装存放目录：Public/module
	"AdminLTE": "admin-lte#^2.3.6"
    "jquery": "1.11.0"
    "vue": "^1.0.26"
    "jquery-form": "good-form#*"
    "vue-resource": "^1.0.2"

#	laravel使用了哪些前端组件？ 非包管理
	layer-v2.4 弹层组件
	error 错误页面模板
	login 用户登录,用户注册模板
	DateTimePicker 时间日期控件 兼容移动端
	moment 时间处理控件
	socket.io-client-1.3.7 web通信
	
	public/js/common.js 公共函数

#	使用手册
1. 如果需要设置 SESSION_DRIVER=redis 那么需要启动 redis-server.exe  默认为file

2. window环境手动运行项目环境下的start_for_win.bat

3. linux环境随服务器启动自动运行start_io.php（必要）  start_web.php（不可必要）

4. 获取项目文件：git clone 或者 github 上下载压缩包

5. 使用命令行 建立数据库魔法
    php artisan migrate
	
6. 填充网站初始数据(由于地区数据比较多，生成比较慢，预计时间5-10分钟内)	
		1 php artisan db:seed
		
		2 php artisan db:seed --class=UserSeeder		 账号默认密码:111111
		
		3 php artisan db:seed --class=RoleSeeder
		
		4 php artisan db:seed --class=PermissionSeeder
		
		5 php artisan db:seed --class=DistrictSeeder	 数据较大
		
7. 开始体验
	[DEMO](http://demo.dc918.com)
	
#	开发进展

	修改密码 
	系统设置
	用户角色
	角色权限
	用户管理
	用户资料
	主导航栏
	资讯管理 
	-文章分类
	-文章资讯
	-文章标签	下一个版本内容
	产品管理	
	-产品分类	
	-产品内容	
	图片管理 
	链接管理 
	-链接分类
	-友情链接
	日志管理 
	信件管理 
	用户头像 
	题库管理	
	-题库分类	
	 -题库类型
	 -单选题
	 -多选题
	 -判断题
	微信管理 	
	>公众号管理 
	>关注回复	
	>默认回复	
	>文本回复	 
	>图文回复	 
	>微信菜单	 
	>粉丝列表	 
	>渠道二维码	下一个版本内容 
	>优惠券		下一个版本内容
	>大转盘		下一个版本内容 
	>摇一摇		下一个版本内容 
	消息管理 	下一个版本内容
	第三方登录  下一个版本内容
	单点登录    下一个版本内容
	

#	注意事项
	请谨慎使用“composer update” 全部更新操作 会重置一些组件的默认配置设置
	可以使用“composer update vender/.....” 选择性更新安装扩展
	
#	打赏小二
![image](https://github.com/q1082121/laravelcms/blob/master/public/images/alipay.jpg)

#	示例图片
![image](https://github.com/q1082121/laravelcms/blob/master/public/images/home/demo/1.png)
![image](https://github.com/q1082121/laravelcms/blob/master/public/images/home/demo/2.png)
![image](https://github.com/q1082121/laravelcms/blob/master/public/images/home/demo/3.png)
![image](https://github.com/q1082121/laravelcms/blob/master/public/images/home/demo/4.png)
![image](https://github.com/q1082121/laravelcms/blob/master/public/images/home/demo/5.png)
![image](https://github.com/q1082121/laravelcms/blob/master/public/images/home/demo/6.png)
![image](https://github.com/q1082121/laravelcms/blob/master/public/images/home/demo/7.png)
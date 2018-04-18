<?php
	function lang($phrase){
		static $lang = array(
			'hello' => 'مرحبا',
			'Settings'=>'إعدادات',
			'Logout'=>'تسجيل الخروج',
			'login_to_app'=>'تسجيل الدخول',
			'app_title'=>'تطبيق إدارة الشاحنات',
			'intro_user'=>'تعريف المستخدم',
			'login_error'=>'لقد أدخلت كلمة مرور خاطئة أو إسم مستخدم غير صحيح',
			'login'=>'تسجيل الدخول',
			'username'=>'إسم المستخدم',
			'user_password'=>'كلمة السر',
		);
		return $lang[$phrase];
	}
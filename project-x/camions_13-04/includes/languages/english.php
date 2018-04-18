<?php
	function lang($phrase){
		static $lang = array(
			// Navbar
			'Home' => 'Home',
			'Categories' => 'Categories',
			'link' => 'link',
			'Edit_Profile' => 'Edit Profile',
			'Settings' => 'Settings',
			'Logout' => 'Logout',
			'items' => 'items',
			'members' => 'members',
			'statistics' => 'statistics',
			'logs' => 'logs'
			//--------------
		);
		return $lang[$phrase];
	}
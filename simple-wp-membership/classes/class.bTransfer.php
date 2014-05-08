<?php
class BTransfer {
	public static $default_fields = array(
		'first_name'=>'','last_name'=>'',
		'user_name'=>'','email'=>'',
		'password'=>'',
		'phone'=>'','account_state'=>'',
		'member_since'=>'','subscription_starts'=>'',
		'address_street'=>'','address_city'=>'',
		'address_state'=>'','address_zipcode'=>'',
		'company_name'=>'','country'=>'',
		'membership_level'=>'2');
	public static $default_level_fields = array(
		'alias'=>'','role'=>'',
		'subscription_period'=>'','subscription_unit'=>'days');

	public static $admin_messages = array();	
	private static $_this;
	private $message;
	private function __contruct(){
		$this->message = get_option('swpm-messages');
	}
	public static function get_instance(){
		self::$_this = empty(self::$_this)? new BTransfer():self::$_this;
		self::$_this->message = get_option('swpm-messages');
        return self::$_this;
	}
	public function get($key){
		$m = isset($this->message[$key])? $this->message[$key]: "";
		$this->message[$key] = "";
		update_option('swpm-messages', $this->message);
		return $m;
	}
	public function set($key, $value){
		$this->message[$key] = $value;		
		update_option('swpm-messages', $this->message);
	}
	public static function get_real_ip_addr(){
		if (!empty($_SERVER['HTTP_CLIENT_IP']))   
			$ip=$_SERVER['HTTP_CLIENT_IP'];
		else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   
			$ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
		else
			$ip=$_SERVER['REMOTE_ADDR'];
			
		return $ip;
	}	
}
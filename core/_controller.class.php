<?php
defined('BASEURL') OR die("HAYOoo.. mau ngapain? (-_- ')");
#[AllowDynamicProperties]
class _Controller{
	private static $instance;
	function __construct(){
		self::$instance =& $this;
		
		// load core class
		foreach (loaded_class() as $name => $class)
		{	
			$this->$name = cekClass(strtolower($name));
		}
	}

	public static function &get_instance(){
		return self::$instance;
	}

}
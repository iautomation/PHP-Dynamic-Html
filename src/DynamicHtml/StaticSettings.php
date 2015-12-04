<?php

namespace DynamicHtml;

class StaticSettings {
	public static $echo = true;

	protected static function isVisibleSetting($name){
		$class = get_called_class();
		return isset($class::$$name);
	}
	public static function setSetting($name, $value){
		if(self::isVisibleSetting($name))
			static::$$name = $value;
	}
	public static function setSettings($ar=[]){
		foreach($ar as $name=>$value)
			self::setSetting($name, $value);
	}
	public static function getSetting($name){
		if(self::isVisibleSetting($name))
			return static::$$name;
		return null;
	}
}
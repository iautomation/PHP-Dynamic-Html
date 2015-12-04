<?php

namespace DynamicHtml;

class DynamicTags extends StaticSettings {
	public static $indent_char = "\t";
	public static $indent = true;
	public static $echo = true;
	protected static $tag_log = [];

	public static function createTag($name, $attr=[], $shorttag=false){
		if(!$shorttag)self::addTagToLog($name);
		return self::createStartTag($name, $attr);
	}
	public static function createStartTag($name, $attr=[]){
		$pieces = [$name, self::attrArrayToStr($attr)];
		return self::output('<'.implode(' ', array_filter($pieces)).'>');
	}
	public static function createEndTag($name){
		return self::output('</'.$name.'>');
	}

	public static function addTagToLog($name){
		self::$tag_log[] = $name;
	}
	public static function popTagFromLog(){
		return array_pop(self::$tag_log);
	}

	public static function end($amount=1){
		$output = '';
		if($amount>count(self::$tag_log))$amount = count(self::$tag_log);
		for($i=$amount-1; $i>=0; $i--)
			$output .= self::createEndTag(self::popTagFromLog());
		return $output;
	}
	public static function endAll(){
		return self::end(count(self::$tag_log));
	}

	public static function attrArrayToStr($attr){
		$output = '';
		foreach($attr as $key=>$value){
			$value = str_replace('"', '\\"', $value);
			$output .= (' '.$key.'="'.$value.'"');
		}
		return trim($output);
	}

	public static function output($str){
		if(!self::$echo)return $str;
		echo $str;
	}
}
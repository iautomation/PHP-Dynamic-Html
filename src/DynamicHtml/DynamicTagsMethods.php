<?php

namespace DynamicHtml;

class DynamicTagsMethods extends DynamicTags {
	public static function base($href, $attr=[]){
		$attr = array_replace(compact('href'), $attr);
		return DynamicTags::createTag(__FUNCTION__, $attr, false);
	}
	public static function br($attr=[]){
		return DynamicTags::createTag(__FUNCTION__, $attr, false);
	}
	public static function hr($attr=[]){
		return DynamicTags::createTag(__FUNCTION__, $attr, false);
	}
	public static function input($type, $name, $attr=[]){
		$attr = array_replace(compact('type', 'name'), $attr);
		return DynamicTags::createTag(__FUNCTION__, $attr, false);
	}
	public static function link($href, $attr=[]){
		$attr = array_replace(['rel'=>'stylesheet', 'type'=>'text/css'], compact('href'), $attr);
		return DynamicTags::createTag(__FUNCTION__, $attr, false);
	}
	public static function meta($attr=[]){
		return DynamicTags::createTag(__FUNCTION__, $attr, false);
	}
	public static function a($href, $attr=[], $content=true){
		if(is_string($attr)){
			$content = $attr;
			$attr = [];
		}
		$attr = array_replace(compact('href'), $attr);
		return DynamicTags::createTag(__FUNCTION__, $attr, $content);
	}
	public static function style($attr=[], $content=true){
		if(is_string($attr)){
			$content = $attr;
			$attr = [];
		}
		$attr = array_replace(['type'=>'text/css'], $attr);
		return DynamicTags::createTag(__FUNCTION__, $attr, $content);
	}
	public static function script($src, $attr=[], $content=true){
		if(is_string($attr)){
			$content = $attr;
			$attr = [];
		}
		$attr = array_replace(['type'=>'text/css'], compact('src'), $attr);
		return DynamicTags::createTag(__FUNCTION__, $attr, $content);
	}
}
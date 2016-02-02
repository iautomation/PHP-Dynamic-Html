<?php

namespace DynamicHtml;

/**
 * This is a PHP library that dynamically creates html tags.
 *
 * Buy me ramen(Paypal)! manualpayments@gmail.com
 *
 * @link       http://iautomation.us/
 * @package    DynamicHtml
 * @license    http://opensource.org/licenses/gpl-license.php  GNU Public License
 * @author     Joshua McKenzie <josh@ia.lc>
 */
class DynamicTags {
	protected static $tag_log = [];

	/**
	 * Creates a tag or tag set, in some cases logging for futher usage.
	 * If $content is a string, the content and ending tag will be returned as well as the starting tag.
	 * If $content is true, only the starting tag will be returned, but the ending tag will be logged for later.
	 * if $content is false|null, the element is a shorttag element, and no ending tag will be provided.
	 *
	 * Examples:
	 * create('div', 'id=main&class=inner');
	 * create('span', ['data-author', 'john doe'], 'John Doe');
	 * create('input', null, null);
	 * 
	 * @param  string         $name    tag name; e.g. div
	 * @param  array|string   $attr    tag attributes; formatted as array or query string
	 * @param  boolean|string $content content of element or indicator. read description for full more info
	 * @return string                  returns tag or tag set
	 */
	public static function create($name, $attr=null, $content=true){
		$output = '';
		$shorttag = $content==false || is_null($content);
		$output .= self::createStartTag($name, $attr);
		if(!$shorttag)self::addTagToLog($name);
		if(is_string($content)){
			$output .= $content;
			$output .= self::end();
		}
		return $output;
	}

	/**
	 * Ends tag set(s)
	 *
	 * Examples:
	 * end(3);
	 * 
	 * @param  integer $amount amount of tags to end
	 * @return string          ending tag(s) data
	 */
	public static function end($amount=1){
		$output = '';
		if($amount>count(self::$tag_log))$amount = count(self::$tag_log);
		for($i=$amount-1; $i>=0; $i--)
			$output .= self::createEndTag(self::removeTagFromLog());
		return $output;
	}
	/**
	 * Ends all tag sets
	 *
	 * Examples:
	 * endAll();
	 * 
	 * @return string ending tag(s) data
	 */
	public static function endAll(){
		return self::end(count(self::$tag_log));
	}

	/**
	 * Creates a start tag
	 *
	 * Examples:
	 * createStartTag('div', 'id=test123');
	 * 
	 * @param  string         $name    tag name; e.g. div
	 * @param  array|string   $attr    tag attributes; formatted as array or query string
	 * @return string         start tag data
	 */
	public static function createStartTag($name, $attr=null){
		$pieces = [$name, self::createAttrString($attr)];
		$pieces_string = implode(' ', array_filter($pieces));
		return ('<'.$pieces_string.'>');
	}
	/**
	 * Creates an end tag
	 *
	 * Examples:
	 * createEndTag('div');
	 * 
	 * @param  string $name    tag name; e.g. div
	 * @return string end tag data
	 */
	public static function createEndTag($name){
		return ('</'.$name.'>');
	}

	/**
	 * Adds a tag to the log to end later
	 *
	 * Examples:
	 * addTagToLog('div');
	 * 
	 * @param string $name tag name
	 */
	public static function addTagToLog($name){
		self::$tag_log[] = $name;
	}
	/**
	 * Removes and returns one tag from the tag log
	 *
	 * Examples:
	 * removeTagFromLog();
	 * 
	 * @return string tag name
	 */
	public static function removeTagFromLog(){
		return array_pop(self::$tag_log);
	}

	/**
	 * Interprets and merges input params into one array, which will later be used for attributes
	 * Later params will override earlier ones
	 * 
	 * Examples:
	 * mergeAttr(['id'=>'overridden'], 'id=overrides', 'id=overridesonceagain');
	 * 
	 * @return array merged arrays
	 */
	public static function mergeAttr(){
		$all_attr = func_get_args();
		$all_attr = array_filter($all_attr);
		foreach($all_attr as $x=>$attr){
			if(is_string($attr))parse_str($attr, $all_attr[$x]);
		}
		return call_user_func_array('array_replace', $all_attr);
	}
	/**
	 * Creates a tag attribute string from array or param string
	 * @param  array|string $attr tag attributes; array or param string
	 *
	 * Examples:
	 * createAttributeString('id=test&class=test2');
	 * 
	 * @return string             tag attribute string
	 */
	public static function createAttrString($attr=null){
		$output = '';
		if(is_string($attr))parse_str($attr, $attr);
		if(is_null($attr))return '';
		foreach($attr as $key=>$value){
			$value = str_replace('"', '\\"', $value);
			if(is_null($value) or empty($value))$output .= (' '.$key);
			else $output .= (' '.$key.'="'.$value.'"');
		}
		return trim($output);
	}

	/**
	 * Maps a tag name along with an array of arrays, each representing a column of each param
	 * If the columns are uneven, the largest wins the count. Undefined values will produce as null
	 *
	 * Note: This function also works on extend classes. So hack away
	 *
	 * Examples:
	 * mapColsArr('div', [
	 *   ['id=one', 'id=two'],
	 *   ['content of one', 'content of two']
	 * ]);
	 * 
	 * @param  string $method  tag name
	 * @param  array  $columns array of arrays, each being a column of the function param
	 * @return string          result of all map calls
	 */
	public static function mapColsArr($method, $columns){
		$output = '';
		$max_count = max(array_map(function($value){ return count($value); }, $columns));
		for($i=0; $i<$max_count; $i++){
			$args = [];
			foreach($columns as $column){
				$args[] = isset($column[$i]) ? $column[$i] : null;
			}
			$output .= call_user_func_array(get_called_class().'::'.$method, $args);
		}
		return $output;
	}
	/**
	 * See mapColsArr. This function will pack all additional arguments into an array and call mapColsArr()
	 *
	 * Examples:
	 * mapCols(
	 *   'div',
	 *   ['id=one', 'id=two'],
	 *   ['content of one', 'content of two']
	 * );
	 * 
	 * @param  string $method  tag name
	 * @return string          result of all map calls
	 */
	public static function mapCols($method){
		$args = func_get_args();
		array_shift($args);
		return self::mapColsArr($method, $args);
	}
	/**
	 * Maps a tag name along with an array of arrays, each representing a *row* of params
	 *
	 * Note: This function also works on extend classes. So again, hack away
	 *
	 * Examples:
	 * mapRowsArr('div', [
	 *   ['id=one', 'content of one'],
	 *   ['id=two', 'content of two']
	 * ]);
	 * 
	 * @param  string $method  tag name
	 * @param  array  $columns array of arrays, each being a *row* of the function params
	 * @return string          result of all map calls
	 */
	public static function mapRowsArr($method, $rows){
		$output = '';
		foreach($rows as $row){
			$output .= call_user_func_array(get_called_class().'::'.$method, $row);
		}
		return $output;
	}
	/**
	 * See mapRowsArr. This function will pack all additional arguments into an array and call mapRowsArr()
	 *
	 * Examples:
	 * mapRows(
	 *   'div',
	 *   ['id=one', 'content of one'],
	 *   ['id=two', 'content of two']
	 * );
	 * 
	 * @param  string $method  tag name
	 * @return string          result of all map calls
	 */
	public static function mapRows($method){
		$args = func_get_args();
		array_shift($args);
		return self::mapRowsArr($method, $args);
	}

	/**
	 * Calls create().
	 * Unless defined as a function, all tags will be created via this magic method.
	 * @param  string $method tag name
	 * @param  array  $args   arguments for the create function
	 * @return string         result of the create function
	 */
	public static function __callStatic($method, $args){
		$args = array_merge([$method], $args);
		return call_user_func_array('self::create', $args);
	}
}

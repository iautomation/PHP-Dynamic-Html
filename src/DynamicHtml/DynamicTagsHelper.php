<?php

namespace DynamicHtml;

/**
 * A few helper methods for DynamicTags. A showcase example of what the class can do
 * Calling an undefined method will call DynamicTags::__callStatic, which in turn will
 * create the tag for you. For more info, see the DyanamicTags class.
 *
 * Buy me ramen(Paypal)! manualpayments@gmail.com
 *
 * @link       http://iautomation.us/
 * @package    DynamicHtml
 * @license    http://opensource.org/licenses/gpl-license.php  GNU Public License
 * @author     Joshua McKenzie <josh@ia.lc>
 */
class DynamicTagsHelper extends DynamicTags {
	/**
	 * Creates a link tag for a css stylesheet, and provides shortcut params for the href attribute
	 * A very simple example of how DynamicTags can be extended.
	 * Without this function, we would have to type: `DynamicTags::link("rel=stylesheet&type=text/css&href=$href")` or simular
	 * With this we can type as the example below
	 *
	 * Examples:
	 * stylesheet('style.css');
	 * stylesheet('style.less', 'rel=stylesheet/less');
	 * 
	 * @param  string       $href href attribute
	 * @param  array|string $attr tag attributes; formatted as array or query string
	 * @return string             tag html
	 */
	public static function stylesheet($href, $attr=null){
		$attr = self::mergeAttr(['rel'=>'stylesheet', 'type'=>'text/css'], compact('href'), $attr);
		return self::create(__FUNCTION__, $attr, false);
	}
	/**
	 * Creates a hyperlink, providing a shortcut to defining the href. Another good example.
	 * One might wonder why $attr is the second parameter, when the common case is that $href
	 * and $content will be defined. I did it this way to stay uniform. Attributes first,
	 * content last. Rememeber, if $content stays as true, then an ending tag will not be provided
	 * until DynamicTags::end() is called.
	 *
	 * Examples:
	 * hyperlink('page1.php', null, 'Page 1');
	 * 
	 * @param  string       $href    href attribute
	 * @param  array|string $attr    tag attributes; formatted as array or query string
	 * @param  bool|string  $content see DynamicTags::createTag() for more info
	 * @return string                tag html
	 */
	public static function hyperlink($href, $attr=null, $content=true){
		$attr = self::mergeAttr(compact('href'), $attr);
		return self::create('a', $attr, $content);
	}

	/**
	 * Lastly, an example that works with other methods. This function builds a table(obviously).
	 * 
	 * @param  array  $body_rows   array of arrays, representing the row fields
	 * @param  array  $head_fields array, representing the header row fields
	 * @return string              tag html
	 */
	public static function table($body_rows, $head_fields=null){
		$output = '';
		$output .= parent::table();
		if(!is_null($head_fields))
			$output .= self::tableHead($head_fields);
		$output .= self::tableBody($body_rows);
		$output .= self::end();
		return $output;
	}
	public static function tableHead($fields){
		return self::thead().self::tableHeadRow($fields).self::end();
	}
	public static function tableHeadRow($fields){
		return self::tr().self::mapCols('th', null, $fields).self::end();
	}
	public static function tableBody($rows){
		return self::tbody().self::mapCols('tableBodyRow', $rows).self::end();
	}
	public static function tableBodyRow($fields){
		return self::tr().self::mapCols('td', null, $fields).self::end();
	}
}


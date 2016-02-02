<?php

namespace DynamicHtml;

/**
 * Methods for DynamicTags. A showcase example of what the class can do
 *
 * Buy me ramen(Paypal)! manualpayments@gmail.com
 *
 * @link       http://iautomation.us/
 * @package    DynamicHtml
 * @license    http://opensource.org/licenses/gpl-license.php  GNU Public License
 * @author     Joshua McKenzie <josh@ia.lc>
 */
class DynamicTagsShort extends DynamicTags {
	/**
	 * Calls DynamicTags::create() with short tag as default
	 *
	 * Examples:
	 * hr();
	 * br('class=clearboth');
	 * 
	 * @param  string $method tag name
	 * @param  array  $args   arguments for the create function
	 * @return string         result of the create function
	 */
	public static function __callStatic($method, $args){
		$args = array_merge([$method], $args);
		$args[2] = false;
		return call_user_func_array('self::create', $args);
	}
}

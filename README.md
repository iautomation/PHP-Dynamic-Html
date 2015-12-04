#PHP-Dynamic-Html
An extensible and dynamic HTML helper library for PHP

## Introduction
This library, and it's classes, are fairly straight-forward. The encouragement is to extend with your own tags/methods, for others to use, and for your own projects.

The initial motivation was to further divide server-side code from client-side code. A furthering motivation was to provide an extensible interface for script-to-HTML mapping in the most basic of forms.

## Example Usage

### Basic Examples
```php
T::body(['class'=>'page1']); // body start tag, pass attributes
	T::h1('My Heading'); // string replaces attributes argument, thus auto closing the tag
	T::main(); // main start tag
		echo 'Foo Bar';
T::endAll(); // end main & body
```
The last line can also be written as:
```php
T::end(2);
```
The `end` method may be used to end any number of tags, defaulting to 1

#### What if we don't want it to automatically echo?
Fortunately, we have a setting for this:
`T::setSetting('echo', 0);`
After that, content is returned instead of echoed

One more example, using the provided DynamicTagsMethods class(see DynamicTagsMethods.php for all supported methods)
```php
use DynamicHtml\DynamicTags as T;
use DynamicHtml\DynamicTagsMethods as TM;

TM::a('/foo/', 'bar'); // <a href="/foo/">bar</a>
TM::a('/foo/', ['id'=>'baz'], 'baz'); // <a href="/foo/" id="baz">baz</a>
```

### Extending example
```php
use DynamicHtml\DynamicTags as T;
use DynamicHtml\DynamicTagsMethods;

class TM extends DynamicTagsMethods {
	public static function hyperlink($href, $content, $attr=[]){
		return self::a($href, $attr).self::output($content).self::end();
	}
}

TM::hyperlink('test123', 'test123'); // <a href="test123">test123</a>
```
You might wonder why we wrapped the `$content` variable with the `output` method, but not the other. This is to support echoing or not. It's up to output() to make the decision to echo, depending on the setting. The methods, however, already call output(), so we don't need it there.

### Complex Extending Example
```php
use DynamicHtml\DynamicTags as T;
use DynamicHtml\DynamicTagsMethods;


class TableHelper extends DynamicTagsMethods {
	public static function table($body_fields, $head_fields){
		return parent::table().self::head($head_fields).self::body($body_fields).parent::end();
	}
	public static function head($fields){
		return parent::thead().self::headRow($fields).parent::end();
	}
	public static function headRow($fields){
		return array_reduce($fields, function($fields_html, $field){
			return $fields_html.parent::th().parent::output($field).parent::end();
		});
	}
	public static function body($rows){
		return parent::tbody().self::bodyRows($rows).parent::end();
	}
	public static function bodyRows($rows){
		return array_reduce($rows, function($rows_html, $fields){
			return $rows_html.self::bodyRow($fields);
		});
	}
	public static function bodyRow($fields){
		return array_reduce($fields, function($fields_html, $field){
			return $fields_html.parent::td().parent::output($field).parent::end();
		});
	}
}

TableHelper::table(
	[
		['asdf', 'adsfdf']
	],
	['1', '2']
);
```

## DynamicTags Methods
```
createTag($name, $attr=[], $content=true)

end($amount=1)
endAll()

output($str)

__callStatic($name, $args)
```

## DynamicTagsMethods Methods
```
base($href, $attr=[])
br($attr=[])
hr($attr=[])
input($type, $name, $attr=[])
link($href, $attr=[])
meta($attr=[])
a($href, $attr=[], $content=true)
style($attr=[], $content=true)
script($src, $attr=[], $content=true)
```

## Installation
### Via Composer
```
composer require iautomation/dynamic-html
```
and
```
include './vendor/autoload.php'
```
Otherwise, simply include the class files
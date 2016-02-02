#PHP-Dynamic-Html
An extensible and dynamic HTML helper library for PHP

## Update
The new version includes some cleaner approaches and more logical interfaces.
The changes are as follows:
- *Removed auto echo setting*: It seemed to only complicate what should be simple, so I removed this. Storage and output methods can be approached from the custom user app itself.
- *Added `DynamicTags::mergeAttr()`*: Converts & merges passed arrays. Especially useful for extending. See examples below.
- *Changed `DynamicTags::attrArrayToStr()` to `DynamicTags::createAttrString()`* and added support for param string as well as array
- *Added `mapColsArr()`, `mapCols()`, `mapRowsArr()`, and `mapRows()`* See docs below

## Introduction
This library, and it's classes, are fairly straight-forward. The encouragement is to extend with your own tags/methods, for others to use, and for your own projects.

The initial motivation was to further divide server-side code from client-side code. A furthering motivation was to provide an extensible interface for script-to-HTML mapping in the most basic of forms, saving us from messy(and slow) code.

## Installation
### Via Composer
```
composer require iautomation/dynamic-html
```
and include composer if needed
```php
include './vendor/autoload.php'
```

### Alternate
Include the class files from the src directory

## Example Usage

### Basic Example

Below is a full html document written completely with DynamicTags
```php
<?php

use \DynamicHtml\DynamicTags as DT;
use \DynamicHtml\DynamicTagsShort as DTS;
use \DynamicHtml\DynamicTagsHelper as DTH;

echo DT::create('!DOCTYPE', 'html', null);
echo DT::html();

echo DT::head();
	echo DT::base('href=http://blah.com&id=blah'); // or echo DT::base(['href'=>'http://blah.com', 'id'=>'blah']);
	echo DT::meta('name=description&content=This is the description');
echo DT::end();

echo DT::body();

echo DT::header();
echo DT::ul();
echo DT::li(null, DTH::hyperlink('http://...', null, 'one'));
echo DT::li(null, DTH::hyperlink('http://...', null, 'two'));
echo DT::li(null, DTH::hyperlink('http://...', null, 'three'));
echo DT::end(2);

echo DTH::table([
	['1', '2', '3'],
	['2', '3', '1'],
]);

echo DT::style();
echo <<<EOT
[testselector] {
	background: blue;
	color:white;
}
EOT;
echo DT::end();
echo DT::div('testselector', 'hi');

echo DT::endAll();
```

## Classes

Below is specific documentation to each class, it's methods, and example usage.

### DynamicHtml\DynamicTags
The main class providing methods to convert attributes, create start and end tags, and record/output end tags using the `end()` method.

#### Example Usage
```php
use \DynamicHtml\DynamicTags as DT;

echo DT::span('id=name', 'John Doe'); // or
echo DT::span(['id'=>'name'], 'John Doe'); // or
echo DT::span('id=name').'John Doe'.DT::end();
```

#### Methods
- `__callStatic($method, $args)`
- `create($name, $attr=null, $content=true)`
- `end($amount=1)`
- `endAll()`
- `createStartTag($name, $attr=null)`
- `createEndTag($name)`
- `addTagToLog($name)`
- `removeTagFromLog()`
- `mergeAttr()`
- `createAttrString($attr=null)`
- `mapColsArr($method, $columns)`
- `mapCols($method)`
- `mapRowsArr($method, $rows)`
- `mapRows($method)`

### DynamicHtml\DynamicTagsShort
A helper to accessing DynamicTags, only with the short tag configured(by setting $content to false)

#### Example Usage
```php
use \DynamicHtml\DynamicTagsShort as DTS;

echo DTS::br();
// without this class, we'd have to type:
// echo DT::br(null, false);
```

#### Methods
- `__callStatic($method, $args)`

### DynamicHtml\DynamicTagsHelper
Lastly, we have the a helper class, though useful, can also be seen as an example class. Provides a showcase of methods extending DynamicTags.

#### Example Usage
```php
use \DynamicHtml\DynamicTagsHelper as DTH;

echo DTH::hyperlink('http://...', null, 'one');
echo DTH::table([
	['1', '2', '3'],
	['2', '3', '1'],
]);
```

#### Methods
- `stylesheet($href, $attr=null)`
- `hyperlink($href, $attr=null, $content=true)`
- `table($body_rows, $head_fields=null)`
- `tableHead($fields)`
- `tableHeadRow($fields)`
- `tableBody($rows)`
- `tableBodyRow($fields)`


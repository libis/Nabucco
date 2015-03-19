<?php
function utf8_htmlspecialchars($value)
{
return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}
function show_untitled_items($title)
{
// Remove all whitespace and formatting before checking to see if the title 
// is empty.
$prepTitle = trim(strip_formatting($title));
if (empty($prepTitle)) {
return __('[Untitled]');
}
return $title;
}
/**
* Partial for the admin bar.
*/
function admin_bar() {
echo common('admin-bar');
}
/**
* Styles for admin bar.
*/
function admin_bar_css() {
queue_css_url('//fonts.googleapis.com/css?family=Arvo:400', 'screen');
queue_css_file('admin-bar', 'screen');
}
/**
* Adds 'admin-bar' to the class attribute for the body tag.
*/
function admin_bar_class($attributes) {
$attributes['class'] = trim('admin-bar '.$attributes['class']);
return $attributes;
}
/**
* Custom function to retrieve the element name is metadata is given (from items/show.php)
*
* @param string $metadata The metadata of which the label is needed
* @return string The element name.
*/
function libis_get_element_name($metadata){    
$item = get_current_record('item');
//search elements of itemtype
$type = $item->getItemType();
$db = get_db();
$elements = $db->getTable('Element')->findByItemType($type->id);
$texts = $db->getTable('ElementText')->findByRecord($item);
foreach($texts as $text):   
if($text->text == $metadata):
$element = $db->getTable('Element')->find($text->element_id);
return $element->name;
endif;        
endforeach;   
}
?>

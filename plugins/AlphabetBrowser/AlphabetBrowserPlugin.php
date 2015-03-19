<?php
require_once dirname(__FILE__) . '/helpers/AlphabetBrowserFunctions.php';
class AlphabetBrowserPlugin extends Omeka_Plugin_AbstractPlugin
{
protected $_hooks = array(
'items_browse_sql'
);
public function hookItemsBrowseSql($args){
$sortDir = 'ASC';
$db = get_db();
$select = $args['select'];
$params = $args["params"];
if(isset($params['sort_dir'])):
$sortDir = $params['sort_dir'];
if($sortDir == 'd'):
$sortDir = 'DESC';
else:
$sortDir = 'ASC';
endif;
endif;
$startsWithData = isset($params['starts_with']) ? explode(',', $params['starts_with']) : false;
if($startsWithData) {
//ItemTable builds in a order by id, which we don't want
$select->reset('order');
//data like 'Element Set', 'Element', 'Character'
if(count($startsWithData) == 3) {
$startsWith = $startsWithData[2];
$element = $db->getTable('Element')->findByElementSetNameAndElementName($startsWithData[0], $startsWithData[1]);
if ($element) {
$select->joinLeft(array('ett_sort' => $db->ElementText),
"ett_sort.record_id = items.id AND ett_sort.record_type = 'Item' AND ett_sort.element_id = {$element->id}",
array())
->where("ett_sort.text REGEXP '^$startsWith'")
->group('items.id')                                    
->order("ett_sort.text $sortDir");
}                
} else {
throw new Exception("Starts With data must be like 'Element Set', 'Element', 'Character' ");
}
}
}    
}
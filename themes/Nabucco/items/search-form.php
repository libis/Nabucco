<?php
if (!empty($formActionUri)):
$formAttributes['action'] = $formActionUri;
else:
$formAttributes['action'] = url(array('controller'=>'items',
'action'=>'browse'));
endif;
$formAttributes['method'] = 'GET';
?>
<form <?php echo tag_attributes($formAttributes); ?>>
<div id="search-keywords" class="field">
<?php echo $this->formLabel('keyword-search', __('Search for Keywords')); ?>
<div class="inputs">
<?php
echo $this->formText(
'search',
@$_REQUEST['search'],
array('id' => 'keyword-search', 'size' => '40')
);
?>
</div>
</div>
<div id="search-narrow-by-fields" class="field">
<div class="label"><?php echo __('Narrow by Specific Fields'); ?></div>
<div class="inputs">
<?php
// If the form has been submitted, retain the number of search
// fields used and rebuild the form
if (!empty($_GET['advanced'])) {
$search = $_GET['advanced'];
} else {
$search = array(array('field'=>'','type'=>'','value'=>''));
}
//Here is where we actually build the search form
foreach ($search as $i => $rows): ?>
<div class="search-entry">
<?php
//The POST looks like =>
// advanced[0] =>
//[field] = 'description'
//[type] = 'contains'
//[terms] = 'foobar'
//etc
echo $this->formSelect(
"advanced[$i][element_id]",
@$rows['element_id'],
array(
'title' => __("Search Field"),
'id' => null,
'class' => 'advanced-search-element'
),
get_table_options('Element', null, array(
'record_types' => array('Item', 'All'),
'sort' => 'alphaBySet')
)
);
echo $this->formSelect(
"advanced[$i][type]",
@$rows['type'],
array(
'title' => __("Search Type"),
'id' => null,
'class' => 'advanced-search-type'
),
label_table_options(array(
'contains' => __('contains'),
'does not contain' => __('does not contain'),
'is exactly' => __('is exactly'),
'is empty' => __('is empty'),
'is not empty' => __('is not empty'))
)
);
echo $this->formText(
"advanced[$i][terms]",
@$rows['terms'],
array(
'size' => '20',
'title' => __("Search Terms"),
'id' => null,
'class' => 'advanced-search-terms'
)
);
/*echo $this->formSelect(
"advanced[$i][element_id]",
@$rows['element_id'],
array(
'title' => __("Search Terms Select"),
'id' => null,
'class' => 'advanced-search-element'
),
findTextPairs(@$rows['element_id'])
);*/
?>
<button type="button" class="remove_search" disabled="disabled" style="display: none;"><?php echo __('Remove field'); ?></button>
</div>
<?php endforeach; ?>
</div>
<button type="button" class="add_search"><?php echo __('Add a Field'); ?></button>
</div>
<!-- Type: Hidden -->
<?php
echo $this->formHidden(
'type',
@$_REQUEST['type'],
array('id' => 'item-type-search'),
get_table_options('ItemType')
);
?> 
<div class="field">
<?php echo $this->formLabel('featured', __('Featured/Non-Featured')); ?>
<div class="inputs">
<?php
echo $this->formSelect(
'featured',
@$_REQUEST['featured'],
array(),
label_table_options(array(
'1' => __('Only Featured Items'),
'0' => __('Only Non-Featured Items')
))
);
?>
</div>
</div>
<?php fire_plugin_hook('public_items_search', array('view' => $this)); ?>
<div>
<?php if (!isset($buttonText)) $buttonText = __('Search'); ?>
<input type="submit" class="submit" name="submit_search" id="submit_search_advanced" value="<?php echo $buttonText ?>">
</div>
</form>
<?php echo js_tag('items-search'); ?>
<script type="text/javascript">
jQuery(document).ready(function () {
Omeka.Search.activateSearchButtons();   
jQuery('.search-entry optgroup').replaceWith(function () {
return jQuery(this).children();
});
var blackListElements = [
"Contributor",
"Subject",
"Title",
"Creator",
"Date",
"Description",
"Publisher",
"Coverage",
"Format",
"Identifier",
"Language",
"Relation",
"Rights",
"Source",
"Type",       
"Alternative name",
"Archive name",
"Author",
"BCC",
"Bibliography",
"Bibliography ID",
"Biographical Text",
"Birth Date",
"Birthplace",
"Bit Rate/Frequency",
"Book",
"CC",
"Compression",
"Death Date",
"Director",
"Duration",
"Editor",
"Email Body",
"End date",
"Entity ID",
"Event Type",
"Family",
"From",
"Gender",
"Interviewee",
"Interviewer",
"Journal",
"Lesson Plan Text",
"Local URL",
"Location",
"Materials",
"Name",
"Number of Attachments",
"Objectives",
"Occupation",
"Original Format",
"Pages",
"Participants",
"Physical Dimensions",
"Place of origin",
"Producer",
"Profession",
"Profile",
"Publication ID",
"Publication year",
"Related objects",
"Related publications",
"Short title",
"Standards",
"Start date",
"Status",
"Subject Line",
"Text",
"Time Summary",
"Title",
"To",
"Transcription",
"URL",
];
jQuery.each(blackListElements, function (index, value) {
jQuery(".advanced-search-element option").filter(function(){
if(jQuery(this).text() == value)
{jQuery(this).remove();}
});
});
});
</script>

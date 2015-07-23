<?php
$pageTitle = __('Catalogue - Advanced search');
echo head(array('title' => $pageTitle,
'bodyclass' => 'items advanced-search'));
?>
<div id="primary">    
<h1><?php echo $pageTitle; ?></h1>
<p class="search-description"><?php echo libis_get_simple_page_content('search_info_catalogue_advanced');?>
</p> 
<?php echo $this->partial('items/search-form.php',
array('formAttributes' =>
array('id'=>'advanced-search-form'))); ?>
</div>
<?php echo foot(); ?>

<?php
$pageTitle = __('Catalogue - Advanced search');
echo head(array('title' => $pageTitle,
'bodyclass' => 'items advanced-search'));
?>
<div id="primary">    
<h1><?php echo $pageTitle; ?></h1>
<p class="search-description">
Proin tincidunt tempus turpis vitae iaculis. Proin at condimentum elit, a tempus enim. Nullam dignissim augue sed feugiat viverra. Vestibulum luctus mattis accumsan.
</p> 
<?php echo $this->partial('items/search-form.php',
array('formAttributes' =>
array('id'=>'advanced-search-form'))); ?>
</div>
<?php echo foot(); ?>

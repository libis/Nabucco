<?php

$getParams = $_GET;
$per_page = 10;
if(isset($getParams['per_page'])):
    $per_page = $getParams['per_page'];
endif;
?>
<nav class="pagination-nav" aria-label="<?php echo __('Pagination'); ?>">
<ul class="pagination">
<?php if (isset($this->previous)): ?>
<!-- Previous page link -->
<li class="pagination_previous">
<?php $getParams['page'] = $previous; ?>
<a rel="prev" href="<?php echo html_escape($this->url(array(), null, $getParams)); ?>"><span class="screen-reader-text"><?php echo __('Previous Page'); ?></span></a>
</li>
<?php endif; ?>
<li class="page-input">
<form action="<?php echo html_escape($this->url()); ?>" method="get" accept-charset="utf-8">
<?php
$hiddenParams = array();
$entries = explode('&', http_build_query($getParams));
foreach ($entries as $entry) {
if(!$entry) {
continue;
}
list($key, $value) = explode('=', $entry);
$hiddenParams[urldecode($key)] = urldecode($value);
}
foreach($hiddenParams as $key => $value) {
if($key != 'page' && $key != 'per_page') {
echo $this->formHidden($key,$value);
}
}
// Manually create this input to allow an omitted ID
$pageInput = '<input type="text" name="page" title="'
. html_escape(__('Current Page'))
. '" value="'
. html_escape($this->current) . '">';
echo __('%s of %s', $pageInput, $this->last);
?>               

</li>
<?php if (isset($this->next)): ?>
<!-- Next page link -->
<li class="pagination_next">
<?php $getParams['page'] = $next; ?>
<a rel="next" href="<?php echo html_escape($this->url(array(), null, $getParams)); ?>"><span class="screen-reader-text"><?php echo __('Next Page'); ?></span></a>
</li>

<?php endif; ?>

</ul>
  
</nav>
<div class="per_page_select">
    <select name="per_page" onchange="this.form.submit()">
        <option value="10" <?php echo $per_page == '10' ? 'selected' : ''; ?>>10 results per page</option>
        <option value="20" <?php echo $per_page == '20' ? 'selected' : ''; ?>>20 results per page</option>    
        <option value="50" <?php echo $per_page == '50' ? 'selected' : ''; ?>>50 results per page</option>
    </select>    
  
</div>
</form>
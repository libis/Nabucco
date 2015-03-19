<?php
echo head(array(
'title' => metadata('exhibit_page', 'title') . ' &middot; ' . metadata('exhibit', 'title'),
'bodyclass' => 'exhibits show'));
?>
<div id="primary">
<h1><span class="exhibit-page"><?php echo metadata('exhibit_page', 'title'); ?></h1>
<?php exhibit_builder_render_exhibit_page(); ?>
</div>
<div id="secondary">
<h2><?php echo exhibit_builder_link_to_exhibit($exhibit); ?></h2>
<nav id="exhibit-pages">
<?php echo emiglio_exhibit_builder_page_nav(); ?>
</nav>
</div>
<div id="exhibit-page-navigation">
<?php if ($prevLink = exhibit_builder_link_to_previous_page()): ?>
<div id="exhibit-nav-prev">
<?php echo $prevLink; ?>
</div>
<?php endif; ?>
<?php if ($nextLink = exhibit_builder_link_to_next_page()): ?>
<div id="exhibit-nav-next">
<?php echo $nextLink; ?>
</div>
<?php endif; ?>
<div id="exhibit-nav-up">
<?php echo exhibit_builder_page_trail(); ?>
</div>
</div>
<?php echo foot(); ?>

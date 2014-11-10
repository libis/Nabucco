<?php echo head(array(
    'title' => metadata('simple_pages_page', 'title'),
    'bodyclass' => 'page simple-page',
    'bodyid' => metadata('simple_pages_page', 'slug')
)); ?>
<div id="primary">
    <p id="simple-pages-breadcrumbs"><?php echo simple_pages_display_breadcrumbs(); ?></p>
    <h2><?php echo metadata('simple_pages_page', 'title'); ?></h2>
    <?php
    $text = metadata('simple_pages_page', 'text', array('no_escape' => true));
    echo $this->shortcodes($text);
    ?>
</div>
<?php
    $parent_id = get_current_record('simple_pages_page')->parent_id;
    $parent_page = get_db()->getTable('SimplePagesPage')->find($parent_id);
?>
<?php if(simple_pages_get_links_for_children_pages() || $parent_id != 0):?>
<div id="secondary">
    <div class="secondary-block">
        <?php 
           
            
            if($parent_id != 0):
                echo "<h2>".$parent_page->title."</h2>";
                echo simple_pages_navigation($parent_id);
            else:
                $page_id = get_current_record('simple_pages_page')->id;
                echo "<h2>".get_current_record('simple_pages_page')->title."</h2>";
                echo simple_pages_navigation($page_id);
            endif;
        ?>
    </div>
</div>
<?php endif; ?>
<?php echo foot(); ?>

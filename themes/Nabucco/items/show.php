<?php echo head(array('title' => metadata($item, array('Dublin Core', 'Title')), 'bodyclass' => 'items show')); ?>


<div id="primary">    
    <h2><?php echo metadata($item, array('Dublin Core', 'Title')); ?></h2>
    
    <?php 
        $item = get_current_record('Item');
        $type = $item->getItemType()->name;
        if($type = 'News'):
            echo "<div class='date'>".metadata('item',array('Item Type Metadata','End date'))."</div>";
        endif;
    ?>
    <!--  The following function prints all the the metadata associated with an item: Dublin Core, extra element sets, etc. See http://omeka.org/codex or the examples on items/browse for information on how to print only select metadata fields. -->
    <?php //echo all_element_texts($item); ?>
     <!-- The following returns all of the files associated with an item. -->
    <?php if (metadata($item, 'has files')): ?>
    <div id="itemfiles" class="element">        
        <?php echo files_for_item(); ?>
    </div>
    <?php endif; ?>
     
    <p><?php echo metadata($item, array('Dublin Core', 'Description')); ?></p>
     
    <!-- The following prints a list of all tags associated with the item -->
    <?php if (metadata($item, 'has tags')): ?>
    <div id="item-tags" class="element">
        <div class="element-text tags"><?php echo tag_string('item'); ?></div>
    </div>
    <?php endif; ?>

    <?php fire_plugin_hook('public_items_show', array('view' => $this, 'item' => $item)); ?>
     
     
    <ul class="item-pagination navigation">
        <li id="previous-item" class="previous"><?php echo link_to_previous_item_show('< Previous'); ?></li>
        <li id="next-item" class="next"><?php echo link_to_next_item_show('Next >'); ?></li>
    </ul>

</div><!-- end primary -->



<?php echo foot();

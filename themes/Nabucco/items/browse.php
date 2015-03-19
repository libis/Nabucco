<?php
$type = false;
if (isset($_GET['type'])):
    $type = strip_tags($_GET['type']);
endif;
switch ($type):
    case 'tablet':
        $pageTitle = 'Catalogue';
        $element = 'Item Type Metadata,Museum No.';
        $sortLinks[__('Museum n°')] = 'Item Type Metadata,Museum No.';
        $sortLinks[__('Publication')] = 'Item Type Metadata,Publication';
        $sortLinks[__('Place of issue')] = 'Item Type Metadata,Place of issue';
        break;
    case 'people':
        $pageTitle = 'People';
        $element = 'Item Type Metadata,Name';
        $sortLinks[__('Name')] = 'Item Type Metadata,Name';
        break;
    case 'bibliography':
        $pageTitle = 'Biblography';
        $element = 'Item Type Metadata,Title';
        $sortLinks[__('Title')] = $element;
        $sortLinks[__('Year')] = 'Item Type Metadata,Publication year';
        $sortLinks[__('Author')] = 'Item Type Metadata,Author';
        break;
    case 'archive':
        $pageTitle = 'Archives';
        $element = 'Item Type Metadata,Archive name';
        $sortLinks[__('Archive name')] = $element;
        break;
    default:
        $pageTitle = __('Browse');
        $element = '';
        $sortLinks[__('Title')] = 'Dublin Core,Title';
        $sortLinks[__('Date Added')] = 'added';
        break;
endswitch;
echo head(array('title' => $pageTitle, 'bodyclass' => 'items browse'));
?>
<div id="primary" class="browse">
    <h1><?php echo $pageTitle; ?> <?php echo __('(%s total)', $total_results); ?></h1>
    <div id="left">
        <div id="search-container">        
            <form id="search-form" method="get" action="/omeka/nabucco/items/browse" name="search-form">
                <input id="query" type="text" title="Search" value="" name="search">
                <input type="hidden" name="type" value="<?php echo $type; ?>">
                <button id="submit_search" value="Search" type="submit" name="submit_search">Search</button>
            </form>
        </div>  
        <?php if ($type == 'tablet'): ?>    
            <p><span class="advanced-search-link"><?php echo link_to_item_search(__('Advanced Search')); ?></span></p>
        <?php
        else:
            echo alphabet_browser_nav($type, $element);
        endif;
        ?>
        <p class="search-description">
            Proin tincidunt tempus turpis vitae iaculis. Proin at condimentum elit, a tempus enim. Nullam dignissim augue sed feugiat viverra. Vestibulum luctus mattis accumsan.
        </p>    
    </div>
    <div id="right">
        <?php echo item_search_filters(); ?>
        <?php echo pagination_links(); ?>
        <?php if ($total_results > 0): ?>
            <?php
            $sortLinks[__('Date Added')] = 'added';
            ?>
            <div id="sort-links">
                <span class="sort-label"><?php echo __('Sort by: '); ?></span><?php echo browse_sort_links($sortLinks); ?>
            </div>
        <?php else: ?>
            <h3>No results were found.</h3> 
        <?php endif; ?>
<?php foreach (loop('items') as $item): ?>        
    <?php if ($item->getItemType()->name == 'Tablet'): ?>
                <div class="item hentry">   
                    <h2><?php echo link_to_item('<span class="museum">museum n°    </span>' . metadata($item, array('Item Type Metadata', 'Museum No.'), array('class' => 'permalink'))); ?></h2>
                    <table>               
        <?php if ($text = metadata($item, array('Item Type Metadata', 'Publication'))): ?>
                            <tr><td class="title-cell">
                                    <h3>Publication</h3>
                                </td><td><?php echo $text; ?>
                                </td></tr>
        <?php endif; ?>
        <?php if ($text = metadata($item, array('Item Type Metadata', 'Archive'))): ?>
                            <tr><td class="title-cell">
                                    <h3>Archive</h3>
                                </td><td><?php echo $text; ?>
                                </td></tr>
                        <?php endif; ?>
                        <?php
                        if ($day = metadata($item, array('Item Type Metadata', 'Day'))):
                            $day = libis_get_date($day, metadata($item, array('Item Type Metadata', 'Day remark')));
                        endif;
                        ?>
                        <?php
                        if ($month = metadata($item, array('Item Type Metadata', 'Month'))):
                            $month = libis_get_date($month, metadata($item, array('Item Type Metadata', 'Month remark')));
                        endif;
                        ?>
        <?php
        if ($year = metadata($item, array('Item Type Metadata', 'Year'))):
            $year = libis_get_date($year, metadata($item, array('Item Type Metadata', 'Year remark')));
        endif;
        ?>
        <?php
        if ($king = metadata($item, array('Item Type Metadata', 'King'))):
            $king = libis_get_date($king, metadata($item, array('Item Type Metadata', 'King remark')));
        endif;
        ?>
                        <tr><td class="title-cell">
                                <h3>Babylonian date</h3>
                            </td><td><?php echo $day . "." . $month . "." . $year . " " . $king; ?>
                            </td></tr>
                        <?php if ($text = metadata($item, array('Item Type Metadata', 'Julian date'))): ?>
                            <tr><td class="title-cell">
                                    <h3>Julian date</h3>
                                </td><td><?php echo $text; ?>
                                </td></tr>
        <?php endif; ?>
                        <?php if ($text = metadata($item, array('Item Type Metadata', 'Type and content'))): ?>
                            <tr><td class="title-cell">
                                    <h3>Type and content</h3>
                                </td><td><?php echo $text; ?>
                                </td></tr>
                            <?php endif; ?>
                    <?php if ($text = metadata($item, array('Item Type Metadata', 'Place of issue'))): ?>
                            <tr><td class="title-cell">
                                    <h3>Place of issue</h3>
                                </td><td><?php echo $text; ?>
                                </td></tr>
        <?php endif; ?>                
                    </table>  
                            <?php if (metadata($item, 'has tags')): ?>
                        <div class="tags"><p><strong><?php echo __('Tags'); ?>: </strong>
            <?php echo tag_string('items'); ?></p>
                        </div>
                            <?php endif; ?>
                        <?php endif; ?>
                        <?php if ($item->getItemType()->name == 'People'): ?>
                    <div class="item hentry">   
                        <h2><?php echo link_to_item('<span class="museum">Name    </span>' . metadata($item, array('Item Type Metadata', 'Name'), array('class' => 'permalink'))); ?></h2>
                        <table>               
                            <?php if ($text = metadata($item, array('Item Type Metadata', 'Entity ID'))): ?>
                                <tr><td class="title-cell">
                                        <h3>Entity ID</h3>
                                    </td><td><?php echo $text; ?>
                                    </td></tr>
        <?php endif; ?>
                            <?php if ($text = metadata($item, array('Item Type Metadata', 'Gender'))): ?>
                                <tr><td class="title-cell">
                                        <h3>Gender</h3>
                                    </td><td><?php echo $text; ?>
                                    </td></tr>
        <?php endif; ?>   
                            <?php if ($text = metadata($item, array('Item Type Metadata', 'Name'))): ?>
                                <tr><td class="title-cell">
                                        <h3>Name</h3>
                                    </td><td><?php echo $text; ?>
                                    </td></tr>
                                <?php endif; ?>
                        <?php if ($text = metadata($item, array('Item Type Metadata', 'Place of origin'))): ?>
                                <tr><td class="title-cell">
                                        <h3>Place of origin</h3>
                                    </td><td><?php echo $text; ?>
                                    </td></tr>
        <?php endif; ?>
                        </table>  
                                <?php if (metadata($item, 'has tags')): ?>
                            <div class="tags"><p><strong><?php echo __('Tags'); ?>: </strong>
            <?php echo tag_string('items'); ?></p>
                            </div>
                                <?php endif; ?>
                            <?php endif; ?>
                            <?php if ($item->getItemType()->name == 'Bibliography'): ?>
                        <div class="item hentry">   
                            <h2><?php echo link_to_item(metadata($item, array('Item Type Metadata', 'Title'), array('class' => 'permalink'))); ?></h2>
                            <table>               
                                <?php if ($text = metadata($item, array('Item Type Metadata', 'Short title'))): ?>
                                    <tr><td class="title-cell">
                                            <h3>Short title</h3>
                                        </td><td><?php echo $text; ?>
                                        </td></tr>
        <?php endif; ?>
                                <?php if ($text = metadata($item, array('Item Type Metadata', 'Publication year'))): ?>
                                    <tr><td class="title-cell">
                                            <h3>Year</h3>
                                        </td><td><?php echo $text; ?>
                                        </td></tr>
                                    <?php endif; ?>   
                            <?php if ($text = metadata($item, array('Item Type Metadata', 'Author'))): ?>                
                                    <tr><td class="title-cell">
                                            <h3>Author</h3>
                                        </td><td><?php echo $text; ?>
                                        </td></tr>                
        <?php endif; ?>
                            </table>  
                                    <?php if (metadata($item, 'has tags')): ?>
                                <div class="tags"><p><strong><?php echo __('Tags'); ?>: </strong>
            <?php echo tag_string('items'); ?></p>
                                </div>
                                    <?php endif; ?>
                                <?php endif; ?>   
                                <?php if ($item->getItemType()->name == 'Archive'): ?>
                            <div class="item hentry">   
                                <h2><?php echo link_to_item(metadata($item, array('Item Type Metadata', 'Title'), array('class' => 'permalink'))); ?></h2>
                                <table>               
                                    <?php if ($text = metadata($item, array('Item Type Metadata', 'Alternative name'))): ?>
                                        <tr><td class="title-cell">
                                                <h3>Alternative name</h3>
                                            </td><td><?php echo $text; ?>
                                            </td></tr>
                                        <?php endif; ?>
                                <?php if ($text = metadata($item, array('Item Type Metadata', 'Related objects'))): ?>
                                        <tr><td class="title-cell">
                                                <h3>Related objects</h3>
                                            </td><td><?php echo $text; ?>
                                            </td></tr>
                            <?php endif; ?>   
                                </table>  
                            <?php if (metadata($item, 'has tags')): ?>
                                    <div class="tags"><p><strong><?php echo __('Tags'); ?>: </strong>
                        <?php echo tag_string('items'); ?></p>
                                    </div>
                    <?php endif; ?>
    <?php endif; ?>        
    <?php echo fire_plugin_hook('public_items_browse_each', array('view' => $this, 'item' => $item)); ?>
                        </div><!-- end class="item hentry" -->
<?php endforeach; ?>
<?php echo fire_plugin_hook('public_items_browse', array('items' => $items, 'view' => $this)); ?>
<?php echo pagination_links(); ?>
                </div>
            </div>
<?php echo foot(); ?>

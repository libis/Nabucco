<?php
$type = false;

  $session = new Zend_Session_Namespace('pagination_help');
  $per_page = $session->per_page;

if(isset($_GET['per_page'])):
    $per_page = $_GET['per_page'];
else:
    $per_page = get_option('public_per_page');
endif;

if (isset($_GET['type'])):
    $type = strip_tags($_GET['type']);
endif;
switch ($type):
    case 'tablet':
        $pageTitle = 'Catalogue';
        $element = 'Item Type Metadata,Museum No.';
        $sortLinks[__('Museum n°')] = 'Item Type Metadata,Museum No.';
        $sortLinks[__('Publication')] = 'Item Type Metadata,Publication';        
        $sortLinks[__('Archive')] = 'Item Type Metadata,Place of issue';        
        $sortLinks[__('Julian date')] = 'Item Type Metadata,Type & Julian date';
        $sortLinks[__('Type & content')] = 'Item Type Metadata,Type & content';
        $sortLinks[__('Place of issue')] = 'Item Type Metadata,Place of issue';
        $search_info = libis_get_simple_page_content('search_info_catalogue');
        break;
    case 'people':
        $pageTitle = 'People';
        $element = 'Item Type Metadata,Name';
        $sortLinks[__('Name')] = 'Item Type Metadata,Name';
        $search_info = libis_get_simple_page_content('search_info_people');
        break;
    case 'bibliography':
        $pageTitle = 'Biblography';
        $element = 'Item Type Metadata,Title';
        $sortLinks[__('Title')] = $element;
        $sortLinks[__('Year')] = 'Item Type Metadata,Publication year';
        $sortLinks[__('Author')] = 'Item Type Metadata,Author';
        $search_info = libis_get_simple_page_content('search_info_bibliography');
        break;
    case 'archive':
        $pageTitle = 'Archives';
        $element = 'Item Type Metadata,Archive name';
        $sortLinks[__('Archive name')] = $element;
        $search_info = libis_get_simple_page_content('search_info_archive');
        break;
    default:
        $pageTitle = __('Browse');
        $element = '';
        $sortLinks[__('Title')] = 'Dublin Core,Title';
        $search_info = "";
        break;
endswitch;
echo head(array('title' => $pageTitle, 'bodyclass' => 'items browse'));
?>
<div id="primary" class="browse">
    <h1><?php echo $pageTitle; ?> <?php echo __('(%s total)', $total_results); ?></h1>
    <p class="search-description">
        <?php echo $search_info;?>
    </p>
    <div id="left">
        <div id="search-container">        
            <form id="search-form" method="get" action="<?php echo WEB_ROOT; ?>/items/browse" name="search-form">
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
            
    </div>
    <div id="right">
        <?php echo item_search_filters(); ?>
        <?php echo pagination_links(array('per_page'=>$per_page)); ?>
        
        <?php if ($total_results > 0): ?>
                <div id="sort-links">
                <span class="sort-label"><?php echo __('Sort by: '); ?></span><?php echo browse_sort_links($sortLinks); ?>
            </div>
        <?php else: ?>
            <h3>No results were found.</h3> 
        <?php endif; ?>
        
        
        <table>
            <tr>  
        <?php if($type == 'tablet'):?>            
                <th>Museum n°</th>
                <th>Publication</th>
                <th>Archive</th>
                <th>Babylonian date</th>
                <th>Julian date</th>
                <th>Type & content</th>
                <th>Place of issue</th>  
        <?php endif;?>                
        <?php if($type == 'people'):?>           
                <th>Name</th>
                <th>Gender</th>
                <th>Places</th>
        <?php endif;?>
        <?php if($type == 'archive'):?>            
                <th>Name</th>
                <th>Alternative name</th>
                <th>Related objects</th>
        <?php endif;?>
        <?php if($type == 'bibliography'):?>            
                <th>Title</th>
                <th>Short title</th>
                <th>Publication year</th>
                <th>Author</th>
        <?php endif;?>   
        <tr>        
            
        <?php foreach (loop('items') as $item): ?>     
            <?php
                $relations = libis_get_relations($item, 'subject');
                $object_relations = libis_get_relations($item, 'object');
                if(isset($_SERVER['QUERY_STRING']) && !empty($_SERVER['QUERY_STRING'])):
                    $showlink = record_url('item').'?' . $_SERVER['QUERY_STRING'];
                else:
                    $showlink = record_url('item');
                endif;
            ?>
            <tr>  
            <?php if ($item->getItemType()->name == 'Tablet'): ?>                 
                    <td><?php echo '<a href="'.$showlink.'">'.metadata($item, array('Item Type Metadata', 'Museum No.')).'</a>'; ?></td>
                    <td>
                        <?php if ($pub = metadata($item, array('Item Type Metadata', 'Publication'))): 
                            if ($text = metadata($item, array('Item Type Metadata', 'Text number'))):
                                $pub .= " " . $text;    
                            endif;
                            if ($text = metadata($item, array('Item Type Metadata', 'Page number'))):
                                $pub .= ", " . $text; 
                            endif;
                            if (isset($object_relations['bibliographies'])):                                    
                                echo link_to($object_relations['bibliographies'][0], null,$pub);
                            else:
                                echo $pub;
                            endif; 
                        endif;?>      
                    </td>
                    <td>
                        <?php
                        if(isset($object_relations['archives'])):
                           $text = ''; 
                           foreach ($object_relations['archives'] as $archive):
                                $text .= link_to($archive, null, metadata($archive, array('Dublin Core', 'Title')));
                            endforeach;                            
                            echo rtrim($text, ', ');
                        endif;
                        ?>
                    </td>    
                    <td>
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
                      
                        <?php echo $day . "." . $month . "." . $year . " " . $king; ?>
                            
                    </td>
                    <td>
                        <?php if ($text = metadata($item, array('Item Type Metadata', 'Julian date'))): ?>
                           <?php echo $text; ?>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if ($text = metadata($item, array('Item Type Metadata', 'Type and content'))): ?>
                           <?php echo $text; ?>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if (isset($object_relations['places'])): ?>
                            <?php
                                $text = ''; 
                                foreach ($object_relations['places'] as $place):
                                    $text .= link_to($place, null, metadata($place, array('Dublin Core', 'Title'))).", ";
                                endforeach;
                                echo rtrim($text, ', ');
                             ?>
                        <?php endif; ?>     
                    </td>
                </tr>                    
                
                <?php endif; ?>                    
                 
                    
                <?php if ($item->getItemType()->name == 'People'): ?>
                    <td><?php echo '<a href="'.$showlink.'">'.metadata($item, array('Item Type Metadata', 'Name')).'</a>'; ?></td>
                    <td><?php if ($text = metadata($item, array('Item Type Metadata', 'Gender'))): ?>
                        <?php echo $text; ?>
                        <?php endif; ?>   
                    </td>
                    <td>
                        <?php if (isset($relations['places'])): ?>
                            <?php
                                $text = ''; 
                                foreach ($relations['places'] as $place):
                                    $text .= link_to($place, null, metadata($place, array('Dublin Core', 'Title'))).", ";
                                endforeach;
                                echo rtrim($text, ', ');
                             ?>
                        <?php endif; ?>
                    </td>  
                        
                    <?php endif; ?>
                    <?php if ($item->getItemType()->name == 'Bibliography'): ?>
                        <td><?php echo '<a href="'.$showlink.'">'.metadata($item, array('Dublin Core', 'Title')).'</a>'; ?></td>
                        <td><?php if ($text = metadata($item, array('Item Type Metadata', 'Short title'))): ?>
                            <?php echo $text; ?>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($text = metadata($item, array('Item Type Metadata', 'Publication year'))): ?>
                            <?php echo $text; ?>
                            <?php endif; ?>   
                        </td>
                        <td>
                            <?php if ($text = metadata($item, array('Item Type Metadata', 'Author'), array('all' => 'true', 'delimiter' => '<br>'))): ?>                
                            <?php echo $text; ?>
                            <?php endif; ?>
                        </td>      
                    <?php endif;?>    
                    <?php if ($item->getItemType()->name == 'Archive'): ?>
                        <td><?php echo '<a href="'.$showlink.'">'.metadata($item, array('Dublin Core', 'Title')).'</a>'; ?></td>
                        <td><?php if ($text = metadata($item, array('Item Type Metadata', 'Alternative name'))): ?>
                            <?php echo $text; ?>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if (isset($relations['tablets'])): ?>
                            <?php
                                foreach ($relations['tablets'] as $tablet):
                                    $text =  link_to($tablet, null, metadata($tablet, array('Dublin Core', 'Title'))) . ", ";
                                endforeach;
                                echo rtrim($text, ', ');
                            ?>   
                            <?php endif; ?>   
                        </td>      

                    <?php endif; ?>         
                             
                    </tr>
                    <?php endforeach; ?>
                   
                    </table>                        
                                           
                    <?php echo fire_plugin_hook('public_items_browse', array('items' => $items, 'view' => $this)); ?>
                    <?php echo pagination_links(array('per_page'=>$per_page)); ?>
                </div>
            </div>
            <?php echo foot(); ?>

<?php
echo head(array('title' => 'Browse glossaries', 'bodyclass' => 'items browse'));
?>
<div id="primary" class="browse">
    <h1>Browse glossaries <?php echo __('(%s total)', $total_results); ?></h1>
    <div id="left">
        <div id="search-container"> 
            <form id="search-form" name="search-form" action="" method="get">
                <input id="query" type="text" name="search" title="Search">
                <input type="hidden" value="glossary" name="type">
                <button id="submit_search" name="submit_search" type="submit" value="Search">Search</button>
            </form>   
        </div>   
        <?php echo alphabet_browser_nav('Glossary', 'Dublin Core,Title');?>
        <p class="search-description"><?php echo libis_get_simple_page_content('search_info_glossary');?>
    </div>
    <div id="right">
        <?php echo item_search_filters(); ?>
        
<?php
    if(sizeof($items)==0):
        echo "<h3>No results were found.</h3>"; 
    endif;

    $item_array=array();$tree=array();
    foreach($items as $item):
        $title = metadata($item,array('Item Type Metadata','Label'));
        $hierarchy = metadata($item,array('Item Type Metadata','Hierarchy'),array('all'=>true));
        $item_array[$title]['hierarchy'] =  $hierarchy;
        $relations = libis_get_relations($item,'subject');
        
        $item_array[$title]['objects']=array();
        $item_links[$title] = link_to($item,'show',$title);
    endforeach;
     
    foreach($item_array as $row):            
            if(!isset($tree[$row['hierarchy'][0]])):
                $tree[$row['hierarchy'][0]]=array();
                //$tree[$row['hierarchy'][0]]['Related objects']=$row['objects'];
            endif;
            
            if(isset($row['hierarchy'][1]) && !isset($tree[$row['hierarchy'][0]][$row['hierarchy'][1]])):
                $tree[$row['hierarchy'][0]][$row['hierarchy'][1]]=array();
                //$tree[$row['hierarchy'][0]][$row['hierarchy'][1]]['Related objects']=$row['objects'];
            endif;
            
            if(isset($row['hierarchy'][2]) && !isset($tree[$row['hierarchy'][0]][$row['hierarchy'][1]][$row['hierarchy'][2]])):
                $tree[$row['hierarchy'][0]][$row['hierarchy'][1]][$row['hierarchy'][2]]=array();
                //$tree[$row['hierarchy'][0]][$row['hierarchy'][1]][$row['hierarchy'][2]]['Related objects']=$row['objects'];
            endif;
            
            if(isset($row['hierarchy'][3]) && !isset($tree[$row['hierarchy'][0]][$row['hierarchy'][1]][$row['hierarchy'][2]][$row['hierarchy'][3]])):
                $tree[$row['hierarchy'][0]][$row['hierarchy'][1]][$row['hierarchy'][2]][$row['hierarchy'][3]]=array();
                //$tree[$row['hierarchy'][0]][$row['hierarchy'][1]][$row['hierarchy'][2]][$row['hierarchy'][3]]['Related objects']=$row['objects'];
            endif;           
        endforeach;
        
        
        foreach($tree as $key=>$value):?>
            <ul class='gloss gloss-first'>
            <?php if(isset($item_links[$key])):?>                
                <li><span class='top-li'><?php echo $item_links[$key] ?></span></li>                
            <?php endif;?>
            <?php if(is_array($value)):?>
                <ul>
                <?php foreach($value as $key=>$value):?> 
                    <?php if(isset($item_links[$key])):?>   
                    <li><?php echo $item_links[$key] ?></li>
                    <?php endif;?>
                    <?php if(is_array($value)):?>
                        <ul>
                        <?php foreach($value as $key=>$value):?>
                            <?php if(isset($item_links[$key])):?>   
                            <li><?php echo $item_links[$key];?> </li>
                            <?php endif;?>
                            <?php if(is_array($value)):?>
                                <ul>
                                <?php foreach($value as $key=>$value):?>
                                    <?php if(isset($item_links[$key])):?>   
                                    <li><?php echo $item_links[$key];?></li> 
                                    <?php endif;?>
                                <?php endforeach;?>
                                </ul>                
                            <?php endif;?>
                        <?php endforeach;?>
                        </ul>                
                    <?php endif;?>
                
                <?php endforeach;?>
                </ul>                
            <?php endif;?>            
            </ul>   
        <?php endforeach;?>
    </div>
    <?php echo foot(); ?>
<?php echo head(array('title' => metadata($item, array('Dublin Core', 'Title')), 'bodyclass' => 'items show')); ?>
<div id="primary"> 
    
    <h1><span class="title-type"><?php echo $type = $item->getItemType()->name;?></span><?php echo metadata($item, array('Dublin Core', 'Title')); ?></h1>
    <?php
    $item = get_current_record('Item');
    $relations = libis_get_relations($item, 'subject');
    $object_relations = libis_get_relations($item, 'object');
    
    if ($type = 'News'):
        echo "<div class='date'>" . metadata('item', array('Item Type Metadata', 'End date')) . "</div>";
    endif;
    ?>
    <!-- TABLET -->
    <?php if ($item->getItemType()->name == 'Tablet'): ?>
        <div class="item hentry">  
            <div class="show-section">
                <div class="show-block">        
                    <?php if ($text = metadata($item, array('Item Type Metadata', 'NaBuCCo No.'))): ?>
                        <div class="item-meta">
                            <p><span class="show-title">NaBuCCo No.</span>
                                <?php echo $text; ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($text = metadata($item, array('Item Type Metadata', 'Museum No.'))): ?>
                        <div class="item-meta">
                            <p><span class="show-title">Museum No.</span>
                        <?php echo $text; ?></p>
                        </div>  
                    <?php endif; ?>
                    
                    <?php if ($text = metadata($item, array('Item Type Metadata', 'Museum inv.'))): ?>
                        <div class="item-meta">
                            <p><span class="show-title">Museum Inv.</span>
                                <?php echo $text; ?></p>
                        </div>               
                    <?php endif; ?>
                    
                    <?php if (isset($object_relations['tablets'])): ?>
                        <div class="item-meta">
                            <p><span class="show-title">Duplicate</span>
                        <?php
                        foreach ($object_relations['tablets'] as $tablet):
                            echo link_to($tablet, null, metadata($tablet, array('Dublin Core', 'Title')));
                        endforeach;
                        ?></p>
                        </div>
                    <?php endif; ?>
                    
                    <div class="show-interal-block">   
                            <?php if ($text = metadata($item, array('Item Type Metadata', 'Publication'))): ?>
                                <div class="item-meta">
                                    <p><span class="show-title">Publication</span>                       
                                        <?php echo $text; ?>                                       
                                        <?php if ($text = metadata($item, array('Item Type Metadata', 'Text number'))): ?>
                                            <?php echo " " . $text; ?>    
                                        <?php endif; ?>
                                        <?php if ($text = metadata($item, array('Item Type Metadata', 'Page number'))): ?>
                                            <?php echo ", " . $text; ?>    
                                        <?php endif; ?>
                                    </p>
                                </div>  
                            <?php endif; ?>
                            <?php if ($text = metadata($item, array('Item Type Metadata', 'Volume number'))): ?>
                                <div class="item-meta">
                                    <p><span class="show-title">Volume No.</span> 
                                    <?php echo $text; ?></p>
                                </div>        
                            <?php endif; ?>
                            
                        </div>
                    
                    <div class="show-interal-block">  
                        <?php if ($text = metadata($item, array('Item Type Metadata', 'Period'))): ?>
                            <div class="item-meta">
                                <p><span class="show-title">Period</span>
                                <?php echo $text; ?>
                            </div>
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
                        <div class="item-meta">    
                            <p><span class="show-title">Babylonian date</span>
                                <?php echo $day . "." . $month . "." . $year . " " . $king; ?></p>                       
                        </div>
                       
                        <?php if ($text = metadata($item, array('Item Type Metadata', 'Date remark'))): ?>                    
                             <div class="item-meta">    
                            <p><span class="show-title">Date remark</span>   
                            <?php echo $text; ?></p>    
                            </div>
                        <?php endif; ?>  
                        
                        <?php if ($text = metadata($item, array('Item Type Metadata', 'Julian date'))): ?>
                            <div class="item-meta">
                                <p><span class="show-title">Julian date</span>
                                    <?php echo $text; ?></p>
                            </div>      
                        <?php endif; ?>
                    </div>    
                    <div class="show-interal-block">                           
                        <?php if (isset($object_relations['places'])): ?>
                                <div class="item-meta">
                                    <p><span class="show-title">Place of issue</span>
                                        <?php
                                        foreach ($object_relations['places'] as $place):
                                            echo link_to($place, null, metadata($place, array('Dublin Core', 'Title')));
                                        endforeach;
                                        ?></p>
                                </div>
                            <?php endif; ?>

                            <?php if (isset($object_relations['archives'])): ?>
                                <div class="item-meta">
                                    <p><span class="show-title">Archive</span>
                                        <?php
                                        foreach ($object_relations['archives'] as $archive):
                                            echo link_to($archive, null, metadata($archive, array('Dublin Core', 'Title')));
                                        endforeach;
                                        ?></p>
                                </div>
                            <?php endif; ?>

                        </div>
                    <div class="show-interal-block">  
                        
                        <?php if ($text = metadata($item, array('Item Type Metadata', 'Type and content'))): ?>                
                                <div class="item-meta">
                                    <p><span class="show-title">Type and content</span>
                                        <?php echo $text; ?></p>
                                </div>                
                            <?php endif; ?>
                        <?php if ($text = metadata($item, array('Item Type Metadata', 'Other markings'))): ?>
                            <div class="item-meta">
                                <p><span class="show-title">Other Markings</span>
                                    <?php echo $text; ?></p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="show-block"> 
                    <?php if ($text = metadata($item, array('Item Type Metadata', 'Paraphrase'))): ?>
                        <div class="item-meta">
                            <h3>Paraphrase</h3>
                            <p><?php echo html_entity_decode($text); ?></p>
                        </div>
                    <?php endif; ?>
                    <?php if ($text = metadata($item, array('Item Type Metadata', 'Akkadian keywords'))): ?>
                        <div class="item-meta">
                            <h3>Akkadian keywords</h3>
                            <p><?php echo $text; ?></p>
                        </div>
                    <?php endif; ?>
                    <?php if ($text = metadata($item, array('Item Type Metadata', 'General keywords'))): ?>
                        <div class="item-meta">
                            <h3>General keywords</h3>
                            <p><?php echo $text; ?></p>
                        </div>                  
                    <?php endif; ?>                 
                </div>
                <?php if (isset($relations['people'])): ?>
                <div class="show-block"> 
                    <table>
                        <tr>
                            <th><h3>Persons</h3></th>
                        <th><h3>Role</h3></th>
                        <th><h3>Profession</h3></th>
                        <th><h3>Status</h3></th>
                        </tr>        
                    <?php
                    foreach ($relations['people'] as $person):
                        $people = $person[0];
                        $meta = $person[1];
                        echo "<tr>" . libis_print_person($people, $meta) . "</tr>";
                    endforeach;
                    ?>
                    </table>
                </div>
                <?php endif; ?>
                <div class="show-block">
                    <?php if ($text = metadata($item, array('Item Type Metadata', 'Transliteration'))): ?>
                        <div class="item-meta">
                            <h3>Transliteration</h3>
                            <p><?php echo html_entity_decode($text);?></p>
                        </div>
                    <?php endif; ?>
                    <?php if ($text = metadata($item, array('Item Type Metadata', 'Items and quantifiable data'))): ?>
                        <div class="item-meta">
                            <h3>Items and quantifiable data</h3>
                            <p><?php echo html_entity_decode($text); ?></p>
                        </div>
                    <?php endif; ?>                
                </div>
            </div> 
        </div>
        <?php endif; ?>
        <!-- PEOPLE -->  
        <?php if ($item->getItemType()->name == 'People'): ?>
        <div class="item hentry">
            <?php if ($text = metadata($item, array('Item Type Metadata', 'Entity ID'))): ?>
                <div class="item-meta">
                    <h3>Entity ID</h3>
                    <p><?php echo $text; ?></p>
                </div>
            <?php endif; ?>
            <?php if ($text = metadata($item, array('Item Type Metadata', 'Gender'))): ?>
                <div class="item-meta">
                    <h3>Gender</h3>
                    <p><?php echo $text; ?></p>
                </div>
            <?php endif; ?>
            <?php if ($text = metadata($item, array('Item Type Metadata', 'Name'))): ?>
                <div class="item-meta">
                    <h3>Name</h3>
                    <p><?php echo $text; ?></p>
                </div>
            <?php endif; ?> 
            <?php if (isset($relations['places'])): ?>
                <div class="item-meta">
                    <h3>Place of origin</h3>
                        <p><?php
                        foreach ($relations['places'] as $place):
                            echo link_to($place, null, metadata($place, array('Dublin Core', 'Title')));
                        endforeach;
                        ?></p>
                </div>
            <?php endif; ?>
            <?php if ($text = metadata($item, array('Item Type Metadata', 'Kinship circle'))): ?>
                <div class="item-meta">
                    <h3>Kinship circle</h3>
                    <p><?php echo $text; ?></p>
                </div>
            <?php endif; ?> 
            <?php if ($text = metadata($item, array('Item Type Metadata', 'Family'))): ?>
                <div class="item-meta">
                    <h3>Family</h3>
                    <p><?php echo $text; ?></p>
                </div>
            <?php endif; ?>
            <?php if ($text = metadata($item, array('Item Type Metadata', 'Profile'))): ?>
                <div class="item-meta">
                    <h3>Profile</h3>
                    <p><?php echo $text; ?></p>
                </div>
            <?php endif; ?> 
            <?php if (isset($relations['tablets'])): ?>
                <div class="item-meta">
                    <h3>Related objects</h3>
                    <ul>
                    <?php
                    foreach ($relations['tablets'] as $tablet):
                        echo "<li>" . link_to($tablet, null, metadata($tablet, array('Dublin Core', 'Title'))) . "</li>";
                    endforeach;
                    ?>
                    </ul>
                </div>
            <?php endif; ?>
        </div>    
        <?php endif; ?>
        <!-- Bibliography -->  
        <?php if ($item->getItemType()->name == 'Bibliography'): ?>
            <div class="item hentry">
            <?php if ($text = metadata($item, array('Item Type Metadata', 'Short title'))): ?>
                <div class="item-meta">
                    <h3>Short title</h3>
                    <p><?php echo $text; ?></p>
                </div>
            <?php endif; ?>
            <?php if ($text = metadata($item, array('Item Type Metadata', 'Author'),array('all'=>'true','delimiter'=>'<br>'))): ?>
                <div class="item-meta">
                    <h3>Author</h3>
                    <p><?php echo $text; ?></p>
                </div>
            <?php endif; ?>
            <?php if ($text = metadata($item, array('Item Type Metadata', 'Publication year'))): ?>
                <div class="item-meta">
                    <h3>Year</h3>
                    <p><?php echo $text; ?></p>
                </div>
            <?php endif; ?> 
            <?php if ($text = metadata($item, array('Item Type Metadata', 'Journal'))): ?>
                <div class="item-meta">
                    <h3>Journal</h3>
                    <p><?php echo $text; ?></p>
                </div>
            <?php endif; ?>
            <?php if ($text = metadata($item, array('Item Type Metadata', 'Volume no.'))): ?>
                <div class="item-meta">
                    <h3>Volume no.</h3>
                    <p><?php echo $text; ?></p>
                </div>
            <?php endif; ?> 
            <?php if ($text = metadata($item, array('Item Type Metadata', 'Book'))): ?>
                <div class="item-meta">
                    <h3>Book</h3>
                    <p><?php echo $text; ?></p>
                </div>
            <?php endif; ?>
            <?php if ($text = metadata($item, array('Item Type Metadata', 'Pages'))): ?>
                <div class="item-meta">
                    <h3>Pages</h3>
                    <p><?php echo $text; ?></p>
                </div>
            <?php endif; ?> 
            <?php if ($text = metadata($item, array('Item Type Metadata', 'Editor'),array('all'=>'true','delimiter'=>'<br>'))): ?>
                <div class="item-meta">
                    <h3>Editor</h3>
                    <p><?php echo $text; ?></p>
                </div>
            <?php endif; ?>     
            <?php if (isset($relations['publications'])): ?>
                <div class="item-meta">
                    <h3>Related publications</h3>
                    <ul>
                    <?php
                    foreach ($relations['publications'] as $publications):
                        echo "<li>" . link_to($publications, null, metadata($publications, array('Dublin Core', 'Title'))) . "</li>";
                    endforeach;
                    ?>
                    </ul>
                </div>
            <?php endif; ?>
            <?php if (isset($relations['tablets'])): ?>
                <div class="item-meta">
                    <h3>Related objects</h3>
                    <ul>
                    <?php
                    foreach ($relations['tablets'] as $tablet):
                        echo "<li>" . link_to($tablet, null, metadata($tablet, array('Dublin Core', 'Title'))) . "</li>";
                    endforeach;
                    ?>
                    </ul>
                </div>
            <?php endif; ?>
            </div>    
        <?php endif; ?>
        
        <!-- Archive -->
        <?php if ($item->getItemType()->name == 'Archive'): ?>
            <div class="item hentry">
            <?php if ($text = metadata($item, array('Item Type Metadata', 'Archive name'))): ?>
                <div class="item-meta">
                    <h3>Archive name</h3>
                    <p><?php echo $text; ?></p>
                </div>
            <?php endif; ?>  
            <?php if ($text = metadata($item, array('Item Type Metadata', 'Alternative name'))): ?>
                <div class="item-meta">
                    <h3>Alternative name</h3>
                    <p><?php echo $text; ?></p>
                </div>
            <?php endif; ?> 
            <?php if (isset($relations['tablets'])): ?>
                <div class="item-meta">
                    <p><span class="show-title">Related objects</span>
                    <ul>
                    <?php
                    foreach ($relations['tablets'] as $tablet):
                        echo "<li>" . link_to($tablet, null, metadata($tablet, array('Dublin Core', 'Title'))) . "</li>";
                    endforeach;
                    ?>
                    </ul></p>
                </div>
            <?php endif; ?>
            </div>    
        <?php endif; ?>   
        
        <!-- PLACES -->
        <?php if ($item->getItemType()->name == 'Place'): ?>
            <div class="item hentry">
            <?php if ($text = metadata($item, array('Item Type Metadata', 'Place name'))): ?>
                <div class="item-meta">
                    <h3>Name</h3>
                    <p><?php echo $text; ?></p>
                </div>
            <?php endif; ?>  
            <?php if ($text = metadata($item, array('Item Type Metadata', 'Place description'))): ?>
                <div class="item-meta">
                    <h3>Place description</h3>
                    <p><?php echo $text; ?></p>
                </div>
            <?php endif; ?> 
            <?php if (isset($relations['tablets'])): ?>
                <div class="item-meta">
                    <p><span class="show-title">Related objects</span>
                    <ul>
                    <?php
                    foreach ($relations['tablets'] as $tablet):
                        echo "<li>" . link_to($tablet, null, metadata($tablet, array('Dublin Core', 'Title'))) . "</li>";
                    endforeach;
                    ?>
                    </ul></p>
                </div>
            <?php endif; ?>
                 </div>
        <?php endif; ?>
            <?php fire_plugin_hook('public_items_show', array('view' => $this, 'item' => $item)); ?>
       <!-- end class="item hentry" -->   
                    <!--  The following function prints all the the metadata associated with an item: Dublin Core, extra element sets, etc. See http://omeka.org/codex or the examples on items/browse for information on how to print only select metadata fields. -->
        <?php //echo all_element_texts($item);  ?>
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
            <ul class="item-pagination navigation">
                <li id="previous-item" class="previous"><?php echo link_to_previous_item_show('< Previous'); ?></li>
                <li id="next-item" class="next"><?php echo link_to_next_item_show('Next >'); ?></li>
            </ul>
    </div><!-- end primary -->
<?php echo foot(); ?>
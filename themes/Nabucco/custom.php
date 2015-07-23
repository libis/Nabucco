<?php

function emiglio_exhibit_builder_page_nav($exhibitPage = null) {
    if (!$exhibitPage) {
        if (!($exhibitPage = get_current_record('exhibit_page', false))) {
            return;
        }
    }
    $exhibit = $exhibitPage->getExhibit();
    $html = '<ul class="exhibit-page-nav navigation" id="secondary-nav">' . "\n";
    $pages = $exhibit->getTopPages();
    foreach ($pages as $page) {
        $current = (exhibit_builder_is_current_page($page)) ? 'class="current"' : '';
        $html .= "<li $current>" . exhibit_builder_link_to_exhibit($exhibit, $page->title, array(), $page);
        if ($page->countChildPages() > 0) {
            $childPages = $page->getChildPages();
            $html .= '<ul class="child-pages">';
            foreach ($childPages as $childPage) {
                $current = (exhibit_builder_is_current_page($childPage)) ? 'class="current"' : '';
                $html .= "<li $current>" . exhibit_builder_link_to_exhibit($exhibit, $childPage->title, array(), $childPage) . '</li>';
            }
            $html .= '</ul>';
        }
        $html .='</li>';
    }
    $html .= '</ul>' . "\n";
    $html = apply_filters('exhibit_builder_page_nav', $html);
    return $html;
}

function libis_get_simple_page_content($title) {
    $page = get_record('SimplePagesPage', array('title' => $title));
    if($page):
        return $page->text;
    else:
        return false;
    endif;
}

function libis_get_news() {
    $html = "<ul>";
    $news = get_records('item', array('type' => 'News'), 999);
    $today = date('Ymd');
    $news_array = array();
//loop through news items to find current ones
    $i = 0;
    foreach ($news as $new):
        $end = metadata($new, array('Item Type Metadata', 'End date'));
        $start = metadata($new, array('Item Type Metadata', 'Start date'));
        $start = strtotime($start);
        $start = date('Ymd', $start);
        $end = strtotime($end);
        $end = date('Ymd', $end);
        if ($end > $today):
//+i to help sort news with same start date
            $news_array[$start . $i] = "<li><a href='" . record_url($new) . "'>" . metadata($new, array('Dublin Core', 'Title')) . "</a></li>";
            $i++;
        endif;
        if ($i == 5):
            break;
        endif;
    endforeach;
//sort the resulting array   
    krsort($news_array);
//make a list
    foreach ($news_array as $news):
        $html .= $news;
    endforeach;
    $html .= "</ul>";
    return $html;
}

function libis_get_date($date, $remark) {
    switch ($remark):
        case '-':
            $date = $date;
            break;
        case '?':
            $date = $date . '?';
            break;
        case '[]':
            $date = '[' . $date . ']';
            break;
        case '[ ]':
            $date = '[' . $date . ']';
            break;
        case '[ ]?':
            $date = '[' . $date . ']?';
            break;
        case '[-]':
            $date = $remark;
            break;
        case 'nodate':
            $date = $remark;
            break;
    endswitch;
    
    if($date == '-'):
        if($remark == 'no day' || $remark =='no year' || $remark =='no month' || $remark =='no king'):
            $date = $remark;
        endif;
    endif;
    
    return $date;
}

function libis_get_persons($Object_ID, $item_id) {
//zoek element type persons_tablets
    $db = get_db();
    $texts_ob = $db->getTable('ElementText')->findByElement(90);
//zoek tabletperson waar object ID gelijk is aan var
    foreach ($texts_ob as $text_ob):
        if ($text_ob->text == $Object_ID && $text_ob->record_id != $item_id):
            $item = get_record_by_id('item', $text_ob->record_id);
            $tabletperson = $item;
            return libis_get_person_details($tabletperson);
        endif;
    endforeach;
}

function libis_get_person_details($tabletperson) {
//zoek element type persons_tablets
    $db = get_db();
    $persons = array();
    $texts_ent = $db->getTable('ElementText')->findByElement(57);
    $entity = metadata($tabletperson, array('Item Type Metadata', "Entity ID"), 'all');
//zoek person waar enity ID gelijk is aan var
    foreach ($texts_ent as $text_ent):
        if ($index = array_search($text_ent->text, $entity)):
            $item = get_record_by_id('item', $text_ent->record_id);
            if ($item->getItemType()->name == 'People'):
                echo $item->getItemType()->name;
                $person = $item;
                $persons[$person->title]['link'] = link_to($person);
                $roles = metadata($tabletperson, array('Item Type Metadata', "Role"), 'all');
                $persons[$person->title]['role'] = $roles[$index];
                if ($professions = metadata($tabletperson, array('Item Type Metadata', "Profession"), 'all')):
                    $persons[$person->title]['profession'] = $professions[$index];
                else:
                    $persons[$person->title]['profession'] = '';
                endif;
                if ($status = metadata($tabletperson, array('Item Type Metadata', "Status"), 'all')):
                    $persons[$person->title]['status'] = $status[$index];
                else:
                    $persons[$person->title]['status'] = '';
                endif;
            endif;
        endif;
    endforeach;
    if (empty($persons)):
        false;
    else:
        return $persons;
    endif;
}

function libis_get_relations($item, $direction = 'subject') {
    if ($direction == 'object'):
        $results = ItemRelationsPlugin::prepareObjectRelations($item);
    else:
        $results = ItemRelationsPlugin::prepareSubjectRelations($item);
    endif;
    $item_relations = array();
    foreach ($results as $relation):
        if ($direction == 'object'):
            $item = get_record_by_id('item', $relation['subject_item_id']);
        else:
            $item = get_record_by_id('item', $relation['object_item_id']);
        endif;
        $itemtype = $item->getItemType()->name;
        switch ($itemtype):
            case('People'):                
                $item_relations['people'][] = $item;
                break;
            case('Place'):
                $item_relations['places'][] = $item;
                break;
            case('Bibliography'):
                $item_relations['bibliographies'][] = $item;
                break;
            case('Archive'):
                $item_relations['archives'][] = $item;
                break;
            case('Tablet'):
                $item_relations['tablets'][] = $item;
                break;
            case('Glossary'):
                $item_relations['glossaries'][] = $item;
                break;
        endswitch;
    endforeach;
    if ($item_relations):
        return $item_relations;
    else:
        return false;
    endif;
}

function libis_find_meta_person($id,$metas){
    $result='';
    foreach($metas as $meta):
        $meta = explode(';',$meta);
        if($meta[0]== $id):
            $result = $meta[1];
            break;
        endif;
    endforeach;
    return $result;
}

function libis_print_person($person, $tablet) {
    $id = metadata($person, array('Item Type Metadata', "Entity ID"));
    
    $roles = metadata($tablet, array('Item Type Metadata', "Person role"),array('all'=>true));
    $profs = metadata($tablet, array('Item Type Metadata', "Person profession"),array('all'=>true));
    $statuss = metadata($tablet, array('Item Type Metadata', "Person status"),array('all'=>true));
    
    $role = str_replace(array("<br>", "<br />"), "", libis_find_meta_person($id,$roles));
    $prof = str_replace(array("<br>", "<br />"), "", libis_find_meta_person($id,$profs));
    $status = str_replace(array("<br>", "<br />"), "", libis_find_meta_person($id,$statuss));   
   
    return "<td>" . link_to($person, $action = null, metadata($person, array('Item Type Metadata', 'Name'))) . "</td>"
            . "<td>" . rtrim($role, ',') . "</td>"
            . "<td>" . rtrim($prof, ',') . "</td>"
            . "<td>" . rtrim($status, ',') . "</td>";
}

function findTextPairs($elementID) {
    $db = get_Db();
    $texts = findUniqueByElement($elementID);
    $selectOptions = array();
    foreach ($texts as $text) {
        $selectOptions[$text['text']] = __($text['text']);
    }
    return label_table_options($selectOptions);
}

function findUniqueByElement($elementID){
    $db = get_Db();
    $texts = $db->getTable('ElementText');
    $select = $texts->getSelect()->where('element_texts.element_id = ?', (int)$elementID)->group('element_texts.text');
    return $texts->fetchObjects($select);
}

function libis_advanced_search_seperate_fields() {
    $db = get_db();
    $elements = $db->getTable('Element')->findByItemType(21);
    $list = array('Period','Type and content','Month','King','Other markings','Akkadian keywords','General keywords');
    $i = 0;
    foreach ($elements as $element):
        if(in_array($element->name,$list)):
            $form .= $element->name . " " . formSelect(
                            "advanced[$i][element_id]", $element->id, array(
                        'title' => __("Search Terms Select"),
                        'id' => null,
                        'class' => 'advanced-search-element'
                            ), findTextPairs($element->id)
            );
        endif;
        $i++;
    endforeach;
}

function libis_places_tree(){
    $places = get_records('Item', array("type"=>'Place'),9999);
    
    
    $places = libis_order_places($places);
    
    $html = "<ul class='map-tree'>";
    foreach($places as $place):
        $place_item = get_record_by_id('Item', $place['id']);
        $html .= "<li><a class='map-tree-button' href='#'>+</a>".link_to($place_item,null,metadata($place_item,array('Item Type Metadata','Place name')))."</li>";
        if(!empty($place['children'])):
            $html .= "<li class='map-tree-hidden'><ul>";
            foreach($place['children'] as $child):                
                $child = get_record_by_id('Item', $child);
                $html .= "<li>".link_to($child,null,metadata($child,array('Item Type Metadata','Place name')))."</li>";
            endforeach;
            $html .="</ul></li>";
        endif;
        
    endforeach;
    $html .="</ul>";
    
    return $html;
    
    
}

function libis_order_places($places){
    $p = array();
    foreach($places as $place):
        $pid = metadata($place,array('Item Type Metadata','Parent id'));
        $id = metadata($place,array('Item Type Metadata','Place id'));
        
        if ( $pid === null ) {
            if ( !array_key_exists( $id, $p ) ) {
              $p[ $id ] = array(
                'id' => '',
                'children' => array()
              );
            }
            $p[$id]['id'] = $place->id;//metadata($place,array('Item Type Metadata','Place name'));
        }else {
            if ( !array_key_exists( $pid, $p ) ) {
              $p[ $pid ] = array(
                'id' => '',
                'children' => array()
              );
            }
            $p[ $pid ]['children'][ $id ] = $place->id;//metadata($place,array('Item Type Metadata','Place name'));
        }         
    endforeach;       
    return $p;
}

function libis_get_glossary($items){
    //$params['hasImage'] = 1;
        
        $item_array = array();
        $tree = array();
        
        foreach($items as $item):
            $title = metadata($item,array('Item Type Metadata','Label'));
            $hierarchy = metadata($item,array('Item Type Metadata','Hierarchy'),array('all'=>true));
            $item_array[$title]['hierarchy'] =  $hierarchy;
            $relations = libis_get_relations($item,'subject');
            if($relations['tablets']):
                foreach($relations['tablets'] as $tablet):
                    $item_array[$title]['objects'][] = link_to($tablet, null, $title);
                endforeach;
            else:
                 $item_array[$title]['objects']=array();
            endif;
            
        endforeach;
        
        foreach($item_array as $row):            
            if(!isset($tree[$row['hierarchy'][0]])):
                $tree[$row['hierarchy'][0]]=array();
                $tree[$row['hierarchy'][0]]['Related objects']=$row['objects'];
            endif;
            
            if(isset($row['hierarchy'][1]) && !isset($tree[$row['hierarchy'][0]][$row['hierarchy'][1]])):
                $tree[$row['hierarchy'][0]][$row['hierarchy'][1]]=array();
                $tree[$row['hierarchy'][0]][$row['hierarchy'][1]]['Related objects']=$row['objects'];
            endif;
            
            if(isset($row['hierarchy'][2]) && !isset($tree[$row['hierarchy'][0]][$row['hierarchy'][1]][$row['hierarchy'][2]])):
                $tree[$row['hierarchy'][0]][$row['hierarchy'][1]][$row['hierarchy'][2]]=array();
                $tree[$row['hierarchy'][0]][$row['hierarchy'][1]][$row['hierarchy'][2]]['Related objects']=$row['objects'];
            endif;
            
            if(isset($row['hierarchy'][3]) && !isset($tree[$row['hierarchy'][0]][$row['hierarchy'][1]][$row['hierarchy'][2]][$row['hierarchy'][3]])):
                $tree[$row['hierarchy'][0]][$row['hierarchy'][1]][$row['hierarchy'][2]][$row['hierarchy'][3]]=array();
                $tree[$row['hierarchy'][0]][$row['hierarchy'][1]][$row['hierarchy'][2]][$row['hierarchy'][3]]['Related objects']=$row['objects'];
            endif;           
        endforeach;
        
        $html = '';
        foreach($tree as $key=>$value):
            $html .= "<ul class='gloss-0'>";
            $html .= "<li><span class='top-li'>".$key."</span></li>";
            if(is_array($value)):
                $html .= "<ul>";
                foreach($value as $key=>$value):                    
                    if($key == 'Related objects'):
                        $html .= glossary_get_objects($value);
                    else:
                        $html .= "<li>".$key."</li>";                                       
                        if(is_array($value)):
                            $html .= "<ul>";
                            foreach($value as $key=>$value):
                                if($key == 'Related objects'):
                                    $html .= glossary_get_objects($value);
                                else:
                                    $html .= "<li>".$key."</li>";
                                    if(is_array($value)):
                                        $html .= "<ul>";
                                        foreach($value as $key=>$value):
                                            if($key == 'Related objects'):
                                                $html .= glossary_get_objects($value);
                                            else:
                                                $html .= "<li>".$key."</li>";                        
                                            endif;              
                                        endforeach;
                                        $html .= "</ul>";                
                                    endif;
                                endif;
                            endforeach;
                            $html .= "</ul>";                
                        endif;
                     endif;
                endforeach;
                $html .= "</ul>";                
            endif;            
            $html .= "</ul>";
        endforeach;
               
       return $html;
}

/**
 * source = http://omeka.readthedocs.org/en/latest/Tutorials/recipes/retainingSearchSortOrderWhenPaging.html
 */
function custom_paging()
{
//Starts a conditional statement that determines a search has been run
    if (isset($_SERVER['QUERY_STRING'])) {

        // Sets the current item ID to the variable $current
        $current = metadata('item', 'id');

        //Break the query into an array
        parse_str($_SERVER['QUERY_STRING'], $queryarray);

        //Items don't need the page level
        unset($queryarray['page']);

        $itemIds = array();
        $list = array();
        if (isset($queryarray['query'])) {
                //We only want to browse previous and next for Items
                $queryarray['record_types'] = array('Item');
                //Get an array of the texts from the query.
                $textlist = get_db()->getTable('SearchText')->findBy($queryarray);
                //Loop through the texts ans populate the ids and records.
                foreach ($textlist as $value) {
                        $itemIds[] = $value->record_id;
                        $record = get_record_by_id($value['record_type'], $value['record_id']);
                        $list[] = $record;
                }
        }
        elseif (isset($queryarray['advanced'])) {
                if (!array_key_exists('sort_field', $queryarray))
                {
                        $queryarray['sort_field'] = 'added';
                        $queryarray['sort_dir'] = 'd';
                }
                //Get an array of the items from the query.
                $list = get_db()->getTable('Item')->findBy($queryarray);
                foreach ($list as $value) {
                        $itemIds[] = $value->id;
                        $list[] = $value;
                }
        }
        //Browsing all items in general
        else
        {
                if (!array_key_exists('sort_field', $queryarray))
                {
                        $queryarray['sort_field'] = 'added';
                        $queryarray['sort_dir'] = 'd';
                }
                $list = get_db()->getTable('Item')->findBy($queryarray);
                foreach ($list as $value) {
                        $itemIds[] = $value->id;
                }
        }

        //Update the query string without the page and with the sort_fields
        $updatedquery = http_build_query($queryarray);
        $updatedquery = preg_replace('/%5B[0-9]+%5D/simU', '%5B%5D', $updatedquery);

        // Find where we currently are in the result set
        $key = array_search($current, $itemIds);

        // If we aren't at the beginning, print a Previous link
        if ($key > 0) {
            $previousItem = $list[$key - 1];
            $previousUrl = record_url($previousItem, 'show') . '?' . $updatedquery;
                $text = __('&larr; Previous Item');
            echo '<li id="previous-item" class="previous"><a href="' . html_escape($previousUrl) . '">' . $text . '</a></li>';
        }

        // If we aren't at the end, print a Next link
        if ($key >= 0 && $key < (count($list) - 1)) {
            $nextItem = $list[$key + 1];
            $nextUrl = record_url($nextItem, 'show') . '?' . $updatedquery;
                $text = __("Next Item &rarr;");
                echo '<li id="next-item" class="next"><a href="' . html_escape($nextUrl) . '">' . $text . '</a></li>';
        }
    } else {
        // If a search was not run, then the normal next/previous navigation is displayed.
        echo '<li id="previous-item" class="previous">'.link_to_previous_item_show().'</li>';
        echo '<li id="next-item" class="next">'.link_to_next_item_show().'</li>';
    }
}


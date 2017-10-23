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
    $html = "";
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
            $news_array[$start . $i] = metadata($new, array('Item Type Metadata', 'End date'))."-"."
              <a href='" . record_url($new) . "'>" . metadata($new, array('Dublin Core', 'Title')) . "</a></br>";
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
    return $html;
}

function libis_get_date($date, $remark) {
    switch ($remark):
        case '-':
            $date = $date;
            break;
        case '':
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
    $codes = metadata($tablet, array('Item Type Metadata', "Person code"),array('all'=>true));

    $role = str_replace(array("<br>", "<br />"), "", libis_find_meta_person($id,$roles));
    $prof = str_replace(array("<br>", "<br />"), "", libis_find_meta_person($id,$profs));
    $status = str_replace(array("<br>", "<br />"), "", libis_find_meta_person($id,$statuss));
    $code = str_replace(array("<br>", "<br />"), "", libis_find_meta_person($id,$codes));

    return "<td>" . link_to($person, $action = null, "<b>".$code."</b> ".metadata($person, array('Item Type Metadata', 'Name'))) . "</td>"
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
    usort($places, function($a, $b){ return strcmp($a["name"], $b["name"]); });
    //echo "<pre>".var_dump($places)."</pre>";
    $html = "<ul class='map-tree'>";
    foreach($places as $place):
        $place_item = get_record_by_id('Item', $place['id']);

            if(!empty($place['children'])):
                usort($place['children'], function($a, $b){ return strcmp($a["name"], $b["name"]); });
                $html .= "<li><a class='map-tree-button' href='#'>+</a>".link_to($place_item,null,metadata($place_item,array('Item Type Metadata','Place name')))."</li>";

                $html .= "<li class='map-tree-hidden'><ul>";
                foreach($place['children'] as $child):
                    $child = get_record_by_id('Item', $child['id']);
                    if($child->id != $place_item->id):
                        $html .= "<li>".link_to($child,null,metadata($child,array('Item Type Metadata','Place name')))."</li>";
                    endif;
                endforeach;
                $html .="</ul></li>";
            else:
                $html .= "<li>".link_to($place_item,null,metadata($place_item,array('Item Type Metadata','Place name')))."</li>";
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
        $name = metadata($place,array('Item Type Metadata','Place name'));
        $name = transliterateString($name);

        if ( $pid === null  || $pid == 1 || $pid == 4 ) {
            if ( !array_key_exists( $id, $p ) ) {
              $p[ $id ] = array(
                'id' => $place->id,
                'name'=> $name,
                'children' => array()
              );
            }
            $p[$id]['id'] = $place->id;//metadata($place,array('Item Type Metadata','Place name'));
            $p[$id]['name'] = $name;
        }else {
            if ( !array_key_exists( $pid, $p ) ) {
              $p[ $pid ] = array(
                'id' => $place->id,
                'name'=> $name,
                'children' => array()
              );
            }
            $p[ $pid ]['children'][ $id ]['id'] = $place->id;//metadata($place,array('Item Type Metadata','Place name'));
            $p[$pid]['children'][ $id ]['name'] = $name;
        }
    endforeach;
    return $p;
}

function transliterateString($txt) {
    $transliterationTable = array('á' => 'a', 'Á' => 'A', 'à' => 'a', 'À' => 'A', 'ă' => 'a', 'Ă' => 'A', 'â' => 'a', 'Â' => 'A', 'å' => 'a', 'Å' => 'A', 'ã' => 'a', 'Ã' => 'A', 'ą' => 'a', 'Ą' => 'A', 'ā' => 'a', 'Ā' => 'A', 'ä' => 'ae', 'Ä' => 'AE', 'æ' => 'ae', 'Æ' => 'AE', 'ḃ' => 'b', 'Ḃ' => 'B', 'ć' => 'c', 'Ć' => 'C', 'ĉ' => 'c', 'Ĉ' => 'C', 'č' => 'c', 'Č' => 'C', 'ċ' => 'c', 'Ċ' => 'C', 'ç' => 'c', 'Ç' => 'C', 'ď' => 'd', 'Ď' => 'D', 'ḋ' => 'd', 'Ḋ' => 'D', 'đ' => 'd', 'Đ' => 'D', 'ð' => 'dh', 'Ð' => 'Dh', 'é' => 'e', 'É' => 'E', 'è' => 'e', 'È' => 'E', 'ĕ' => 'e', 'Ĕ' => 'E', 'ê' => 'e', 'Ê' => 'E', 'ě' => 'e', 'Ě' => 'E', 'ë' => 'e', 'Ë' => 'E', 'ė' => 'e', 'Ė' => 'E', 'ę' => 'e', 'Ę' => 'E', 'ē' => 'e', 'Ē' => 'E', 'ḟ' => 'f', 'Ḟ' => 'F', 'ƒ' => 'f', 'Ƒ' => 'F', 'ğ' => 'g', 'Ğ' => 'G', 'ĝ' => 'g', 'Ĝ' => 'G', 'ġ' => 'g', 'Ġ' => 'G', 'ģ' => 'g', 'Ģ' => 'G', 'ĥ' => 'h', 'Ĥ' => 'H', 'ħ' => 'h', 'Ħ' => 'H','Ḫ'=>'H', 'í' => 'i', 'Í' => 'I', 'ì' => 'i', 'Ì' => 'I', 'î' => 'i', 'Î' => 'I', 'ï' => 'i', 'Ï' => 'I', 'ĩ' => 'i', 'Ĩ' => 'I', 'į' => 'i', 'Į' => 'I', 'ī' => 'i', 'Ī' => 'I', 'ĵ' => 'j', 'Ĵ' => 'J', 'ķ' => 'k', 'Ķ' => 'K', 'ĺ' => 'l', 'Ĺ' => 'L', 'ľ' => 'l', 'Ľ' => 'L', 'ļ' => 'l', 'Ļ' => 'L', 'ł' => 'l', 'Ł' => 'L', 'ṁ' => 'm', 'Ṁ' => 'M', 'ń' => 'n', 'Ń' => 'N', 'ň' => 'n', 'Ň' => 'N', 'ñ' => 'n', 'Ñ' => 'N', 'ņ' => 'n', 'Ņ' => 'N', 'ó' => 'o', 'Ó' => 'O', 'ò' => 'o', 'Ò' => 'O', 'ô' => 'o', 'Ô' => 'O', 'ő' => 'o', 'Ő' => 'O', 'õ' => 'o', 'Õ' => 'O', 'ø' => 'oe', 'Ø' => 'OE', 'ō' => 'o', 'Ō' => 'O', 'ơ' => 'o', 'Ơ' => 'O', 'ö' => 'oe', 'Ö' => 'OE', 'ṗ' => 'p', 'Ṗ' => 'P', 'ŕ' => 'r', 'Ŕ' => 'R', 'ř' => 'r', 'Ř' => 'R', 'ŗ' => 'r', 'Ŗ' => 'R', 'ś' => 's', 'Ś' => 'S','Ṣ'=>'S', 'ŝ' => 's', 'Ŝ' => 'S', 'š' => 's', 'Š' => 'S', 'ṡ' => 's', 'Ṡ' => 'S', 'ş' => 's', 'Ş' => 'S', 'ș' => 's', 'Ș' => 'S', 'ß' => 'SS', 'ť' => 't', 'Ť' => 'T', 'ṫ' => 't', 'Ṫ' => 'T', 'ţ' => 't', 'Ţ' => 'T', 'ț' => 't','Ṭ'=>'T', 'Ț' => 'T', 'ŧ' => 't', 'Ŧ' => 'T', 'ú' => 'u', 'Ú' => 'U', 'ù' => 'u', 'Ù' => 'U', 'ŭ' => 'u', 'Ŭ' => 'U', 'û' => 'u', 'Û' => 'U', 'ů' => 'u', 'Ů' => 'U', 'ű' => 'u', 'Ű' => 'U', 'ũ' => 'u', 'Ũ' => 'U', 'ų' => 'u', 'Ų' => 'U', 'ū' => 'u', 'Ū' => 'U', 'ư' => 'u', 'Ư' => 'U', 'ü' => 'ue', 'Ü' => 'UE', 'ẃ' => 'w', 'Ẃ' => 'W', 'ẁ' => 'w', 'Ẁ' => 'W', 'ŵ' => 'w', 'Ŵ' => 'W', 'ẅ' => 'w', 'Ẅ' => 'W', 'ý' => 'y', 'Ý' => 'Y', 'ỳ' => 'y', 'Ỳ' => 'Y', 'ŷ' => 'y', 'Ŷ' => 'Y', 'ÿ' => 'y', 'Ÿ' => 'Y', 'ź' => 'z', 'Ź' => 'Z', 'ž' => 'z', 'Ž' => 'Z', 'ż' => 'z', 'Ż' => 'Z', 'þ' => 'th', 'Þ' => 'Th', 'µ' => 'u', 'а' => 'a', 'А' => 'a', 'б' => 'b', 'Б' => 'b', 'в' => 'v', 'В' => 'v', 'г' => 'g', 'Г' => 'g', 'д' => 'd', 'Д' => 'd', 'е' => 'e', 'Е' => 'E', 'ё' => 'e', 'Ё' => 'E', 'ж' => 'zh', 'Ж' => 'zh', 'з' => 'z', 'З' => 'z', 'и' => 'i', 'И' => 'i', 'й' => 'j', 'Й' => 'j', 'к' => 'k', 'К' => 'k', 'л' => 'l', 'Л' => 'l', 'м' => 'm', 'М' => 'm', 'н' => 'n', 'Н' => 'n', 'о' => 'o', 'О' => 'o', 'п' => 'p', 'П' => 'p', 'р' => 'r', 'Р' => 'r', 'с' => 's', 'С' => 's', 'т' => 't', 'Т' => 't', 'у' => 'u', 'У' => 'u', 'ф' => 'f', 'Ф' => 'f', 'х' => 'h', 'Х' => 'h', 'ц' => 'c', 'Ц' => 'c', 'ч' => 'ch', 'Ч' => 'ch', 'ш' => 'sh', 'Ш' => 'sh', 'щ' => 'sch', 'Щ' => 'sch', 'ъ' => '', 'Ъ' => '', 'ы' => 'y', 'Ы' => 'y', 'ь' => '', 'Ь' => '', 'э' => 'e', 'Э' => 'e', 'ю' => 'ju', 'Ю' => 'ju', 'я' => 'ja', 'Я' => 'ja', '[' => '');
    return str_replace(array_keys($transliterationTable), array_values($transliterationTable), $txt);
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
function custom_paging(){
    //Starts a conditional statement that determines a search has been run
    if ($_SERVER['QUERY_STRING']!="") {
        // Sets the current item ID to the variable $current
        $current = metadata('item', 'id');
        //Break the query into an array
        parse_str($_SERVER['QUERY_STRING'], $queryarray);
        //Items don't need the page level
        unset($queryarray['page']);

        $itemIds = array();
        $list = array();

        if (isset($queryarray['advanced']) || isset($queryarray['search'])){
            if (!array_key_exists('sort_field', $queryarray)){
                $queryarray['sort_field'] = 'added';
                $queryarray['sort_dir'] = 'd';
            }
            //Get an array of the items from the query.
            $items = get_db()->getTable('Item')->findBy($queryarray);

            foreach ($items as $value) {
                $itemIds[] = $value->id;
                $list[] = $value;
            }
        }
        //Browsing all items in general
        else{
            if (!array_key_exists('sort_field', $queryarray)){
                    $queryarray['sort_field'] = 'added';
                    $queryarray['sort_dir'] = 'd';
            }
            $items = get_db()->getTable('Item')->findBy($queryarray);
            foreach ($items as $value) {
                    $itemIds[] = $value->id;
                    $list[] = $value;
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

        if(get_current_record('Item')->getItemType()->name == 'Place' || get_current_record('Item')->getItemType()->name == 'Glossary'):
            if(get_current_record('Item')->getItemType()->name == 'Place'):?>
                <center><li class="return"><i><a href="<?php echo url('/geolocation/map/browse/'); ?>">Return to search results</a></i></li></center>
            <?php else: ?>
                <center><li class="return"><i><a href="<?php echo url('/glossary'); ?>">Return to search results</a></i></li></center>
            <?php endif;?>
        <?php else:
            if(substr($_SERVER['QUERY_STRING'], 0, 5) == 'query'):?>
                <center><li class="return"><i><a href="<?php echo url('search').'?'.$_SERVER['QUERY_STRING'] ?>">Return to search results</a></i></li></center>
            <?php else:?>
                <center><li class="return"><i><a href="<?php echo url('items/browse').'?'.$_SERVER['QUERY_STRING'] ?>">Return to search results</a></i></li></center>
            <?php endif;
        endif;
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
        if(substr($_SERVER['QUERY_STRING'], 0, 5) == 'query'):?>
            <center><li class="return"><i><a href="<?php echo url('search').'?'.$_SERVER['QUERY_STRING'] ?>">Return to search results</a></i></li></center>
        <?php else:?>
            <center><li class="return"><i><a href="<?php echo url('items/browse').'?'.$_SERVER['QUERY_STRING'] ?>">Return to search results</a></i></li></center>
        <?php endif;
        echo '<li id="next-item" class="next">'.link_to_next_item_show().'</li>';
    }
}

function type_and_content_search(){
    $records = get_records('Item', array("type"=>'Type and content'),9999);
    $types = array();$final = array();
    $i=0;
    foreach($records as $record):
        $types[$i]['name'] = metadata($record,array('Item Type Metadata','Name'));
        $types[$i]['parent'] = metadata($record,array('Item Type Metadata','Parent name'));
        $i++;
    endforeach;

    $tree = build_tree_type($types);

    $final = type_make_assoc($tree,"0");

    return $final;
}

function build_tree_type(array $types, $parent_0 = null) {
    $branch = array();$row = array();

    foreach ($types as $type) {

        if ($type["parent"] == $parent_0) {
            $children = build_tree_type($types, $type["name"]);
            if ($children) {
                $type['children'] = $children;
            }

            if(isset($type["children"])):
                $row = array($type["name"],$type["children"]);
            else:
                $row = array($type["name"]);
            endif;
            $branch[] = $row;
        }
    }

    return $branch;
}

function type_make_assoc($tree,$level){
  $final = array();
  foreach($tree as $branch):
      if(isset($branch['1'])):
        $final[$branch[0]] = type_make_assoc($branch['1'],$level);

      else:
        $final[$branch[0]]="";
      endif;
  endforeach;

  return $final;
}

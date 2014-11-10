<?php 

function emiglio_exhibit_builder_page_nav($exhibitPage = null)
{
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

function libis_get_simple_page_content($title){
    $page = get_record('SimplePagesPage',array('title'=>$title));
    return $page->text;
}

function libis_get_news(){
    $html="<ul>";
    $news = get_records('item',array('type'=>'News'),999);
    $today = date('Ymd');
    $news_array = array();
    //loop through news items to find current ones
    $i=0;
    foreach($news as $new):
        $end = metadata($new,array('Item Type Metadata','End date'));
        $start = metadata($new,array('Item Type Metadata','Start date'));
        $start = strtotime($start);
        $start = date('Ymd',$start);
        
        $end = strtotime($end);
        $end = date('Ymd',$end);
      
        if($end > $today):
            //+i to help sort news with same start date
            $news_array[$start.$i]= "<li><a href='".record_url($new)."'>".metadata($new,array('Dublin Core','Title'))."</a></li>";
            $i++;
        endif;
        if($i == 5):
            break;
        endif;
    endforeach;
    
    //sort the resulting array   
    krsort($news_array);
    
    //make a list
    foreach($news_array as $news):
        $html .= $news;
    endforeach;
    
    $html .= "</ul>";
    return $html;
}
?>
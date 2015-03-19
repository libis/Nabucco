<?php
require_once dirname(__FILE__) . '/helpers/GlossaryFunctions.php';

class GlossaryPlugin extends Omeka_Plugin_AbstractPlugin
{
    protected $_hooks = array('initialize');
    
    /**
    * Add the translations.
    */
    public function hookInitialize()
    {    
        add_shortcode('glossary', array($this, 'shortcode'));
    }   

    
    public function shortcode($args, $view)
    {
        //$params['hasImage'] = 1;
        $items = get_records('Item', array('type' => 'Glossary'), 999);
        
        
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
}

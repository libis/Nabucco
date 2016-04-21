<?php

class RelationImportPlugin extends Omeka_Plugin_AbstractPlugin
{
    protected $_hooks = array('initialize');

    public function hookInitialize()
    {
        add_shortcode('relation_import', array($this, 'shortcode'));
    }

    public function shortcode($args, $view)
    {
        $i=0;$y=0;
        //delete old relations
        $db = get_Db();
        $all = $db->getTable('ItemRelationsRelation')->findAll();
        foreach($all as $one):
            $i++;
            $one->delete();  
        endforeach;
        echo "deleted ".$i." relations / ";
        $i=0;
        $related_objects_element = $db->getTable('Element')->findByElementSetNameAndElementName('Item Type Metadata', 'Related objects');  
        $object_ID_element = $db->getTable('Element')->findByElementSetNameAndElementName('Item Type Metadata', 'Object ID');  

        if(!$related_objects_element || !$object_ID_element):
            return "Both element Related objects and Object ID have to exist";
        endif;

        //get all related objects texts
        $texts = $db->getTable('ElementText')->findByElement($related_objects_element->id);

        foreach($texts as $related_object):
            //find item corresponding with Object ID
            $select = $db->getTable('ElementText')->getSelect();
            $select->where('element_id = ?',(int)$object_ID_element->id);
            $select->where('text = ?',trim((string)$related_object->text));
            $tablets = $db->getTable('ElementText')->fetchObjects($select);
            
            if(empty($tablets)):
                //skip to next since no object ID was found
                $y++;
                continue;
            endif;
                
            foreach($tablets as $tablet):  
                $item = get_record_by_id('item', $related_object->record_id);
                $type = $item->getItemType()->name;
                
                if($type == 'Tablet' || $type == 'People'):
                    $subject = $tablet->record_id;
                    $object = $related_object->record_id;
                else:
                    $subject = $related_object->record_id;
                    $object = $tablet->record_id;
                endif;

                if($subject && $object):
                    $itemRelation = new ItemRelationsRelation;
                    $itemRelation->subject_item_id = $subject;
                    $itemRelation->property_id = 1;
                    $itemRelation->object_item_id = $object;
                    $itemRelation->save();
                    $i++;
                endif;
            endforeach; 
        endforeach;
        
        
        echo "made ".$i." relations <br><br>".sizeof($texts)." related objects found of which ".$y." pointed to non-existing Object IDs";
    }
}

?>
<?php

function glossary_get_objects($objects){
    if(!empty($objects)):
        $html = "";
        foreach($objects as $object):
            $html .= "<li>".$object."</li>";
        endforeach;
        return $html;
    endif;
}


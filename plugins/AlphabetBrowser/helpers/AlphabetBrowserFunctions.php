<?php
function alphabet_browser_nav($type,$element){
$letters = array('A', 'B', 'C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z') ;
echo "<ul class='alphabet_list'>";
foreach($letters as $letter):
echo "<li class='pagination_range'>";
echo "<a href='".current_url()."?type=".$type."&starts_with=".$element.",".$letter."&sort_field=".$element."'>".$letter."</a></li>";
endforeach;
echo "</ul>";
}
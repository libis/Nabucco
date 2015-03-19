<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<kml xmlns="http://earth.google.com/kml/2.0">
<Document>
<name>Omeka Items KML</name>
<?php /* Here is the styling for the balloon that appears on the map */ ?>
<Style id="item-info-balloon">
<BalloonStyle>
<text><![CDATA[
<div class="geolocation_balloon">
<div class="geolocation_balloon_title">$[namewithlink]</div>
<div class="geolocation_balloon_thumbnail">$[description]</div>
<p class="geolocation_balloon_description">$[Snippet]</p>
</div>
]]></text>
</BalloonStyle>
</Style>
<?php
foreach(loop('item') as $item):        
$location = $locations[$item->id];
$url = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
if (strpos($url,'admin') === false):
    $relations = libis_get_relations($item, 'subject');
endif;
?>
<Placemark>
    
<name><![CDATA[<?php echo metadata('item', array('Dublin Core', 'Title'));?>]]></name>
<namewithlink><![CDATA[<?php echo link_to_item(metadata('item' , array('Dublin Core', 'Title')), array('class' => 'view-item')); ?>]]></namewithlink>
<Snippet maxLines="2"><![CDATA[
    
    <?php if ($text = metadata($item, array('Item Type Metadata', 'Place description'))): ?>
        <div class="item-meta">
            <b>Place description</b>
            <p><?php echo $text; ?></p>
        </div>
    <?php endif; ?> 
    <?php if (isset($relations['tablets'])): ?>
        <div class="item-meta">
            <b>Related objects</b>
            <ul>
            <?php
            foreach ($relations['tablets'] as $tablet):
                echo "<li>" . link_to($tablet, null, metadata($tablet, array('Dublin Core', 'Title'))) . "</li>";
            endforeach;
            ?>
            </ul></p>
        </div>
    <?php endif; ?>
    
    ]]></Snippet>    
<description><![CDATA[<?php 
// @since 3/26/08: movies do not display properly on the map in IE6, 
// so can't use display_files(). Description field contains the HTML 
// for displaying the first file (if possible).
if (metadata($item, 'has thumbnail')) {
echo link_to_item(item_image('thumbnail'), array('class' => 'view-item'));                
}
?>]]></description>
<Point>
<coordinates><?php echo $location['longitude']; ?>,<?php echo $location['latitude']; ?></coordinates>
</Point>
<?php if ($location['address']): ?>
<address><![CDATA[<?php echo $location['address']; ?>]]></address>
<?php endif; ?>
</Placemark>
<?php endforeach; ?>
</Document>
</kml>

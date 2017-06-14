<?php
queue_css_file('geolocation-items-map');
$title = __('Places and maps') . ' ' . __('(%s total)', $totalItems);
echo head(array('title' => $title, 'bodyclass' => 'map browse'));
?>
<div id="primary">

    <h1><?php echo $title; ?></h1>
    <?php
    echo item_search_filters();
    echo pagination_links();
    ?><div id="geolocation-browse">

    <?php
        $formAttributes['action'] = $_SERVER['REQUEST_URI'];
        $formAttributes['method'] = 'GET';
    ?>
    <form id="map-public-search" <?php echo tag_attributes($formAttributes); ?>>


    <input type="submit" value="<?php echo __('Search'); ?>" id="submit_search_advanced" name="submit_search">
    <?php
        echo $this->formText(
            'search',
            @$_REQUEST['search'],
            array('id' => 'query','class'=>'map-input', 'size' => '40')
        );
    ?>
    <p class='search-description'><?php echo libis_get_simple_page_content('search_info_places'); ?></p>
    </form>

    <div id="map-links" class="map-links-wrapper">


    <h2><?php echo __('Browse places'); ?></h2>
    <?php echo libis_places_tree();?></div>



    <?php echo $this->googleMap('map_browse', array('params' => $params));?>

    <script>
        jQuery(document).ready(function() {
            jQuery('.map-tree-button').click(function(e){
               e.preventDefault();
               jQuery(this).parent().next('.map-tree-hidden').toggle();
            });
        });
    </script>
    </div>

</div>

<?php echo foot(); ?>

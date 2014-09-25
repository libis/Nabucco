<!DOCTYPE html>
<html lang="<?php echo get_html_lang(); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=yes" />
    <?php if ( $description = option('description')): ?>
    <meta name="description" content="<?php echo $description; ?>" />
    <?php endif; ?>
    <?php
    if (isset($title)) {
        $titleParts[] = strip_formatting($title);
    }
    $titleParts[] = option('site_title');
    ?>
    <title><?php echo implode(' &middot; ', $titleParts); ?></title>

    <?php echo auto_discovery_link_tags(); ?>

    <!-- Plugin Stuff -->
    <?php fire_plugin_hook('public_head', array('view'=>$this)); ?>

    <!-- Stylesheets -->
    <?php
    queue_css_url('//fonts.googleapis.com/css?family=Lato');
    queue_css_file(array('iconfonts', 'style','wallop'));
    echo head_css();
    ?>

    <!-- JavaScripts -->
    <?php 
    queue_js_file('globals');
    queue_js_file('jquery-accessibleMegaMenu');
    queue_js_file('backstretch');
    queue_js_file('wallop');
    echo head_js(); 
    ?>
</head>

<?php echo body_tag(array('id' => @$bodyid, 'class' => @$bodyclass)); ?>
    <?php fire_plugin_hook('public_body', array('view'=>$this)); ?>
    <div id="wrap">

        <header role="banner">

            <?php fire_plugin_hook('public_header', array('view'=>$this)); ?>
            <div id="map"><img src='<?php echo img('world.jpg');?>'></div>
            <div id="site-title">Na.Bu.CCo<?php //echo link_to_home_page(theme_logo()); ?></div>

            <div id="search-container">
                <?php if (get_theme_option('use_advanced_search') === null || get_theme_option('use_advanced_search')): ?>
                <?php echo search_form(array('show_advanced' => true)); ?>
                <?php else: ?>
                <?php echo search_form(); ?>
                <?php endif; ?>
            </div>           
            
            <?php echo theme_header_image(); ?>

        </header>
        <nav id="top-nav">
            <?php //echo public_nav_main(); ?>
            <ul class="navigation nav-menu">
            <li class="nav-item">
            <a id="" href="">Home</a>
            </li>
            <li class="nav-item">
            <a id="" href="">Catalogue</a>
            </li>
            <li class="nav-item">
            <a id="" href="">People</a>
            </li>
            <li class="nav-item">
            <a id="" href="">Places and maps</a>
            </li>
            <li class="nav-item">
            <a id="" href="">Archives</a>
            </li>
            <li class="nav-item">
            <a id="" href="">Glossaries</a>
            </li>
            <li class="nav-item">
            <a id="" href="">Bibliography</a>
            </li>
            <li class="nav-item">
            <a id="" href="">Explore</a>
            </li>
            </ul>
        </nav>
        
        <article id="content">
        
            <?php fire_plugin_hook('public_content_top', array('view'=>$this)); ?>

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
    <div id="map">
    <a href="<?php echo url("/");?>">
    <img src='<?php echo img('Logo_NaBuCCo.jpg');?>'>
    </a></div>
<div id="header-wrapper">
<div id="site-title">
N<span class="smallcaps">a</span>B<span class="smallcaps">u</span>CC<span class="smallcaps">o</span>
</div>
<div id="search-container">
    <form id="search-form" method="get" action="<?php echo WEB_ROOT;?>/search" name="search-form">
        <input id="query" type="text" title="Search" name="query">
        <input type="hidden" name="query_type" value="exact_match">
        <button id="submit_search" value="Search" type="submit" name="submit_search">Search</button>
    </form>
</div>
<?php echo theme_header_image(); ?>
</div>
</header>
<nav id="top-nav" role="navigation">
<?php echo public_nav_main();?>
</nav>
<article id="content">
<?php fire_plugin_hook('public_content_top', array('view'=>$this)); ?>

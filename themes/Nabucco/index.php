<?php echo head(array('bodyid'=>'home')); ?>

<?php if ($homepageText = get_theme_option('Homepage Text')): ?>
    <!-- Homepage Text -->
    <div id="intro">
    <?php echo $homepageText; ?>
    </div>
<?php endif; ?>

<div id="primary">
    
    <div class="wallop-slider photo-slider wallop-slider--slide">        
   
        <ul class="wallop-slider__list">
            <?php $items = get_records('Item', array('type'=>'Fotoshow'));?>
            <?php $current=true;$class="";?>
            <?php foreach($items as $item):?>
                <?php if($current){$class="wallop-slider__item--current";}?>
                <li class="wallop-slider__item <?php echo $class;?>">
                <?php $current = false;$class=""; ?>    
                <div class="slide-title slide-text">
                    <a href="<?php echo record_url($item);?>"><?php echo metadata($item,array('Dublin Core','Title')); ?></a>
                </div>   
                <div class="slide-content slide-text">
                    <a href="<?php echo record_url($item);?>"><?php echo metadata($item,array('Dublin Core','Description'),array('snippet'=>50)); ?></a>
                </div>    
                <?php if(metadata($item,array('Dublin Core','Rights'))):?>
                    <div class="rights">&copy;<?php echo " ".metadata($item,array('Dublin Core','Rights')); ?></div>
                <?php endif;?> 
                <a href="<?php echo record_url($item);?>"><?php echo item_image('fullsize', array(), 0, $item);?></a>
                </li>
            <?php endforeach; ?>         
        </ul>
        <button class="wallop-slider__btn wallop-slider__btn--previous btn btn--previous" disabled="disabled"><img src="<?php echo img("left.png")?>"></button>
        <button class="wallop-slider__btn wallop-slider__btn--next btn btn--next"><img src="<?php echo img("right.png")?>"></button>
    </div>        
   
    <div class="primary-section">
        <div class="welcome">
            <?php echo libis_get_simple_page_content('Welcome');?>
        </div>
    </div>
    <div class="primary-section">
       
    </div>
    
    

</div>
<div id="secondary">
    <div class="secondary-block">
        <h2>About</h2>
        <div id="right-nav">
            <?php echo nav(simple_pages_get_links_for_children_pages(12));?>            
        </div>    
    </div>
    
    <div class="secondary-block">
        <?php echo libis_get_simple_page_content('Related projects');?>
    </div>
    
    <div class="featured">
        <h2>News</h2>
        <div id="right-nav" class="links">
            <?php echo libis_get_news();?>
        </div>     
    </div>
    <?php if (get_theme_option('Display Featured Collection') !== '0'): ?>
    <!-- Featured Collection -->
    <!-- <div id="featured-collection" class="featured">
        <h2><?php echo __('Featured Collection'); ?></h2>
        <?php echo random_featured_collection(); ?>
    </div>--><!-- end featured collection -->
    <?php endif; ?>

    <?php if ((get_theme_option('Display Featured Exhibit') !== '0')
           && plugin_is_active('ExhibitBuilder')
           && function_exists('exhibit_builder_display_random_featured_exhibit')): ?>
    <!-- Featured Exhibit -->
    <?php echo exhibit_builder_display_random_featured_exhibit(); ?>
    <?php endif; ?>
</div>

<script>
jQuery(document).ready(function() {
         var photoslider = new WallopSlider('.photo-slider');
    //autoplay
    var count = photoslider.allItemsArrayLength;
    var start = photoslider.currentItemIndex;
    var end = count+1;
    var index = start;
    
   
    jQuery(function(){
        setInterval(function() {
            photoslider.goTo(index);     
            ++index;
            if (index == end) {index=start}
        },5000);
    });
});
</script>

<?php echo foot(); ?>

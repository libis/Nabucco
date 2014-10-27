<?php echo head(array('bodyid'=>'home')); ?>

<?php if ($homepageText = get_theme_option('Homepage Text')): ?>
    <!-- Homepage Text -->
    <div id="intro">
    <?php echo $homepageText; ?>
    </div>
<?php endif; ?>

<div id="primary">
    
    <div class="wallop-slider photo-slider wallop-slider wallop-slider--vertical-slide">        
   
        <ul class="wallop-slider__list">
          <li class="wallop-slider__item wallop-slider__item--current">
            <div class="slide-title slide-text">
                <a href="">Tablet C - Babylon</a>
             </div>   
        
             <div class="slide-content slide-text">
                <a href="">Lorem ipsum - 2301 BC.</a>
            </div>
            <img src="<?php echo img("slide.jpg");?>">
          </li>
          <li class="wallop-slider__item">
            <div class="slide-title slide-text">
                <a href="">Tablet C - Babylon</a>
             </div>   
        
             <div class="slide-content slide-text">
                <a href="">Lorem ipsum - 2301 BC.</a>
            </div>
            <img src="<?php echo img("slide.jpg");?>">
          </li>
          <li class="wallop-slider__item">
             <div class="slide-title slide-text">
                <a href="">Tablet C - Babylon</a>
             </div>   
        
             <div class="slide-content slide-text">
                <a href="">Lorem ipsum - 2301 BC.</a>
            </div>
            <img src="<?php echo img("slide.jpg");?>">
          </li>
        </ul>
        <button class="wallop-slider__btn wallop-slider__btn--previous btn btn--previous" disabled="disabled"><img src="<?php echo img("left.png")?>"></button>
        <button class="wallop-slider__btn wallop-slider__btn--next btn btn--next"><img src="<?php echo img("right.png")?>"></button>
    </div>        
   
    <div class="primary-section">
        <div class="welcome">
        <p><span class="start">The Neo-Babylonian Cuneiform Corpus (<b>NaBuCCo</b>)</span>
            aims at making available the large corpus of archival documents from first millennium BCE Babylonia to historians of the ancient world in general and Assyriologists in particular.</p>
        </div>
    </div>
    <div class="primary-section">
       
    </div>
    
    

</div>
<div id="secondary">
    <div class="secondary-block">
        <h2>About</h2>
        <div id="right-nav">
            <ul>
                <li><a href="">About the project</a></li>
                <li><a href="">Funding</a></li>
                <li><a href="">Current coverage</a></li>
                <li><a href="">Staff</a></li>
            </ul>
        </div>    
    </div>
    
    <div class="secondary-block">
        <h2>Related projects</h2>
        <div id="right-nav" class="links">
            <ul>
                <li><a href="">Link to other project 1</a></li>
                <li><a href="">Link to other project 2</a></li>
                <li><a href="">Link to other project 3</a></li>
            </ul>
        </div>        
    </div>
    
    <div class="featured">
        <h2>News</h2>
        <div id="right-nav" class="links">
            <ul>
                <li><a href="">Link to news 1</a></li>
                <li><a href="">Link to news 2</a></li>
                <li><a href="">Link to news 3</a></li>
            </ul>
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

<?php echo foot(); ?>

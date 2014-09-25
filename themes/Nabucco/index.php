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
            <div class="slide-description">
                <div class="slide-title slide-text">
                <a href="">Tablet A - Babylon</a>
                </div>
                <div class="slide-text">
                <a href="">Lorem ipsum - 2301 BC.</a>
                </div>
            </div>  
            <img src="<?php echo img("slide.jpg");?>">
          </li>
          <li class="wallop-slider__item">
            <div class="slide-description">
                <div class="slide-title slide-text">
                <a href="">Tablet B - Babylon</a>
                </div>
                <div class="slide-text">
                <a href="">Lorem ipsum - 2301 BC.</a>
                </div>
            </div>  
            <img src="<?php echo img("slide.jpg");?>">
          </li>
          <li class="wallop-slider__item">
            <div class="slide-description">
                <div class="slide-title slide-text">
                <a href="">Tablet C - Babylon</a>
                </div>
                <div class="slide-text">
                <a href="">Lorem ipsum - 2301 BC.</a>
                </div>
            </div>  
            <img src="<?php echo img("slide.jpg");?>">
          </li>
        </ul>
        <button class="wallop-slider__btn wallop-slider__btn--previous btn btn--previous" disabled="disabled"><img src="<?php echo img("left.png")?>"></button>
        <button class="wallop-slider__btn wallop-slider__btn--next btn btn--next"><img src="<?php echo img("right.png")?>"></button>
    </div>        
   
    <div class="primary-section">
        <h2>Titel</h2>
        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla eget vestibulum ligula. Vivamus volutpat condimentum massa vel dignissim. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Praesent suscipit fermentum augue, eget venenatis nisi ornare nec. Sed sit amet ex pharetra, ornare nisi quis, sodales tellus. Aliquam quis massa ornare, bibendum mauris vel, pellentesque arcu. Phasellus at augue quis justo malesuada consequat. Quisque porta euismod velit. Vivamus iaculis rutrum nibh eget malesuada. Nullam a aliquet libero. Praesent hendrerit ultrices odio ut tempus. Ut vitae accumsan est, quis ultricies lorem. </p>
    </div>
    <div class="primary-section">
        <div class="section-left">
            <h2>Titel</h2>
            <img src="<?php echo img('placeholder.png');?>">
            <p>Vivamus pretium pretium nisl id tempor. Pellentesque sagittis dignissim erat nec viverra. Nunc in velit feugiat, mollis risus ut, laoreet enim. Nulla facilisi. Phasellus ac placerat elit, eu condimentum tortor. </p>
        </div>
        <div class="section-right">
            <h2>Titel</h2>            
            <p>Nulla eget risus placerat, commodo erat a, vehicula lacus. Mauris arcu urna, auctor id convallis eu, bibendum ornare erat. Integer quis accumsan lorem. Duis ultrices consectetur elit eu dictum. Maecenas augue risus, pellentesque consequat tellus et, dignissim dictum est. </p>
        </div>
    </div>
    
    

</div>
<div id="secondary">
    <div class="secondary-block">
        <h2>About</h2>
         <img src="<?php echo img('placeholder.png');?>">
        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus ullamcorper viverra erat, ut convallis urna vehicula ut. In ac gravida ex. Maecenas ut tempus erat. In vitae erat urna.</p>
        <div id="right-nav">
            <ul>
                <li><a href="">About the project</a></li>
                <li><a href="">Funding</a></li>
                <li><a href="">Current coverage</a></li>
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

        </article>

        <footer>
            <div id="footer-nav-container">
            <nav id="bottom-nav">
                <?php //echo public_nav_main(); ?>
                <ul>
                    <li><a href="">Technical info</a></li>
                    <li><a href="">Credits and copyright</a></li>
                    <li><a href="">Contact</a></li>
                </ul>
            </nav>
            </div>
            <div id="footer-content">                
                <div id="footer-logo">
                    <p>
                        <a href="http://kuleuven.be"><img src="<?php echo img('logo_kuleuven.png');?>"></a>
                        <a href="http://kuleuven.be"><img src="<?php echo img('wien_logo.jpg');?>"></a>
                        <a href="http://kuleuven.be"><img src="<?php echo img('belspo_logo.jpeg');?>"></a>                    
                    </p>
                </div>
                <div id="footer-text">
                    <?php echo get_theme_option('Footer Text'); ?>
                    <?php if ((get_theme_option('Display Footer Copyright') == 1) && $copyright = option('copyright')): ?>
                        <p><?php echo $copyright; ?></p>
                    <?php endif; ?>
                </div>
                <?php fire_plugin_hook('public_footer', array('view'=>$this)); ?>
            </div>
        </footer>

    </div><!-- end wrap -->

    <script>
        
        var photoslider = new WallopSlider('.photo-slider');
    jQuery(document).ready(function() {
        
        jQuery('#wrap').backstretch("<?php echo img('bg4.jpg')?>");
            
        jQuery("#top-nav").accessibleMegaMenu({
            /* prefix for generated unique id attributes, which are required 
               to indicate aria-owns, aria-controls and aria-labelledby */
            uuidPrefix: "accessible-megamenu",
        
            /* css class used to define the megamenu styling */
            menuClass: "nav-menu",
        
            /* css class for a top-level navigation item in the megamenu */
            topNavItemClass: "nav-item",
        
            /* css class for a megamenu panel */
            panelClass: "sub-nav",
        
            /* css class for a group of items within a megamenu panel */
            panelGroupClass: "sub-nav-group",
        
            /* css class for the hover state */
            hoverClass: "hover",
        
            /* css class for the focus state */
            focusClass: "focus",
        
            /* css class for the open state */
            openClass: "open"
        });
    });

    </script>
</body>
</html>

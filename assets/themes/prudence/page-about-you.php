<?php
/*
Template Name: About You Section Template
*/
wp_enqueue_script('grayscale',get_stylesheet_directory_uri().'/lib/js/grayscale.js',FALSE,FALSE,TRUE);
add_filter( 'genesis_site_layout', '__genesis_return_full_width_content' );
function msdlab_do_post_tabs() {
    global $aboutyou_metabox;
    $aboutyou_metabox->the_meta();
    $nav_tabs = $tab_content = array();
    $i=0;
    while($aboutyou_metabox->have_fields('tabs')):
        $attachment_id = get_attachment_id_from_src($aboutyou_metabox->get_the_value('image'));
        $image = wp_get_attachment_image_src( $attachment_id, 'tab' );
        if($i==0){$buttontext = wp_strip_all_tags($aboutyou_metabox->get_the_value('title'));}
        $nav_tabs[$i] = '<li'.($i==0?' class="active"':'').'><a href="#'.sanitize_title(wp_strip_all_tags($aboutyou_metabox->get_the_value('title'))).'" id="tab-'.sanitize_title(wp_strip_all_tags($aboutyou_metabox->get_the_value('title'))).'" data-toggle="tab" data-option-value=".'.sanitize_title(wp_strip_all_tags($aboutyou_metabox->get_the_value('title'))).'"><img class="img-circle grayscale" src="'.$image[0].'" /><img class="img-circle logo-mark" src="'.get_stylesheet_directory_uri().'/lib/img/logo_mark.svg" /><h4 class="tab-title">'.$aboutyou_metabox->get_the_value('title').'</h4></a></li>';       
        $tab_content[$i] = '<div class="tab-pane fade'.($i==0?' in active':'').'" id="'.sanitize_title(wp_strip_all_tags($aboutyou_metabox->get_the_value('title'))).'"><h3 class="content-title">'.wp_strip_all_tags($aboutyou_metabox->get_the_value('title')).'</h3>'.apply_filters('the_content',$aboutyou_metabox->get_the_value('content')).'</div>';
        $i++;
    endwhile; //end loop
    print '<div class="about-you-tabs">
            <div class="btn-group">
            <button type="button" class="btn btn-default dropdown-toggle mobile-only" data-toggle="dropdown"><strong>'.$buttontext.'</strong>
            <span class="caret"></span>
            <span class="sr-only">Toggle Dropdown</span>
            </button>
    ';
    print '<!-- Nav tabs -->
        <ul class="nav nav-tabs tabs-'.count($nav_tabs).'" role="menu">
        '.implode("\n", $nav_tabs).'
        </ul>
        ';
    print '</div>
    ';
    print '<!-- Tab panes -->
        <div class="tab-content">
        '.implode("\n", $tab_content).'
        </div>
        '; 
    print '</div>
    ';
    if(is_active_sidebar('aboutyou')){
        print '<div class="about-you-widget-area row">';
        dynamic_sidebar('aboutyou');
        print '</div>';
    }
    ?>
    <script>
    function graytabs(){
        var tabimg = jQuery('.nav-tabs li .grayscale').not('.nav-tabs li.active .grayscale');
        grayscale(tabimg);
    }
    jQuery(window).load(function($){
        graytabs();
    });
    jQuery(document).ready(function($) {
        //tab greys
        /*jQuery('.nav-tabs li .grayscale').not('.nav-tabs li.active .grayscale').mouseenter(function(){
            grayscale.reset($(this));
        });
        jQuery('.nav-tabs li .grayscale').not('.nav-tabs li.active .grayscale').mouseleave(function(){
            grayscale($(this));
        });*/
       //alert(location.hash);
       var browsers = "<?php print implode(' ', browser_body_class(array())); ?>";
       if(browsers.indexOf('safari')>-1){
           var hash = location.hash.replace('tab-', '');
       } else {
           var hash = window.location.hash.replace('tab-', '');
       }
       
        hash && $('ul.nav a[href="' + hash + '"]').tab('show');
        if(ga){
            if($('.nav-tabs').length > 0){
                var filter = $('.nav-tabs .active a').attr('href').replace('#','');  
                ga('send', 'event', 'AboutYouNav', 'Nav', 'Tab: '+filter);
            }
        }
        
        $('ul.dropdown-menu li').removeClass(function(){
            if($(this).find('a').attr('href')==hash){
               $(this).parents('.btn-group').find('.btn.first-child strong').html($(this).find('h4.tab-title').html().replace( /<.*?>/g, '' ));
                return false;
            } else {
                return 'active';
            }
        }); 
        $('.about-you-widget-area .widget').show();  
        $('.about-you-widget-area .widget:not(.' + filter + ', .all)').hide();  
        $('.nav-tabs li').on('show.bs.tab',function(e){
            console.log(e.target);
            grayscale.reset($(e.target).find('.grayscale'));
            grayscale($(e.relatedTarget).find('.grayscale'));
            var filter = $(e.target).attr('href').replace('#','');  
            ga('send', 'event', 'AboutYouTabs', 'Click', 'Tab: '+filter);
            //console.log(filter);
            $('.about-you-widget-area .widget').show();  
            $('.about-you-widget-area .widget:not(.' + filter + ', .all)').hide();  
        });
        
        $('li.about-you li a').click(function(){
            var hashes = $(this).attr('href').split('#tab-');
            var hash = '#'+hashes[1];
            $('ul.nav a[href="' + hash + '"]').tab('show');
            $('ul.dropdown-menu li').removeClass(function(){
            if($(this).find('a').attr('href')==hash){
               $(this).parents('.btn-group').find('.btn.first-child strong').html($(this).find('h4.tab-title').html().replace( /<.*?>/g, '' ));
                return false;
            } else {
                return 'active';
            }
        }); 
        });
        
    });
    </script>
    <?php
}
add_action('genesis_after_loop','msdlab_do_post_tabs');
function msdlab_landingpage_tabs_js(){
    print "
    <script>
jQuery(document).ready(function($) {
    $('.dropdown-menu li').click(function(){
        $(this).siblings().removeClass('active');
        $(this).parents('.btn-group').find('.btn.first-child strong').html($(this).find('h4.tab-title').html().replace( /<.*?>/g, '' ));
    })
});
</script>";
}
add_action('wp_footer','msdlab_landingpage_tabs_js');

remove_action('genesis_before_content_sidebar_wrap', 'msdlab_do_breadcrumbs'); //to outside of the loop area
genesis();

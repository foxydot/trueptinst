<?php
/*
Template Name: About Us Section Template
*/
remove_action('genesis_sidebar','genesis_do_sidebar');
add_action('genesis_sidebar','msdlab_do_about_us_sidebar');
function msdlab_do_about_us_sidebar(){
    if(is_active_sidebar('aboutus')){
        print '<div class="about-us-widget-area">';
        dynamic_sidebar('aboutus');
        print '</div>';
    }
}
add_action('wp_head','msdlab_do_about_us_layout');

function msdlab_do_about_us_layout(){
    if($page_layout = genesis_get_custom_field( '_genesis_layout' )){
        switch($page_layout){
            case 'content-sidebar':
            case 'content-sidebar-sidebar':
                add_filter( 'genesis_site_layout', '__genesis_return_content_sidebar_sidebar' );
                remove_action('genesis_sidebar_alt','genesis_do_sidebar_alt');
                add_action('genesis_sidebar_alt','genesis_do_sidebar');
                
                break;
            default:        
                add_filter( 'genesis_site_layout', '__genesis_return_content_sidebar' );
                break;
        }
    } else {
        add_filter( 'genesis_site_layout', '__genesis_return_content_sidebar' );
    }
}
genesis();
<?php
/*** Bootstrappin **/

add_filter( 'genesis_attr_site-inner', 'msdlab_bootstrap_site_inner', 10);
add_filter( 'genesis_attr_breadcrumb', 'msdlab_bootstrap_breadcrumb', 10);
add_filter( 'genesis_attr_content-sidebar-wrap', 'msdlab_bootstrap_content_sidebar_wrap', 10);
add_filter( 'genesis_attr_content', 'msdlab_bootstrap_content', 10);
add_filter( 'genesis_attr_sidebar-primary', 'msdlab_bootstrap_sidebar', 10);
add_filter( 'genesis_attr_sidebar-secondary', 'msdlab_bootstrap_sidebar_alt', 10);

 /*** Bootstrappin **/

function msdlab_bootstrap_site_inner( $attributes ){
    $layout = genesis_site_layout();
    $template = get_page_template();
    if($template == get_stylesheet_directory().'/page-about-us.php' && $layout == 'content-sidebar-sidebar'){
        $attributes['class'] .= ' row';
    } else {
        $attributes['class'] .= ' container';
    }
    return $attributes;
}

function msdlab_bootstrap_breadcrumb( $attributes ){
    $layout = genesis_site_layout();
    $template = get_page_template();
    if($template == get_stylesheet_directory().'/page-about-us.php' && $layout == 'content-sidebar-sidebar'){
        $attributes['class'] .= '';
    } else {
        $attributes['class'] .= ' row';
    }
    return $attributes;
}

function msdlab_bootstrap_content_sidebar_wrap( $attributes ){
    $attributes['class'] .= ' row';
    $layout = genesis_site_layout();
    $template = get_page_template();
    switch($layout){
        case 'content-sidebar':
        case 'sidebar-content':
            break;
        case 'content-sidebar-sidebar':
        case 'sidebar-sidebar-content':
        case 'sidebar-content-sidebar':
            if($template == get_stylesheet_directory().'/page-about-us.php'){
                $attributes['class'] .= ' row col-md-9 col-sm-12';
            }
            break;
        case 'full-width-content':
            break;
    }
    return $attributes;
}

function msdlab_bootstrap_content( $attributes ){
    $layout = genesis_site_layout();
    $template = get_page_template();
    switch($layout){
        case 'content-sidebar':
        case 'sidebar-content':
        case 'content-sidebar-sidebar':
        case 'sidebar-sidebar-content':
        case 'sidebar-content-sidebar':
            if($template == get_stylesheet_directory().'/page-about-us.php'){
                $attributes['class'] .= ' col-md-12';
            } else {
                $attributes['class'] .= ' col-md-9 col-sm-12';
            }
            break;
        case 'full-width-content':
            $attributes['class'] .= ' col-md-12';
            break;
    }
    return $attributes;
}

function msdlab_bootstrap_sidebar( $attributes ){
    $layout = genesis_site_layout();
    $template = get_page_template();
    switch($layout){
        case 'content-sidebar':
        case 'sidebar-content':
            if($template == get_stylesheet_directory().'/page-about-us.php'){
                $attributes['class'] = preg_replace('/sidebar-primary/i','row',$attributes['class']);
            } else {
                $attributes['class'] .= ' col-md-3 hidden-sm hidden-xs';
            }
            break;
        case 'content-sidebar-sidebar':
        case 'sidebar-sidebar-content':
        case 'sidebar-content-sidebar':
            if($template == get_stylesheet_directory().'/page-about-us.php'){
                $attributes['class'] = preg_replace('/sidebar-primary/i','row',$attributes['class']);
            } else {
                $attributes['class'] .= ' col-md-3 hidden-sm hidden-xs';
            }
            break;
        case 'full-width-content':
            $attributes['class'] .= ' hidden';
            break;
    }
    return $attributes;
}

function msdlab_bootstrap_sidebar_alt( $attributes ){
    $layout = genesis_site_layout();
    $template = get_page_template();
    switch($layout){
        case 'content-sidebar':
        case 'sidebar-content':
        case 'content-sidebar-sidebar':
        case 'sidebar-sidebar-content':
        case 'sidebar-content-sidebar':
            if($template == get_stylesheet_directory().'/page-about-us.php'){
                $attributes['class'] .= ' col-md-3 hidden-sm hidden-xs';
            }
            break;
        case 'full-width-content':
            $attributes['class'] .= ' hidden';
            break;
    }
    return $attributes;
}
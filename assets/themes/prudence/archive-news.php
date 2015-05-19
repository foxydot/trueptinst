<?php
global $wp_filter;
//ts_var( $wp_filter['body_class'] );


add_action('genesis_before_loop','msdlab_add_page_content_to_archive');
remove_action( 'genesis_entry_content', 'genesis_do_post_content' );

if(is_cpt('post')){
    remove_action('genesis_sidebar','genesis_do_sidebar');
    add_action('genesis_sidebar','msdlab_do_blog_sidebar');
}

add_filter('genesis_post_title_text','msdlab_news_title');
function msdlab_news_title($title){
    global $post;
    $title .= ' '.trim(get_the_excerpt($post->ID)) . ' - ' . get_the_time( 'M j' );
    return $title;
}
remove_action( 'genesis_entry_header', 'genesis_post_info', 12 ); //remove the info (date, posted by,etc.)
remove_action( 'genesis_entry_footer', 'genesis_post_meta' ); //remove the meta (filed under, tags, etc.)
global $wp_filter;
//ts_var( $wp_filter['genesis_entry_content'] );
genesis();

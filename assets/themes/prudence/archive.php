<?php

global $wp_filter;
//ts_var( $wp_filter['body_class'] );

remove_action( 'genesis_entry_content', 'genesis_do_post_content' );
add_action('genesis_before_loop','msdlab_archive_title');
function msdlab_archive_title(){
    if ( is_day() ) :
        $title = sprintf( __( 'Daily Archives: %s', 'veritas' ), '<span>' . get_the_date() . '</span>' );
    elseif ( is_month() ) :
        $title = sprintf( __( 'Monthly Archives: %s', 'veritas' ), '<span>' . get_the_date( _x( 'F Y', 'monthly archives date format', 'veritas' ) ) . '</span>' );
    elseif ( is_year() ) :
        $title = sprintf( __( 'Yearly Archives: %s', 'veritas' ), '<span>' . get_the_date( _x( 'Y', 'yearly archives date format', 'veritas' ) ) . '</span>' );
    elseif ( is_cpt('news') ):
        $title = sprintf( __( '%s', 'veritas' ), '<span>News</span>' );
    else :
        $title = _e( 'Archives', 'veritas' );
    endif;
    print '<h1 class="entry-title" itemprop="headline">'.$title.'</h1>';
}
if(is_cpt('post')){
    remove_action('genesis_sidebar','genesis_do_sidebar');
    add_action('genesis_sidebar','msdlab_do_blog_sidebar');
}
genesis();

<?php
error_reporting(E_ALL);
function get_all_content_info_into_table(){
    $args['post_type'] = 'post';
    $args['posts_per_page'] = -1;
    $viewpoints = new WP_Query($args);
    if ( $viewpoints->have_posts() ) {
            echo '
            <style>td{border: 1px solid #999;}</style>
            <table>
            <tr><th>Title</th><th>Author</th><th>Categories</th><th>Tags</th></tr>';
        while ( $viewpoints->have_posts() ) {
            $viewpoints->the_post();
            echo '<tr>';
            echo '<td>' . get_the_title() . '</td>';
            echo '<td>' . get_the_author() . '</td>';
            echo '<td>' . get_the_category_list() . '</td>';
            echo '<td>' . get_the_tag_list() . '</td>';
            echo '</tr>';
        }
            echo '</table>';
    } else {
        // no posts found
    }
}
add_action('genesis_loop','get_all_content_info_into_table');
genesis();

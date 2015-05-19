<?php
/**
 * Add new image sizes
 */
add_image_size('home-banner', 1080, 380, TRUE);
add_image_size('headshot-lg', 330, 264, TRUE);
add_image_size('headshot-md', 240, 195, TRUE);
add_image_size('headshot-sm', 115, 115, TRUE);
add_image_size('tab', 170, 170, TRUE);
add_image_size('mini-thumbnail', 90, 90, TRUE);
add_image_size('medlg', 500, 500, FALSE);
add_image_size('facebook', 200, 200, TRUE);
add_image_size('linkedin', 180, 110, TRUE);

//* Display a custom favicon
add_filter( 'genesis_pre_load_favicon', 'sp_favicon_filter' );
function sp_favicon_filter( $favicon_url ) {
    return get_stylesheet_directory_uri().'/lib/img/favicon.ico';
}

/**
 * Manipulate the featured image
 */
function msd_post_image() {
    global $post;
    //setup thumbnail image args to be used with genesis_get_image();
    $size = 'post-image'; // Change this to whatever add_image_size you want
    $default_attr = array(
            'class' => "alignnone attachment-$size $size",
            'alt'   => $post->post_title,
            'title' => $post->post_title,
    );

    // This is the most important part!  Checks to see if the post has a Post Thumbnail assigned to it. You can delete the if conditional if you want and assume that there will always be a thumbnail
    if ( has_post_thumbnail() && is_page() ) {
        printf( '<a title="%s" href="%s">%s</a>', get_permalink(), the_title_attribute( 'echo=0' ), genesis_get_image( array( 'size' => $size, 'attr' => $default_attr ) ) );
    }

}

/**
 * Add new image sizes to the media panel
 */
if(!function_exists('msd_insert_custom_image_sizes')){
function msd_insert_custom_image_sizes( $sizes ) {
	global $_wp_additional_image_sizes;
	if ( empty($_wp_additional_image_sizes) )
		return $sizes;

	foreach ( $_wp_additional_image_sizes as $id => $data ) {
		if ( !isset($sizes[$id]) )
			$sizes[$id] = ucfirst( str_replace( '-', ' ', $id ) );
	}

	return $sizes;
}
}
add_filter( 'image_size_names_choose', 'msd_insert_custom_image_sizes' );

add_shortcode('carousel','msd_bootstrap_carousel');
function msd_bootstrap_carousel($atts){
    $slidedeck = new SlideDeck();
    extract( shortcode_atts( array(
        'id' => NULL,
    ), $atts ) );
    $sd = $slidedeck->get($id);
    $slides = $slidedeck->fetch_and_sort_slides( $sd );
    $i = 0;
    foreach($slides AS $slide){
        $active = $i==0?' active':'';
        $items .= '
        <div style="background: url('.$slide['image'].') center top no-repeat #000000;background-size: cover;" class="item'.$active.'">
           '.$slide['content'].'
        </div>';
        $i++;
    }
    return msd_carousel_wrapper($items,array('id' => $id));
}

function msd_carousel_wrapper($slides,$params = array()){
    extract( array_merge( array(
    'id' => NULL,
    'navleft' => '‹',
    'navright' => '›'
    ), $params ) );
    return '
<div class="carousel slide" id="myCarousel_'.$id.'">
    <div class="carousel-inner">'.($slides).'</div>
    <a data-slide="prev" href="#myCarousel_'.$id.'" class="left carousel-control">'.$navleft.'</a>
    <a data-slide="next" href="#myCarousel_'.$id.'" class="right carousel-control">'.$navright.'</a>
</div>';
}
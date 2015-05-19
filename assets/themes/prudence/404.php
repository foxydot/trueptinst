<?php

//* Remove default loop
remove_action( 'genesis_loop', 'genesis_do_loop' );

add_action( 'genesis_loop', 'msdlab_404' );
/**
 * This function outputs a 404 "Not Found" error message
 *
 * @since 1.6
 */
function msdlab_404() {

	echo genesis_html5() ? '<article class="entry">' : '<div class="post hentry">';

		printf( '<h1 class="entry-title">%s</h1>', __( 'Not found, error 404', 'genesis' ) );
		echo '<div class="entry-content">';

			if ( genesis_html5() ) :

				echo '<p>' . sprintf( __( 'The page you are looking for no longer exists. Perhaps you can return back to the site\'s <a href="%s">homepage</a> and see if you can find what you are looking for. Or, you can try finding it by using the search form below.', 'genesis' ), home_url() ) . '</p>';

				echo '<p>' . get_search_form() . '</p>';

			else :
	?>

			<p><?php printf( __( 'The page you are looking for no longer exists. Perhaps you can return back to the site\'s <a href="%s">homepage</a> and see if you can find what you are looking for. Or, you can try finding it with the information below.', 'genesis' ), home_url() ); ?></p>

<?php
			endif;
            
            do_action('after_404');

			echo '</div>';

		echo genesis_html5() ? '</article>' : '</div>';

}

genesis();

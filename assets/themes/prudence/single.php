<?php
remove_action('genesis_sidebar','genesis_do_sidebar');
add_action('genesis_sidebar','msdlab_do_blog_sidebar');
genesis();
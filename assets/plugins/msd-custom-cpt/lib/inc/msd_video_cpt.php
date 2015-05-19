<?php
/**
 * @package MSD Video CPT
 * @version 0.1
 */

class MSDVideoCPT {
     //Properties
    var $cpt = 'msd_video';
    //Methods
    /**
    * PHP 4 Compatible Constructor
    */
    public function MSDVideoCPT(){$this->__construct();}

    /**
     * PHP 5 Constructor
     */
    function __construct(){
        global $wpalchemy_media_access,$current_screen;
        
        $this->plugin_url = plugin_dir_url('msd-custom-cpt/msd-custom-cpt.php');
        $this->plugin_path = plugin_dir_path('msd-custom-cpt/msd-custom-cpt.php');
        
        
        add_action( 'init', array(&$this,'register_cpt_video') );
        add_action( 'init', array(&$this,'register_taxonomy_video_tags') );
        add_action( 'init', array(&$this,'register_thumbnail') );
        //add_action('admin_head', array(&$this,'plugin_header'));
        
        add_action('widgets_init',array('MSD_Widget_Video_Remix','init'),10);
        
        add_action('admin_print_scripts', array(&$this,'add_admin_scripts') );
        add_action('admin_print_styles', array(&$this,'add_admin_styles') );
        
        add_shortcode( 'video-grid', array(&$this,'msd_video_grid') );
        add_shortcode( 'video-slider', array(&$this,'msd_video_slider') );  
        add_shortcode( 'video-popup-slider', array(&$this,'msd_video_popup_slider') );  
        add_shortcode( 'video-all', array(&$this,'all_video_items') );
        add_filter( 'the_content', array(&$this,'add_lazy_src_to_allowed_attributes') );
        add_filter( 'enter_title_here', array(&$this,'change_default_title') );
        wp_enqueue_script('lazy-bootstrap-carousel',$this->plugin_url.'lib/js/lazy-bootstrap-carousel.js',array('jquery','bootstrap-jquery'));
        wp_enqueue_script('msd-video-jquery',$this->plugin_url.'lib/js/msd-video.jquery.js',array('jquery','bootstrap-jquery'));
        add_action('admin_footer',array(&$this,'info_footer_hook') );

        if(!class_exists('WPAlchemy_MediaAccess')){
            include_once (WP_CONTENT_DIR . '/wpalchemy/MediaAccess.php');
        }

        $wpalchemy_media_access = new WPAlchemy_MediaAccess();
    }
    
    public function register_taxonomy_video_tags() {
    
        $labels = array(
                'name' => _x( 'Video Tags', 'video' ),
                'singular_name' => _x( 'Video Tag', 'video' ),
                'search_items' => _x( 'Search Video Tags', 'video' ),
                'popular_items' => _x( 'Popular Video Tags', 'video' ),
                'all_items' => _x( 'All Video Tags', 'video' ),
                'parent_item' => _x( 'Parent Video Tag', 'video' ),
                'parent_item_colon' => _x( 'Parent Video Tag:', 'video' ),
                'edit_item' => _x( 'Edit Video Tag', 'video' ),
                'update_item' => _x( 'Update Video Tag', 'video' ),
                'add_new_item' => _x( 'Add New Video Tag', 'video' ),
                'new_item_name' => _x( 'New Video Tag Name', 'video' ),
                'separate_items_with_commas' => _x( 'Separate video tags with commas', 'video' ),
                'add_or_remove_items' => _x( 'Add or remove video tags', 'video' ),
                'choose_from_most_used' => _x( 'Choose from the most used video tags', 'video' ),
                'menu_name' => _x( 'Video Tags', 'video' ),
        );
    
        $args = array(
                'labels' => $labels,
                'public' => true,
                'show_in_nav_menus' => true,
                'show_ui' => true,
                'show_tagcloud' => false,
                'hierarchical' => false,
    
                'rewrite' => true,
                'query_var' => true
        );
    
        register_taxonomy( 'msd_video_tag', array('msd_video'), $args );
    }
        
    function register_cpt_video() {
        $labels = array( 
            'name' => _x( 'Video Items', 'video' ),
            'singular_name' => _x( 'Video Item', 'video' ),
            'add_new' => _x( 'Add New', 'video' ),
            'add_new_item' => _x( 'Add New Video Item', 'video' ),
            'edit_item' => _x( 'Edit Video Item', 'video' ),
            'new_item' => _x( 'New Video Item', 'video' ),
            'view_item' => _x( 'View Video Item', 'video' ),
            'search_items' => _x( 'Search Video Items', 'video' ),
            'not_found' => _x( 'No video items found', 'video' ),
            'not_found_in_trash' => _x( 'No video items found in Trash', 'video' ),
            'parent_item_colon' => _x( 'Parent Video Item:', 'video' ),
            'menu_name' => _x( 'Video Items', 'video' ),
        );
    
        $args = array( 
            'labels' => $labels,
            'hierarchical' => true,
            'description' => 'Customer Videos',
            'supports' => array( 'title', 'editor', 'author', 'thumbnail','page-attributes'),
            'taxonomies' => array('msd_video_tag'),
            'public' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'menu_position' => 20,
            
            'show_in_nav_menus' => true,
            'publicly_queryable' => true,
            'exclude_from_search' => false,
            'has_archive' => true,
            'query_var' => true,
            'can_export' => true,
            'rewrite' => array('slug'=>'video','with_front'=>false),
            'capability_type' => 'post'
        );
    
        register_post_type( $this->cpt, $args );
    }

    function add_admin_scripts() {
        global $current_screen;
        if($current_screen->post_type == $this->cpt){
            wp_enqueue_script('media-upload');
            wp_enqueue_script('thickbox');
            wp_register_script('my-upload', plugin_dir_url(dirname(__FILE__)).'/js/msd-upload-file.js', array('jquery','media-upload','thickbox'),FALSE,TRUE);
            wp_enqueue_script('my-upload');
        }
    }
    
    function add_admin_styles() {
        global $current_screen;
        if($current_screen->post_type == $this->cpt){
            wp_enqueue_style('thickbox');
            wp_enqueue_style('custom_meta_css',plugin_dir_url(dirname(__FILE__)).'/css/meta.css');
        }
    }  
           
    function register_thumbnail(){
        if (class_exists('MultiPostThumbnails')) {
            new MultiPostThumbnails(
                    array(
                            'label' => 'Grid Thumbnail',
                            'id' => 'grid-image',
                            'post_type' => 'msd_video'
                    )
            );
        }
    }

    function plugin_header() {
        global $post_type;
        ?>
            <style>
            <?php if (($_GET['post_type'] == 'msd_video') || ($post_type == 'msd_video')) : ?>
            #icon-edit { background:transparent url('<?php echo get_stylesheet_directory_uri().'/lib/img/msd_video-over.png';?>') no-repeat; }      
            <?php endif; ?> 
            #adminmenu #menu-posts-msd_video div.wp-menu-image{background:transparent url("<?php echo get_stylesheet_directory_uri().'/lib/img/msd_video.png';?>") no-repeat center center;}
            #adminmenu #menu-posts-msd_video:hover div.wp-menu-image,#adminmenu #menu-posts-msd_profile.wp-has-current-submenu div.wp-menu-image{background:transparent url("<?php echo get_stylesheet_directory_uri().'/lib/img/msd_video-over.png';?>") no-repeat center center;} 
            </style>
            <?php
        }
        function get_video_items($tags){
            $args = array( 
                'post_type' => 'msd_video', 
                'numberposts' => -1,
                'order' => 'ASC',
                'orderby' => 'menu_order',
            );
            if(count($tags)>0){
                $args['tax_query'] =  array(
                        array(
                                'taxonomy' => 'msd_video_tag',
                                'field' => 'slug',
                                'terms' => $tags
                        )
                );
            }
            return get_posts($args);
        }


        function get_random_video($tags){
            $args = array( 
                'post_type' => 'msd_video', 
                'numberposts' => 1,
                'order' => 'ASC',
                'orderby' => 'rand',
            );
            if(strlen(trim($tags))>0){
                $tag_array = explode(',', $tags);
                $args['tax_query']['relation'] = "OR";
                foreach($tag_array AS $tag){
                    $args['tax_query'][] =  
                        array(
                            'taxonomy' => 'msd_video_tag',
                            'field' => 'slug',
                            'terms' => $tag
                    );
                }
            }
            $array_of_video = get_posts($args);
            return $array_of_video[0];
        }

        function get_video_items_for_team_member($team_id){
            global $video;
            $args = array( 
                'post_type' => 'msd_video', 
                'numberposts' => -1,
                'order' => 'ASC',
                'orderby' => 'menu_order',
                'meta_query' => array(
                   array(
                       'key' => '_video_team_members',
                       'value' => '"'.$team_id.'"',
                       'compare' => 'LIKE',
                   )
               )
            );
            $the_videos = get_posts($args);
            $i=0;
            foreach($the_videos AS $vid){
                $video->the_meta($vid->ID);
                $the_videos[$i]->video_url = $video->get_the_value('video_url');
                $i++;
            }
            return($the_videos);
        }
        
        function get_video_grid_image($item){
            global $video,$post;
            $video->the_meta($item->ID);
            $video_url = $video->get_the_value('video_url');
            if($video_url!=''){
                if (class_exists('MultiPostThumbnails') && $post_thumbnail_id = MultiPostThumbnails::get_post_thumbnail_id('msd_video', 'grid-image',$item->ID)) {
                    $featured_image = wp_get_attachment_image_src( $post_thumbnail_id, 'video', false, $attr );
                    $featured_image = $featured_image[0];
                } elseif(preg_match('/^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=)([^#\&\?]*).*/i',$video_url,$matches)){
                    $videoid = $matches[2];
                    $featured_image = 'http://img.youtube.com/vi/'.$videoid.'/0.jpg';
                } elseif(preg_match('/^.*(vimeo.com\/(video\/)?)([\d]*).*/i',$video_url,$matches)){
                    $static_url = 'http://vimeo.com/api/oembed.xml?url='.$video_url;
                    $xml = simplexml_load_file($static_url);
                    $featured_image = $xml->thumbnail_url;
                }
            } else {
                if (class_exists('MultiPostThumbnails') && $post_thumbnail_id = MultiPostThumbnails::get_post_thumbnail_id('msd_video', 'grid-image',$item->ID)) {
                    $featured_image = wp_get_attachment_image_src( $post_thumbnail_id, 'video', false, $attr );
                    $featured_image = $featured_image[0];
                } else {
                    $featured_image = featured_image($item->ID,'video');
                    $featured_image = $featured_image[0];
                }
            }
            return $featured_image;
        }
        
        function get_video_content($item){
            global $video,$post;
            $video->the_meta($item->ID);
            $video_url = $video->get_the_value('video_url');
            if($video_url!=''){
                if(preg_match('/^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=)([^#\&\?]*).*/i',$video_url,$matches)){
                    $video_url = preg_replace('@http(s)?\:\/\/@i', 'httpv://', $video_url);
                    $norelated = strrpos($video_url,'?')>1?'&rel=0':'?rel=0';
                    $content = $video_url.$norelated;
                    if(function_exists('lyte_parse')) { $content = lyte_parse($content); }
                } elseif(preg_match('/^.*(vimeo.com\/(video\/)?)([\d]*).*/i',$video_url,$matches)){
                    $static_url = 'http://vimeo.com/api/oembed.xml?url='.$video_url;
                    $xml = simplexml_load_file($static_url);
                    $content = $xml->html;
                }
            } else {
                $large_image = wp_get_attachment_image_src( get_post_thumbnail_id($item->ID),'large' );
                $content = $large_image?'<img lazy-src="'.$large_image[0].'" class="dropshadow" />':FALSE;
            }
            $content = array(
                    'title' => $item->post_title,
                    'description' => $item->post_content,
                    'image' => $content,
            );
            if($content_array = $this->get_additional_video_content($item)){
                array_unshift($content_array,$content);
                return $content_array;
            }
            $content_array[] = $content;
            return $content_array;
        }
        
        function get_additional_video_content($item){
            global $video,$post;
            if($video->have_fields('multientry')):
                while($video->have_fields('multientry')):
                    $content = array(
                        'title' => $video->get_the_value('title')?$video->get_the_value('title'):$item->post_title,
                        'description' => $video->get_the_value('description')?$video->get_the_value('description'):$item->post_content,
                        'image' => '<img src="'.$video->get_the_value('image').'" class="dropshadow" />',
                    );
                    $content_array[] = $content;
                endwhile; //end loop
                return $content_array;
            endif;
            return FALSE;
        }

        function msd_video_grid( $atts ){
            global $video,$post;
            if($atts['tags']){$atts['tags'] = explode(',',$atts['tags']);}
            extract( shortcode_atts( array(
            'tags' => array(),
            'cols' => 2,
            ), $atts ) );
            $ID = $tags[0];
                
            $items = $this->get_video_items($tags);
            //$count = (floor(count($items)/$cols))*$cols; //kill the orphans
            $count = count($items);
                        
            $items = array_slice($items, 0, $count);
            $i = 1;
            foreach($items AS $item){
                $video->the_meta($item->ID);
                //$video_url = $video->get_the_value('video_url');
                $video_url = preg_replace('@//vimeo.com/@i','//player.vimeo.com/video/',$video->get_the_value('video_url'));
                $featured_image = $this->get_video_grid_image($item);
                $content = $this->get_video_content($item);
        
                $menu .= '<li class="tab-'.$item->post_name.'"><iframe src="'.$video_url.'?title=0&amp;byline=0&amp;portrait=0" width="650" height="280" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe><h4>'.$item->post_title.'</h4></li>'."\n";
                $i++;
            }
        
            return "\n".'<div class="video-grid video-'.$ID.'">'."\n".'<ul class="grid">'."\n".$menu."\n".'</ul>'."\n".'<div class="content">'."\n".$slides."\n".'</div>'."\n".$nav."\n".'</div>';
        }
        function msd_video_list( $atts ){
            global $video,$post;
            extract( shortcode_atts( array(
            'tags' => '',
            'cols' => 4,
            ), $atts ) );
            $tags = explode(',',$tags);
            $ID = $tags[0];
            
            $items = $this->get_video_items($tags);
            $count = (floor(count($items)/$cols))*$cols;
            $items = array_slice($items, 0, $count);
            $i = 1;
            foreach($items AS $item){
                $video->the_meta($item->ID);
                $video_url = $video->get_the_value('video_url');
                $featured_image = $this->get_video_grid_image($item);
                $content = $this->get_video_content($item);
                
                $menu .= '<li class="tab-'.$item->post_name.'"><a href="#'.$item->post_name.'" title="'.$item->post_title.'" style="background:url('.$featured_image.') no-repeat center center;background-size:cover;" data-toggle="modal">'.$item->post_title.'</a><h3>'.$item->post_title.'</h3></li>'."\n";
                $j = 0;
                foreach ($content AS $piece){
                    if(!empty($piece['image'])){
                        $key = $j==0?'':'-'.$j;
                        $slides .=  '<div id="'.$item->post_name.$key.'" class="modal hide fade div-'.$item->post_name.$key.'" role="dialog">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                </div>
                                <div class="modal-body">
                                    <div class="video-piece">'.remove_wpautop(apply_filters('the_content', $piece['image'])).'</div>
                                    <h3 class="video-piece-title">'.$piece['title'].'</h3>
                                    <div class="entry-content">'.remove_wpautop(apply_filters('the_content', $piece['description'])).'</div>
                                </div>
                            </div>';
                        $j++;
                    }
                }
                $i++;               
            }

            return "\n".'<div class="video video-'.$ID.'">'."\n".'<ul class="nav">'."\n".$menu."\n".'</ul>'."\n".'<div class="content">'."\n".$slides."\n".'</div>'."\n".$nav."\n".'</div>';            
        }   

        function msd_video_slider( $atts ){
            global $video,$post;
            extract( shortcode_atts( array(
            'tags' => '',
            'cols' => 4,
            ), $atts ) );
            $tags = explode(',',$tags);
            $ID = $tags[0];
            $items = $this->get_video_items($tags);
            $count = (floor(count($items)/$cols))*$cols;
            $items = array_slice($items, 0, $count);
            $i = 1;
            foreach($items AS $item){
                $active = $i==1?' active':'';
                $video->the_meta($item->ID);
                $video_url = $video->get_the_value('video_url');
                $featured_image = $this->get_video_grid_image($item);
                $content = $this->get_video_content($item);
                $j = 0;
                foreach ($content AS $piece){
                    if(!empty($piece['image'])){
                        $key = $j==0?'':'-'.$j;
                        $slides .=  '<div id="'.$item->post_name.$key.'" class="item div-'.$item->post_name.$key.$active.'">
                            <div class="video-piece">'.remove_wpautop(apply_filters('the_content', $piece['image'])).'</div>
                            <h3 class="video-piece-title">'.$piece['title'].'</h3>
                            <div class="entry-content">'.remove_wpautop(apply_filters('the_content', $piece['description'])).'</div>
                        </div>';
                        $j++;
                    }
                }
                $i++;
            }
            $nav = ' <!-- Image loading -->
                <div class="loading hide"><i class="icon-spinner icon-spin icon-large"></i></div>
                    <!-- Carousel nav -->
            <a class="video-control video-control-'.$ID.' left" href="#video-'.$ID.'" data-slide="prev">&lsaquo;</a>
            <a class="video-control video-control-'.$ID.' right" href="#video-'.$ID.'" data-slide="next">&rsaquo;</a>';
        
            return "\n".'<div id="video-'.$ID.'" class="carousel slide video">'."\n".'<div class="carousel-inner">'."\n".$slides."\n".'</div>'."\n".$nav."\n".'</div>';
        }

        function msd_video_popup_slider( $atts ){
            global $video,$post;
            extract( shortcode_atts( array(
            'tags' => '',
            'cols' => 4,
            ), $atts ) );
            $tags = explode(',',$tags);
            $ID = $tags[0];
            $items = $this->get_video_items($tags);
            $count = (floor(count($items)/$cols))*$cols;
            $items = array_slice($items, 0, $count);
            $i = 1;
            foreach($items AS $item){
                $active = $i==1?' active':'';
                $video->the_meta($item->ID);
                $video_url = $video->get_the_value('video_url');
                $featured_image = $this->get_video_grid_image($item);
                $content = $this->get_video_content($item);             
                $menu .= '<li class="tab-'.$item->post_name.'" title="'.$item->post_title.'" style="background:url('.$featured_image.') no-repeat center center;background-size:cover;"><a href="#'.$ID.'" title="'.$item->post_name.'" data-toggle="modal">'.$item->post_title.'</a></li>'."\n";
                $j = 0;
                foreach ($content AS $piece){
                    if(!empty($piece['image'])){
                        $key = $j==0?'':'-'.$j;
                        $slides .=  '<div id="'.$item->post_name.$key.'" class="item div-'.$item->post_name.$key.$active.'">
                            <div class="video-piece">'.remove_wpautop(apply_filters('the_content', $piece['image'])).'</div>
                            <h3 class="video-piece-title">'.$piece['title'].'</h3>
                            <div class="entry-content">'.remove_wpautop(apply_filters('the_content', $piece['description'])).'</div>
                        </div>';
                        $j++;
                    }
                }           
                $i++;
            }
            $nav = ' <!-- Image loading -->
                <div class="loading hide"><i class="icon-spinner icon-spin icon-large"></i></div>
                <!-- Carousel nav -->
            <a class="video-control video-control-'.$ID.' left" href="#video-'.$ID.'" data-slide="prev">&lsaquo;</a>
            <a class="video-control video-control-'.$ID.' right" href="#video-'.$ID.'" data-slide="next">&rsaquo;</a>';
            $slides = '<div id="'.$ID.'" class="modal hide fade div-'.$ID.'" role="dialog">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div id="video-'.$ID.'" class="carousel slide video">
                            <div class="carousel-inner">
                                '.$slides.'
                            </div>
                                '.$nav.'
                        </div>
                    </div>
                </div>';
            return "\n".'<div class="video-grid video-popup-slider video-'.$ID.'">'."\n".'<ul class="nav">'."\n".$menu."\n".'</ul>'."\n".$slides."\n".'</div>';
        }
    //some utils
    function add_lazy_src_to_allowed_attributes($content){
        global $allowedposttags;
        $tags = array('a','img','button','div');
        $atts = array('lazy-src');
        foreach($tags AS $t){
            foreach($atts AS $a){
                $allowedposttags[$t][$a]=true;
            }
        }
        $content = wp_kses($content,$allowedposttags);
        return $content;
    }   
    
    
    
        function all_video_items( $atts ){
            global $video,$post;
            extract( shortcode_atts( array(
            'cols' => 4,
            ), $atts ) );
            $ID = 'all';
            $items = $this->get_video_items(array());
            $i = 1;
            foreach($items AS $item){
                $video->the_meta($item->ID);
                $video_url = $video->get_the_value('video_url');
                $featured_image = $this->get_video_grid_image($item);
                $content = $this->get_video_content($item);
        
                $menu .= '<li class="tab-'.$item->post_name.'" style="background:url('.$featured_image.') no-repeat center center;background-size:cover;"><a href="#'.$item->post_name.'" data-toggle="modal">'.$item->post_title.'</a><a href="'.get_edit_post_link( $item->ID ).'"><i class="icon-edit"></i></a>'.get_the_term_list($item->ID,'msd_video_tag').'</li>'."\n";
                $j = 0;
                foreach ($content AS $piece){
                    if(!empty($piece['image'])){
                        $key = $j==0?'':'-'.$j;
                        $slides .=  '<div id="'.$item->post_name.$key.'" class="item div-'.$item->post_name.$key.$active.'">
                            <div class="video-piece">'.remove_wpautop(apply_filters('the_content', $piece['image'])).'</div>
                            <h3 class="video-piece-title">'.$piece['title'].'</h3>
                            <div class="entry-content">'.remove_wpautop(apply_filters('the_content', $piece['description'])).'</div>
                        </div>';
                        $j++;
                    }
                }   
                $i++;
            }
        
            return "\n".'<div class="video-grid video-'.$ID.'">'."\n".'<ul class="nav">'."\n".$menu."\n".'</ul>'."\n".'<div class="content">'."\n".$slides."\n".'</div>'."\n".$nav."\n".'</div>';
        }
            
function change_default_title( $title ){
        $screen = get_current_screen();
        if  ( $screen->post_type == 'msd_video' ) {
            return __('Enter Video Title Here','msd_video');
        } else {
            return $title;
        }
    }
    
    function info_footer_hook()
    {
      if($current_screen->post_type == $this->cpt){
        
        ?><script type="text/javascript">
            jQuery('#titlediv').after(jQuery('#_video_metabox'));
            jQuery('#postdivrich').hide();
        </script><?php
        }
    }
}
$video_cpt = new MSDVideoCPT();

class MSD_Widget_Video_Remix extends WP_Widget {
    function __construct() {
        $widget_ops = array('classname' => 'widget_video_remix', 'description' => __('Selects and displays random video on page load.'));
        parent::__construct('video_remix', __('Video Remix'), $widget_ops, $control_ops);
    }
    function widget( $args, $instance ) {
        global $video;
        $video_cpt = new MSDVideoCPT();
        extract($args);
        echo $before_widget; 
        $post = $video_cpt->get_random_video($instance['tags']);
        $video->the_meta($post->ID);
        //$video_url = preg_replace('@https?:@i','',$video->get_the_value('video_url'));
        $video_url = preg_replace('@//vimeo.com/@i','//player.vimeo.com/video/',$video->get_the_value('video_url'));
        print '<h4 class="widget-title widgettitle">'.$post->post_title.'</h4>';
        print '<div class="wrap">
        <br />
        <iframe src="'.$video_url.'?title=0&amp;byline=0&amp;portrait=0" width="321" height="190" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';
        if(isset($instance['more_url']) && $instance['more_url'] != ''){
            print '<div class="link">
                    <a class="readmore" target="_self" href="'.$instance['more_url'].'">More Videos ></a>
                    <div class="clear"></div>
                    </div>';
        }
        print '
        <div class="clearfix"></div>
        </div>';
        echo $after_widget;
    }
    function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        $instance['tags'] = $new_instance['tags'];
        $instance['more_url'] = $new_instance['more_url'];
        return $instance;
    }
    function form( $instance ) {
        $instance = wp_parse_args( (array) $instance, array( 'tags' => '', 'more_url' => '' ) );
        $ret = '<p><label for="'.$this->get_field_id('tags').'">'.__('Tags').'</label>
        <input class="widefat" id="'.$this->get_field_id('tags').'" name="'.$this->get_field_name('tags').'" type="text" value="'.esc_attr($instance['tags']).'" />
        </p>';
        $ret .= '<p><label for="'.$this->get_field_id('more_url').'">'.__('URL for More Videos').'</label>
        <input class="widefat" id="'.$this->get_field_id('more_url').'" name="'.$this->get_field_name('more_url').'" type="text" value="'.esc_attr($instance['more_url']).'" />
        </p>';
        print $ret;
    }
    function init() {
        if ( !is_blog_installed() )
            return;
        register_widget('MSD_Widget_Video_Remix');
    }  
}
<?php 
if (!class_exists('MSDNewsCPT')) {
    class MSDNewsCPT {
        //Properties
        var $cpt = 'news';

        //Methods
        /**
        * PHP 4 Compatible Constructor
        */
        public function MSDNewsCPT(){$this->__construct();}
    
        /**
         * PHP 5 Constructor
         */
        function __construct(){
            global $current_screen;
            //"Constants" setup
            $this->plugin_url = plugin_dir_url('msd-custom-cpt/msd-custom-cpt.php');
            $this->plugin_path = plugin_dir_path('msd-custom-cpt/msd-custom-cpt.php');
            //Actions
            add_action( 'init', array(&$this,'register_taxonomy_news_category') );
            add_action( 'init', array(&$this,'register_cpt_news') );
            add_action( 'init', array(&$this,'add_custom_metaboxes') );
            add_action('admin_head', array(&$this,'plugin_header'));
            add_action('wp_enqueue_scripts', array(&$this,'add_front_scripts') );
            add_action('admin_enqueue_scripts', array(&$this,'add_admin_scripts') );
            add_action('admin_enqueue_scripts', array(&$this,'add_admin_styles') );
            add_action('admin_footer',array(&$this,'info_footer_hook') );
            // important: note the priority of 99, the js needs to be placed after tinymce loads
            add_action('admin_print_footer_scripts',array(&$this,'admin_print_footer_scripts'),99);
            
            //Filters
            //add_filter( 'pre_get_posts', array(&$this,'custom_query') );
            add_filter( 'enter_title_here', array(&$this,'change_default_title') );
            
        }

        function register_taxonomy_news_category(){
            
            $labels = array( 
                'name' => _x( 'News categories', 'news-category' ),
                'singular_name' => _x( 'News category', 'news-category' ),
                'search_items' => _x( 'Search news categories', 'news-category' ),
                'popular_items' => _x( 'Popular news categories', 'news-category' ),
                'all_items' => _x( 'All news categories', 'news-category' ),
                'parent_item' => _x( 'Parent news category', 'news-category' ),
                'parent_item_colon' => _x( 'Parent news category:', 'news-category' ),
                'edit_item' => _x( 'Edit news category', 'news-category' ),
                'update_item' => _x( 'Update news category', 'news-category' ),
                'add_new_item' => _x( 'Add new news category', 'news-category' ),
                'new_item_name' => _x( 'New news category name', 'news-category' ),
                'separate_items_with_commas' => _x( 'Separate news categories with commas', 'news-category' ),
                'add_or_remove_items' => _x( 'Add or remove news categories', 'news-category' ),
                'choose_from_most_used' => _x( 'Choose from the most used news categories', 'news-category' ),
                'menu_name' => _x( 'News categories', 'news-category' ),
            );
        
            $args = array( 
                'labels' => $labels,
                'public' => true,
                'show_in_nav_menus' => true,
                'show_ui' => true,
                'show_tagcloud' => false,
                'hierarchical' => true, //we want a "category" style taxonomy, but may have to restrict selection via a dropdown or something.
        
                'rewrite' => array('slug'=>'news-category','with_front'=>false),
                'query_var' => true
            );
        
            register_taxonomy( 'news_category', array($this->cpt), $args );
        }
        
        function register_cpt_news() {
        
            $labels = array( 
                'name' => _x( 'News', 'news' ),
                'singular_name' => _x( 'News', 'news' ),
                'add_new' => _x( 'Add New', 'news' ),
                'add_new_item' => _x( 'Add New News', 'news' ),
                'edit_item' => _x( 'Edit News', 'news' ),
                'new_item' => _x( 'New News', 'news' ),
                'view_item' => _x( 'View News', 'news' ),
                'search_items' => _x( 'Search News', 'news' ),
                'not_found' => _x( 'No news found', 'news' ),
                'not_found_in_trash' => _x( 'No news found in Trash', 'news' ),
                'parent_item_colon' => _x( 'Parent News:', 'news' ),
                'menu_name' => _x( 'News', 'news' ),
            );
        
            $args = array( 
                'labels' => $labels,
                'hierarchical' => false,
                'description' => 'News',
                'supports' => array( 'title', 'editor', 'author', 'thumbnail' ),
                'taxonomies' => array( 'news_category'),
                'public' => true,
                'show_ui' => true,
                'show_in_menu' => true,
                'menu_position' => 20,
                
                'show_in_nav_menus' => true,
                'publicly_queryable' => true,
                'exclude_from_search' => true,
                'has_archive' => true,
                'query_var' => true,
                'can_export' => true,
                'rewrite' => array('slug'=>'about-us/news','with_front'=>false),
                'capability_type' => 'post'
            );
        
            register_post_type( $this->cpt, $args );
        }
        
        function plugin_header() {
            global $post_type;
            ?>
            <?php
        }
        
        
    function print_footer_scripts()
        {

        }      
        function add_front_scripts(){
           global $post_type;
            if($post_type == $this->cpt){
            }
        }
         
        function add_admin_scripts() {
            global $current_screen;
            if($current_screen->post_type == $this->cpt){
                wp_enqueue_script('media-upload');
                wp_enqueue_script('thickbox');
            }
        }
        
        function add_admin_styles() {
            global $current_screen;
            if($current_screen->post_type == $this->cpt){
                wp_enqueue_style('thickbox');
                wp_enqueue_style('custom_meta_css',plugin_dir_url(dirname(__FILE__)).'css/meta.css');
            }
        }   
            
       function admin_print_footer_scripts()
        {
            global $current_screen, $areas;
            if($current_screen->post_type == $this->cpt){
                print '<script type="text/javascript">/* <![CDATA[ */
                    jQuery(function($)
                    {
                        var i=1;
                        $(\'.customEditor textarea\').each(function(e)
                        {
                            var id = $(this).attr(\'id\');
             
                            if (!id)
                            {
                                id = \'customEditor-\' + i++;
                                $(this).attr(\'id\',id);
                            }
             
                            tinyMCE.execCommand(\'mceAddControl\', false, id);
             
                        });
                                            
                        var img = $(\'.imagemap\'),
                            list = $(\'#statelist\');
                        
                        img.mapster({
                            mapKey: \'state\',
                            boundList: list.find(\'input\'),
                            listKey: \'value\',
                            listSelectedAttribute: \'checked\',
                            areas : ['.$areas.']
                        });
                    });
                /* ]]> */</script>';
                }
        }

        function change_default_title( $title ){
            global $current_screen;
            if  ( $current_screen->post_type == $this->cpt ) {
                return __('News Title','news');
            } else {
                return $title;
            }
        }
        
        function info_footer_hook()
        {
            global $current_screen;
            if($current_screen->post_type == $this->cpt){
                ?><script type="text/javascript">
                        jQuery('#postdivrich').before(jQuery('#_newsurl_metabox'));
                    </script><?php
            }
        }
        

        function custom_query( $query ) {
            if(!is_admin()){
                $is_news = ($query->query_vars['news_type'] || $query->query_vars['market_sector'])?TRUE:FALSE;
                if($query->is_main_query() && $query->is_search){
                    $searchterm = $query->query_vars['s'];
                    // we have to remove the "s" parameter from the query, because it will prevent the posts from being found
                    $query->query_vars['s'] = "";
                    
                    if ($searchterm != "") {
                        $query->set('meta_value',$searchterm);
                        $query->set('meta_compare','LIKE');
                    };
                    $query->set( 'post_type', array('post','page',$this->cpt) );
                }
                elseif( $query->is_main_query() && $query->is_archive && $is_news ) {
                    $meta_query = array(
                           array(
                               'key' => '_news_feature',
                               'value' => 'true',
                               'compare' => '='
                           )
                       );
                    $query->set( 'meta_query', $meta_query);
                    $query->set( 'post_type', array('post','page',$this->cpt) );
                    $query->set('posts_per_page', 30);
                }
            }
        }     
        function add_custom_metaboxes(){
            global $newsurl_metabox;
            $newsurl_metabox = new WPAlchemy_MetaBox(array
            (
                'id' => '_newsurl',
                'title' => 'URL to news item',
                'types' => array($this->cpt),
                'context' => 'normal', // same as above, defaults to "normal"
                'priority' => 'high', // same as above, defaults to "high"
                'template' => WP_PLUGIN_DIR.'/'.plugin_dir_path('msd-custom-cpt/msd-custom-cpt.php') . '/lib/template/newsurl-meta.php',
                'autosave' => TRUE,
                'mode' => WPALCHEMY_MODE_EXTRACT, // defaults to WPALCHEMY_MODE_ARRAY
                'prefix' => '_msdlab_' // defaults to NULL
            ));
        } 
        function do_news_url($url) {
            global $post;
            if($post->post_type == 'news'){
                global $newsurl_metabox;
                $newsurl_metabox->the_meta($post->ID);
                $newsurl = $newsurl_metabox->get_the_value('newsurl');
                if ( strlen( $newsurl ) == 0 ){
                    return $url;
                } else {
                    return msdlab_http_sanity_check($newsurl);
                }
            }
            return $url;
        } 
        function do_news_url_display(){
            global $newsurl_metabox, $post;$newsurl_metabox->the_meta();
            $newsurl = $newsurl_metabox->get_the_value('newsurl');
            if ( strlen( $newsurl ) == 0 || !is_single())
                return;
        
            $newsurl = sprintf( '<a class="entry-newsurl" href="%s">View Article</a>', msdlab_http_sanity_check($newsurl) );
            echo $newsurl . "\n";
        }    
  } //End Class
} //End if class exists statement
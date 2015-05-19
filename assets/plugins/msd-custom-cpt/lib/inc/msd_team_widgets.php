<?php
class MSD_Widget_Team_Viewpoints extends WP_Widget {

    function __construct() {
        $widget_ops = array('classname' => 'widget_team_viewpoints', 'description' => __('Viewpoint for a team user. Show on '));
        $control_ops = array('width' => 400, 'height' => 350);
        
        parent::__construct('team_viewpoints', __('Team Viewpoints'), $widget_ops, $control_ops);
    }

    function widget( $args, $instance ) {
        global $wp_query,$contact_info,$blogs;
        $team_member = $wp_query->get_queried_object();
        $team_member_id = $team_member->ID;
        $contact_info->the_meta($team_member_id);
        $team_member_author_id = $contact_info->get_the_value('_team_user_id');
        if($team_member_author_id){
        extract($args);
        $query_args = array(
            'post_type' => 'post',
            'author' => $team_member_author_id,
            'posts_per_page'   => 3,
        );
        $blogs = get_posts($query_args);
        
        $args = array(
            'post_type' => 'post',
            'posts_per_page' => '4',
            'meta_query' => array(
                array(
                    'key'     => '_coauthor_team_members',
                    'value'   => $team_member_id,
                    'compare' => 'LIKE',
                ),
            ),
        );
        if(count($blogs)>0){
            $args['suppress_filters'] = FALSE;
            add_filter('posts_where',array(&$this,'msdlab_modify_posts_where'));
        } 
        $viewpoints = get_posts($args);
        if(count($blogs)>0){
            remove_filter('posts_where',array(&$this,'msdlab_modify_posts_where'));
        }
        
        if(count($viewpoints)>0){
        $title = apply_filters( 'widget_title', empty($instance['title']) ? '' : $instance['title'], $instance, $this->id_base);
        echo $before_widget; ?>
        <div class="team-viewpoints">
            <?php if ( !empty( $title ) ) { echo $before_title . $title . $after_title; } ?>
            <div class="col-md-12">
            <?php foreach($viewpoints AS $viewpoint){
                print '<div class="article">
                <div class="title-link">'.$viewpoint->post_title.'</div>
                <div class="author">'.get_the_author_meta('display_name',$viewpoint->post_author);
                
                $coauthors = get_post_meta($viewpoint->ID,'_coauthor_team_members', TRUE);
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
                            if($i == $total_coauthors){
                                print ' and ';
                            } else {
                                print ', ';
                            }
                            print esc_html( $coauthor_bio->post_title );
                    }
                }
                
                
                
                
                
                
                
                
                
                print '</div>
                <div class="date">'.get_the_time( 'l, F j, Y', $viewpoint ).'</div>
                <a class="read-more" href="'.get_post_permalink($viewpoint->ID).'">Read More ></a>
                </div>';
            } ?>
            </div>
            <div class="clear"></div>
        </div>
        <?php
        
        print $url?'<a href="'.$url.'"'.$target.' class="msd-widget-text"></a>':'';
        echo $after_widget;
        }
        }
    }

    function msdlab_modify_posts_where($data){
        global $blogs,$wpdb;
        if(count($blogs)>0){
            foreach($blogs AS $k=>$v){
                $blogids[] = $v->ID;
            }
            $ids = implode(',',$blogids);
            $or_where = ' OR '.$wpdb->posts.'.ID IN ('.$ids.')';
            $new_data = $data.$or_where;
            return($new_data);
        } else {
            return($data);
        }
    }

    function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        return $instance;
    }

    function form( $instance ) {
        $instance = wp_parse_args( (array) $instance, array( 'title' => '', 'text' => '' ) );
        $title = strip_tags($instance['title']);
?>
        <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
        <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>        
<?php
    }
    
    function init() {
        if ( !is_blog_installed() )
            return;
        register_widget('MSD_Widget_Team_Viewpoints');
    }    
}

    
    add_action('widgets_init',array('MSD_Widget_Team_Viewpoints','init'),10);
?>
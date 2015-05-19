<?php 
if (!class_exists('MSDTeamDisplay')) {
    class MSDTeamDisplay {
        //Properties
        var $cpt = 'team_member';
        //Methods
        /**
        * PHP 4 Compatible Constructor
        */
        public function MSDTeamDisplay(){$this->__construct();}
    
        /**
         * PHP 5 Constructor
         */
        function __construct(){
            global $current_screen;
            //"Constants" setup
            $this->plugin_url = plugin_dir_url('msd-custom-cpt/msd-custom-cpt.php');
            $this->plugin_path = plugin_dir_path('msd-custom-cpt/msd-custom-cpt.php');
            //Actions
                     
            //Filters
            add_filter( 'genesis_attr_headshot', array(&$this,'msdlab_headshot_context_filter' ));
        }
 
        function get_team_member_by_practice($practice_area) {
           $args = array(
            'posts_per_page'   => -1,
            'orderby'          => 'title',
            'order'            => 'ASC',
            'practice_area'    => $practice_area,
            'post_type'        => $this->cpt,
            );
            $posts = get_posts($args);
            $i = 0;
            foreach($posts AS $post){
                $posts[$i]->lastname = get_post_meta($post->ID,'_team_member__team_member_last_name',TRUE);
                $i++;
            }
            usort($posts,array(&$this,'sort_by_lastname'));
            return $posts;
        }  
        
        function get_all_team_members(){
            $args = array(
            'posts_per_page'   => -1,
            'orderby'          => 'title',
            'order'            => 'ASC',
            'post_type'        => $this->cpt,
            );
            $posts = get_posts($args);
            $i = 0;
            foreach($posts AS $post){
                $posts[$i]->lastname = get_post_meta($post->ID,'_team_member__team_last_name',TRUE);
                $i++;
            }
            usort($posts,array(&$this,'sort_by_lastname'));
            return $posts;
        }     
        
        function team_display($team,$attr = array()){
            global $post,$msd_custom,$contact_info,$primary_practice_area,$jobtitle_metabox;
            extract($attr);
            $headshot = get_the_post_thumbnail($team->ID,'headshot-md');
            $terms = wp_get_post_terms($team->ID,'practice_area');
            $primary_practice_area->the_meta($team->ID);
            $jobtitle_metabox->the_meta($team->ID);
            $practice_areas = '';
            if(count($terms)>0){
                foreach($terms AS $term){
                    $practice_areas[] = $term->slug;
                }
                
                $practice_areas = implode(' ', $practice_areas);
            }
            $mini_bio = msdlab_excerpt($team->ID);
            $team_contact_info = '';
            $contact_info->the_meta($team->ID);
            $contact_info->the_field('_team_member_phone');
            if($contact_info->get_the_value() != ''){ 
                $team_contact_info .= '<li class="phone"><span class="fa-stack fa-lg"><i class="fa fa-circle fa-stack-2x"></i><i class="fa fa-phone fa-stack-1x fa-inverse"></i></span>'.msd_str_fmt($contact_info->get_the_value(),'phone').'</li>';
            } 
            
            $contact_info->the_field('_team_member_mobile');
            if($contact_info->get_the_value() != ''){
                $team_contact_info .= '<li class="mobile"><span class="fa-stack fa-lg"><i class="fa fa-circle fa-stack-2x"></i><i class="fa fa-mobile-phone fa-stack-1x fa-inverse"></i></span> '.msd_str_fmt($contact_info->get_the_value(),'phone').'</li>';
            }
            
            $contact_info->the_field('_team_member_linked_in');
            if($contact_info->get_the_value() != ''){
                $team_contact_info .= '<li class="linkedin"><a href="'.$contact_info->get_the_value().'"><span class="fa-stack fa-lg"><i class="fa fa-circle fa-stack-2x"></i><i class="fa fa-linkedin-square fa-stack-1x fa-inverse"></i></span> Connect</a></li>';
            }
            
            $contact_info->the_field('_team_member_bio_sheet');
            if($contact_info->get_the_value() != ''){
                $team_contact_info .= '<li class="vcard"><a href="'.$contact_info->get_the_value().'"><span class="fa-stack fa-lg"><i class="fa fa-circle fa-stack-2x"></i><i class="fa fa-download-alt fa-stack-1x fa-inverse"></i></span> Download Bio</a></li>';
            }
            
            $contact_info->the_field('_team_member_email');
            if($contact_info->get_the_value() != ''){
                $team_contact_info .= '<li class="email"><span class="fa-stack fa-lg"><i class="fa fa-circle fa-stack-2x"></i><i class="fa fa-envelope fa-stack-1x fa-inverse"></i></span> '.msd_str_fmt($contact_info->get_the_value(),'email').'</li>';
            }
            $teamstr = '
            <a class="team-member '.$practice_areas.' '.$team->post_name.'" href="'.get_post_permalink($team->ID).'">
                <div class="headshot">
                    '.$headshot.'
                </div>
                <div class="info">
                    <h4>'.$team->post_title.'</h4>
                    <h5>'.$jobtitle_metabox->get_the_value('jobtitle').'</h5>
                    ';
            $teamstr .= '
                </div>
            </a>';
            return $teamstr;
    }   
        
        function sort_by_lastname( $a, $b ) {
            return $a->lastname == $b->lastname ? 0 : ( $a->lastname < $b->lastname ) ? -1 : 1;
        } 
        
        function get_all_practice_areas(){
            return get_terms('practice_area',array('orderby'=>'slug','order'=>'ASC'));
        }
        
        function msd_add_team_member_headshot(){
            global $post;
            //setup thumbnail image args to be used with genesis_get_image();
            $size = 'headshot-lg'; // Change this to whatever add_image_size you want
            $default_attr = array(
                    'class' => "attachment-$size $size",
                    'alt'   => $post->post_title,
                    'title' => $post->post_title,
            );
        
            // This is the most important part!  Checks to see if the post has a Post Thumbnail assigned to it. You can delete the if conditional if you want and assume that there will always be a thumbnail
            genesis_markup( array(
                'html5'   => '<section %s>',
                'xhtml'   => '<div id="headshot" class="headshot-wrapper">',
                'context' => 'headshot'
            ) );
            do_action('msdlab_before_team_member_headshot');
            if ( has_post_thumbnail() ) {
                printf( '%s', genesis_get_image( array( 'size' => $size, 'attr' => $default_attr ) ) );
            }
            do_action('msdlab_after_team_member_headshot');
            genesis_markup( array(
                'html5'   => '</section>',
                'xhtml'   => '</div>'
            ) );
        }
        /**
         * Callback for dynamic Genesis 'genesis_attr_$context' filter.
         * 
         * Add custom attributes for the custom filter.
         * 
         * @param array $attributes The element attributes
         * @return array $attributes The element attributes
         */
        function msdlab_headshot_context_filter( $attributes ){
                $attributes['class'] .= ' alignleft';
                // return the attributes
                return $attributes;
        }
        
        function msd_team_member_contact_info(){
            global $post,$contact_info;
            $fields = array(
                    'phone' => 'phone',
                    'mobile' => 'mobile-phone',
                    'linkedin' => 'linkedin-square',
                    'vcard' => 'download-alt',
                    'email' => 'envelope-alt',
            );
            ?>
            <ul class="team-member-contact-info">
                <?php $contact_info->the_field('_team_phone'); ?>
                <?php if($contact_info->get_the_value() != ''){ ?>
                    <li class="phone"><span class="fa-stack fa-lg"><i class="fa fa-circle fa-stack-2x"></i><i class="fa fa-phone fa-stack-1x fa-inverse"></i></span> <?php //print msd_str_fmt($contact_info->get_the_value(),'phone'); ?></li>
                <?php } ?>
                
                <?php $contact_info->the_field('_team_mobile'); ?>
                <?php if($contact_info->get_the_value() != ''){ ?>
                    <li class="mobile"><span class="fa-stack fa-lg"><i class="fa fa-circle fa-stack-2x"></i><i class="fa fa-mobile-phone fa-stack-1x fa-inverse"></i></span> <?php //print msd_str_fmt($contact_info->get_the_value(),'phone'); ?></li>
                <?php } ?>
                
                <?php $contact_info->the_field('_team_linked_in'); ?>
                <?php if($contact_info->get_the_value() != ''){ ?>
                    <li class="linkedin"><a href="<?php print $contact_info->get_the_value(); ?>"><span class="fa-stack fa-lg"><i class="fa fa-circle fa-stack-2x"></i><i class="fa fa-linkedin-square fa-stack-1x fa-inverse"></i></span></a></li>
                <?php } ?>
                
                <?php $contact_info->the_field('_team_bio_sheet'); ?>
                <?php if($contact_info->get_the_value() != ''){ ?>
                    <li class="vcard"><a href="<?php print $contact_info->get_the_value(); ?>"><span class="fa-stack fa-lg"><i class="fa fa-circle fa-stack-2x"></i><i class="fa fa-download-alt fa-stack-1x fa-inverse"></i></span></a></li>
                <?php } ?>
                
                <?php $contact_info->the_field('_team_email'); ?>
                <?php if($contact_info->get_the_value() != ''){ ?>
                    <li class="email"><a href="mailto:<?php print antispambot($contact_info->get_the_value());?>" class="email"><span class="fa-stack fa-lg"><i class="fa fa-circle fa-stack-2x"></i><i class="fa fa-envelope fa-stack-1x fa-inverse"></i></span> <?php //print msd_str_fmt($contact_info->get_the_value(),'email'); ?></a></li>
                <?php } ?>
            </ul>
            <?php
        }
        function msd_team_additional_info(){
            global $post,$additional_info;
            $fields = array(
                    'experience' => 'Experience',
                    'decisions' => 'Notable Decisions',
                    'honors' => 'Honors/Distinctions',
                    'admissions' => 'Admissions',
                    'affiliations' => 'Professional Affiliations',
                    'community' => 'Community Involvement',
                    'presentations' => 'Presentations',
                    'publications' => 'Publications',
                    'education' => 'Education',
            );
            $i = 0; ?>
            <h3 class="toggle">More Info<span class="expand">Expand <i class="fa fa-angle-down"></i></span><span class="collapse">Collapse <i class="fa fa-angle-up"></i></span></h3>
            <ul class="team-additional-info">
            <?php
            foreach($fields AS $k=>$v){
            ?>
                <?php $additional_info->the_field('_team_'.$k); ?>
                <?php if($additional_info->get_the_value() != ''){ ?>
                    <li>
                        <h3><?php print $v; ?></h3>
                        <?php print font_awesome_lists(apply_filters('the_content',$additional_info->get_the_value())); ?>
                    </li>
                <?php 
                $i++;
                }
            } ?>
            </ul>
            <?php
        }
        function font_awesome_lists($str){
            $str = strip_tags($str,'<a><li><ul><h3><b><strong><i>');
            $str = preg_replace('/<ul(.*?)>/i','<ul class="icons-ul"\1>',$str);
            $str = preg_replace('/<li>/i','<li><i class="fa fa-li fa fa-caret-right"></i>',$str);
            return $str;
        }
        function msd_team_sidebar(){
            global $post,$primary_practice_area;
            $terms = wp_get_post_terms($post->ID,'practice_area');
            $ppa = $primary_practice_area->get_the_value('primary_practice_area');
            print '<div class="sidebar-content">';
            if(count($terms)>0){
                print '<div class="widget">
                    <div class="widget-wrap">
                    <h4 class="widget-title widgettitle">Practice Areas</h4>
                    <ul>';
                foreach($terms AS $term){
                    if($term->slug == $ppa){
                        if($test = get_page_by_path('/practice-areas/'.$term->slug)){
                         $ret = '<li><a href="/practice-areas/'.$term->slug.'">'.$term->name.'</a></li>'.$ret;
                        } else {
                         $ret = '<li>'.$term->name.'</li>'.$ret;
                        }
                    } else {
                        if($test = get_page_by_path('/practice-areas/'.$term->slug)){
                         $ret .= '<li><a href="/practice-areas/'.$term->slug.'">'.$term->name.'</a></li>';
                        } else {
                         $ret .= '<li>'.$term->name.'</li>';
                        }
                    }
                }
                print $ret;
                print '</ul>
                </div>
                </div>';
            }
            dynamic_sidebar('team-sidebar');
            print '</div>';
        }
        function msd_do_team_member_job_title() {
            global $jobtitle_metabox;
            $jobtitle_metabox->the_meta();
            $jobtitle = $jobtitle_metabox->get_the_value('jobtitle');
        
            if ( strlen( $jobtitle ) == 0 )
                return;
        
            $jobtitle = sprintf( '<h2 class="entry-subtitle">%s</h2>', apply_filters( 'genesis_post_title_text', $jobtitle ) );
            echo apply_filters( 'genesis_post_title_output', $jobtitle ) . "\n";
        }
  } //End Class
} //End if class exists statement
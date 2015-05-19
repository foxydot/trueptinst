<?php
/*
Template Name: Quarterly Insights
*/

global $quarterly_insights, $qi_meta;
$qi_meta = $quarterly_insights->the_meta();

//remove_action( 'genesis_loop', 'genesis_do_loop' );
//add_action( 'genesis_before_loop', 'msdlab_do_qi_header' );
//add_action( 'genesis_loop', 'msdlab_do_qi_loop' );
add_action( 'genesis_after_loop', 'msdlab_add_supplemental');
add_action( 'print_footer_scripts', 'msdlab_qi_footer_js');
//remove_action('genesis_before_content_sidebar_wrap', 'msdlab_do_breadcrumbs'); //to outside of the loop area


function msdlab_do_qi_header(){
    $ret = '<div class="orange-banner"><div class="wrap">Quarterly Insights</div></div>';
    print $ret;
}

function msdlab_do_qi_loop(){
    global $qi_meta;
    $args['p'] = $qi_meta['featured-insight'];
    remove_action('genesis_entry_content','genesis_do_post_content');
    add_action('genesis_entry_content','the_excerpt',10);
    genesis_custom_loop( $args );
}

function msdlab_add_supplemental(){
    global $qi_meta;
    foreach($qi_meta['supplements'] AS $sup){
        $args = array(
            'post_type' => 'team_member',
            'p'=> $sup['supplement-author'],
        );
        $size = 'headshot-sm';
        $author_bio = array_pop(get_posts($args));
        if($author_bio){
            $author_attr = array(
                'class' => "hidden-xs",
                'alt'   => trim($author_bio->post_title),
                'title' => trim($author_bio->post_title),
            );
            $thumb = get_the_post_thumbnail($author_bio->ID,$size,$author_attr);
            $position = get_post_meta($author_bio->ID,'_msdlab_jobtitle',TRUE);
        } else {
            $attr = array(
                'class' => "hidden-xs",
            );
            $attachment_id = get_option('msdsocial_default_avatar');
            $thumb = wp_get_attachment_image( $attachment_id, $size, 0, $attr );
        }
        $links .= '<li><a href="#'.sanitize_title_with_dashes($sup['supplement-title']).'">'.$sup['supplement-title'].'</a></li>';
        $content .= '<div class="supplemental-article">
        <header class="entry-header">'.$thumb.'
        <h2 itemprop="headline" id="'.sanitize_title_with_dashes($sup['supplement-title']).'" class="entry-title">'.$sup['supplement-title'].'</h2>
        <p class="entry-meta">Contributed by <span itemtype="http://schema.org/Person" itemscope="itemscope" itemprop="author" class="entry-author"><a rel="author" itemprop="url" class="entry-author-link" href="'.get_the_permalink($author_bio->ID).'"><span itemprop="name" class="entry-author-name">'.trim($author_bio->post_title).'</span></a></span><br />
        <span class="job-title">'.$position.'</span>
        </header>
        '.apply_filters('the_content',$sup['supplement-content']).'
        <div class="back-to-top"><a href="#">Back to Top <i class="fa fa-angle-up"></i></a></div>
        </div>';
    }
    //print '<div class="supplemental-anchors"><h2 class="entry-subtitle">More Quarterly Insights</h2>';
    print '<ul class="qi_links">'.$links.'</ul>';
    print $content;
}

function msdlab_qi_footer_js(){
    print '<script type="text/javascript">
    jQuery(document).ready(function($) {
        //$(".supplemental-article").equalHeightColumns();
        $(".back-to-top a").click(function(event){
            event.preventDefault();
            $("body,html").animate({
                scrollTop: 0 ,
                }, 500
            );
        });
        $(".qi_links li a").click(function(event){
            event.preventDefault();
            var id = $(this).attr("href");
            $("body,html").animate({
                scrollTop: $(id).offset().top,
                }, 500
            );
        });
    });
    </script>';
}
genesis();

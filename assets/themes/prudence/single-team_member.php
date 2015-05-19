<?php
$msd_team_display = new MSDTeamDisplay;
remove_action('genesis_sidebar','genesis_do_sidebar');
add_action('genesis_sidebar','msdlab_do_about_us_sidebar');
function msdlab_do_about_us_sidebar(){
    if(is_active_sidebar('team')){
        dynamic_sidebar('team');
    }
}
add_action('genesis_entry_content',array(&$msd_team_display,'msd_add_team_member_headshot'),2);
add_action('msdlab_after_team_member_headshot',array(&$msd_team_display,'msd_team_member_contact_info'));
add_action('genesis_entry_header',array(&$msd_team_display,'msd_do_team_member_job_title'));
remove_action( 'genesis_entry_header', 'genesis_post_info', 12);
//global $wp_filter; ts_var( $wp_filter['genesis_entry_header'] );
genesis();
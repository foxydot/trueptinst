<?php
/*
Template Name: Team Index
*/
add_filter( 'genesis_pre_get_option_site_layout', '__genesis_return_full_width_content' );
function msdlab_team_filter(){
   $msd_team_display = new MSDTeamDisplay;
   $terms = $msd_team_display->get_all_practice_areas();
   $allbutton = '<div id="filters" class="allbutton"><a href="#" data-filter="*" class="active">View All Team Members</a></div>';
   foreach($terms AS $term){
       $filters[] = '<a href="#" data-filter=".'.$term->slug.'">'.$term->name.'</a>';
   }
   $menu = $allbutton.'<div id="filters">'.implode(' | ', $filters).'</div>';
   print $menu;
}
add_action('genesis_entry_content','msdlab_team_filter');
function msdlab_team(){
    $msd_team_display = new MSDTeamDisplay;
    $team = $msd_team_display->get_all_team_members();
    print '<div id="team-members">';
    foreach($team AS $team_member){
        print $msd_team_display->team_display($team_member);
    }
    print '</div>';
}
add_action('genesis_entry_content','msdlab_team');
function msdlab_team_footer_scripts(){
    print '<script>
        jQuery(window).load(function() {
            jQuery("#team-members").isotope({
              itemSelector : ".team-member",
              layoutMode: "fitRows",
            }).css("min-height","2056px"); 
            
            // filter items when filter link is clicked
            jQuery("#filters a").click(function(){
              jQuery("#filters a").removeClass("active");
              jQuery(this).addClass("active");
              var selector = jQuery(this).attr("data-filter");
              jQuery("#team-members").isotope({
                  itemSelector : ".team-member",
                  layoutMode : "fitRows",
                  filter: selector
                }); 
              return false;
            });   
            jQuery( window ).scroll(function() {
                jQuery("#team-members").isotope();
            });
        } );
    </script>';
}
add_action('wp_footer','msdlab_team_footer_scripts');
genesis();
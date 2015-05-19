<?php
$practice_areas = array_values(get_the_terms($post->ID, 'practice_area'));
if(count($practice_areas)==0){
    $practice_areas = array_values(get_terms('practice_area'));
}
$mb->the_field('primary_practice_area');
print 'Reduce your choices by selecting all practice areas for this team member and saving the team member listing before choosing a Primary Practice Area!';
print '<br/ ><select name="'.$mb->get_the_name().'">
    <option value="">Select...</option>';
    foreach($practice_areas AS $pa){
        print '<option value="'.$pa->slug.'"'.$mb->the_select_state("$pa->slug").'>'.$pa->name.'</option>';
    }
print '</select>';
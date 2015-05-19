<?php 
$fields = array(
	'experience' => 'Experience',
	'honors' => 'Honors/Distinctions',
	'affiliations' => 'Professional Affiliations',
	'community' => 'Community Involvement',
	'presentations' => 'Presentations',
	'publications' => 'Publications',
	'education' => 'Education',
);
$i = 0; ?>
<ul class="team_meta_control customEditor gform_fields top_label description_below">
<?php
foreach($fields AS $k=>$v){
?>
	<?php $mb->the_field('_team_'.$k); ?>
	<li class="gfield even" id="field_<?php $mb->the_name(); ?>"><label for="<?php $mb->the_name(); ?>"
		class="gfield_label"><?php print $v; ?></label>
	<div class="ginput_container last-child even">
			<textarea name="<?php print $mb->get_the_name(); ?>" id="<?php print $mb->get_the_name(); ?>"><?php print $mb->get_the_value(); ?></textarea>
			<?php // wp_editor($mb->get_the_value(),$mb->get_the_name(),array()); ?>
		</div>
	</li>
<?php 
$i++;
} ?>
</ul>
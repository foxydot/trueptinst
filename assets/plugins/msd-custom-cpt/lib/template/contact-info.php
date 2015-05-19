<?php global $wpalchemy_media_access; ?>
<ul class="team_meta_control gform_fields top_label description_below" id="gform_fields_4">
	<?php $mb->the_field('_team_last_name'); ?>
	<li class="gfield gfield_contains_required even"
		id="field_team_last_name"><label for="<?php $mb->the_name(); ?>" class="gfield_label">Last Name (for alphabetizing)
	</label>
	<div class="ginput_container last-child even">
			<input type="text" tabindex="24" class="medium" value="<?php $mb->the_value(); ?>"
				id="<?php $mb->the_name(); ?>" name="<?php $mb->the_name(); ?>">
		</div>
	</li>
    <?php $mb->the_field('_team_user_id'); ?>
    <li class="gfield"
        id="field_team_user_id"><label for="<?php $mb->the_name(); ?>" class="gfield_label">Blog User (for blog posts)
    </label>
    <div class="ginput_container">
            <?php $blogusers = get_users(); ?>
            <select id="<?php $mb->the_name(); ?>" name="<?php $mb->the_name(); ?>">
                <option value=0>No Viewpoints</option>
                <?php foreach($blogusers AS $bu){ ?>
                    <option value="<?php print $bu->ID; ?>"<?php $mb->the_select_state($bu->ID); ?>><?php print $bu->display_name; ?></option>
                <?php } ?>
            </select>
        </div>
    </li>
	<?php $mb->the_field('_team_phone'); ?>
	<li class="gfield gfield_contains_required even"
		id="field_team_phone"><label for="<?php $mb->the_name(); ?>" class="gfield_label">Phone
	</label>
	<div class="ginput_container last-child even">
			<input type="text" tabindex="28" class="medium" value="<?php $mb->the_value(); ?>"
				id="<?php $mb->the_name(); ?>" name="<?php $mb->the_name(); ?>">
		</div>
	</li>
	<?php $mb->the_field('_team_mobile'); ?>
	<li class="gfield gfield_contains_required even"
		id="field_team_mobile"><label for="<?php $mb->the_name(); ?>" class="gfield_label">Mobile
	</label>
	<div class="ginput_container last-child even">
			<input type="text" tabindex="29" class="medium" value="<?php $mb->the_value(); ?>"
				id="<?php $mb->the_name(); ?>" name="<?php $mb->the_name(); ?>">
		</div>
	</li>
	<?php $mb->the_field('_team_fax'); ?>
	<li class="gfield even" id="field_team_fax"><label for="<?php $mb->the_name(); ?>"
		class="gfield_label">Fax</label>
	<div class="ginput_container last-child even">
			<input type="text" tabindex="30" class="medium" value="<?php $mb->the_value(); ?>"
				id="<?php $mb->the_name(); ?>" name="<?php $mb->the_name(); ?>">
		</div>
	</li>
	<?php $mb->the_field('_team_linked_in'); ?>
	<li class="gfield even" id="field_team_linked_in"><label for="<?php $mb->the_name(); ?>"
		class="gfield_label">Linked In URL</label>
	<div class="ginput_container last-child even">
			<input type="text" tabindex="32" class="medium" value="<?php $mb->the_value(); ?>"
				id="<?php $mb->the_name(); ?>" name="<?php $mb->the_name(); ?>" placeholder="http://">
		</div>
	</li>
	
    
    <?php $mb->the_field('_team_vcard'); ?>
    <li class="gfield even" id="field_team_vcard"><label for="<?php $mb->the_name(); ?>"
        class="gfield_label">vCard File</label>
    <div class="ginput_container last-child even">
        <?php $wpalchemy_media_access->setGroupName('team_vcard'. $mb->get_the_index())->setInsertButtonLabel('Insert This')->setTab('gallery'); ?>
        
        <?php echo $wpalchemy_media_access->getField(array('name' => $mb->get_the_name(), 'value' => $mb->get_the_value())); ?>
        <?php echo $wpalchemy_media_access->getButton(array('label' => '+')); ?>
        </div>
    </li>   
    
    <?php $mb->the_field('_team_bio_sheet'); ?>
    <li class="gfield even" id="field_team_bio_sheet"><label for="<?php $mb->the_name(); ?>"
        class="gfield_label">Bio Sheet File</label>
    <div class="ginput_container last-child even">
        <?php $wpalchemy_media_access->setGroupName('team_bio_sheet'. $mb->get_the_index())->setInsertButtonLabel('Insert This')->setTab('gallery'); ?>
        
        <?php echo $wpalchemy_media_access->getField(array('name' => $mb->get_the_name(), 'value' => $mb->get_the_value())); ?>
        <?php echo $wpalchemy_media_access->getButton(array('label' => '+')); ?>
        </div>
    </li>
	
	<?php $mb->the_field('_team_email'); ?>
	<li class="gfield last-child odd" id="field_team_email"><label
		for="<?php $mb->the_name(); ?>" class="gfield_label">Email</label>
	<div class="ginput_container last-child even">
			<input type="text" tabindex="34" class="medium" value="<?php $mb->the_value(); ?>"
				id="<?php $mb->the_name(); ?>" name="<?php $mb->the_name(); ?>">
		</div>
	</li>
</ul>
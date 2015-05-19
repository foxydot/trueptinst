<ul class="meta_control">
    <li>
    <?php $metabox->the_field('project_id'); ?>
    <label id="<?php $metabox->the_name(); ?>_label" for="<?php $metabox->the_name(); ?>">Project ID#</label>
    <div class="ginput_container"><input type="text" tabindex="1" value="<?php $metabox->the_value(); ?>" id="<?php $metabox->the_name(); ?>" name="<?php $metabox->the_name(); ?>"></div>
    </li>
    <li>
    <?php $metabox->the_field('feature'); ?>
    <label id="<?php $metabox->the_name(); ?>_label" for="<?php $metabox->the_name(); ?>">Feature with Abstract</label>
    <div class="ginput_container"><input type="checkbox" tabindex="2" name="<?php $mb->the_name(); ?>" value="true"<?php $mb->the_checkbox_state('true'); ?>/></div>
    </li>
</ul>
<?php global $wpalchemy_media_access; ?>
<style>
    .aboutyou_meta_control .table {display: block; width: 100%;}
    .aboutyou_meta_control .row {display: block;cursor: move;border-bottom: 1px solid #333;}
    .aboutyou_meta_control .row:before,
.aboutyou_meta_control .row:after {
    content: " "; /* 1 */
    display: table; /* 2 */
}

.aboutyou_meta_control .row:after {
    clear: both;
}

/**
 * For IE 6/7 only
 * Include this rule to trigger hasLayout and contain floats.
 */
.aboutyou_meta_control .row {
    *zoom: 1;
}
.aboutyou_meta_control .cell {display: block; clear: both;margin-left: 1rem;}
    .even {background: #eee;}
    .odd {background: #fff;}
    .file input[type="text"] {width: 75%}
    .aboutyou_meta_control label{ display:block; font-weight:bold; margin-right: 1%;float: left; width: 14%; text-align: right;}
 .input_container{width: 85%;float: left;}
.aboutyou_meta_control textarea, .aboutyou_meta_control input[type='text'], .aboutyou_meta_control select,.aboutyou_meta_control .wp-editor-wrap
{ display:inline;margin-bottom:3px; width: 90%;
     }
     .aboutyou_meta_control .file input[type='text']{width: 76%;}
</style>
<div class="aboutyou_meta_control">
 <p id="warning" style="display: none;background:lightYellow;border:1px solid #E6DB55;padding:5px;">Order has changed. Please click Save or Update to preserve order.</p>
    <div class="table">
    <?php $i = 0; ?>
    <?php while($mb->have_fields_and_multi('tabs')): ?>
    <?php $mb->the_group_open(); ?>
    <div class="row <?php print $i%2==0?'even':'odd'; ?>">
        <div class="cell">
        <?php $mb->the_field('title'); ?>
        <label>Tab Title</label>            
        <div class="input_container">
            <input type="text" name="<?php $mb->the_name(); ?>" value="<?php $mb->the_value(); ?>"/></div>
        </div>
        <div class="cell file">
            <label>Tab Image</label>
            <div class="input_container">
        <?php $mb->the_field('image'); ?>
        <?php $wpalchemy_media_access->setGroupName('tab-img'. $mb->get_the_index())->setInsertButtonLabel('Insert This')->setTab('gallery'); ?>
        <?php echo $wpalchemy_media_access->getField(array('name' => $mb->get_the_name(), 'value' => $mb->get_the_value())); ?>
        <?php echo $wpalchemy_media_access->getButton(array('label' => 'Add Image')); ?>
            </div>
        </div>
        <div class="cell">
            <label>Tab Content</label>
            <div class="input_container">
                <?php 
                $mb->the_field('content');
                $mb_content = html_entity_decode($mb->get_the_value(), ENT_QUOTES, 'UTF-8');
                $mb_editor_id = sanitize_key($mb->get_the_name());
                $mb_settings = array('textarea_name'=>$mb->get_the_name(),'textarea_rows' => '5',);
                wp_editor( $mb_content, $mb_editor_id, $mb_settings );
                ?>
           </div>
        </div>
        <div class="cell">
            <a href="#" class="dodelete button alignright">Remove Tab</a>
        </div>
    </div>
    <?php $i++; ?>
    <?php $mb->the_group_close(); ?>
    <?php endwhile; ?>
    </div>
    <p style="margin-bottom:15px; padding-top:5px;"><a href="#" class="docopy-tabs button">Add Tab</a>
    <a href="#" class="dodelete-tabs button">Remove All Tabs</a></p>
</div>
<script>
jQuery(function($){
    $("#wpa_loop-tabs").sortable({
        change: function(){
            $("#warning").show();
        }
    });
});</script>
<?php global $wpalchemy_media_access; ?>

<?php 

$postid = is_admin()?$_GET['post']:$post->ID;
$template_file = get_post_meta($postid,'_wp_page_template',TRUE);
  // check for a template type
if (is_admin()){
    if($template_file == 'quarterly-insights.php' ) {
        $qi = new quarterlyInsights;
         ?>
<div class="my_meta_control">
    <p id="warning" style="display: none;background:lightYellow;border:1px solid #E6DB55;padding:5px;">Order has changed. Please click Save or Update to preserve order.</p>
    <div class="table">
        <?php /* ?>
    <div class="row">
        <div class="insight box">
            <div class="cell">
                <?php $mb->the_field('featured-insight'); ?>
                <label>Featured Insight</label>            
                <div class="input_container">
                    <select name="<?php $mb->the_name(); ?>">
                        <?php print $qi->insight_select($mb->get_the_value()); ?>
                    </select>
                </div>
            </div>
        </div>
    </div>
    <div class="row"><hr></div>
        <?php */ ?>
    <?php $i = 1; ?>
    <?php while($mb->have_fields_and_multi('supplements')): ?>
    <?php $mb->the_group_open(); ?>
    <div class="row <?php print $i%2==1?'even':'odd'; ?>">
        <div class="content-area box">
            <div class="cell">
                <?php $mb->the_field('supplement-title'); ?>
                <label>Title</label>            
                <div class="input_container">
                    <input type="text" name="<?php $mb->the_name(); ?>" value="<?php $mb->the_value(); ?>"/>
                </div>
            </div>
            <div class="cell">
                <?php $mb->the_field('supplement-author'); ?>
                <label>Supplement Author</label>            
                <div class="input_container">
                    <select name="<?php $mb->the_name(); ?>">
                        <?php print $qi->author_select($mb->get_the_value()); ?>
                    </select>
                </div>
            </div>
            <div class="cell">
                <label>Content</label>
                <div class="input_container">
                    <?php 
                    $mb->the_field('supplement-content');
                    $mb_content = html_entity_decode($mb->get_the_value(), ENT_QUOTES, 'UTF-8');
                    $mb_editor_id = sanitize_key($mb->get_the_name());
                    $mb_settings = array('textarea_name'=>$mb->get_the_name(),'textarea_rows' => '5',);
                    wp_editor( $mb_content, $mb_editor_id, $mb_settings );
                    ?>
               </div>
            </div>
        </div>
        <div class="cell footer">
            <a href="#" class="dodelete button alignright">Remove Supplement <?php print $i ?></a>
        </div>
    </div>
    <?php $i++; ?>
    <?php $mb->the_group_close(); ?>
    <?php endwhile; ?>
    </div>
    <p style="margin-bottom:15px; padding-top:5px;"><a href="#" class="docopy-supplements button">Add Supplemental Insight</a>
</div>
<?php
} else {
    print "Select \"Quarterly Insights\" template and save to activate.";
}
} 
class quarterlyInsights{
    function __construct(){
        add_action('admin_footer',array(&$this,'subtitle_footer_hook'));
    }
    
    function author_select($value){
        $team = new MSDTeamDisplay;
        $team_members = $team->get_all_team_members();
        $ret = '<option value="">--SELECT--</option>';
        foreach ($team_members as $item):
        $selected = $value == $item->ID?' selected':'';
        $ret .= 
        '<option value="'.$item->ID.'"'.$selected.'>'.$item->post_title.'</option>';
        endforeach;
        return $ret;
    }
    
    function insight_select($value){
        $args = array(
            'posts_per_page' => 20,
            'post_status' => 'any'
            );
        $posts = get_posts($args);
        $ret = '<option value="">--SELECT--</option>';
        foreach ($posts as $post):
        $selected = $value == $post->ID?' selected':'';
        $ret .= 
        '<option value="'.$post->ID.'"'.$selected.'>'.$post->post_title.'</option>';
        endforeach;
        return $ret;
    }
    
    function subtitle_footer_hook()
    {
        ?><script type="text/javascript">
            jQuery('#_qi_metabox').after(jQuery('#postdivrich'));
            //jQuery('#postdivrich').hide();
        </script><?php
    }
}
?>

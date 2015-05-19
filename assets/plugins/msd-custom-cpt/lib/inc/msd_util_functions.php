<?php
if( ! function_exists('msd_taxonomy_description')){
    function msd_taxonomy_description() {
        global $wp,$wp_query;
        $tax = $wp_query->query_vars[taxonomy];
        $term = $wp_query->query_vars[$tax];
        $term_info = get_term_by('slug',$term,$tax);
        $description = $term_info->description;
        print '<h2 class="entry-title">'.$term_info->name.'</h2>
        <div class="'.$tax.' '.$term.' description">'.$description.'</div>';
    }
}

if( ! function_exists('msd_taxonomy_children')){
    function msd_taxonomy_children() {
        global $wp,$wp_query;
        $tax = $wp_query->query_vars[taxonomy];
        $term = $wp_query->query_vars[$tax];
        $term_info = get_term_by('slug',$term,$tax);
        $child_terms = get_term_children($term_info->term_id,$tax);
        if($child_terms){
            foreach($child_terms AS $child){
                $child_term = get_term_by( 'id', $child, $tax );
                $list .= '<li><a href="' . get_term_link( $child, $tax ) . '">' . $child_term->name . '</a></li>';
            }
            print '<ul class="child-terms">'.$list.'</ul>';
        }
    }
}

if( ! function_exists('msd_list_taxonomy')){
    function msd_list_taxonomy($taxonomy,$title=''){
        global $post,$current_category;
        if(get_post_type()=='project' && is_single()){
            global $decorations;
            $referrers = array_filter(explode('/',preg_replace('@'.get_site_url(1,FALSE,'http').'@i','',$_SERVER['HTTP_REFERER'])));
            $current_taxonomy = array_shift($referrers);
            $decorations = array();
            foreach($referrers AS $referrer){
                $current_term = get_term_by('slug', $referrer, preg_replace('/-/','_',$current_taxonomy));
                $decorations['cats'][] = '.cat-item-'.$current_term->term_id;
                $decorations['parents'][] = '.cat-item-'.$current_term->parent;
            }
        }
        
        $orderby      = 'name'; 
        $show_count   = 0;      // 1 for yes, 0 for no
        $pad_counts   = 0;      // 1 for yes, 0 for no
        $hierarchical = 1;      // 1 for yes, 0 for no
    
        $args = array(
          'taxonomy'     => $taxonomy,
          'orderby'      => $orderby,
          'show_count'   => $show_count,
          'pad_counts'   => $pad_counts,
          'hierarchical' => $hierarchical,
          'title_li'     => '<strong><a>'.$title.'</a></strong>'
        );
        
        return wp_list_categories( $args );
    }
}

if( ! function_exists('msd_sidebar_taxonomy_menu')){
    function msd_sidebar_taxonomy_menu(){
        print '<div class="taxonomy_menu">';
        print '<ul class="menu">';
        print '<li>Our Work<ul>';
        print msd_list_taxonomy('project_type','Project Types');
        print msd_list_taxonomy('market_sector','Market Sectors');
        print '<li><strong><a href="projects-state">By State</a></strong></li>';
        print '</ul></li>';
        print '</ul>';
        print '</div>';
    }
}

if ( ! function_exists( 'msd_trim_headline' ) ) :
	function msd_trim_headline($text, $length = 35) {
		$raw_excerpt = $text;
		if ( '' == $text ) {
			$text = get_the_content('');
		}
			$text = strip_shortcodes( $text );
			$text = preg_replace("/<img[^>]+\>/i", "", $text); 
			$text = apply_filters('the_content', $text);
			$text = str_replace(']]>', ']]&gt;', $text);
			$text = strip_tags($text);
			$excerpt_length = apply_filters('excerpt_length', $length);
			$excerpt_more = apply_filters('excerpt_more',false);
			$words = preg_split("/[\n\r\t ]+/", $text, $excerpt_length + 1, PREG_SPLIT_NO_EMPTY);
			if ( count($words) > $excerpt_length ) {
				array_pop($words);
				$text = implode(' ', $words);
				$text = $text . $excerpt_more;
			} else {
				$text = implode(' ', $words);
			}
	
		
		return apply_filters('wp_trim_excerpt', $text, $raw_excerpt);
		//return $text;
	}
endif;
/**
 * @desc Checks to see if the given plugin is active.
 * @return boolean
 */
if( ! function_exists('msd_is_plugin_active')){
	function msd_is_plugin_active($plugin) {
		return in_array($plugin, (array) get_option('active_plugins', array()));
	}
}
if ( ! function_exists( 'msdlab_http_sanity_check' ) ) :
function msdlab_http_sanity_check($url){
    if(stripos($url,'http://')===FALSE && stripos($url,'https://')===FALSE){
        $url = 'http://'.$url;
    }
    return $url;
}
endif;
/*
 * A useful troubleshooting function. Displays arrays in an easy to follow format in a textarea.
*/
if ( ! function_exists( 'ts_data' ) ) :
function ts_data($data){
	$ret = '<textarea class="troubleshoot" cols="100" rows="20">';
	$ret .= print_r($data,true);
	$ret .= '</textarea>';
	print $ret;
}
endif;
/*
 * A useful troubleshooting function. Dumps variable info in an easy to follow format in a textarea.
*/
if ( ! function_exists( 'ts_var' ) && function_exists( 'ts_data' ) ) :
function ts_var($var){
	ts_data(var_export( $var , true ));
}
endif;


if(!function_exists('msd_str_fmt')){
    function msd_str_fmt($str,$format = FALSE){
        switch($format){
            case 'email':
                $ret = '<a href="mailto:'.antispambot($str).'" class="email">'.antispambot($str).'</a>';
                break;
            case 'phone':
                $str = preg_replace("/[^0-9]/", "", $str);
                if(strlen($str) == 7)
                    $ret = preg_replace("/([0-9]{3})([0-9]{4})/", "$1 $2", $str);
                elseif(strlen($str) == 10)
                    $ret = preg_replace("/([0-9]{3})([0-9]{3})([0-9]{4})/", "$1 $2 $3", $str);
                else
                    $ret = $str;
                break;
            default:
                $ret = $str;
                break;
        }
        return $ret;
    }
}

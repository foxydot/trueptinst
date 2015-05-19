jQuery(document).ready(function(){
    
/*RESPONSIVE NAVIGATION, COMBINES MENUS EXCEPT FOR FOOTER MENU*/

    //jQuery('.menu').not('#footer .menu, #footer-widgets .menu').wrap('<div id="nav-response" class="nav-responsive">');
    jQuery('#menu-primary-links').wrap('<div id="nav-response" class="nav-responsive">');
    jQuery('#nav-response').append('<a href="#" id="pull" class="closed"><strong>MENU</strong></a>');   
    
    //move the search box
    if(jQuery('#pull').css('display') !== 'none'){
        var mysearch = jQuery('.nav-responsive').find('li.search');
        jQuery('#pull').before(mysearch);
    }
    
    function sf_duplicate_menu( menu, append_to, menu_id, menu_class ){
                var jQuerycloned_nav;
                
                menu.clone().attr('id',menu_id).removeClass().attr('class',menu_class).appendTo( append_to );
                jQuerycloned_nav = append_to.find('> ul');
                jQuerycloned_nav.find('.menu_slide').remove();
                jQuerycloned_nav.find('li:first').addClass('sf_first_mobile_item');
                jQuerycloned_nav.find('li.menu-item-has-children>a').append('<span class="toggle-switch pull-right"><i class="fa fa-2x fa-chevron-circle-right"></i></span>');
                jQuerycloned_nav.find('li.menu-item-has-children .sub-menu').hide();
                jQuerycloned_nav.find('li.current-menu-ancestor>.sub-menu').show();
                jQuerycloned_nav.find('li.current-menu-ancestor').removeClass( 'closed' ).addClass( 'opened' );
                jQuerycloned_nav.find('li.current-menu-ancestor>a>span>i').removeClass('fa-chevron-circle-right').addClass('fa-chevron-circle-down');
                
                append_to.click( function(){
                    if ( jQuery(this).hasClass('closed') ){
                        jQuery(this).removeClass( 'closed' ).addClass( 'opened' );
                        jQuerycloned_nav.slideDown( 500 );
                    } else {
                        jQuery(this).removeClass( 'opened' ).addClass( 'closed' );
                        jQuerycloned_nav.slideUp( 500 );
                    }
                    return false;
                } );
                
                append_to.find('a').click( function(event){
                    event.stopPropagation();
                } );
            }
    
    //combinate
    sf_duplicate_menu( jQuery('.nav-responsive>ul'), jQuery('#pull'), 'mobile_menu', 'sf_mobile_menu' );
    
            
     jQuery('#pull li.about-you .toggle-switch, #pull li.about-us .toggle-switch').click( function(){
        if ( jQuery(this).parent('a').parent('li').hasClass('opened') ){
            jQuery(this).children('i').removeClass('fa-chevron-circle-down').addClass('fa-chevron-circle-right');
            jQuery(this).parent('a').parent('li').removeClass( 'opened' ).addClass( 'closed' );
            jQuery(this).parent('a').parent('li').find('.sub-menu').slideUp( 500 ).parent('li').removeClass( 'opened' ).addClass( 'closed' ).find('i').removeClass('fa-chevron-circle-down').addClass('fa-chevron-circle-right');
        } else {
            jQuery(this).children('i').removeClass('fa-chevron-circle-right').addClass('fa-chevron-circle-down');
            jQuery(this).parent('a').parent('li').removeClass( 'closed' ).addClass( 'opened' );
            jQuery(this).parent('a').parent('li').find('.sub-menu').slideDown( 500 );
            jQuery(this).parent('a').parent('li').find('.sub-menu>li>.sub-menu').hide();
            jQuery(this).parent('a').parent('li').find('.sub-menu>li').addClass('closed');
        }
        return false;
    } );
    
    
});
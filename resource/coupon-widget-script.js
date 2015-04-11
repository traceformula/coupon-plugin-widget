var flag = 0;
var tmp = jQuery("#couponWidget").children().length;
jQuery( document ).ready(function() {
    jQuery('#couponWidget .couponItem:gt(0)').hide();
    
    jQuery(function() {
        jQuery('#couponWidget .couponItem:gt(0)').hide();   
//        alert(tmp);
        setInterval( sliderCoupon, 6000);
    });
});

function sliderCoupon(){
    for(i=0; i<=(tmp-2) ;i++){
        var style = jQuery('#couponWidget').children().eq(i).attr('style') ;
        
        if((style == "display: block;" || style == null) && i != (tmp-2)){
            jQuery('#couponWidget').children().eq(i).fadeOut();
            jQuery('#couponWidget').children().eq(i+1).fadeIn();
            break;
        }
        if( i == (tmp-2) ){
            jQuery('#couponWidget').children().eq(i).fadeOut();
            jQuery('#couponWidget').children().eq(0).fadeIn();   
        }
    }
}



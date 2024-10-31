<?php
require_once(dirname(__FILE__) . "/example-widget-popup.php");


	function widget_shortcode($atts) {
                
    $customer_api_key = get_option('apikey'); //"nfek66vrvw51-fbdc40"; // api key customer
    $onlineafspraken_logo = get_option('logo'); //"0"; //0 = uit | 1 = aan
    $facebook_login = get_option('fblogin'); //"0"; // 0 = uit | 1 = aan
    $onlineafspraken_language = get_option('oalang'); // "3"; // 1 = engels | 3 = nederlands | 4 = frans | 5 = duits
    $onlineafspraken_type = get_option('oaltype'); // "0"; // 0 = verdana | 1 = arial | 2 = tahoma
    $onlineafspraken_font_size_text = get_option('oafstext'); // "14"; // in px
    $onlineafspraken_font_size_header_text = get_option('oafshtext'); // "17"; // in px
    $onlineafspraken_font_size_calender_text = get_option('oafsctext'); // "17"; // in px
    $onlineafspraken_frame_width = get_option('oaframewidth'); // "400"; // breedte in px (minimaal 400 px)
    $onlineafspraken_frame_height = get_option('oaframeheight'); // "800"; // hoogte in px (minimaal 600 px)
    $onlineafspraken_align_frame = get_option('oaalign');

return '
      <iframe src="http://widget.onlineafspraken.nl/iFrame/book/key/'.$customer_api_key.'/w/'.$onlineafspraken_frame_width.'/h/'.$onlineafspraken_frame_height.'/f/'.$onlineafspraken_type.'/fs2/'.$onlineafspraken_font_size_text.'/fs1/'.$onlineafspraken_font_size_header_text.'/fs3/'.$onlineafspraken_font_size_calender_text.'/c0/ffffff/c1/35aadc/c2/dd7b30/c3/111111/c4/35aadc/l/1/siteLanguageId/'.$onlineafspraken_language.'/fb/'.$facebook_login.'/logo/'.$onlineafspraken_logo.'/at/0"
      frameborder="0" scrolling="no" width="'.$onlineafspraken_frame_width.'" height="'.$onlineafspraken_frame_height.'" align='.$onlineafspraken_align_frame.'"></iframe>
';
 
}

add_shortcode( 'onlineafspraken' , 'widget_shortcode' );

?>
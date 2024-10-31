<?php
require_once(dirname(__FILE__) . "/example-widget-popup.php");

class oa_widget extends WP_Widget {
    
    function oa_widget() {
		/* Widget settings. */
		$widget_ops = array( 'classname' => 'onlineafspraken', 'description' => __('De OnlineAfspraken widget geeft het afspraken frame weer op uw website.','woa') );

		/* Widget control settings. */
		$control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'onlineafspraken-widget' );

		/* Create the widget. */
		$this->WP_Widget( 'onlineafspraken-widget', __('OnlineAfspraken Widget','woa'), $widget_ops, $control_ops );
                
    }
    
         /**
	 * Display the widget on the screen.
	 */
	function widget( $args ) {
		extract( $args );
                
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
    
    ?>
		<?php echo $before_widget . $before_title . $title . $after_title; ?>
      <iframe src="http://widget.onlineafspraken.nl/iFrame/book/key/<?php echo $customer_api_key; ?>/w/<?php echo $onlineafspraken_frame_width; ?>/h/<?php echo $onlineafspraken_frame_height; ?>/f/<?php echo $onlineafspraken_type; ?>/fs2/<?php echo $onlineafspraken_font_size_text; ?>/fs1/<?php echo $onlineafspraken_font_size_header_text; ?>/fs3/<?php echo $onlineafspraken_font_size_calender_text; ?>/c0/ffffff/c1/35aadc/c2/dd7b30/c3/111111/c4/35aadc/l/1/siteLanguageId/<?php echo $onlineafspraken_language; ?>/fb/<?php echo $facebook_login; ?>/logo/<?php echo $onlineafspraken_logo; ?>/at/0"
      frameborder="0" scrolling="no" width="<?php echo $onlineafspraken_frame_width; ?>" height="<?php echo $onlineafspraken_frame_height; ?>" align="<?php echo $onlineafspraken_align_frame; ?>"></iframe>
                <?php echo $after_widget; ?>
 <?php
    
}
}
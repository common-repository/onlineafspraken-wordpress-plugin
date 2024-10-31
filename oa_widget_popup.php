<?php
require_once(dirname(__FILE__) . "/example-widget-popup.php");

class oa_widget_popup extends WP_Widget {

	/**
	 * Widget setup.
	 */
	function oa_widget_popup() {
		/* Widget settings. */
		$widget_ops = array( 'classname' => 'onlineafsprakenpopup', 'description' => __('Zet de button voor het pop-up frame op uw website.','woa') );

		/* Widget control settings. */
		$control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'onlineafspraken-popup-widget' );

		/* Create the widget. */
		$this->WP_Widget( 'onlineafspraken-popup-widget', __('OnlineAfspraken Pop-up Widget','woa'), $widget_ops, $control_ops );
	}

	/**
	 * Display the widget on the screen.
	 */
	function widget( $args ) {
		extract( $args );

		/* variables from the widget settings. */
		$title = "";
                $customer_api_key = get_option('apikey'); //"nfek66vrvw51-fbdc40"; // api key customer
                $onlineafspraken_logo = get_option('logo'); //"0"; //0 = uit | 1 = aan
                $facebook_login = get_option('fblogin'); //"0"; // 0 = uit | 1 = aan
                $onlineafspraken_language = get_option('oalang'); // "3"; // 1 = engels | 3 = nederlands | 4 = frans | 5 = duits
                $onlineafspraken_type = get_option('oaltype'); // "0"; // 0 = verdana | 1 = arial | 2 = tahoma
                $onlineafspraken_font_size_text = get_option('oafstext'); // "14"; // in px
                $onlineafspraken_font_size_header_text = get_option('oafshtext'); // "17"; // in px
                $onlineafspraken_font_size_calender_text = get_option('oafsctext'); // "17"; // in px
                $onlineafspraken_align_button = get_option('oaalignb');
                $onlineafspraken_img_button = get_option('img_oa');
                
                $path = plugin_dir_url( __FILE__ );
                //List files in images directory
                $dir = $path."oaimg";
                $popup_jquery = $path."javascripts/jquery/*.js";
                $popup = $path."javascripts/top_up-min.js";
                $popup_images = $path."javascripts/images/top_up/";

		/* Before widget (defined by themes). */
		echo $before_widget;

		/* Display the widget title (before and after defined by themes). */
		if ( $title )
			echo $before_title . $title . $after_title;
?>
                <script type="text/javascript" src="<?php echo $popup_jquery; ?>"></script> 
                <script type="text/javascript">
                TopUp.images_path = "<?php echo $popup_images; ?>";
                </script> 
                <script type="text/javascript" src="http://gettopup.com/releases/latest/top_up-min.js"></script>
                <a href="http://widget.onlineafspraken.nl/iFrame/book/key/<?php echo $customer_api_key; ?>/w/400/h/800/f/<?php echo $onlineafspraken_type; ?>/fs2/<?php echo $onlineafspraken_font_size_text; ?>/fs1/<?php echo $onlineafspraken_font_size_header_text; ?>/fs3/<?php echo $onlineafspraken_font_size_calender_text; ?>/c0/ffffff/c1/35aadc/c2/dd7b30/c3/111111/c4/35aadc/l/1/siteLanguageId/<?php echo $onlineafspraken_language; ?>/fb/<?php echo $facebook_login; ?>/logo/<?php echo $onlineafspraken_logo; ?>/at/0" toptions="type = iframe, effect = fade, width = 400, height = 800, overlayClose = 1, resizable = 0, shaded = 1">
                <img src="<?php echo $dir.'/'.$onlineafspraken_img_button; ?>" align="<?php echo $onlineafspraken_align_button; ?>"/></a>
<?php

		/* After widget (defined by themes). */
		echo $after_widget;
	}
	
}
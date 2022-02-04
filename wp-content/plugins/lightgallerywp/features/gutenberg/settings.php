<?php
namespace LightGallery;
add_action('plugins_loaded', function() {
	$booleanOptions = array(
		array('text' => 'Yes', 'value' => 'true'),
		array('text' => 'No', 'value' => 'false')
	);
	new SmartlogixSettingsWrapper(array(
		'menuName' => 'Gutenberg Settings',
		'pageName' => 'Gutenberg Settings',
		'menuParent' => 'edit.php?post_type=lightgalleries',
		'settingsName' => 'lightgallerywp_default_gallery_settings',
		'callBackFunctions' => array(
			'admin_enqueue_scripts' => 'LightGallery\lightgallerywp_settings_admin_enqueue_scripts'
		),
		'metaboxes' => array(
			'light_gallery_settings' => 'LightGallery Settings'
		),
		'controls' => apply_filters('lightgallerywp_settings_controls', array(
			array(
				'metabox' => 'light_gallery_settings',
				'section' => 'Gallery Basics',
				'type' => 'toggle',
				'label' => 'Enable Lightgallery for all Images',
				'id' => 'enable_indivigual_images_ignore',
				'info' => 'All image links on the site will appear in a Lightgallery'
			),
			array(
				'metabox' => 'light_gallery_settings',
				'section' => 'Gallery Basics',
				'type' => 'toggle',
				'label' => 'Enable Lightgallery for all Galleries',
				'id' => 'enable_gutenberg_gallery_ignore',
				'info' => 'All galleries on the site will have Lightgallery functionality'
			),
			array(
				'metabox' => 'light_gallery_settings',
				'section' => 'Gallery Basics',
				'type' => 'text',
				'label' => 'Add Custom class for gallery container',
				'id' => 'addClass_string',
				'info' => 'This can be used to set different style for different galleries'
			),
			array(
				'metabox' => 'light_gallery_settings',
				'section' => 'Gallery Basics',
				'type' => 'toggle',
				'label' => 'Allow Media Overlap ',
				'id' => 'allowMediaOverlap_boolean',
				'data' => $booleanOptions,
				'info' => 'If true, toolbar, captions and thumbnails will not overlap with media element.  This will not effect thumbnails if animateThumb is false.  Also, toggle thumbnails button is not displayed if allowMediaOverlap is false'
			),
			array(
				'metabox' => 'light_gallery_settings',
				'section' => 'Gallery Basics',
				'type' => 'number',
				'label' => 'Backdrop transition duration',
				'id' => 'backdropDuration_number',
			),
			array(
				'metabox' => 'light_gallery_settings',
				'section' => 'Gallery Basics',
				'type' => 'toggle',
				'label' => 'Closable',
				'id' => 'closable_boolean',
				'data' => $booleanOptions,
				'info' => 'If "No", the user won\'t be able to close the gallery.'
			),
			array(
				'metabox' => 'light_gallery_settings',
				'section' => 'Gallery Basics',
				'type' => 'toggle',
				'label' => 'Close On Tap',
				'id' => 'closeOnTap_boolean',
				'data' => $booleanOptions,
				'info' => 'Allows clicks on black area to close gallery.'
			),
			array(
				'metabox' => 'light_gallery_settings',
				'section' => 'Gallery Basics',
				'type' => 'toggle',
				'label' => 'Controls',
				'id' => 'controls_boolean',
				'data' => $booleanOptions,
				'info' => 'If false, prev/next buttons will not be displayed.'
			),
			array(
				'metabox' => 'light_gallery_settings',
				'section' => 'Gallery Basics',
				'type' => 'toggle',
				'label' => 'Counter',
				'id' => 'counter_boolean',
				'data' => $booleanOptions,
				'info' => 'Whether to show total number of images and index number of currently displayed image.'
			),
			array(
				'metabox' => 'light_gallery_settings',
				'section' => 'Gallery Basics',
				'type' => 'toggle',
				'label' => 'Download',
				'id' => 'download_boolean',
				'data' => $booleanOptions,
				'info' => 'Enable download button.'
			),
			array(
				'metabox' => 'light_gallery_settings',
				'section' => 'Gallery Basics',
				'type' => 'toggle',
				'label' => 'Enable Drag',
				'id' => 'enableDrag_boolean',
				'data' => $booleanOptions,
				'info' => 'Enables desktop mouse drag support.'
			),
			array(
				'metabox' => 'light_gallery_settings',
				'section' => 'Gallery Basics',
				'type' => 'toggle',
				'label' => 'Enable Swipe',
				'id' => 'enableSwipe_boolean',
				'data' => $booleanOptions,
				'info' => 'Enables swipe support for touch devices.'
			),
			array(
				'metabox' => 'light_gallery_settings',
				'section' => 'Gallery Basics',
				'type' => 'toggle',
				'label' => 'Esc Key Support',
				'id' => 'escKey_boolean',
				'data' => $booleanOptions,
				'info' => 'Whether the LightGallery could be closed by pressing the "Esc" key.'
			),
			array(
				'metabox' => 'light_gallery_settings',
				'section' => 'Gallery Basics',
				'type' => 'toggle',
				'label' => 'Get captions from alt or title tags',
				'id' => 'getCaptionFromTitleOrAlt_boolean',
				'data' => $booleanOptions,
				'info' => 'Option to get captions from alt or title tags.'
			),
			array(
				'metabox' => 'light_gallery_settings',
				'section' => 'Gallery Basics',
				'type' => 'text',
				'label' => 'Height',
				'id' => 'height_string',
				'info' => 'Height of the gallery. example \'100%\' , \'300px\''
			),
			array(
				'metabox' => 'light_gallery_settings',
				'section' => 'Gallery Basics',
				'type' => 'number',
				'label' => 'Delay for hiding gallery controls',
				'id' => 'hideBarsDelay_number',
				'info' => 'Delay for hiding gallery controls in ms.  Pass 0 or leave empty if you don\'t want to hide the controls'
			),
			array(
				'metabox' => 'light_gallery_settings',
				'section' => 'Gallery Basics',
				'type' => 'toggle',
				'label' => 'Hide Controls On End',
				'id' => 'hideControlOnEnd_boolean',
				'data' => $booleanOptions,
				'info' => 'If true, prev/next button will be hidden on first/last image.'
			),
			array(
				'metabox' => 'light_gallery_settings',
				'section' => 'Gallery Basics',
				'type' => 'toggle',
				'label' => 'KeyPress Navigation',
				'id' => 'keyPress_boolean',
				'data' => $booleanOptions,
			),
			array(
				'metabox' => 'light_gallery_settings',
				'section' => 'Gallery Basics',
				'type' => 'toggle',
				'label' => 'Loop',
				'id' => 'loop_boolean',
				'data' => $booleanOptions,
				'info' => 'If false, will disable the ability to loop back to the beginning of the gallery from the last slide.'
			),
			array(
				'metabox' => 'light_gallery_settings',
				'section' => 'Gallery Basics',
				'type' => 'toggle',
				'label' => 'Mousewheel Navigation',
				'id' => 'mousewheel_boolean',
				'data' => $booleanOptions,
				'info' => 'Ability to navigate to next/prev slides on mousewheel.'
			),
			/*array(
				'metabox' => 'light_gallery_settings',
				'section' => 'Gallery Basics',
				'type' => 'textarea',
				'label' => 'Next HTML',
				'id' => 'nextHtml_string',
				'info' => 'Custom html for next control'
			),*/
			array(
				'metabox' => 'light_gallery_settings',
				'section' => 'Gallery Basics',
				'type' => 'number',
				'label' => 'Number Of Slide Items In Dom ',
				'id' => 'numberOfSlideItemsInDom_number',
				'info' => 'Control how many slide items should be kept in dom at a time<br />To improve performance by reducing number of gallery items in the dom, lightGallery keeps only the lowest possible number of slides in the dom at a time.  This has a minimum value of 3'
			),
			array(
				'metabox' => 'light_gallery_settings',
				'section' => 'Gallery Basics',
				'type' => 'number',
				'label' => 'Number of preload slides',
				'id' => 'preload_number',
			),
			/*array(
				'metabox' => 'light_gallery_settings',
				'section' => 'Gallery Basics',
				'type' => 'textarea',
				'label' => 'Prev HTML',
				'id' => 'prevHtml_string',
				'info' => 'Custom html for prev control'
			),*/
			array(
				'metabox' => 'light_gallery_settings',
				'section' => 'Gallery Basics',
				'type' => 'number',
				'label' => 'Show Bars After',
				'id' => 'showBarsAfter_number',
				'info' => 'Delay in hiding controls for the first time when gallery is opened.'
			),
			array(
				'metabox' => 'light_gallery_settings',
				'section' => 'Gallery Basics',
				'type' => 'toggle',
				'label' => 'Show Close Icon',
				'id' => 'showCloseIcon_boolean',
				'data' => $booleanOptions,
				'info' => 'If false, close button won\'t be displayed.'
			),
			array(
				'metabox' => 'light_gallery_settings',
				'section' => 'Gallery Basics',
				'type' => 'toggle-reverse',
				'label' => 'Show Maximize Icon',
				'id' => 'showMaximizeIcon_boolean',
				'data' => $booleanOptions,
				'info' => 'Show maximize icon.'
			),
			array(
				'metabox' => 'light_gallery_settings',
				'section' => 'Gallery Basics',
				'type' => 'number',
				'label' => 'Slide Delay',
				'id' => 'slideDelay_number',
				'info' => 'Delay slide transitions.'
			),
			array(
				'metabox' => 'light_gallery_settings',
				'section' => 'Gallery Basics',
				'type' => 'toggle',
				'label' => 'Slide End Animation',
				'id' => 'slideEndAnimation_boolean',
				'data' => $booleanOptions,
				'info' => 'Enable slideEnd animation.'
			),
			array(
				'metabox' => 'light_gallery_settings',
				'section' => 'Gallery Basics',
				'type' => 'number',
				'label' => 'Speed',
				'id' => 'speed_number',
				'info' => 'Transition duration (in ms).  Defaults to 400'
			),
			array(
				'metabox' => 'light_gallery_settings',
				'section' => 'Gallery Basics',
				'type' => 'number',
				'label' => 'Start Animation Duration',
				'id' => 'startAnimationDuration_number',
				'info' => 'Zoom from image animation duration.  Defaults to 400'
			),
			array(
				'metabox' => 'light_gallery_settings',
				'section' => 'Gallery Basics',
				'type' => 'number',
				'label' => 'Swipe Threshold',
				'id' => 'swipeThreshold_number',
				'info' => 'By setting the swipeThreshold (in px) you can set how far the user must swipe for the next/prev image..  Defaults to 50'
			),
			array(
				'metabox' => 'light_gallery_settings',
				'section' => 'Gallery Basics',
				'type' => 'toggle',
				'label' => 'Swipe To Close',
				'id' => 'swipeToClose_boolean',
				'data' => $booleanOptions,
				'info' => 'Allows vertical drag/swipe to close gallery.'
			),
			array(
				'metabox' => 'light_gallery_settings',
				'section' => 'Gallery Basics',
				'type' => 'text',
				'label' => 'Width',
				'id' => 'width_string',
				'info' => 'Width of the gallery. example \'100%\' , \'300px\''
			),
			array(
				'metabox' => 'light_gallery_settings',
				'section' => 'Gallery Basics',
				'type' => 'toggle',
				'label' => 'Enable zoom from origin effect',
				'id' => 'zoomFromOrigin_boolean',
				'data' => $booleanOptions,
			),
			'zoom_plugin_pro_upsell' => array(
				'metabox' => 'light_gallery_settings',
				'section' => 'Zoom Plugin <span>PRO</span>',
				'type' => 'html',
				'label' => '',
				'id' => '',
				'data' => lightgallerywp_settings_upgrade_to_pro_banner()
			),
			'thumbnails_plugin_pro_upsell' => array(
				'metabox' => 'light_gallery_settings',
				'section' => 'Thumbnails Plugin <span>PRO</span>',
				'type' => 'html',
				'label' => '',
				'id' => '',
				'data' => lightgallerywp_settings_upgrade_to_pro_banner()
			),
			'hash_plugin_pro_upsell' => array(
				'metabox' => 'light_gallery_settings',
				'section' => 'Hash Plugin <span>PRO</span>',
				'type' => 'html',
				'label' => '',
				'id' => '',
				'data' => lightgallerywp_settings_upgrade_to_pro_banner()
			),
			'autoplay_plugin_pro_upsell' => array(
				'metabox' => 'light_gallery_settings',
				'section' => 'Autoplay Plugin <span>PRO</span>',
				'type' => 'html',
				'label' => '',
				'id' => '',
				'data' => lightgallerywp_settings_upgrade_to_pro_banner()
			),
			'rotate_plugin_pro_upsell' => array(
				'metabox' => 'light_gallery_settings',
				'section' => 'Rotate Plugin <span>PRO</span>',
				'type' => 'html',
				'label' => '',
				'id' => '',
				'data' => lightgallerywp_settings_upgrade_to_pro_banner()
			),
			'share_plugin_pro_upsell' => array(
				'metabox' => 'light_gallery_settings',
				'section' => 'Share Plugin <span>PRO</span>',
				'type' => 'html',
				'label' => '',
				'id' => '',
				'data' => lightgallerywp_settings_upgrade_to_pro_banner()
			),
			'pager_plugin_pro_upsell' => array(
				'metabox' => 'light_gallery_settings',
				'section' => 'Pager Plugin <span>PRO</span>',
				'type' => 'html',
				'label' => '',
				'id' => '',
				'data' => lightgallerywp_settings_upgrade_to_pro_banner()
			),
			'fullscreen_plugin_pro_upsell' => array(
				'metabox' => 'light_gallery_settings',
				'section' => 'FullScreen Plugin <span>PRO</span>',
				'type' => 'html',
				'label' => '',
				'id' => '',
				'data' => lightgallerywp_settings_upgrade_to_pro_banner()
			),
		), $booleanOptions)
	));
});

function lightgallerywp_settings_admin_enqueue_scripts() {
	wp_enqueue_script('jquery');
	wp_enqueue_script('lightgallerywp-admin-script', plugins_url('../assets/js/lightgallery-admin.js', dirname(__FILE__)), array(), '1.0', 'all');
	wp_enqueue_style('lightgallerywp-admin-styles', plugins_url('../assets/css/lightgallery-admin.css', dirname(__FILE__)), array(), '1.0', 'all');
}

function lightgallerywp_settings_upgrade_to_pro_banner() {
	return lightgallerywp_load_file('upgrade-banner.php');
}
?>
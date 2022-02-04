<?php
namespace LightGallery;
require_once(dirname(__FILE__).'/shortcode.php');
require_once(dirname(__FILE__).'/invoke-script.php');

add_action('plugins_loaded', function() {
	$booleanOptions = array(
		array('text' => 'Yes', 'value' => 'true'),
		array('text' => 'No', 'value' => 'false')
	);
	new SmartlogixCPTWrapper(array(
		'name' => 'lightgalleries',
		'singularName' => 'LightGallery',
		'pluralName' => 'LightGallery',
		'callBackFunctions' => array(
			'meta_box_content' => 'LightGallery\lightgallerywp_cpt_get_slides',
			'admin_enqueue_scripts' => 'LightGallery\lightgallerywp_cpt_admin_enqueue_scripts'
		),
		'metaboxes' => array(
			'light_gallery_slides' => 'LightGallery Slides',
			'light_gallery_settings' => 'LightGallery Settings'
		),
		'controls' => apply_filters('lightgallerywp_cpt_controls', array(
			array(
				'metabox' => 'light_gallery_settings',
				'section' => 'Embed',
				'type' => 'html',
				'label' => 'Shortcode',
				'id' => '',
				'data' => '<p id="embed_shortcode_fail">Please publish the gallery to receive your embed shortcode.</p>
					<code id="embed_shortcode_success" style="display: none;">[lightgallery id=""]</code>
					<p id="embed_shortcode_success_instruction">You can use this "shortcode" in your posts or pages.</p>'
			),
			array(
				'metabox' => 'light_gallery_settings',
				'section' => 'Layout',
				'type' => 'select',
				'label' => 'Gallery Layout',
				'id' => 'layout_ignore',
				'data' => array(
					array('text' => 'Justified Gallery', 'value' => 'justified'),
					array('text' => 'Inline', 'value' => 'inline'),
				)
			),
			array(
				'metabox' => 'light_gallery_settings',
				'section' => 'Layout',
				'type' => 'number',
				'label' => 'Default thumbnails Width (Optional, can be overridden per slide)',
				'id' => 'slide_global_width_ignore',
				'default' => '220'
			),
			array(
				'metabox' => 'light_gallery_settings',
				'section' => 'Layout',
				'type' => 'number',
				'label' => 'Default thumbnails height (Optional, can be overridden per slide)',
				'id' => 'slide_global_height_ignore',
				'default' => '220'
			),
			array(
				'metabox' => 'light_gallery_settings',
				'section' => 'Layout',
				'type' => 'text',
				'label' => 'Inline gallery width',
				'id' => 'inline_width_ignore',
				'style' => 'layout_option inline_layout_option',
				'default' => '100%'
			),
			array(
				'metabox' => 'light_gallery_settings',
				'section' => 'Layout',
				'type' => 'text',
				'label' => 'Inline gallery height',
				'id' => 'inline_height_ignore',
				'style' => 'layout_option inline_layout_option',
				'default' => '60%'
			),
			array(
				'metabox' => 'light_gallery_settings',
				'section' => 'Layout',
				'type' => 'number',
				'label' => 'Justified gallery row height in pixels',
				'id' => 'justified_gallery_row_height_ignore',
				'style' => 'layout_option justified_layout_option',
				'default' => '220'
			),
			array(
				'metabox' => 'light_gallery_settings',
				'section' => 'Layout',
				'type' => 'text',
				'label' => 'Justified gallery maximum row height in pixels',
				'id' => 'justified_gallery_max_row_height_ignore',
				'style' => 'layout_option justified_layout_option',
				'default' => 'false'
			),
			array(
				'metabox' => 'light_gallery_settings',
				'section' => 'Layout',
				'type' => 'number',
				'label' => 'Justified gallery limit number of rows to show',
				'id' => 'justified_gallery_max_row_count_ignore',
				'style' => 'layout_option justified_layout_option',
				'default' => '0'
			),
			array(
				'metabox' => 'light_gallery_settings',
				'section' => 'Layout',
				'type' => 'text',
				'label' => "Justified gallery last row style",
				'info' => "Decide to justify the last row (using 'justify') or not (using 'nojustify'), or to hide the row if it can't be justified (using 'hide'). By default, using 'nojustify', the last row images are aligned to the left, but they can be also aligned to the center (using 'center') or to the right (using 'right').",
				'id' => 'justified_gallery_last_row_ignore',
				'style' => 'layout_option justified_layout_option',
				'default' => 'nojustify'
			),
			array(
				'metabox' => 'light_gallery_settings',
				'section' => 'Layout',
				'type' => 'number',
				'label' => 'Thumbnail margin',
				'info' => 'Decide the margins between the thumbnail images',
				'id' => 'justified_gallery_margin_ignore',
				'style' => 'layout_option justified_layout_option',
				'default' => '1'
			),
			array(
				'metabox' => 'light_gallery_settings',
				'section' => 'Layout',
				'type' => 'text',
				'label' => 'Thumbnail border size',
				'info' => 'Decide the border size of the gallery. With a negative value the border will be the same as the margins.',
				'id' => 'justified_gallery_border_ignore',
				'style' => 'layout_option justified_layout_option',
				'default' => '-1'
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
				'data' => lightgallerywp_cpt_upgrade_to_pro_banner()
			),
			'thumbnails_plugin_pro_upsell' => array(
				'metabox' => 'light_gallery_settings',
				'section' => 'Thumbnails Plugin <span>PRO</span>',
				'type' => 'html',
				'label' => '',
				'id' => '',
				'data' => lightgallerywp_cpt_upgrade_to_pro_banner()
			),
			'hash_plugin_pro_upsell' => array(
				'metabox' => 'light_gallery_settings',
				'section' => 'Hash Plugin <span>PRO</span>',
				'type' => 'html',
				'label' => '',
				'id' => '',
				'data' => lightgallerywp_cpt_upgrade_to_pro_banner()
			),
			'autoplay_plugin_pro_upsell' => array(
				'metabox' => 'light_gallery_settings',
				'section' => 'Autoplay Plugin <span>PRO</span>',
				'type' => 'html',
				'label' => '',
				'id' => '',
				'data' => lightgallerywp_cpt_upgrade_to_pro_banner()
			),
			'rotate_plugin_pro_upsell' => array(
				'metabox' => 'light_gallery_settings',
				'section' => 'Rotate Plugin <span>PRO</span>',
				'type' => 'html',
				'label' => '',
				'id' => '',
				'data' => lightgallerywp_cpt_upgrade_to_pro_banner()
			),
			'share_plugin_pro_upsell' => array(
				'metabox' => 'light_gallery_settings',
				'section' => 'Share Plugin <span>PRO</span>',
				'type' => 'html',
				'label' => '',
				'id' => '',
				'data' => lightgallerywp_cpt_upgrade_to_pro_banner()
			),
			'pager_plugin_pro_upsell' => array(
				'metabox' => 'light_gallery_settings',
				'section' => 'Pager Plugin <span>PRO</span>',
				'type' => 'html',
				'label' => '',
				'id' => '',
				'data' => lightgallerywp_cpt_upgrade_to_pro_banner()
			),
			'fullscreen_plugin_pro_upsell' => array(
				'metabox' => 'light_gallery_settings',
				'section' => 'FullScreen Plugin <span>PRO</span>',
				'type' => 'html',
				'label' => '',
				'id' => '',
				'data' => lightgallerywp_cpt_upgrade_to_pro_banner()
			),
			array(
				'metabox' => 'light_gallery_settings',
				'section' => 'Advanced',
				'type' => 'text',
				'label' => 'Gallery Container',
				'id' => 'advanced_container_ignore',
				'info' => 'Advanced Users : If you want to apply lightGallery to any specific HTML block, instead of using the shortcode, you can just specify any HTML element containing the gallery items.<br/ > For example, if you want to target a specific WordPress default gallery, add a custom classname to the gallery block from the gutenberg editor adwanced tab and specify the className as Gallery container. Then using the Gallery selector pass `a` as selector.'
			),
			array(
				'metabox' => 'light_gallery_settings',
				'section' => 'Advanced',
				'type' => 'text',
				'label' => 'Gallery Selector',
				'id' => 'advanced_selector_ignore',
				'info' => 'Advanced Users : Specify the individual gallery elements. </br><a href="https://www.lightgalleryjs.com/docs/settings/#selector">JavaScript API Docs</a></br> <a href="https://www.lightgalleryjs.com/demos/html-markup/">Usage Demo</a>'
			),
		), $booleanOptions)
	));
});

function lightgallerywp_cpt_admin_enqueue_scripts() {
	wp_enqueue_script('jquery');
	wp_enqueue_script('lightgallerywp-admin-script', plugins_url('../assets/js/lightgallery-admin.js', dirname(__FILE__)), array(), '1.0', 'all');
	wp_enqueue_style('lightgallerywp-admin-styles', plugins_url('../assets/css/lightgallery-admin.css', dirname(__FILE__)), array(), '1.0', 'all');
}

function lightgallerywp_cpt_upgrade_to_pro_banner() {
	return lightgallerywp_load_file('upgrade-banner.php');
}

function lightgallerywp_cpt_get_slides($args) {
	if($args['metaboxID'] == 'light_gallery_slides') {
		echo '<div class="slides_wrapper" style="margin: 15px 0 0;">';
			echo '<div class="slides_add_new_wrapper">';
				echo '<input id="wp_lightgalleries_data_slide_button_add_new_ignore" type="button" value="Add Slides" class="input button-primary" />';
			echo '</div>';
			echo '<div class="slides_current_wrapper" '.((isset($args['data']['slide_image_ignore']) && is_array($args['data']['slide_image_ignore']) && (count($args['data']['slide_image_ignore']) > 0))?'style="display: block;"':'style="display: none;"').'>';
				echo '<div class="lg-tab-content lg-slide-content" style="margin: 15px 0 0; padding: 0 15px; border: 1px solid #ddd; border-radius: 5px; position: relative;">';
					echo '<label style="font-weight: bold; position: absolute; left: 15px; top: -10px; background: #FFFFFF; padding: 0px 10px;">Slides</label>';
					if(isset($args['data']['slide_image_ignore']) && is_array($args['data']['slide_image_ignore']) && (count($args['data']['slide_image_ignore']) > 0)) {
						$index = 0;
						$slideWidths = $args['data']['slide_width_ignore'];
						$slideHeights = $args['data']['slide_height_ignore'];
						$slideTitles = $args['data']['slide_title_ignore'];
						$slideDescriptions = $args['data']['slide_description_ignore'];
						foreach($args['data']['slide_image_ignore'] as $slideImage) {
							echo '<fieldset class="slide_current_wrapper">';
								echo '<div class="slide_current_wrapper_inner"><div class="lg-fileupload-image">';
									echo SmartlogixControlsWrapper::get_control('upload', 'Slide Image', 'wp_lightgalleries_data[slide_image_ignore][]', 'wp_lightgalleries_data[slide_image_ignore][]', $slideImage, '', '');
								echo '</div>';
								echo '<div class="lg-fileupload-form">';
									echo '<div class="lg-field-group">';
										echo SmartlogixControlsWrapper::get_control('number-placeholder', 'Thumbnails Width', 'wp_lightgalleries_data[slide_width_ignore][]', 'wp_lightgalleries_data[slide_width_ignore][]', ((isset($slideWidths[$index]))?$slideWidths[$index]:''), '', '');
										echo SmartlogixControlsWrapper::get_control('number-placeholder', 'Thumbnails Height', 'wp_lightgalleries_data[slide_height_ignore][]', 'wp_lightgalleries_data[slide_height_ignore][]', ((isset($slideHeights[$index]))?$slideHeights[$index]:''), '', '');
									echo '</div>';
									echo SmartlogixControlsWrapper::get_control('text', 'Slide Title', 'wp_lightgalleries_data[slide_title_ignore][]', 'wp_lightgalleries_data[slide_title_ignore][]', ((isset($slideTitles[$index]))?$slideTitles[$index]:''), '', '');
									echo SmartlogixControlsWrapper::get_control('text', 'Slide Description', 'wp_lightgalleries_data[slide_description_ignore][]', 'wp_lightgalleries_data[slide_description_ignore][]', ((isset($slideDescriptions[$index]))?$slideDescriptions[$index]:''), '', '');
								echo '</div>';
								echo '<span class="slide_current_remove"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg></span>';
								echo '</div>';
							echo '</fieldset>';
							$index++;
						}
					}
				echo '</div>';
			echo '</div>';
		echo '</div>';
	}
}
?>
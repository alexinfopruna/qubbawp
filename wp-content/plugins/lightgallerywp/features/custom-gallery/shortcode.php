<?php
namespace LightGallery;
add_shortcode('lightgallery', function($atts) {
    global $post;
	$output = '';
	if(is_singular() && is_a($post, 'WP_Post')) {
		$atts = shortcode_atts(array(
			'id' => 0
		), $atts, 'lightgallery');
		if($atts['id'] != 0) {
			$settings = get_post_meta($atts['id'], 'wp_lightgalleries_data', true);
			$isInline = (($settings['layout_ignore'] == 'inline')?true:false);
			$style = '';
			if($isInline) {
				$inlineWidth = (($settings['inline_width_ignore'] != '')?$settings['inline_width_ignore']:'100%');
				$inlineHeight = (($settings['inline_height_ignore'] != '')?$settings['inline_height_ignore']:'60%'); 
				$style = 'height: 0; overflow:hidden; width: '.$inlineWidth.';';
				if(strpos($inlineHeight, '%') !== false) {
					$style .= ' padding-bottom: '.((intval($inlineHeight) / intval($inlineWidth)) * 100).'%;';
				} else {
					$style .= ' padding-bottom: '.intval($inlineHeight).'%;';
				}
			}
			$output .= '<div id="lightgallery-grid-'.$atts['id'].'" class="lightgallery-grid'.(($isInline)?' lg-wp-inline':'').'" style="'.$style.'">';
				if(isset($settings['slide_image_ignore']) && (count($settings['slide_image_ignore']) > 0)) {
					$index = 0;
					$slideWidths = $settings['slide_width_ignore'];
					$slideHeights = $settings['slide_height_ignore'];
					$slideTitles = $settings['slide_title_ignore'];
					$slideDescriptions = $settings['slide_description_ignore'];
					$globalWidth = SmartlogixControlsWrapper::get_value($settings, 'slide_global_width_ignore', '220');
					$globalHeight = SmartlogixControlsWrapper::get_value($settings, 'slide_global_height_ignore', '220');
					foreach($settings['slide_image_ignore'] as $slideImage) {
						if($slideImage != '') {
							$fullSizeImage = wp_get_attachment_image_src($slideImage, 'full');
							if(is_array($fullSizeImage) && ($fullSizeImage[0] != '')) {
								$slideWidth = $globalWidth;
								if(isset($slideWidths[$index]) && ($slideWidths[$index] != '')) {
									$slideWidth = $slideWidths[$index];
								}
								$slideHeight = $globalHeight;
								if(isset($slideHeights[$index]) && ($slideHeights[$index] != '')) {
									$slideHeight = $slideHeights[$index];
								}
								$thumbnailImage = wp_get_attachment_image_src($slideImage, array($slideWidth, $slideHeight));
								$output .= '<a class="lightgallery-grid-item" data-lg-size="'.$fullSizeImage[1].'-'.$fullSizeImage[2].'" data-sub-html="'.((isset($slideTitles[$index]) && ($slideTitles[$index] != ''))?'<h4>'.$slideTitles[$index].'</h4>':'').((isset($slideDescriptions[$index]) && ($slideDescriptions[$index] != ''))?'<p>'.$slideDescriptions[$index].'</p>':'').'" data-src="'.$fullSizeImage[0].'" style="width: '.$slideWidth.'px; height: '.$slideHeight.'px;">';
									$output .= '<img src="'.$thumbnailImage[0].'" style="width: '.$slideWidth.'px; height: '.$slideHeight.'px;" />';
								$output .= '</a>';
							}
						}
						$index++;
					}
				}
			$output .= '</div>';
			$output .= '<script type="text/javascript">';
			$settings['gallery_id'] = $atts['id'];
			$settings['plugins_multioption'] = lightgallerywp_get_active_plugins($settings);
			$settings['invoke_target_ignore'] = '#lightgallery-grid-'.$settings['gallery_id'];
			$settings['invoke_target_selector_ignore'] = '.lightgallery-grid-item';
			$settings['invoke_license_key_ignore'] = apply_filters('lightgallerywp_license_key', '');
			$output .= lightgallerywp_get_custom_gallery_lightgallery_scripts($settings, $isInline);
			$output .= '</script>';
			if(!$isInline) {
				$output .= '<script type="text/javascript">';
				$output .= lightgallerywp_get_custom_gallery_justified_gallery_scripts($settings);
				$output .= '</script>';
			}
		}
	}
	return $output;
});

function lightgallerywp_get_custom_gallery_lightgallery_scripts($settings, $isInline) {
	return lightgallerywp_load_file('custom-gallery/script'.(($isInline)?'-inline':'').'-js.php', $settings);
}

function lightgallerywp_get_custom_gallery_justified_gallery_scripts($settings) {
	return lightgallerywp_load_file('custom-gallery/justified-gallery-js.php', $settings);
}
?>
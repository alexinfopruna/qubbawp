<?php
add_action('wp_enqueue_scripts', function() {	
	wp_enqueue_script('justifiedgalleryjs', plugins_url('assets/js/jquery.justifiedGallery.min.js', dirname(__FILE__)), array('jquery'), '3.8.1');
	wp_enqueue_script('lightgalleryjs', plugins_url('assets/js/lightgallery.min.js', dirname(__FILE__)), array(), '1.0');
	wp_enqueue_style('justifiedgallerycss', plugins_url('assets/css/jquery.justifiedGallery.css', dirname(__FILE__)), array(), '3.8.1');
	wp_enqueue_style('lightgallerycss', plugins_url('assets/css/lightgallery.css', dirname(__FILE__)), array(), '1.0');
	wp_add_inline_script('lightgalleryjs', 'function lightgallerywp_document_ready(fn) {
		if (document.readyState === "complete" || document.readyState === "interactive") {
			setTimeout(fn, 1);
		} else {
			document.addEventListener("DOMContentLoaded", fn);
		}
	}');
}, 100);

function lightgallerywp_get_setting_parameter($data, $fieldName) {
	switch(true) {
		case strpos($fieldName, '_ignore') !== false:
			return '';
			break;
		case strpos($fieldName, '_string') !== false:
			$parameterType = 'string';
			$parameter = str_replace('_string', '', $fieldName);
			break;
		case strpos($fieldName, '_boolean') !== false:
			$parameterType = 'boolean';
			$parameter = str_replace('_boolean', '', $fieldName);
			break;
		case strpos($fieldName, '_number') !== false:
			$parameterType = 'number';
			$parameter = str_replace('_number', '', $fieldName);
			break;
		case strpos($fieldName, '_multioption') !== false:
			$parameterType = 'multioption';
			$parameter = str_replace('_multioption', '', $fieldName);
			break;
		default:
			$parameterType = 'string';
			$parameter = str_replace('_string', '', $fieldName);
			break;
	}
	$parameter = esc_attr($parameter);
	if(isset($data) && is_array($data) && isset($data[$fieldName]) && ($data[$fieldName] != '')) {
		switch($parameterType) {
			case 'string':
				return $parameter.': "'.esc_attr($data[$fieldName], 'post').'",'.PHP_EOL;
				break;
			case 'boolean':
			case 'number':
				return $parameter.': '.esc_attr($data[$fieldName]).','.PHP_EOL;
				break;
			case 'multioption':
				return $parameter.': ['.esc_attr(implode(',', $data[$fieldName])).'],'.PHP_EOL;
				break;
		}
		
	}
	return '';
}

function lightgallerywp_get_active_plugins($settings) {
	return apply_filters('lightgallerywp_active_plugins', array(), $settings);
}

function lightgallerywp_load_file($file, $args = array()) {
	if(($file != '') && file_exists(dirname(__FILE__).'/'.$file)) {
		extract($args);
		ob_start();
		include(dirname(__FILE__).'/'.$file);
		$content = ob_get_contents();
		ob_end_clean();
		return $content;
	}
}
?>
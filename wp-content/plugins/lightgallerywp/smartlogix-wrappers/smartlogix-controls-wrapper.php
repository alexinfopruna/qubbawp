<?php
namespace LightGallery;
class SmartlogixControlsWrapper {
	public static function get_control($type, $label, $id, $name, $value = '',  $data = null, $info = '', $style = 'input widefat') {
		if($type == 'html') {
			return $data;
		} else {
			$output = '<p class="control">';
			switch($type) {
				case 'text':
					if($label != '') { $output .= '<label for="'.$name.'">'.$label.'</label>'; }
					$output .= '<input type="text" id="'.$id.'" name="'.$name.'" value="'.$value.'" class="multilanguage-input '.$style.'">';
					break;
				case 'number':
					if($label != '') { $output .= '<label for="'.$name.'">'.$label.'</label>'; }
					$output .= '<input type="number" min="0" id="'.$id.'" name="'.$name.'" value="'.$value.'" class="multilanguage-input '.$style.'">';
					break;
				case 'number-placeholder':
					if($label != '') { $output .= '<label for="'.$name.'">'.$label.'</label>'; }
					$output .= '<input placeholder="Optional" type="number" min="0" id="'.$id.'" name="'.$name.'" value="'.$value.'" class="multilanguage-input '.$style.'">';
					break;
				case 'checkbox':
					$output .= '<input type="checkbox" id="'.$id.'" name="'.$name.'" value="1" class="input" '.checked($value, 1, false).' />';
					if($label != '') { $output .= '<label for="'.$name.'">'.$label.'</label>'; }		
					break;	
				case 'textarea':
					if($label != '') { $output .= '<label for="'.$name.'">'.$label.'</label>'; }
					$output .= '<textarea id="'.$id.'" name="'.$name.'" class="multilanguage-input '.$style.'" style="height: 100px;">'.$value.'</textarea>';			
					break;
				case 'textarea-big':
					if($label != '') { $output .= '<label for="'.$name.'">'.$label.'</label>'; }
					$output .= '<textarea id="'.$id.'" name="'.$name.'" class="multilanguage-input '.$style.'" style="height: 300px;">'.$value.'</textarea>';			
					break;
				case 'select':
					if($label != '') { $output .= '<label for="'.$name.'">'.$label.'</label>'; }
					$output .= '<select id="'.$id.'" name="'.$name.'" class="'.$style.'">';
					if($data) {
						foreach($data as $option) {
							$output .= '<option '.((isset($option['parent']))?'data-parent="'.$option['parent'].'"':'').' value="'.$option['value'].'" '.selected($value, $option['value'], false).'>'.$option['text'].'</option>';
						}
					}
					$output .= '</select>';
					break;
				case 'toggle':
					if($label != '') { $output .= '<label for="'.$name.'">'.$label.'</label>'; }
					$isActive  = true;
					if(isset($value)) {
						if($value == 'false') {
							$isActive = false;
						}
					}
					$output .= '<span class="toggle-control-wrapper">';
						$output .= '<span class="toggle-control'.(($isActive)?' active':'').'"></span>';
						$output .= '<input type="text" id="'.$id.'" name="'.$name.'" class="'.$style.' toggle-control-input" value="'.((isset($value) && ($value != ''))?$value:'true').'">';
					$output .= '</span>';
					break;
				case 'toggle-reverse':
					if($label != '') { $output .= '<label for="'.$name.'">'.$label.'</label>'; }
					$isActive  = false;
					if(isset($value)) {
						if($value == 'true') {
							$isActive = true;
						}
					}
					$output .= '<span class="toggle-control-wrapper">';
						$output .= '<span class="toggle-control'.(($isActive)?' active':'').'"></span>';
						$output .= '<input type="text" id="'.$id.'" name="'.$name.'" class="'.$style.' toggle-control-input" value="'.((isset($value) && ($value != ''))?$value:'false').'">';
					$output .= '</span>';
					break;
				case 'upload_array':
					if($label != '') { $output .= '<label for="'.$name.'">'.$label.'</label><br />'; }
					if(isset($data) && isset($value[$data]) && ($value[$data] != '')) {
						$image = wp_get_attachment_image_src($value[$data], 'full');
						$output .= '<a href="#" class="smartlogix_uploader_button"><img src="'.$image[0].'" style="max-height: 360px;margin: 10px 0;border: 1px solid #000;box-shadow: 1px 1px 5px #ddd;display: block;" /></a>';
						$output .= '<a href="#" class="smartlogix_uploader_remove_button button">Remove Image</a>';
						$output .= '<input type="hidden" id="'.$id.'" name="'.$name.'[]" value="'.$value[$data].'" />';
					} else { 
						$output .= '<a href="#" class="smartlogix_uploader_button button">Upload image</a>';
						$output .= '<a href="#" class="smartlogix_uploader_remove_button button" style="display:none">Remove Image</a>';
						$output .= '<input type="hidden" id="'.$id.'" name="'.$name.'[]" value="" />';
					}
					$output .= '<span class="clear"></span>';
					break;
				case 'upload':
					if($label != '') { $output .= '<label for="'.$name.'">'.$label.'</label><br />'; }
					if($value != '') {
						$image = wp_get_attachment_image_src($value, 'full');
						$output .= '<a href="#" class="smartlogix_uploader_button"><img src="'.$image[0].'" style="max-height: 360px;margin: 10px 0;border: 1px solid #000;box-shadow: 1px 1px 5px #ddd;display: block;" /></a>';
						$output .= '<a href="#" class="smartlogix_uploader_remove_button button">Remove Image</a>';
						$output .= '<input type="hidden" id="'.$id.'" name="'.$name.'" value="'.$value.'" />';
					} else { 
						$output .= '<a href="#" class="smartlogix_uploader_button button">Upload image</a>';
						$output .= '<a href="#" class="smartlogix_uploader_remove_button button" style="display:none">Remove Image</a>';
						$output .= '<input type="hidden" id="'.$id.'" name="'.$name.'" value="" />';
					}
					$output .= '<span class="clear"></span>';
					break;
				case 'multiselect':
					if($label != '') { $output .= '<label for="'.$name.'">'.$label.'</label><br />'; }
					$output .= '<select id="'.$id.'" name="'.$name.'[]" class="'.$style.'" multiple="multiple" style="height: 220px">';
					if($data) {
						foreach($data as $option) {
							if(is_array($value) && in_array($option['value'], $value)) {
								$output .= '<option value="'.$option['value'].'" selected="selected">'.$option['text'].'</option>';
							} else {
								$output .= '<option value="'.$option['value'].'">'.$option['text'].'</option>';
							}
						}
					}
					$output .= '</select>';
					break;
				default:
					if($label != '') { $output .= '<label for="'.$name.'">'.$label.'</label>'; }
					$output .= '<input type="'.$type.'" id="'.$id.'" name="'.$name.'" value="'.$value.'" class="'.$style.'">';
					break;
			}
			if($info != '') {
				$output .= '<span class="settings-info">'.$info.'</span>';
			}
			$output .= '</p>';
		}
		return $output;
	}
	
	public static function get_controls_js() {
		$output = '<script type="text/javascript">'.PHP_EOL;
		$output .= 'jQuery(document).ready(function() {'.PHP_EOL;
			$output .= 'jQuery("body").on("click", ".smartlogix_uploader_button", function(e) {'.PHP_EOL; 
				$output .= 'e.preventDefault();'.PHP_EOL;	 
				$output .= 'var button = jQuery(this),'.PHP_EOL;
				$output .= 'custom_uploader = wp.media({'.PHP_EOL;
					$output .= 'title: "Select / Upload Image",'.PHP_EOL;
					$output .= 'library : {'.PHP_EOL;
						$output .= 'uploadedTo : wp.media.view.settings.post.id,'.PHP_EOL;
						$output .= 'type : "image"'.PHP_EOL;
					$output .= '},'.PHP_EOL;
					$output .= 'button: {'.PHP_EOL;
						$output .= 'text: "Use this image"'.PHP_EOL;
					$output .= '},'.PHP_EOL;
					$output .= 'multiple: false'.PHP_EOL;
				$output .= '}).on("select", function() {'.PHP_EOL;
					$output .= 'var attachment = custom_uploader.state().get("selection").first().toJSON();'.PHP_EOL;
					$output .= 'button.html("<img src=\'"+attachment.url+"\' style=\'max-height: 360px;margin: 10px 0;border: 1px solid #000;box-shadow: 1px 1px 5px #ddd;display: block;\'>").removeClass("button");'.PHP_EOL;
					$output .= 'button.next().show();'.PHP_EOL;
					$output .= 'button.next().next().val(attachment.id);'.PHP_EOL;
				$output .= '}).open();'.PHP_EOL;
			$output .= '});'.PHP_EOL;
			$output .= 'jQuery("body").on("click", ".smartlogix_uploader_remove_button", function(e) {'.PHP_EOL;	 
				$output .= 'e.preventDefault();'.PHP_EOL; 
				$output .= 'var button = jQuery(this);'.PHP_EOL;
				$output .= 'button.next().val("");'.PHP_EOL;
				$output .= 'button.hide().prev().html("Upload image").addClass("button");'.PHP_EOL;
			$output .= '});'.PHP_EOL;
		$output .= '});'.PHP_EOL;
		$output .= '</script>'.PHP_EOL;
		return $output;
	}
	
	public static function get_value($data, $fieldName, $default = '') {
		if(isset($data) && is_array($data) && isset($data[$fieldName]) && ($data[$fieldName] != '')) {
			return $data[$fieldName];
		}
		return $default;
	}
}
?>
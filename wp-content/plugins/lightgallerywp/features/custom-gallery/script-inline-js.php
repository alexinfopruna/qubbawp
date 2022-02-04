<?php namespace LightGallery; ?>
lightgallerywp_document_ready(function() {		
	var galleries = document.querySelectorAll('<?php echo esc_js(SmartlogixControlsWrapper::get_value($args, 'invoke_target_ignore')); ?>');
	[].forEach.call(
		galleries,
		function(el) {
			var lg = lightGallery(el, {
				selector: '<?php echo esc_js(SmartlogixControlsWrapper::get_value($args, 'invoke_target_selector_ignore')); ?>',
				licenseKey: '<?php echo esc_js(SmartlogixControlsWrapper::get_value($args, 'invoke_license_key_ignore')); ?>',
				container: el,
				hash: false,
				closable: false,
				<?php
				if(isset($args) && is_array($args)) {
					foreach($args as $key => $value) {
						echo lightgallerywp_get_setting_parameter($args, $key);
					}
				}
				?>
			});
			lg.openGallery();
		}
	);
});
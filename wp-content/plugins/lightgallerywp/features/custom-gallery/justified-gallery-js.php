<?php namespace LightGallery; ?>
lightgallerywp_document_ready(function() {
	jQuery('<?php echo esc_js(SmartlogixControlsWrapper::get_value($args, 'invoke_target_ignore')); ?>').justifiedGallery({
		rowHeight :<?php echo esc_js(SmartlogixControlsWrapper::get_value($args, 'justified_gallery_row_height_ignore')); ?>,
		lastRow : '<?php echo esc_js(SmartlogixControlsWrapper::get_value($args, 'justified_gallery_last_row_ignore')); ?>',
		maxRowHeight : <?php echo esc_js(SmartlogixControlsWrapper::get_value($args, 'justified_gallery_max_row_height_ignore')); ?>,
		maxRowsCount: <?php echo esc_js(SmartlogixControlsWrapper::get_value($args, 'justified_gallery_max_row_count_ignore')); ?>,
		margins: <?php echo esc_js(SmartlogixControlsWrapper::get_value($args, 'justified_gallery_margin_ignore')); ?>,
		border: <?php echo esc_js(SmartlogixControlsWrapper::get_value($args, 'justified_gallery_border_ignore')); ?>
	});
});
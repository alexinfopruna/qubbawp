<?php
namespace LightGallery;
class SmartlogixSettingsWrapper {
	private $menuName;
	private $pageName;
	private $pageIdentifier;
	private $capability;
	private $position;
	private $settingsName;
	private $menuParent;
	private $menuHook;
	private $metaboxes;
	private $controls;
	private $callBackFunctions;	

	function __construct($args) {
		$this->menuName = (isset($args['menuName'])?$args['menuName']:'Settings Menu');
		$this->pageName = (isset($args['pageName'])?$args['pageName']:'Settings Page Title');
		$this->pageIdentifier = (isset($args['pageIdentifier'])?$args['pageIdentifier']:str_replace(' ', '_', strtolower($this->menuName)));
		$this->capability = (isset($args['capability'])?$args['capability']:'manage_options');
		$this->menuParent = (isset($args['menuParent'])?$args['menuParent']:'root');
		$this->position = (isset($args['position'])?$args['position']:100);
		$this->settingsName = (isset($args['settingsName'])?$args['settingsName']:$this->pageIdentifier.'_settings');
		
		if(isset($args['metabox']) &&($args['metabox'] != '')) {
			$this->metaboxes = array(
				str_replace(' ', '_', strtolower($args['metabox'])) => $args['metabox']
			);
		} else {
			if(isset($args['metaboxes']) && is_array($args['metaboxes'])) {
				$this->metaboxes = $args['metaboxes'];
			}
		}
		
		$this->controls = ((isset($args['controls']) && is_array($args['controls']))?$args['controls']:array());
		
		$this->callBackFunctions = ((isset($args['callBackFunctions']) && is_array($args['callBackFunctions']))?$args['callBackFunctions']:array());	
		
		add_filter('admin_init', array($this, 'admin_init'));
		add_filter('admin_menu', array($this, 'admin_menu'));
		add_action('admin_enqueue_scripts', array($this, 'admin_enqueue_scripts'));
		add_filter('screen_layout_columns', array($this, 'screen_layout_columns'), 10, 2);
		add_action('add_meta_boxes', array($this, 'add_meta_boxes'));
	}

	public function admin_init() {
		register_setting($this->settingsName, $this->settingsName);
	}
	
	public function admin_menu() {
		if($this->menuParent == 'root') {
			$this->menuHook = add_menu_page($this->menuName, $this->menuName, $this->capability, $this->pageIdentifier, array($this, 'settings_page_content'), $this->position);
		} else {
			$this->menuHook = add_submenu_page($this->menuParent, $this->menuName, $this->menuName, $this->capability, $this->pageIdentifier, array($this, 'settings_page_content'), $this->position);
		}
	}
	
	public function admin_enqueue_scripts($hook) {
		if($hook == $this->menuHook) {
			wp_enqueue_script('common');
			wp_enqueue_script('wp-lists');
			wp_enqueue_script('postbox');
			if(isset($this->callBackFunctions) && is_array($this->callBackFunctions) && isset($this->callBackFunctions['admin_enqueue_scripts']) && is_callable($this->callBackFunctions['admin_enqueue_scripts'])) {
				call_user_func($this->callBackFunctions['admin_enqueue_scripts']);
			}
		}
	}
	
	public function screen_layout_columns($columns, $screen) {
		if($screen == $this->menuHook) {
			$columns[$this->pageIdentifier] = 2;
		}
		return $columns;
	}
	
	public function add_meta_boxes() {
		if(isset($this->metaboxes) && is_array($this->metaboxes)) {
			$data = get_option($this->settingsName);			
			$index = 1;
			foreach($this->metaboxes as $key => $title) {
				add_meta_box(
					$this->pageIdentifier.'_metabox_'.$index,
					$title,
					array($this, 'meta_box_content'),
					$this->pageIdentifier,
					'normal',
					'default',
					array(
						'index' => $index,
						'metaboxID' => $key,
						'data' => $data,
					)
				);
				$index++;
			}
		}
		if(isset($this->callBackFunctions) && is_array($this->callBackFunctions) && isset($this->callBackFunctions['register_meta_box']) && is_callable($this->callBackFunctions['register_meta_box'])) {
			call_user_func($this->callBackFunctions['register_meta_box']);
		}
	}
	
	public function meta_box_content($post, $args) {	
		$currentSections = array();
		if(isset($this->controls) && is_array($this->controls)) {
			foreach($this->controls as $control) {
				if(isset($control['metabox']) && isset($args['args']['metaboxID']) && ($control['metabox'] == $args['args']['metaboxID'])) {
					if(isset($control['section']) && ($control['section'] != '')) {
						if(!isset($currentSections[$control['section']])) {
							$currentSections[$control['section']] = array();
						}
						$currentSections[$control['section']][] = $control;
					}
				}
			}
		}
		
		if(isset($currentSections) && is_array($currentSections)) {
			echo '<div class="vtabs lg-tabs">';
				echo '<ul>';
					foreach($currentSections as $sectionName => $sectionControls) {
						echo '<li>';
							echo '<a href="#tabs-'.esc_attr(str_replace(array(' ', '-'), '_', strip_tags($sectionName))).'">'.$sectionName.'</a>';
						echo '</li>';
					}
				echo '</ul>';
				foreach($currentSections as $sectionName => $sectionControls) {
					echo '<div id="tabs-'.esc_attr(str_replace(array(' ', '-'), '_', strip_tags($sectionName))).'">';
						echo '<div class="lg-tab-content" style="margin: 0; padding: 0 15px; border: 1px solid #ddd; border-radius: 5px; position: relative;">';
							echo '<label style="font-weight: bold; position: absolute; left: 15px; top: -10px; background: #FFFFFF; padding: 0px 10px;">'.$sectionName.'</label>';
							if(isset($sectionControls) && is_array($sectionControls)) {
								foreach($sectionControls as $sectionControl) {
									echo SmartlogixControlsWrapper::get_control($sectionControl['type'], $sectionControl['label'], $this->settingsName.'_'.$sectionControl['id'], $this->settingsName.'['.$sectionControl['id'].']', SmartlogixControlsWrapper::get_value($args['args']['data'], $sectionControl['id']), ((isset($sectionControl['data']))?$sectionControl['data']:null), ((isset($sectionControl['info']))?$sectionControl['info']:null), 'input widefat'.((isset($sectionControl['style']))?' '.$sectionControl['style']:''));
								}
							}
						echo '</div>';
					echo '</div>';
				}
			echo '</div>';
		}
		if(isset($this->callBackFunctions) && is_array($this->callBackFunctions) && isset($this->callBackFunctions['meta_box_content']) && is_callable($this->callBackFunctions['meta_box_content'])) {
			call_user_func($this->callBackFunctions['meta_box_content'], $args['args']);
		}
		if($args['args']['index'] == 1) {
			echo SmartlogixControlsWrapper::get_controls_js();
		}
	}
	
	public function settings_page_content() {
		do_action('add_meta_boxes', $this->pageIdentifier);
		echo '<div class="wrap">';
			echo '<h2>'.esc_attr($this->pageName).'</h2>';
			settings_errors(); 
			echo '<div class="'.esc_attr($this->pageIdentifier).'_wrap">';
				echo '<form id="'.esc_attr($this->pageIdentifier).'_form" method="post" action="options.php">';
					settings_fields($this->settingsName);
					wp_nonce_field('closedpostboxes', 'closedpostboxesnonce', false);
					wp_nonce_field('meta-box-order', 'meta-box-order-nonce', false);
					echo '<div id="poststuff">';
						 echo '<div id="post-body" class="metabox-holder columns-'.((1 == get_current_screen()->get_columns())?'1':'2').'">';
							echo '<div id="postbox-container-1" class="postbox-container">';
								echo '<div id="submitpost" class="submitbox">';
									echo '<input type="submit" name="submit" id="submit" class="button button-primary" style="width: 100%;padding: 10px 15px;font-size: 28px;" value="Save / Update">';
								echo '</div>';
								do_meta_boxes($this->pageIdentifier, 'side', null);
							echo '</div>';
							echo '<div id="postbox-container-2" class="postbox-container">';
								do_meta_boxes($this->pageIdentifier, 'normal', null);
								do_meta_boxes($this->pageIdentifier, 'advanced', null);
							echo '</div>';
							echo '<br class="clear">';
						echo '</div>';
						echo '<br class="clear">';
					echo '</div>';
				echo '</form>';
			echo '</div>';
		echo '</div>';
	}
}
?>
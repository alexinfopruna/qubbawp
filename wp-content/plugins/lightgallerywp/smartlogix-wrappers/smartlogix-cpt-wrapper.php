<?php
namespace LightGallery;
class SmartlogixCPTWrapper {
	private $name;
	private $singularName;
	private $pluralName;
	private $supports;
	private $metaboxes;
	private $controls;
	private $callBackFunctions;	
	
	private $CPTLabels;
	private $CPTArgs;

	function __construct($args) {
		$this->name = (isset($args['name'])?$args['name']:'cpt_name');
		$this->singularName = (isset($args['singularName'])?$args['singularName']:'CPT Singular Name');
		$this->pluralName = (isset($args['pluralName'])?$args['pluralName']:'CPT Plural Name');
		$this->supports = ((isset($args['supports']) && is_array($args['supports']))?$args['supports']:array('title'));
		
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
		
		$this->CPTLabels = array(
			'name' => $this->pluralName,
			'singular_name' => $this->singularName,
			'add_new' => 'Add New',
			'add_new_item' => 'Add New '.$this->singularName,
			'edit_item' => 'Edit '.$this->singularName,
			'new_item' => 'New '.$this->singularName,
			'view_item' => 'View '.$this->singularName,
			'search_items' => 'Search '.$this->pluralName,
			'not_found' => 'No '.$this->pluralName.' found',
			'not_found_in_trash' => 'No '.$this->pluralName.' found in Trash',
			'parent_item_colon' => 'Parent '.$this->pluralName.':',
			'menu_name' => $this->pluralName,
		);
		if(isset($args['labels']) && is_array($args['labels'])) {
			$this->CPTLabels = array_merge($this->CPTLabels, $args['labels']);
		}

		$this->CPTArgs = array( 
			'labels' => $this->CPTLabels,
			'hierarchical' => true,
			'description' => $this->pluralName,
			'supports' => $this->supports,
			'public' => true,
			'show_ui' => true,
			'menu_position' => 50,
			'register_meta_box_cb' => array($this, 'register_meta_box'),
			'show_in_nav_menus' => true,
			'publicly_queryable' => true,
			'exclude_from_search' => true,
			'has_archive' => false,
			'query_var' => true,
			'can_export' => true,
			'rewrite' => true,
			'capability_type' => 'post'
		);
		
		if(isset($args['args']) && is_array($args['args'])) {
			$this->CPTArgs = array_merge($this->CPTArgs, $args['args']);
		}
		
		add_action('init', array($this, 'init'));
		add_filter('post_updated_messages', array($this, 'post_updated_messages'));
		add_action('admin_enqueue_scripts', array($this, 'admin_enqueue_scripts'));
		add_action('save_post',  array($this, 'save_post'));
	}
	
	public function init() {
		register_post_type($this->name, $this->CPTArgs);

		if(isset($this->callBackFunctions) && is_array($this->callBackFunctions) && isset($this->callBackFunctions['init']) && is_callable($this->callBackFunctions['init'])) {
			call_user_func($this->callBackFunctions['init']);
		}
	}
	
	public function post_updated_messages($messages) {
		$post = get_post();
		$post_type = get_post_type($post);

		$messages[$this->name] = array(
			0  => '',
			1  => $this->singularName.' updated.',
			2  => 'Custom field updated.',
			3  => 'Custom field deleted.',
			4  => $this->singularName.' updated.',
			5  => isset($_GET['revision']) ? sprintf($this->singularName.' restored to revision from %s', wp_post_revision_title((int)$_GET['revision'], false)) : false,
			6  => $this->singularName.' published.',
			7  => $this->singularName.' saved.',
			8  => $this->singularName.' submitted.',
			9  => sprintf($this->singularName.' scheduled for: <strong>%1$s</strong>.', date_i18n( 'M j, Y @ G:i', strtotime($post->post_date))),
			10 => $this->singularName.' draft updated.'
		);

		if(isset($this->callBackFunctions) && is_array($this->callBackFunctions) && isset($this->callBackFunctions['post_updated_messages']) && is_callable($this->callBackFunctions['post_updated_messages'])) {
			call_user_func($this->callBackFunctions['post_updated_messages'], $messages);
		}
		
		return $messages;
	}
	
	public function admin_enqueue_scripts($hook) {
		if(in_array($hook, array('post.php', 'post-new.php'))) {
			$screen = get_current_screen();
			if(is_object($screen) && $this->name == $screen->post_type){
				if(!did_action('wp_enqueue_media')) {
					wp_enqueue_media();
				}
				wp_enqueue_script('jquery');
				wp_enqueue_script('jquery-ui-core');
				wp_enqueue_script('jquery-ui-tabs');
				if(isset($this->callBackFunctions) && is_array($this->callBackFunctions) && isset($this->callBackFunctions['admin_enqueue_scripts']) && is_callable($this->callBackFunctions['admin_enqueue_scripts'])) {
					call_user_func($this->callBackFunctions['admin_enqueue_scripts']);
				}
			}
		}
	}
	
	public function register_meta_box() {
		global $post;
		$data = get_post_meta($post->ID, 'wp_'.$this->name.'_data', true);
		if(isset($this->metaboxes) && is_array($this->metaboxes)) {			
			$index = 1;
			foreach($this->metaboxes as $key => $title) {
				add_meta_box(
					'smartlogix_cpt_metabox_'.$index,
					$title,
					array($this, 'meta_box_content'),
					$this->name,
					'normal',
					'default',
					array(
						'index' => $index,
						'metaboxID' => $key,
						'postID' => $post->ID,
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
		if($args['args']['index'] == 1) {
			wp_nonce_field(plugin_basename(__FILE__), 'wp_'.$this->name.'_nonce');
			echo SmartlogixControlsWrapper::get_controls_js();
		}
		
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
		
		if(isset($currentSections) && is_array($currentSections) && (count($currentSections) > 0)) {
			echo '<div class="vtabs lg-tabs">';
				echo '<ul id="lg-tabs">';
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
									echo SmartlogixControlsWrapper::get_control($sectionControl['type'], $sectionControl['label'], 'wp_'.$this->name.'_data_'.$sectionControl['id'], 'wp_'.$this->name.'_data['.$sectionControl['id'].']', SmartlogixControlsWrapper::get_value($args['args']['data'], $sectionControl['id'], SmartlogixControlsWrapper::get_value($sectionControl, 'default')), ((isset($sectionControl['data']))?$sectionControl['data']:null), ((isset($sectionControl['info']))?$sectionControl['info']:null), 'input widefat'.((isset($sectionControl['style']))?' '.$sectionControl['style']:''));
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
	}
	
	public function save_post($postID) {
		if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) { return; }
		if(isset($_POST['wp_'.$this->name.'_nonce']) && !wp_verify_nonce($_POST['wp_'.$this->name.'_nonce'], plugin_basename( __FILE__ ))) { return; }
		if(isset($_POST['post_type']) && ($this->name == $_POST['post_type'])) {
			if(!current_user_can('edit_post', $postID)) { return; }
		} else { return; }
		if(isset($_POST['wp_'.$this->name.'_data'])) {
			$sanitizedData = map_deep($_POST['wp_'.$this->name.'_data'], 'sanitize_text_field');;
			update_post_meta($postID, 'wp_'.$this->name.'_data', $sanitizedData);
		}
		if(isset($this->callBackFunctions) && is_array($this->callBackFunctions) && isset($this->callBackFunctions['save_post']) && is_callable($this->callBackFunctions['save_post'])) {
			call_user_func($this->callBackFunctions['save_post']);
		}
	}
}
?>
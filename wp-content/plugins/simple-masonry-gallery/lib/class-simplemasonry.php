<?php
/**
 * Simple Masonry Gallery
 *
 * @package    Simple Masonry Gallery
 * @subpackage SimpleMasonry Main Functions
/*
	Copyright (c) 2014- Katsushi Kawamori (email : dodesyoswift312@gmail.com)
	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation; version 2 of the License.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 */

$simplemasonry = new SimpleMasonry();

/** ==================================================
 * Main Functions
 */
class SimpleMasonry {

	/** ==================================================
	 * For multiple shortcode
	 *
	 * @var $simplemasonry_count  simplemasonry_count.
	 */
	private $simplemasonry_count;

	/** ==================================================
	 * For multiple shortcode
	 *
	 * @var $simplemasonry_atts  simplemasonry_atts.
	 */
	private $simplemasonry_atts;

	/** ==================================================
	 * Construct
	 *
	 * @since 6.06
	 */
	public function __construct() {

		add_shortcode( 'simplemasonrygallery', array( $this, 'simplemasonrygallery_func' ) );
		add_action( 'init', array( $this, 'simplemasonrygallery_block_init' ) );

		add_action( 'wp_enqueue_scripts', array( $this, 'load_frontend_scripts' ) );
		add_action( 'wp_footer', array( $this, 'load_localize_scripts_styles' ) );

	}

	/** ==================================================
	 * Attribute block
	 *
	 * @since 1.00
	 */
	public function simplemasonrygallery_block_init() {

		$asset_file = include( plugin_dir_path( __DIR__ ) . 'block/dist/simplemasonrygallery-block.asset.php' );

		wp_register_script(
			'simplemasonrygallery-block',
			plugins_url( 'block/dist/simplemasonrygallery-block.js', dirname( __FILE__ ) ),
			$asset_file['dependencies'],
			$asset_file['version'],
			true
		);

		wp_localize_script(
			'simplemasonrygallery-block',
			'simplemasonrygallery_text',
			array(
				'creategallery' => __( 'Create Gallery' ),
				'updategallery' => __( 'Update gallery' ),
				'space' => __( 'Space', 'simple-masonry-gallery' ),
				'r_images' => __( 'Rounded Images', 'simple-masonry-gallery' ),
				'link' => __( 'Link to Media File' ),
				'width' => __( 'Width' ),
			)
		);

		register_block_type(
			'simple-masonry-gallery/simplemasonrygallery-block',
			array(
				'editor_script'   => 'simplemasonrygallery-block',
				'attributes'      => array(
					'width'    => array(
						'type'    => 'number',
						'default' => 100,
					),
					'padding'  => array(
						'type'    => 'number',
						'default' => 1,
					),
					'r_images' => array(
						'type'    => 'boolean',
						'default' => true,
					),
					'link'     => array(
						'type'    => 'boolean',
						'default' => false,
					),
				),
			)
		);

	}

	/** ==================================================
	 * Add image tag
	 *
	 * @param string $content  content.
	 * @param string $simplemasonry_id  simplemasonry_id.
	 * @return string $content
	 * @since 1.0
	 */
	private function add_img_tag( $content, $simplemasonry_id ) {

		remove_shortcode( 'gallery', 'gallery_shortcode' );
		add_shortcode( 'gallery', array( $this, 'simplemasonry_gallery_shortcode' ) );
		$gallery_code = null;
		$pattern_gallery = '/\[' . preg_quote( 'gallery ' ) . '[^\]]*\]/im';
		if ( ! empty( $content ) && preg_match( $pattern_gallery, $content ) ) {
			preg_match_all( $pattern_gallery, $content, $retgallery );
			foreach ( $retgallery as $ret => $gals ) {
				foreach ( $gals as $gal ) {
					$gallery_code = do_shortcode( $gal );
					$content = str_replace( $gal, $gallery_code, $content );
				}
			}
		}
		remove_shortcode( 'gallery', array( $this, 'simplemasonry_gallery_shortcode' ) );
		add_shortcode( 'gallery', 'gallery_shortcode' );

		$allowed_html = array(
			'a' => array(
				'href' => array(),
				'target' => array(),
			),
			'img' => array(),
		);
		wp_kses( $content, $allowed_html );

		$content = str_replace( '<br />', '', $content );
		$content = str_replace( '<br>', '', $content );
		$content = str_replace( '<p>', '', $content );
		$content = str_replace( '</p>', '', $content );

		$img_code = null;
		if ( preg_match_all( '/<img(.+?)>/mis', $content, $result ) !== false ) {
			if ( count( $result[0] ) > 0 ) {
				foreach ( $result[0] as $value ) {
					$img_code = '<div class="simplemasonry-item-' . $simplemasonry_id . '">' . $value . '</div>';
					if ( ! strpos( $content, $img_code ) ) {
						$content = str_replace( $value, $img_code, $content );
					}
				}
			}
		}

		$content = '<div id="simplemasonry-container-' . $simplemasonry_id . '">' . $content . '</div>';

		return $content;

	}

	/**
	 * The Gallery shortcode.
	 *
	 * This implements the functionality of the Gallery Shortcode for displaying
	 * WordPress images on a post.
	 *
	 * @since 2.5.0
	 *
	 * @param array $attr {
	 *     Attributes of the gallery shortcode.
	 *
	 *     @type string $order      Order of the images in the gallery. Default 'ASC'. Accepts 'ASC', 'DESC'.
	 *     @type string $orderby    The field to use when ordering the images. Default 'menu_order ID'.
	 *                              Accepts any valid SQL ORDERBY statement.
	 *     @type int    $id         Post ID.
	 *     @type string $itemtag    HTML tag to use for each image in the gallery.
	 *                              Default 'dl', or 'figure' when the theme registers HTML5 gallery support.
	 *     @type string $icontag    HTML tag to use for each image's icon.
	 *                              Default 'dt', or 'div' when the theme registers HTML5 gallery support.
	 *     @type string $captiontag HTML tag to use for each image's caption.
	 *                              Default 'dd', or 'figcaption' when the theme registers HTML5 gallery support.
	 *     @type int    $columns    Number of columns of images to display. Default 3.
	 *     @type string $size       Size of the images to display. Default 'thumbnail'.
	 *     @type string $ids        A comma-separated list of IDs of attachments to display. Default empty.
	 *     @type string $include    A comma-separated list of IDs of attachments to include. Default empty.
	 *     @type string $exclude    A comma-separated list of IDs of attachments to exclude. Default empty.
	 *     @type string $link       What to link each image to. Default empty (links to the attachment page).
	 *                              Accepts 'file', 'none'.
	 * }
	 * @return string HTML content to display gallery.
	 */
	public function simplemasonry_gallery_shortcode( $attr ) {

		$post = get_post();

		static $instance = 0;
		$instance++;

		if ( ! empty( $attr['ids'] ) ) {
			/* 'ids' is explicitly ordered, unless you specify otherwise. */
			if ( empty( $attr['orderby'] ) ) {
				$attr['orderby'] = 'post__in';
			}
			$attr['include'] = $attr['ids'];
		}

		/**
		 * Filter the default gallery shortcode output.
		 *
		 * If the filtered output isn't empty, it will be used instead of generating
		 * the default gallery template.
		 *
		 * @since 2.5.0
		 * @since 4.2.0 The `$instance` parameter was added.
		 *
		 * @see gallery_shortcode()
		 *
		 * @param string $output The gallery output. Default empty.
		 * @param array  $attr   Attributes of the gallery shortcode.
		 */
		$output = apply_filters( 'post_gallery', '', $attr, $instance );
		if ( '' != $output ) {
			return $output;
		}

		$html5 = current_theme_supports( 'html5', 'gallery' );
		$atts = shortcode_atts(
			array(
				'order'      => 'ASC',
				'orderby'    => 'menu_order ID',
				'id'         => $post ? $post->ID : 0,
				'itemtag'    => $html5 ? 'figure' : 'dl',
				'icontag'    => $html5 ? 'div' : 'dt',
				'captiontag' => $html5 ? 'figcaption' : 'dd',
				'columns'    => 3,
				'size'       => 'full',
				'include'    => '',
				'exclude'    => '',
				'link'       => 'file',
			),
			$attr,
			'gallery'
		);

		$id = intval( $atts['id'] );

		if ( ! empty( $atts['include'] ) ) {
			$_attachments = get_posts(
				array(
					'include' => $atts['include'],
					'post_status' => 'inherit',
					'post_type' => 'attachment',
					'post_mime_type' => 'image',
					'order' => $atts['order'],
					'orderby' => $atts['orderby'],
				)
			);

			$attachments = array();
			foreach ( $_attachments as $key => $val ) {
				$attachments[ $val->ID ] = $_attachments[ $key ];
			}
		} elseif ( ! empty( $atts['exclude'] ) ) {
			$attachments = get_children(
				array(
					'post_parent' => $id,
					'exclude' => $atts['exclude'],
					'post_status' => 'inherit',
					'post_type' => 'attachment',
					'post_mime_type' => 'image',
					'order' => $atts['order'],
					'orderby' => $atts['orderby'],
				)
			);
		} else {
			$attachments = get_children(
				array(
					'post_parent' => $id,
					'post_status' => 'inherit',
					'post_type' => 'attachment',
					'post_mime_type' => 'image',
					'order' => $atts['order'],
					'orderby' => $atts['orderby'],
				)
			);
		}

		if ( empty( $attachments ) ) {
			return '';
		}

		if ( is_feed() ) {
			$output = "\n";
			foreach ( $attachments as $att_id => $attachment ) {
				$output .= wp_get_attachment_link( $att_id, $atts['size'], true ) . "\n";
			}
			return $output;
		}

		$itemtag = tag_escape( $atts['itemtag'] );
		$captiontag = tag_escape( $atts['captiontag'] );
		$icontag = tag_escape( $atts['icontag'] );
		$valid_tags = wp_kses_allowed_html( 'post' );
		if ( ! isset( $valid_tags[ $itemtag ] ) ) {
			$itemtag = 'dl';
		}
		if ( ! isset( $valid_tags[ $captiontag ] ) ) {
			$captiontag = 'dd';
		}
		if ( ! isset( $valid_tags[ $icontag ] ) ) {
			$icontag = 'dt';
		}

		$columns = intval( $atts['columns'] );
		$itemwidth = $columns > 0 ? floor( 100 / $columns ) : 100;
		$float = is_rtl() ? 'right' : 'left';

		$selector = "gallery-{$instance}";

		$gallery_style = '';

		/**
		 * Filters whether to print default gallery styles.
		 *
		 * @since 3.1.0
		 *
		 * @param bool $print Whether to print default gallery styles.
		 *                    Defaults to false if the theme supports HTML5 galleries.
		 *                    Otherwise, defaults to true.
		 */
		if ( apply_filters( 'use_default_gallery_style', ! $html5 ) ) {
			$gallery_style = "
			<style type='text/css'>
				#{$selector} {
					margin: auto;
				}
				#{$selector} .gallery-item {
					float: {$float};
					margin-top: 10px;
					text-align: center;
					width: {$itemwidth}%;
				}
				#{$selector} img {
					border: 2px solid #cfcfcf;
				}
				#{$selector} .gallery-caption {
					margin-left: 0;
				}
				/* see gallery_shortcode() in wp-includes/media.php */
			</style>\n\t\t";
		}

		$size_class = sanitize_html_class( $atts['size'] );
		$gallery_div = "<div id='$selector' class='gallery galleryid-{$id} gallery-columns-{$columns} gallery-size-{$size_class}'>";

		/**
		 * Filters the default gallery shortcode CSS styles.
		 *
		 * @since 2.5.0
		 *
		 * @param string $gallery_style Default CSS styles and opening HTML div container
		 *                              for the gallery shortcode output.
		 */
		$output = apply_filters( 'gallery_style', $gallery_style . $gallery_div );

		$i = 0;
		foreach ( $attachments as $id => $attachment ) {

			$attr = ( trim( $attachment->post_excerpt ) ) ? array( 'aria-describedby' => "$selector-$id" ) : '';
			if ( ! empty( $atts['link'] ) && 'file' === $atts['link'] ) {
				$image_output = wp_get_attachment_link( $id, $atts['size'], false, false, false, $attr );
			} elseif ( ! empty( $atts['link'] ) && 'none' === $atts['link'] ) {
				$image_output = wp_get_attachment_image( $id, $atts['size'], false, $attr );
			} else {
				$image_output = wp_get_attachment_link( $id, $atts['size'], true, false, false, $attr );
			}
			$image_meta  = wp_get_attachment_metadata( $id );

			$orientation = '';
			if ( isset( $image_meta['height'], $image_meta['width'] ) ) {
				$orientation = ( $image_meta['height'] > $image_meta['width'] ) ? 'portrait' : 'landscape';
			}
			$output .= "<{$itemtag} class='gallery-item'>";
			$output .= "
				<{$icontag} class='gallery-icon {$orientation}'>
					$image_output
				</{$icontag}>";
			if ( $captiontag && trim( $attachment->post_excerpt ) ) {
				$output .= "
					<{$captiontag} class='wp-caption-text gallery-caption' id='$selector-$id'>
					" . wptexturize( $attachment->post_excerpt ) . "
					</{$captiontag}>";
			}
			$output .= "</{$itemtag}>";
			++$i;
			if ( ! $html5 && 0 < $columns && 0 == $i % $columns ) {
				$output .= '<br style="clear: both" />';
			}
		}

		if ( ! $html5 && 0 < $columns && 0 !== $i % $columns ) {
			$output .= "
				<br style='clear: both' />";
		}

		$output .= "
			</div>\n";

		return $output;

	}

	/** ==================================================
	 * Load Script
	 *
	 * @since 5.11
	 */
	public function load_frontend_scripts() {

		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'jquery-masonry' );

	}

	/** ==================================================
	 * Load Localize Script and Style
	 *
	 * @since 6.00
	 */
	public function load_localize_scripts_styles() {

		if ( empty( $this->simplemasonry_atts ) ) {
			return;
		}

		$localize_masonry_settings = array();
		wp_enqueue_script( 'simple-masonry-jquery', plugin_dir_url( __DIR__ ) . 'js/jquery.simplemasonry.js', array( 'jquery' ), '1.00', false );
		foreach ( $this->simplemasonry_atts as $key => $value ) {
			/* Script */
			$localize_masonry_settings = array_merge( $localize_masonry_settings, $value );
			/* Style */
			$width = $value[ 'width' . $key ];
			if ( ! strpos( $width, 'px' ) ) {
				$width = $width . 'px'; }
			$margin = $this->margin_padding_replace( $value[ 'margin' . $key ] );
			$padding = $this->margin_padding_replace( $value[ 'padding' . $key ] );
			$simplemasonry_id = $value[ 'id' . $key ];
			wp_enqueue_style( 'simple-masonry' . $simplemasonry_id, plugin_dir_url( __DIR__ ) . 'css/simplemasonry.css', array(), '1.00' );
			$css = '#simplemasonry-container-' . $simplemasonry_id . '{ margin: 0 auto; padding: 0; }
					.simplemasonry-item-' . $simplemasonry_id . ' { width: ' . $width . '; float: left; margin: ' . $margin . '; padding: ' . $padding . '; }
					.simplemasonry-item-' . $simplemasonry_id . ' img{width:100%; max-width:100%; height:auto; margin:0;}';
			wp_add_inline_style( 'simple-masonry' . $simplemasonry_id, $css );
		}

		/* Script */
		$maxcount = array( 'maxcount' => $this->simplemasonry_count );
		$localize_masonry_settings = array_merge( $localize_masonry_settings, $maxcount );
		wp_localize_script( 'simple-masonry-jquery', 'masonry_settings', $localize_masonry_settings );

	}

	/** ==================================================
	 * CSS margin padding replace
	 *
	 * @param string $str  str.
	 * @return string $replace_str
	 * @since 6.04
	 */
	private function margin_padding_replace( $str ) {

		$replace_str = null;
		$keywords = preg_split( '/[\s]+/', $str, -1, PREG_SPLIT_NO_EMPTY );
		if ( $keywords ) {
			foreach ( $keywords as $val ) {
				if ( ! strpos( $val, 'px' ) && 0 <> $val ) {
					$val = $val . 'px'; }
				$replace_str .= $val . ' ';
			}
			$replace_str = rtrim( $replace_str, ' ' );
		} else {
			if ( ! strpos( $str, 'px' ) && 0 <> $str ) {
				$str = $str . 'px'; }
			$replace_str = $str;
		}

		return $replace_str;

	}

	/** ==================================================
	 * Short code
	 *
	 * @param array  $atts  atts.
	 * @param string $content  content.
	 * @return string $content
	 * @since 5.00
	 */
	public function simplemasonrygallery_func( $atts, $content = null ) {

		$a = shortcode_atts(
			array(
				'width' => '',
				'margin' => '',
				'padding' => '',
			),
			$atts
		);

		if ( empty( $a['width'] ) ) {
			$a['width'] = 100;
		}
		if ( empty( $a['margin'] ) ) {
			$a['margin'] = '1px';
		}
		if ( empty( $a['padding'] ) ) {
			$a['padding'] = '1px';
		}

		++$this->simplemasonry_count;
		$simplemasonry_id = get_the_ID() . '-' . $this->simplemasonry_count;

		$content = $this->add_img_tag( $content, $simplemasonry_id );

		$new_atts = array();
		foreach ( $a as $key => $value ) {
			$new_atts[ $key . $this->simplemasonry_count ] = $value;
		}
		$id_count_tbl = array( 'id' . $this->simplemasonry_count => $simplemasonry_id );
		$this->simplemasonry_atts[ $this->simplemasonry_count ] = array_merge( $new_atts, $id_count_tbl );

		return do_shortcode( $content );

	}

}



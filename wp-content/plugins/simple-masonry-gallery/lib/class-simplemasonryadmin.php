<?php
/**
 * Simple Masonry Gallery
 *
 * @package    Simple Masonry Gallery
 * @subpackage SimpleMasonryAdmin Management screen
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

$simplemasonryadmin = new SimpleMasonryAdmin();

/** ==================================================
 * Management screen
 */
class SimpleMasonryAdmin {

	/** ==================================================
	 * Construct
	 *
	 * @since 6.06
	 */
	public function __construct() {

		add_action( 'admin_menu', array( $this, 'plugin_menu' ) );
		add_filter( 'plugin_action_links', array( $this, 'settings_link' ), 10, 2 );
		add_action( 'admin_print_footer_scripts', array( $this, 'simplemasonry_add_quicktags' ) );

	}

	/** ==================================================
	 * Add a "Settings" link to the plugins page
	 *
	 * @param  array  $links  links array.
	 * @param  string $file   file.
	 * @return array  $links  links array.
	 * @since 1.00
	 */
	public function settings_link( $links, $file ) {
		static $this_plugin;
		if ( empty( $this_plugin ) ) {
			$this_plugin = 'simple-masonry-gallery/simplemasonry.php';
		}
		if ( $file == $this_plugin ) {
			$links[] = '<a href="' . admin_url( 'options-general.php?page=simplemasonry' ) . '">' . __( 'Settings' ) . '</a>';
		}
			return $links;
	}

	/** ==================================================
	 * Settings menu
	 *
	 * @since 1.0
	 */
	public function plugin_menu() {
		add_options_page( 'Simple Masonry Gallery Options', 'Simple Masonry Gallery', 'manage_options', 'simplemasonry', array( $this, 'plugin_options' ) );
	}

	/** ==================================================
	 * Settings page
	 *
	 * @since 1.0
	 */
	public function plugin_options() {

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You do not have sufficient permissions to access this page.' ) );
		}

		$width_html = '<code>width</code>';
		$margin_html = '<code>margin</code>';
		$padding_html = '<code>padding</code>';
		$allowed_html = array(
			'code'  => array(),
		);

		?>
		<div class="wrap">
		<h2>Simple Masonry Gallery</h2>

			<details>
			<summary><strong><?php esc_html_e( 'Various links of this plugin', 'simple-masonry-gallery' ); ?></strong></summary>
			<?php $this->credit(); ?>
			</details>

			<hr>

			<h2 style="margin: 0px 10px;"><?php echo esc_html( __( 'Shortcode' ) . __( 'Settings' ) ); ?>(<?php esc_html_e( 'The settings for the blocks are in each block.', 'simple-masonry-gallery' ); ?>)</h2>
			<li style="margin: 0px 40px;">
			<h3><?php esc_html_e( 'Write a Shortcode. The following text field. Enclose image tags and gallery shortcode.', 'simple-masonry-gallery' ); ?></h3>
			<h3><?php esc_html_e( 'example:', 'simple-masonry-gallery' ); ?></h3>
			<h3><code>&#91simplemasonrygallery&#93&lt;a href="http://blog3.localhost.localdomain/wp-content/uploads/sites/8/2017/01/f8e6a6a7.jpg"&gt;&lt;img src="http://blog3.localhost.localdomain/wp-content/uploads/sites/8/2017/01/f8e6a6a7.jpg" alt="" width="1000" height="626" class="alignnone size-full wp-image-275" /&gt;&lt;/a&gt;
&lt;a href="http://blog3.localhost.localdomain/wp-content/uploads/sites/8/2017/01/f878ff71.jpg"&gt;&lt;img src="http://blog3.localhost.localdomain/wp-content/uploads/sites/8/2017/01/f878ff71.jpg" alt="" width="1000" height="666" class="alignnone size-full wp-image-274" /&gt;&lt;/a&gt;&#91gallery size="full" ids="273,272,271,270"&#93&#91/simplemasonrygallery&#93</code></h3>
			</li>
			<li style="margin: 0px 40px;">
			<h3><?php esc_html_e( 'Write a Shortcode. The following template. Enclose image tags and gallery shortcode.', 'simple-masonry-gallery' ); ?></h3>
			<h3><?php esc_html_e( 'example:', 'simple-masonry-gallery' ); ?></h3>
			<h3><code>&lt;?php echo do_shortcode('&#91simplemasonrygallery width=50&#93&#91gallery link="none" size="full" ids="271,270,269,268"&#93&#91/simplemasonrygallery&#93'); ?&gt;</code></h3>
			</h3>
			</li>
			<li style="margin: 0px 40px;">
			<h3><?php esc_html_e( '"Simple Masonry Gallery" activation, you to include additional buttons for Shortcode in the Text (HTML) mode of the WordPress editor.', 'simple-masonry-gallery' ); ?>
			</h3>
			</li>
			<li style="margin: 0px 40px;">
			<h3><?php esc_html_e( 'Within the Shortcode, it is possible to describe multiple galleries and multiple media.', 'simple-masonry-gallery' ); ?>
			</h3>
			</li>
			<li style="margin: 0px 40px;">
			<h3><?php esc_html_e( 'The shortcode attributes are as follows.', 'simple-masonry-gallery' ); ?>
			<h3>
			<?php
			/* translators: Sample code */
			echo wp_kses( sprintf( __( '%1$s : Specify the column width.', 'simple-masonry-gallery' ), $width_html ), $allowed_html );
			?>
			</h3>
			<h3 style="padding: 0px 40px;">
			<?php
			/* translators: Default value */
			echo esc_html( sprintf( __( 'If not specified, the default value is applied. The default value is %1$d(px).', 'simple-masonry-gallery' ), 100 ) );
			?>
			</h3>
			<h3>
			<?php
			/* translators: Sample code */
			echo wp_kses( sprintf( __( '%1$s : Specify the margin between images.', 'simple-masonry-gallery' ), $margin_html ), $allowed_html );
			?>
			</h3>
			<h3 style="padding: 0px 40px;">
			<?php
			/* translators: Default value */
			echo esc_html( sprintf( __( 'If not specified, the default value is applied. The default value is %1$d(px).', 'simple-masonry-gallery' ), 1 ) );
			?>
			</h3>
			<h3>
			<?php
			/* translators: Sample code */
			echo wp_kses( sprintf( __( '%1$s : Specify the padding between images.', 'simple-masonry-gallery' ), $padding_html ), $allowed_html );
			?>
			</h3>
			<h3 style="padding: 0px 40px;">
			<?php
			/* translators: Default value */
			echo esc_html( sprintf( __( 'If not specified, the default value is applied. The default value is %1$d(px).', 'simple-masonry-gallery' ), 1 ) );
			?>
			</h3>
			<h3><?php esc_html_e( 'You can specify it like CSS.', 'simple-masonry-gallery' ); ?></h3>
			<h3><?php esc_html_e( 'example:', 'simple-masonry-gallery' ); ?></h3>
			<h3><code>&#91simplemasonrygallery width=50 margin=2 padding=2&#93&#91gallery link="none" size="full" ids="271,270,269,268"&#93&#91/simplemasonrygallery&#93</code></h3>
			<h3><code>&#91simplemasonrygallery width=150 margin="2px 1px 3px 0" padding="0 1px 0"&#93&#91gallery link="none" size="full" ids="271,270,269,268"&#93&#91/simplemasonrygallery&#93</code></h3>
			</li>
		</div>

		<?php
	}

	/** ==================================================
	 * Credit
	 *
	 * @since 1.00
	 */
	private function credit() {

		$plugin_name    = null;
		$plugin_ver_num = null;
		$plugin_path    = plugin_dir_path( __DIR__ );
		$plugin_dir     = untrailingslashit( wp_normalize_path( $plugin_path ) );
		$slugs          = explode( '/', $plugin_dir );
		$slug           = end( $slugs );
		$files          = scandir( $plugin_dir );
		foreach ( $files as $file ) {
			if ( '.' === $file || '..' === $file || is_dir( $plugin_path . $file ) ) {
				continue;
			} else {
				$exts = explode( '.', $file );
				$ext  = strtolower( end( $exts ) );
				if ( 'php' === $ext ) {
					$plugin_datas = get_file_data(
						$plugin_path . $file,
						array(
							'name'    => 'Plugin Name',
							'version' => 'Version',
						)
					);
					if ( array_key_exists( 'name', $plugin_datas ) && ! empty( $plugin_datas['name'] ) && array_key_exists( 'version', $plugin_datas ) && ! empty( $plugin_datas['version'] ) ) {
						$plugin_name    = $plugin_datas['name'];
						$plugin_ver_num = $plugin_datas['version'];
						break;
					}
				}
			}
		}
		$plugin_version = __( 'Version:' ) . ' ' . $plugin_ver_num;
		/* translators: FAQ Link & Slug */
		$faq       = sprintf( __( 'https://wordpress.org/plugins/%s/faq', 'simple-masonry-gallery' ), $slug );
		$support   = 'https://wordpress.org/support/plugin/' . $slug;
		$review    = 'https://wordpress.org/support/view/plugin-reviews/' . $slug;
		$translate = 'https://translate.wordpress.org/projects/wp-plugins/' . $slug;
		$facebook  = 'https://www.facebook.com/katsushikawamori/';
		$twitter   = 'https://twitter.com/dodesyo312';
		$youtube   = 'https://www.youtube.com/channel/UC5zTLeyROkvZm86OgNRcb_w';
		$donate    = __( 'https://shop.riverforest-wp.info/donate/', 'simple-masonry-gallery' );

		?>
		<span style="font-weight: bold;">
		<div>
		<?php echo esc_html( $plugin_version ); ?> | 
		<a style="text-decoration: none;" href="<?php echo esc_url( $faq ); ?>" target="_blank" rel="noopener noreferrer">FAQ</a> | <a style="text-decoration: none;" href="<?php echo esc_url( $support ); ?>" target="_blank" rel="noopener noreferrer">Support Forums</a> | <a style="text-decoration: none;" href="<?php echo esc_url( $review ); ?>" target="_blank" rel="noopener noreferrer">Reviews</a>
		</div>
		<div>
		<a style="text-decoration: none;" href="<?php echo esc_url( $translate ); ?>" target="_blank" rel="noopener noreferrer">
		<?php
		/* translators: Plugin translation link */
		echo esc_html( sprintf( __( 'Translations for %s' ), $plugin_name ) );
		?>
		</a> | <a style="text-decoration: none;" href="<?php echo esc_url( $facebook ); ?>" target="_blank" rel="noopener noreferrer"><span class="dashicons dashicons-facebook"></span></a> | <a style="text-decoration: none;" href="<?php echo esc_url( $twitter ); ?>" target="_blank" rel="noopener noreferrer"><span class="dashicons dashicons-twitter"></span></a> | <a style="text-decoration: none;" href="<?php echo esc_url( $youtube ); ?>" target="_blank" rel="noopener noreferrer"><span class="dashicons dashicons-video-alt3"></span></a>
		</div>
		</span>

		<div style="width: 250px; height: 180px; margin: 5px; padding: 5px; border: #CCC 2px solid;">
		<h3><?php esc_html_e( 'Please make a donation if you like my work or would like to further the development of this plugin.', 'simple-masonry-gallery' ); ?></h3>
		<div style="text-align: right; margin: 5px; padding: 5px;"><span style="padding: 3px; color: #ffffff; background-color: #008000">Plugin Author</span> <span style="font-weight: bold;">Katsushi Kawamori</span></div>
		<button type="button" style="margin: 5px; padding: 5px;" onclick="window.open('<?php echo esc_url( $donate ); ?>')"><?php esc_html_e( 'Donate to this plugin &#187;' ); ?></button>
		</div>

		<?php

	}

	/** ==================================================
	 * Add Quick Tag
	 *
	 * @since 5.02
	 */
	public function simplemasonry_add_quicktags() {
		if ( wp_script_is( 'quicktags' ) ) {
			?>
		<script type="text/javascript">
			QTags.addButton( 'simplemasonrygallery', 'simplemasonrygallery', '[simplemasonrygallery]', '[/simplemasonrygallery]' );
		</script>
			<?php
		}
	}

}



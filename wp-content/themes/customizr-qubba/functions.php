<?php



function my_theme_enqueue_styles() {

 $parent_style = 'parent-style'; // Estos son los estilos del tema padre recogidos por el tema hijo.

 wp_enqueue_style( $parent_style, get_template_directory_uri() . '/style.css' );
 wp_enqueue_style( 'child-style',
 get_stylesheet_directory_uri() . '/style.css',
 array( $parent_style ),
 wp_get_theme()->get('Version')
 );
 
 //wp_enqueue_style( "awesome", get_template_directory_uri() . '/assets/shared/fonts/fa/css/fontawesome-all.min.css' );
 //echo get_template_directory_uri() . '/assets/shared/fonts/fa/css/fontawesome-all.min.css';die();
}
add_action( 'wp_enqueue_scripts', 'my_theme_enqueue_styles' );


/* Custom script with no dependencies, enqueued in the header */
add_action('wp_enqueue_scripts', 'qubba_enqueue_custom_js');
function qubba_enqueue_custom_js() {
    //echo get_stylesheet_directory_uri().'/qubba.js';die();
    wp_enqueue_script('custom', get_stylesheet_directory_uri().'/qubba.js',array('jquery'), '', true);
}


/*

function add_orientation_class( $attr, $attachment ) {
echo "UEEEEE!!!";die();

    $metadata = get_post_meta( $attachment->ID, '_wp_attachment_metadata', true);

    // Sanity check: we need both width and height to add the orientation class. If either are missing, we should return the attributes.
    if ( empty($metadata['width']) || empty($metadata['height'])) {
        return $attr;
    }

    // Sanity check x2: class should be set by now, but another filter could have cleared it out.
    if ( !isset($metadata['class'])) {
        $metadata['class'] = '';
    }

    if ( $metadata['width'] > $metadata['height'] ) { // If width is greater than height, the image is a landscape image.
        $attr['class'] .= ' imglandscape';
    } else { // If not, it's a portrait image.
        $attr['class'] .= ' imgportrait';
    }
echo "UEEEEE!!!";die();
    // Return the attributes.
    return $attr;
}

 */



/*

function wpdocs_filter_gallery_img_atts( $atts, $attachment ) {
    echo "2222!!!";die();

    if ( $full_size = wp_get_attachment_image_src( $attachment->ID, 'full' ) ) {
        if ( ! empty( $full_size[0] ) ) {
            $atts['data-full'] = $full_size[0];
        }
    }
    return $atts;
}
add_filter( 'wp_get_attachment_image_attributes', 'wpdocs_filter_gallery_img_atts', 10, 2 );
*/



function wpse302130_add_image_class ($class){
    echo "2222!!!";die();
    $class .= ' img-fluid';
    return $class;
    }
    
add_filter('get_image_tag_class','wpse302130_add_image_class');

// Disable xml-rpc	
add_filter('xmlrpc_enabled', '__return_false');

//disable http
add_filter( 'pre_http_request', '__return_true', 100 );

?>

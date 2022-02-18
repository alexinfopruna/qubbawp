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
?>

<?php
/**
 * The template for displaying the footer credits
 *
 */
?>
<div id="footer__credits" class="footer__credits" <?php czr_fn_echo('element_attributes') ?>>
  <p class="czr-copyright">
    <span class="czr-copyright-text">&copy;&nbsp;<?php echo esc_html( date('Y') ) ?>&nbsp;</span><a class="czr-copyright-link" href="<?php echo esc_url( home_url() ) ?>" title="<?php echo esc_attr( get_bloginfo() ) ?>"><?php echo esc_html( get_bloginfo() ) ?></a><span class="czr-rights-text">&nbsp;&ndash;&nbsp;
        
            <?php _e( 'Carrer del Bonsuccés, 13, 08001, Barcelona', 'customizr') ?>
        -
        

            <?php _e( '+34 93 300 34 52', 'customizr') ?>
        -
            <?php _e( 'qubba@coac.net', 'customizr') ?>
    
    </span>
  </p>

</div>

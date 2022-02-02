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
        &#9742;
        

            <?php _e( '+34 933 99 34 52', 'customizr') ?>
        &#x2709;
            <?php _e( 'qubbaarquitectes@gmail.com', 'customizr') ?>
    
    </span>
  </p>

</div>

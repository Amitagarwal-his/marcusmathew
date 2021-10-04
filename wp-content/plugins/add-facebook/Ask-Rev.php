<?php
if ( ! defined( 'ABSPATH' ) ) exit;

function plugSSfP_check_installation_date() {
 
    $nobug = "";
    $nobug = get_option('plugSFP_hide_noticeSocialFeedTwo');
 
    if (!$nobug) {
        add_action( 'admin_notices', 'plugSSfP_display_admin_notice' );
    }
}
add_action( 'admin_init', 'plugSSfP_check_installation_date' );

function plugSSfP_display_admin_notice() {
 
    $cfb_install_link =  esc_url( network_admin_url('plugin-install.php?tab=plugin-information&plugin=' . 'feather-login-page' . '&TB_iframe=true&width=100%&height=800' ) );
 
    $nobugurl = get_admin_url() . '?sfpdontbug=1';

    echo '<div class="psprev-adm-notice psprev-adm-notice-wp-rating notice">';

    echo '<div style="width:65%; display: inline-block;" ><br> <h3><b>' . __( 'You\'ll Love our Login Page Customizer plugin! ', 'tss' ) . '</b></h3>';

    echo '<p style="font-size:16px;">' . __( ' <i> Tip :  </i> With Feather Login Page Designer you get beautiful login page designs for free, All login page designs are completely customisable so you can easily change images, logos and rebrand it according to your theme and unify the feel and design of you entire WordPress website. So Install the plugin while its <b>Free!!</b> ', 'tss' ) . '</p>
        <a href="' . $nobugurl . '" type="button" class="notice-dismiss-psp"><span class="dashicon  dashicons-no">Dismiss this notice.</span></a>

         </div>';

    echo '<div style="width:25%; display: inline-block; float: right; margin-top: 45px;">

        <a class="psprev-adm-notice-link" href="'.$cfb_install_link.'" target="_blank"><span class="dashicons dashicons-megaphone"></span>' . __( 'Install Now For Free', 'tss' ) . '</a>

        </div>';


 
   // echo( __( "You have been using our Posts Slider for more than a week now, do you like it? If so, please leave us a review with your feedback! <a href=".$reviewurl." target='_blank' class='button button-primary' style='margin:0 20px;'>Leave A Review</a> <a href=".$nobugurl." style='font-size:9px;'>Leave Me Alone</a>" ) ); 
 
    echo "</div>";

    echo "<style>

    .psprev-adm-notice-activation { border-color: #41c4ff; }
    .psprev-adm-notice-activation h4 { font-size: 1.05em; }
    .psprev-adm-notice-activation a { text-decoration: none; }
    .psprev-adm-notice-activation .psprev-adm-notice-link { display: inline-block; padding: 6px 8px; margin-bottom: 10px; color: rgba(52,152,219,1); font-weight: 500; background: #e9e9e9; border-radius: 2px; margin-right: 10px; }
    .psprev-adm-notice-activation .psprev-adm-notice-link span { display: inline-block; text-decoration: none; margin-right: 10px; }
    .psprev-adm-notice-activation .psprev-adm-notice-link:hover { color: #fff; background:#41c4ff; }

    .psprev-adm-notice-wp-rating { border-color: rgba(52,152,219,0.75); }
    .psprev-adm-notice-wp-rating h4 { font-size: 1.05em; }
    .psprev-adm-notice-wp-rating p:last-of-type { margin-bottom: 20px; }
    .psprev-adm-notice-wp-rating a { text-decoration: none; }
    .psprev-adm-notice-wp-rating .psprev-adm-notice-link { display: inline-block; padding: 10px 20px; margin-bottom: 30px; color: #fff; font-weight: 500; background: #FF9800; border-radius: 2px; margin-right: 10px; font-size:18px; }
    .psprev-adm-notice-wp-rating .psprev-adm-notice-link span { display: inline-block; text-decoration: none; margin-right: 10px; }
    .psprev-adm-notice-wp-rating .psprev-adm-notice-link:hover { color: #fff; background: rgba(52,152,219,1); }
    .psprev-adm-notice-wp-rating .dashicons-star-filled { position: relative; top: 1px; width: 15px; height: 15px; font-size: 15px; }
    .notice-dismiss-psp { postition:relative !important; }
        </style>";

}

function plugSSfP_set_no_bug() {
 
    $nobug = "";
 
    if ( isset( $_GET['sfpdontbug'] ) ) {
        $nobug = esc_attr( $_GET['sfpdontbug'] );
    }
 
    if ( 1 == $nobug ) {
 
        add_option( 'plugSFP_hide_noticeSocialFeedTwo', TRUE );
 
    }
 
} add_action( 'admin_init', 'plugSSfP_set_no_bug', 5 );

?>
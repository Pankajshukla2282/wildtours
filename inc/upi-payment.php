<?php
/**
 * UPI payment gateway integration for Panna Wild Tours.
 *
 * @package wildtours
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! function_exists( 'wildtours_upi_payment_customize_register' ) ) {
    add_action( 'customize_register', 'wildtours_upi_payment_customize_register' );
    function wildtours_upi_payment_customize_register( $wp_customize ) {
        $wp_customize->add_section(
            'wildtours_upi_section',
            array(
                'title'       => __( 'UPI Payment Gateway', 'wildtours' ),
                'description' => __( 'Configure UPI payment information for booking and checkout pages.', 'wildtours' ),
                'priority'    => 160,
            )
        );

        $wp_customize->add_setting(
            'wildtours_upi_enabled',
            array(
                'default'           => false,
                'sanitize_callback' => 'wildtours_sanitize_checkbox',
            )
        );

        $wp_customize->add_control(
            'wildtours_upi_enabled',
            array(
                'label'   => __( 'Enable UPI payments', 'wildtours' ),
                'section' => 'wildtours_upi_section',
                'type'    => 'checkbox',
            )
        );

        $wp_customize->add_setting(
            'wildtours_upi_id',
            array(
                'default'           => '',
                'sanitize_callback' => 'sanitize_text_field',
            )
        );

        $wp_customize->add_control(
            'wildtours_upi_id',
            array(
                'label'       => __( 'UPI ID', 'wildtours' ),
                'description' => __( 'Enter your merchant UPI ID (for example: payee@bank).', 'wildtours' ),
                'section'     => 'wildtours_upi_section',
                'type'        => 'text',
            )
        );

        $wp_customize->add_setting(
            'wildtours_upi_name',
            array(
                'default'           => __( 'Panna Wild Tours', 'wildtours' ),
                'sanitize_callback' => 'sanitize_text_field',
            )
        );

        $wp_customize->add_control(
            'wildtours_upi_name',
            array(
                'label'   => __( 'Merchant name', 'wildtours' ),
                'section' => 'wildtours_upi_section',
                'type'    => 'text',
            )
        );

        $wp_customize->add_setting(
            'wildtours_upi_transaction_note',
            array(
                'default'           => __( 'Panna Wild Tours booking payment', 'wildtours' ),
                'sanitize_callback' => 'sanitize_text_field',
            )
        );

        $wp_customize->add_control(
            'wildtours_upi_transaction_note',
            array(
                'label'   => __( 'Transaction note', 'wildtours' ),
                'section' => 'wildtours_upi_section',
                'type'    => 'text',
            )
        );

        $wp_customize->add_setting(
            'wildtours_upi_qr_image',
            array(
                'default'           => '',
                'sanitize_callback' => 'esc_url_raw',
            )
        );

        $wp_customize->add_control(
            new WP_Customize_Image_Control(
                $wp_customize,
                'wildtours_upi_qr_image',
                array(
                    'label'    => __( 'UPI QR code image', 'wildtours' ),
                    'section'  => 'wildtours_upi_section',
                    'settings' => 'wildtours_upi_qr_image',
                )
            )
        );

        $wp_customize->add_setting(
            'wildtours_upi_button_text',
            array(
                'default'           => __( 'Pay with UPI', 'wildtours' ),
                'sanitize_callback' => 'sanitize_text_field',
            )
        );

        $wp_customize->add_control(
            'wildtours_upi_button_text',
            array(
                'label'   => __( 'Button text', 'wildtours' ),
                'section' => 'wildtours_upi_section',
                'type'    => 'text',
            )
        );
    }
}

if ( ! function_exists( 'wildtours_sanitize_checkbox' ) ) {
    function wildtours_sanitize_checkbox( $checked ) {
        return ( ( isset( $checked ) && true === $checked ) || '1' === $checked ) ? true : false;
    }
}

if ( ! function_exists( 'wildtours_get_upi_payment_data' ) ) {
    function wildtours_get_upi_payment_data() {
        return array(
            'enabled'          => get_theme_mod( 'wildtours_upi_enabled', false ),
            'upi_id'           => get_theme_mod( 'wildtours_upi_id', '' ),
            'merchant_name'    => get_theme_mod( 'wildtours_upi_name', __( 'Panna Wild Tours', 'wildtours' ) ),
            'transaction_note' => get_theme_mod( 'wildtours_upi_transaction_note', __( 'Panna Wild Tours booking payment', 'wildtours' ) ),
            'qr_image'         => get_theme_mod( 'wildtours_upi_qr_image', '' ),
            'button_text'      => get_theme_mod( 'wildtours_upi_button_text', __( 'Pay with UPI', 'wildtours' ) ),
        );
    }
}

if ( ! function_exists( 'wildtours_build_upi_url' ) ) {
    function wildtours_build_upi_url( $upi_id, $merchant_name, $note ) {
        $params = array(
            'pa' => $upi_id,
            'pn' => $merchant_name,
            'tn' => $note,
            'cu' => 'INR',
        );

        return 'upi://pay?' . http_build_query( $params );
    }
}

if ( ! function_exists( 'wildtours_upi_payment_markup' ) ) {
    function wildtours_upi_payment_markup() {
        $data = wildtours_get_upi_payment_data();
        if ( empty( $data['enabled'] ) || empty( $data['upi_id'] ) ) {
            return '';
        }

        $upi_url = wildtours_build_upi_url( $data['upi_id'], $data['merchant_name'], $data['transaction_note'] );

        ob_start();
        ?>
        <div class="wildtours-upi-payment">
            <div class="wildtours-upi-payment-inner">
                <div class="wildtours-upi-details">
                    <h3><?php esc_html_e( 'Secure UPI payment', 'wildtours' ); ?></h3>
                    <p><?php esc_html_e( 'Pay securely using any UPI app on your phone. Scan the QR code or copy the UPI ID below.', 'wildtours' ); ?></p>
                    <div class="wildtours-upi-field">
                        <label><?php esc_html_e( 'UPI ID', 'wildtours' ); ?></label>
                        <input type="text" readonly value="<?php echo esc_attr( $data['upi_id'] ); ?>">
                        <button type="button" class="wildtours-upi-copy" data-copy-text="<?php echo esc_attr( $data['upi_id'] ); ?>">
                            <?php esc_html_e( 'Copy UPI ID', 'wildtours' ); ?>
                        </button>
                    </div>
                    <div class="wildtours-upi-actions">
                        <a class="wildtours-upi-button" href="<?php echo esc_url( $upi_url ); ?>">
                            <?php echo esc_html( $data['button_text'] ); ?>
                        </a>
                        <span class="wildtours-upi-note"><?php echo esc_html( $data['transaction_note'] ); ?></span>
                    </div>
                </div>

                <?php if ( ! empty( $data['qr_image'] ) ) : ?>
                    <div class="wildtours-upi-qr">
                        <img src="<?php echo esc_url( $data['qr_image'] ); ?>" alt="<?php esc_attr_e( 'UPI QR code', 'wildtours' ); ?>">
                        <p><?php esc_html_e( 'Scan to pay with your UPI app.', 'wildtours' ); ?></p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }
}

if ( ! function_exists( 'wildtours_append_upi_payment_to_booking' ) ) {
    function wildtours_append_upi_payment_to_booking( $content ) {
        if ( is_admin() || ! is_main_query() || ! is_page() ) {
            return $content;
        }

        $page_title = strtolower( get_the_title() );
        if ( 'booking' !== $page_title ) {
            return $content;
        }

        $upi_html = wildtours_upi_payment_markup();
        if ( empty( $upi_html ) ) {
            return $content;
        }

        return $content . $upi_html;
    }
    add_filter( 'the_content', 'wildtours_append_upi_payment_to_booking', 25 );
}

if ( ! function_exists( 'wildtours_upi_payment_shortcode' ) ) {
    function wildtours_upi_payment_shortcode( $atts = array() ) {
        return wildtours_upi_payment_markup();
    }
    add_shortcode( 'wildtours_upi_payment', 'wildtours_upi_payment_shortcode' );
}

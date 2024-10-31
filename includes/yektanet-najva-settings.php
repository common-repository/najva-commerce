<?php

// Exit if accessed directly
if (!defined('ABSPATH')){
    exit;
}

function yektanetnajva_add_admin_menu(  ) {
    add_options_page( 'najva', 'najva', 'manage_options', 'yektanetnajva', 'yektanetnajva_options_page' );
}

function yektanetnajva_settings_init(  ) {
    register_setting( 'najvaAutoPage', 'yektanetnajva_autosettings' );
    register_setting( 'najvaAdminPage', 'yektanetnajva_adminsettings' );
    register_setting( 'najvaCustomerPage', 'yektanetnajva_customersettings' );
    register_setting( 'najvaSmsPage', 'yektanetnajva_smssettings' );

    add_settings_section(
        'yektanetnajva_najvaPage_section',
        __( '', 'yektanetnajva' ),
        'yektanetnajva_settings_section_callback',
        'najvaAutoPage'
    );

    add_settings_section(
        'yektanetnajva_najvaPageAdminSms_section',
        __( '', 'yektanetnajva' ),
        'yektanetnajva_settings_sectionAdminSms_callback',
        'najvaAdminPage'
    );

    add_settings_section(
        'yektanetnajva_najvaPageCustomerSms_section',
        __( '', 'yektanetnajva' ),
        'yektanetnajva_settings_sectionCustomerSms_callback',
        'najvaCustomerPage'
    );

    add_settings_section(
        'yektanetnajva_najvaPageSms_section',
        __( '', 'yektanetnajva' ),
        'yektanetnajva_settings_sectionSms_callback',
        'najvaSmsPage'
    );

    add_settings_field(
        'yektanetnajva_token',
        __( '<br>Token', 'yektanetnajva' ),
        'yektanetnajva_token_render',
        'najvaAutoPage',
        'yektanetnajva_najvaPage_section'
    );

    add_settings_field(
        'yektanetnajva_sms_token',
        __( '<br>Sms Token', 'yektanetnajva' ),
        'yektanetnajva_sms_token_render',
        'najvaSmsPage',
        'yektanetnajva_najvaPageSms_section'
    );

    add_settings_field(
        'yektanetnajva_admin_status_onhold',
        __( 'preferred status list<br><br>on hold', 'yektanetnajva' ),
        'yektanetnajva_admin_status_onhold_render',
        'najvaAdminPage',
        'yektanetnajva_najvaPageAdminSms_section'
    );

    add_settings_field(
        'yektanetnajva_admin_status_processing',
        __( 'processing', 'yektanetnajva' ),
        'yektanetnajva_admin_status_processing_render',
        'najvaAdminPage',
        'yektanetnajva_najvaPageAdminSms_section'
    );

    add_settings_field(
        'yektanetnajva_admin_status_completed',
        __( 'completed', 'yektanetnajva' ),
        'yektanetnajva_admin_status_completed_render',
        'najvaAdminPage',
        'yektanetnajva_najvaPageAdminSms_section'
    );

    add_settings_field(
        'yektanetnajva_admin_status_refunded',
        __( 'refunded', 'yektanetnajva' ),
        'yektanetnajva_admin_status_refunded_render',
        'najvaAdminPage',
        'yektanetnajva_najvaPageAdminSms_section'
    );

    add_settings_field(
        'yektanetnajva_admin_text_onhold',
        __( '<br><br>on hold text&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp', 'yektanetnajva' ),
        'yektanetnajva_admin_text_onhold_render',
        'najvaAdminPage',
        'yektanetnajva_najvaPageAdminSms_section'
    );

    add_settings_field(
        'yektanetnajva_admin_text_processing',
        __( '<br>processing text&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp', 'yektanetnajva' ),
        'yektanetnajva_admin_text_processing_render',
        'najvaAdminPage',
        'yektanetnajva_najvaPageAdminSms_section'
    );

    add_settings_field(
        'yektanetnajva_admin_text_completed',
        __( '<br>completed text&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp', 'yektanetnajva' ),
        'yektanetnajva_admin_text_completed_render',
        'najvaAdminPage',
        'yektanetnajva_najvaPageAdminSms_section'
    );

    add_settings_field(
        'yektanetnajva_admin_text_refunded',
        __( '<br>refunded text&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp', 'yektanetnajva' ),
        'yektanetnajva_admin_text_refunded_render',
        'najvaAdminPage',
        'yektanetnajva_najvaPageAdminSms_section'
    );

    add_settings_field(
        'yektanetnajva_admin_phone_numbers',
        __( '<br>admins phone numbers&nbsp&nbsp', 'yektanetnajva' ),
        'yektanetnajva_admin_phone_numbers_render',
        'najvaAdminPage',
        'yektanetnajva_najvaPageAdminSms_section'
    );

    add_settings_field(
        'yektanetnajva_admin_sender',
        __( '<br>sender&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp', 'yektanetnajva' ),
        'yektanetnajva_admin_sender_render',
        'najvaAdminPage',
        'yektanetnajva_najvaPageAdminSms_section'
    );

    add_settings_field(
        'yektanetnajva_customer_status_onhold',
        __( 'preferred status list<br><br>on hold', 'yektanetnajva' ),
        'yektanetnajva_customer_status_onhold_render',
        'najvaCustomerPage',
        'yektanetnajva_najvaPageCustomerSms_section'
    );

    add_settings_field(
        'yektanetnajva_customer_status_processing',
        __( 'processing', 'yektanetnajva' ),
        'yektanetnajva_customer_status_processing_render',
        'najvaCustomerPage',
        'yektanetnajva_najvaPageCustomerSms_section'
    );

    add_settings_field(
        'yektanetnajva_customer_status_completed',
        __( 'completed', 'yektanetnajva' ),
        'yektanetnajva_customer_status_completed_render',
        'najvaCustomerPage',
        'yektanetnajva_najvaPageCustomerSms_section'
    );

    add_settings_field(
        'yektanetnajva_customer_status_refunded',
        __( 'refunded', 'yektanetnajva' ),
        'yektanetnajva_customer_status_refunded_render',
        'najvaCustomerPage',
        'yektanetnajva_najvaPageCustomerSms_section'
    );

    add_settings_field(
        'yektanetnajva_customer_text_onhold',
        __( '<br><br>on hold text&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp', 'yektanetnajva' ),
        'yektanetnajva_customer_text_onhold_render',
        'najvaCustomerPage',
        'yektanetnajva_najvaPageCustomerSms_section'
    );

    add_settings_field(
        'yektanetnajva_customer_text_processing',
        __( '<br>processing text&nbsp&nbsp', 'yektanetnajva' ),
        'yektanetnajva_customer_text_processing_render',
        'najvaCustomerPage',
        'yektanetnajva_najvaPageCustomerSms_section'
    );

    add_settings_field(
        'yektanetnajva_customer_text_completed',
        __( '<br>completed text&nbsp&nbsp', 'yektanetnajva' ),
        'yektanetnajva_customer_text_completed_render',
        'najvaCustomerPage',
        'yektanetnajva_najvaPageCustomerSms_section'
    );

    add_settings_field(
        'yektanetnajva_customer_text_refunded',
        __( '<br>refunded text&nbsp&nbsp&nbsp&nbsp&nbsp', 'yektanetnajva' ),
        'yektanetnajva_customer_text_refunded_render',
        'najvaCustomerPage',
        'yektanetnajva_najvaPageCustomerSms_section'
    );

    yektanetnajva_activation_redirect();
}

function yektanetnajva_token_render(  ) {
    $options = get_option( 'yektanetnajva_autosettings' );
    ?>
    <input type='text' name='yektanetnajva_autosettings[yektanetnajva_token]' value='<?php echo $options['yektanetnajva_token']; ?>'>
    <?php
}

function yektanetnajva_sms_token_render(  ) {
    $options = get_option( 'yektanetnajva_smssettings' );
    ?>
    <input type='text' name='yektanetnajva_smssettings[yektanetnajva_sms_token]' value='<?php echo $options['yektanetnajva_sms_token']; ?>'>
    <?php
}

function yektanetnajva_customer_status_onhold_render(  ) {
    $options = get_option( 'yektanetnajva_customersettings' );
    ?>
    <input type='checkbox' name='yektanetnajva_customersettings[yektanetnajva_customer_status_onhold]' <?php checked( $options['yektanetnajva_customer_status_onhold'], 1 ); ?> value='1'>
    <?php
}

function yektanetnajva_customer_status_processing_render(  ) {
    $options = get_option( 'yektanetnajva_customersettings' );
    ?>
    <input type='checkbox' name='yektanetnajva_customersettings[yektanetnajva_customer_status_processing]' <?php checked( $options['yektanetnajva_customer_status_processing'], 1 ); ?> value='1'>
    <?php
}

function yektanetnajva_customer_status_completed_render(  ) {
    $options = get_option( 'yektanetnajva_customersettings' );
    ?>
    <input type='checkbox' name='yektanetnajva_customersettings[yektanetnajva_customer_status_completed]' <?php checked( $options['yektanetnajva_customer_status_completed'], 1 ); ?> value='1'>
    <?php
}

function yektanetnajva_customer_status_refunded_render(  ) {
    $options = get_option( 'yektanetnajva_customersettings' );
    ?>
    <input type='checkbox' name='yektanetnajva_customersettings[yektanetnajva_customer_status_refunded]' <?php checked( $options['yektanetnajva_customer_status_refunded'], 1 ); ?> value='1'>
    <?php
}

function yektanetnajva_customer_text_onhold_render(  ) {
    $options = get_option( 'yektanetnajva_customersettings' );
    ?>
    <textarea cols='40' rows='5' name='yektanetnajva_customersettings[yektanetnajva_customer_text_onhold]'>
		<?php echo $options['yektanetnajva_customer_text_onhold']; ?>
 	</textarea>
    <?php
}

function yektanetnajva_customer_text_processing_render(  ) {
    $options = get_option( 'yektanetnajva_customersettings' );
    ?>
    <textarea cols='40' rows='5' name='yektanetnajva_customersettings[yektanetnajva_customer_text_processing]'>
		<?php echo $options['yektanetnajva_customer_text_processing']; ?>
 	</textarea>
    <?php
}

function yektanetnajva_customer_text_completed_render(  ) {
    $options = get_option( 'yektanetnajva_customersettings' );
    ?>
    <textarea cols='40' rows='5' name='yektanetnajva_customersettings[yektanetnajva_customer_text_completed]'>
		<?php echo $options['yektanetnajva_customer_text_completed']; ?>
 	</textarea>
    <?php
}

function yektanetnajva_customer_text_refunded_render(  ) {
    $options = get_option( 'yektanetnajva_customersettings' );
    ?>
    <textarea cols='40' rows='5' name='yektanetnajva_customersettings[yektanetnajva_customer_text_refunded]'>
		<?php echo $options['yektanetnajva_customer_text_refunded']; ?>
 	</textarea>
    <?php
}

function yektanetnajva_admin_status_onhold_render(  ) {
    $options = get_option( 'yektanetnajva_adminsettings' );
    ?>
    <input type='checkbox' name='yektanetnajva_adminsettings[yektanetnajva_admin_status_onhold]' <?php checked( $options['yektanetnajva_admin_status_onhold'], 1 ); ?> value='1'>
    <?php
}

function yektanetnajva_admin_status_processing_render(  ) {
    $options = get_option( 'yektanetnajva_adminsettings' );
    ?>
    <input type='checkbox' name='yektanetnajva_adminsettings[yektanetnajva_admin_status_processing]' <?php checked( $options['yektanetnajva_admin_status_processing'], 1 ); ?> value='1'>
    <?php
}

function yektanetnajva_admin_status_completed_render(  ) {
    $options = get_option( 'yektanetnajva_adminsettings' );
    ?>
    <input type='checkbox' name='yektanetnajva_adminsettings[yektanetnajva_admin_status_completed]' <?php checked( $options['yektanetnajva_admin_status_completed'], 1 ); ?> value='1'>
    <?php
}

function yektanetnajva_admin_status_refunded_render(  ) {
    $options = get_option( 'yektanetnajva_adminsettings' );
    ?>
    <input type='checkbox' name='yektanetnajva_adminsettings[yektanetnajva_admin_status_refunded]' <?php checked( $options['yektanetnajva_admin_status_refunded'], 1 ); ?> value='1'>
    <?php
}

function yektanetnajva_admin_text_onhold_render(  ) {
    $options = get_option( 'yektanetnajva_adminsettings' );
    ?>
    <textarea cols='40' rows='5' name='yektanetnajva_adminsettings[yektanetnajva_admin_text_onhold]'>
		<?php echo $options['yektanetnajva_admin_text_onhold']; ?>
 	</textarea>
    <?php
}

function yektanetnajva_admin_text_processing_render(  ) {
    $options = get_option( 'yektanetnajva_adminsettings' );
    ?>
    <textarea cols='40' rows='5' name='yektanetnajva_adminsettings[yektanetnajva_admin_text_processing]'>
		<?php echo $options['yektanetnajva_admin_text_processing']; ?>
 	</textarea>
    <?php
}

function yektanetnajva_admin_text_completed_render(  ) {
    $options = get_option( 'yektanetnajva_adminsettings' );
    ?>
    <textarea cols='40' rows='5' name='yektanetnajva_adminsettings[yektanetnajva_admin_text_completed]'>
		<?php echo $options['yektanetnajva_admin_text_completed']; ?>
 	</textarea>
    <?php
}

function yektanetnajva_admin_text_refunded_render(  ) {
    $options = get_option( 'yektanetnajva_adminsettings' );
    ?>
    <textarea cols='40' rows='5' name='yektanetnajva_adminsettings[yektanetnajva_admin_text_refunded]'>
		<?php echo $options['yektanetnajva_admin_text_refunded']; ?>
 	</textarea>
    <?php
}

function yektanetnajva_admin_phone_numbers_render(  ) {
    $options = get_option( 'yektanetnajva_adminsettings' );
    ?>
    <textarea cols='40' rows='5' name='yektanetnajva_adminsettings[yektanetnajva_admin_phone_numbers]'>
		<?php echo $options['yektanetnajva_admin_phone_numbers']; ?>
 	</textarea>
    <?php
}

function yektanetnajva_admin_sender_render(  ) {
    $options = get_option( 'yektanetnajva_adminsettings' );
    ?>
    <textarea cols='40' rows='5' name='yektanetnajva_adminsettings[yektanetnajva_admin_sender]'>
		<?php echo $options['yektanetnajva_admin_sender']; ?>
 	</textarea>
    <?php
}

function yektanetnajva_settings_section_callback(  ) {
    echo __( 'please enter your token for authentication
    Note: By entering your token you are agreeing to send your data to our servers<br>', 'yektanetnajva' );
}

function yektanetnajva_settings_sectionAdminSms_callback(  ) {
    echo __( '', 'yektanetnajva' );
}

function yektanetnajva_settings_sectionCustomerSms_callback(  ) {
    echo __( '', 'yektanetnajva' );
}

function yektanetnajva_settings_sectionSms_callback(  ) {
    echo __( '', 'yektanetnajva' );
}

function yektanetnajva_options_page(  ) {

    ?>
    <form action='options.php' method='post'>

        <h2>najva</h2>

        <?php
            $default_tab = null;
            $tab = isset($_GET['tab']) ? $_GET['tab'] : $default_tab;
        ?>

        <div class="wrap">
            <nav class="nav-tab-wrapper">
                <a href="?page=yektanetnajva" class="nav-tab <?php if($tab===null):?>nav-tab-active<?php endif; ?>">Automation</a>
                <a href="?page=yektanetnajva&tab=sms" class="nav-tab <?php if($tab==='sms'):?>nav-tab-active<?php endif; ?>">Sms</a>
                <a href="?page=yektanetnajva&tab=admin-sms" class="nav-tab <?php if($tab==='admin-sms'):?>nav-tab-active<?php endif; ?>">Admin Sms</a>
                <a href="?page=yektanetnajva&tab=customer-sms" class="nav-tab <?php if($tab==='customer-sms'):?>nav-tab-active<?php endif; ?>">Customer Sms</a>
            </nav>

            <div class="tab-content">
                <?php switch($tab) :
                    case 'admin-sms':
                        settings_fields( 'najvaAdminPage' );
                        yektanetnajva_settings_sectionAdminSms_callback();
                        do_settings_fields('najvaAdminPage', 'yektanetnajva_najvaPageAdminSms_section');
                        break;
                    case 'customer-sms':
                        settings_fields( 'najvaCustomerPage' );
                        yektanetnajva_settings_sectionCustomerSms_callback();
                        do_settings_fields('najvaCustomerPage', 'yektanetnajva_najvaPageCustomerSms_section');
                        break;
                    case 'sms':
                        settings_fields( 'najvaSmsPage' );
                        yektanetnajva_settings_sectionSms_callback();
                        do_settings_fields('najvaSmsPage', 'yektanetnajva_najvaPageSms_section');
                        break;
                    default:
                        settings_fields( 'najvaAutoPage' );
                        yektanetnajva_settings_section_callback();
                        do_settings_fields('najvaAutoPage', 'yektanetnajva_najvaPage_section');
                        break;
                endswitch; ?>
            </div>
        </div>

        <?php
        submit_button();
        ?>

    </form>
    <?php

}


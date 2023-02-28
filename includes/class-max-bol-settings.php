<?phpfunction max_bol_plugin_settings_init() {    register_setting( 'max_bol_plugin', 'max_bol_plugin_options' );    register_setting( 'max_bol_plugin_settings', 'max_bol_plugin_settings' );    max_section_settings();    max_bol_plugin_settings();}add_action( 'admin_init', 'max_bol_plugin_settings_init' );if ( ! function_exists( 'max_section_settings' ) ){    function max_section_settings(){        add_settings_section(            'max_bol_plugin_settings_details',            __( 'Api Link', 'max_bol_plugin' ), 'max_bol_plugin_settings_data',            'max_bol_plugin_settings'        );        add_settings_section(            'max_bol_plugin_settings_details1',            __( 'Account Details', 'max_bol_plugin' ), 'max_bol_plugin_settings_data',            'max_bol_plugin_settings'        );        add_settings_section(            'max_bol_plugin_settings_details2',            __( 'Account Details', 'max_bol_plugin' ), 'max_bol_plugin_settings_data1',            'max_bol_plugin_settings'        );        add_settings_section(            'max_bol_plugin_settings_details3',            __( 'Crone Job Details', 'max_bol_plugin' ), 'max_bol_plugin_settings_data1',            'max_bol_plugin_settings'        );    }}function max_bol_plugin_settings_data( $args ) {    ?>    <p id="<?php echo esc_attr( $args['id'] ); ?>">    </p>    <?php}function max_bol_plugin_settings_data1( $args ) {    ?>    <p id="<?php echo esc_attr( $args['id'] ); ?>" >    </p>    <?php}function max_bol_plugin_options_page() {    add_menu_page(        'Max Bol Plugin',        'Max Bol Plugin',        'manage_options',        'max-bol-api-integration',        'sb_bol_plugin_html',        plugins_url( 'admin/img/logo-colored.png', dirname(__FILE__) )    );}add_action( 'admin_menu', 'max_bol_plugin_options_page' );function sb_bol_plugin_html() {    if ( ! current_user_can( 'manage_options' ) ) {        return;    }    if ( isset( $_GET['settings-updated'] ) ) {        add_settings_error( 'max_bol_plugin_messages', 'max_bol_plugin_message', __( 'Settings Saved', 'max_bol_plugin' ), 'updated' );    }    settings_errors( 'max_bol_plugin_messages' );    ?>    <div class="wrap">        <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>        <div class="tab-content">            <?php            max_settings_tab();            ?>        </div>    </div>    <?php}function max_settings_tab(){    ?>    <form action="options.php" method="post">        <?php        settings_fields( 'max_bol_plugin_settings' );        do_settings_sections( 'max_bol_plugin_settings' );        submit_button( 'Save Settings' );        ?>    </form>    <?php}
<?php

/*
Plugin Name: Dagen Idag
Plugin URI: http://m360.no
Description: Viser dagen i dag info
Version: 1.0
Author: ibrahim Qraiqe
Author URI: http://m360.no
License: A "Slug" license name e.g. GPL2
*/

$dir = plugin_dir_path(__FILE__);
$plugin_url = plugin_dir_url(__FILE__);

define("IBO_DAGEN_IDAG", "IboDagenIdag/Ibo-dagen-idag.php");
define("NRK_DAGEN_IDAG_URL", "http://m.nrk.no/dagenidag/");

include_once ($dir.'inc/functions.php');
include_once ($dir.'inc/admin_general.php');
include_once ($dir.'inc/google-fonts.php');

if( !defined( 'ABSPATH' ) ) {
    exit;
}


class MainClass{
    public function __construct(){
        add_action( 'admin_enqueue_scripts', array( $this, 'init_scripts_admin' ) );

        add_action( 'admin_menu', array( $this, 'add_ibrahim_plugins_menu' ) );
        add_action( 'admin_menu', array( $this, 'add_dagen_idag_submenu' ) );
        add_action( 'admin_init', array( $this, 'init_settings_page' ) );
        add_filter( 'my_available_fonts', 'my_new_fonts' );


    }

    public function init_scripts_admin( ) {
        wp_enqueue_media();
        wp_enqueue_style( 'wp-color-picker');
        wp_enqueue_script( 'wp-color-picker');

        wp_enqueue_script( 'gazaway-dagenidag-script-handle', plugins_url( '/assets/js/main.js', __FILE__ ), array( 'jquery', 'wp-color-picker' ), '', true  );
    }

    public function init_settings_page(){
        $admin_general = new IBODAGENIDAG_AdminGeneral();
        $admin_general->drawAdminPage();
    }
    public function add_ibrahim_plugins_menu() {
        if ( empty ( $GLOBALS['admin_page_hooks']['ibrahim_plugins'] ) ){
            add_menu_page(
                'Gazaway Plugins',
                'Gazaway',
                'manage_options',
                'gazaway_options',
                array($this,'renderIbrahimPage'),
                ibo_asset_url('images/plugin_icon_palestine_flag.png'),//$icon_url
                31.0
            );
        }
    }

    public function add_dagen_idag_submenu() {

        add_submenu_page(
            'gazaway_options',
            'Dagen i dag: v.'.plugin_get_version(IBO_DAGEN_IDAG),
            'Dagen idag',
            'manage_options',
            'ibrahim_plugins_ibo_dagen_idag',
            array( $this, 'createTheForm' ) );

    }

    public function renderIbrahimPage(){
        ?>
        <div>
            <h3>GazaWaysPlugins</h3>
        </div>
        <?php
    }

    public function createTheForm(){
        print '<form method="post" action="options.php">';
        settings_fields( 'ibrahim_plugins_ibo_dagen_idag_group' );
        do_settings_sections( 'ibrahim_plugins_ibo_dagen_idag_page' );

        submit_button();

        // close page wrapper
        print '</form></div>';
    }

}

$mainClass = new MainClass();
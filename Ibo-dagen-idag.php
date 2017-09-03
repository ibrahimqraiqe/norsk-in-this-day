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

include_once ($dir.'inc/functions.php');
include_once ($dir.'inc/admin_general.php');

if( !defined( 'ABSPATH' ) ) {
    exit;
}


class MainClass{
    public function __construct(){
        add_action( 'admin_menu', array( $this, 'add_ibrahim_plugins_menu' ) );
        add_action( 'admin_menu', array( $this, 'add_dagen_idag_submenu' ) );
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
        $admin_general = new IBODAGENIDAG_AdminGeneral();

        add_submenu_page(
            'gazaway_options',
            'Dagen i dag: v.'.plugin_get_version(IBO_DAGEN_IDAG),
            'Dagen idag',
            'manage_options',
            'ibrahim_plugins_ibo_dagen_idag',
            array( $admin_general, 'drawAdminPage' ) );
    }

    public function renderIbrahimPage(){
        ?>
        <div>
            <h3>GazaWaysPlugins</h3>
        </div>
        <?php
    }

}

if ( is_admin() )
    $mainClass = new MainClass();
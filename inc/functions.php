<?php


if ( ! function_exists( 'plugin_get_version' ) ) {
    function plugin_get_version($plugin_file) {
        if ( ! function_exists( 'get_plugins' ) )
            require_once( ABSPATH . 'wp-admin/includes/plugin.php' );

        $plugin_folder = get_plugins();
        //echo '<h3>'.print_r($plugin_folder[$plugin_file],true).'</h3>';

        $name       = $plugin_folder[$plugin_file]['Name'];
        $author     = $plugin_folder[$plugin_file]['Author'];
        $version    = $plugin_folder[$plugin_file]['Version'];
        return $name.' v.'.$version.' by: '.$author;
    }
}


if ( ! function_exists( 'ibo_asset_url' ) ) {
    function ibo_asset_url( $file ) {
        global $plugin_url;
        return preg_replace( '/\s/', '%20', $plugin_url.'assets/'.$file);
    }
}
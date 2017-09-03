<?php

if(!class_exists('DagenIdagObject')){
    class DagenIdagObject{
        public $header;
        public $sitatet;
        public $navnedag;
        public $date;
        public $events;
    }
}

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
if(!function_exists('get_string_between')){
    function get_string_between($string, $start, $end){
        $string = ' ' . $string;
        $ini = strpos($string, $start);
        if ($ini == 0) return '';
        $ini += strlen($start);
        $len = strpos($string, $end, $ini) - $ini;
        return substr($string, $ini, $len);
    }
}

if(!function_exists('getDagenIdagArray')){
     function getDagenIdagArray($dagen_idag_html_splitted){
        $nrk_dagen_idag_array = array();
        foreach($dagen_idag_html_splitted as $line){
            $key = get_string_between($line,'<b>','</b>');
            $value = str_replace("<b>".$key."</b>", "", $line);
            if(strlen($key)>0){
                $nrk_dagen_idag_array[$key] = $value;
            }
        }
        return $nrk_dagen_idag_array;
    }
}

if(!function_exists('getDagenIdagObject')){
    function getDagenIdagObject(){
        $DagenIdagObject = new DagenIdagObject();

        $dagen_idag_url = file_get_contents(NRK_DAGEN_IDAG_URL);

        $dom = new DOMDocument();

        libxml_use_internal_errors(true);
        $dom->loadHTML($dagen_idag_url);
        libxml_use_internal_errors(false);

        $xpath = new DOMXPath($dom);
        $div = $xpath->query('//div[@class="content"]');
        $div = $div->item(0);
        $dagen_idag_html = $dom->saveXML($div);
        $dagen_idag_html_splitted = preg_split('/<br[^>]*>/i', $dagen_idag_html,-1,PREG_SPLIT_NO_EMPTY);


        $header = (count($dagen_idag_html_splitted)>0)?$dagen_idag_html_splitted[0]:NULL;
        $navnedag =  (count($dagen_idag_html_splitted)>2)?$dagen_idag_html_splitted[2]:NULL;

        $lastObj = end($dagen_idag_html_splitted);
        $secondLastObj = $dagen_idag_html_splitted[count($dagen_idag_html_splitted)-2];
        $sitatet = strlen($lastObj)>6?$lastObj:$secondLastObj;

        $DagenIdagObject->sitatet = $sitatet;
        $DagenIdagObject->header = $header;
        $DagenIdagObject->navnedag = $navnedag;

        $nrk_dagen_idag_array = getDagenIdagArray($dagen_idag_html_splitted);
        $DagenIdagObject->date = $dagen_idag_html_splitted[3];
        $events = array();
        foreach($nrk_dagen_idag_array as $year => $value){
            if(strlen($value)>0 && !is_numeric($year))$events[$year]=$value;
        }
        $DagenIdagObject->events = $events;
        return $DagenIdagObject;
    }
}
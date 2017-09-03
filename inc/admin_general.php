<?php

class IBODAGENIDAG_AdminGeneral{

    public function get_string_between($string, $start, $end){
        $string = ' ' . $string;
        $ini = strpos($string, $start);
        if ($ini == 0) return '';
        $ini += strlen($start);
        $len = strpos($string, $end, $ini) - $ini;
        return substr($string, $ini, $len);
    }


    public function getDagenIdagArray($dagen_idag_html_splitted){
        $nrk_dagen_idag_array = array();
        foreach($dagen_idag_html_splitted as $line){
            $key = $this->get_string_between($line,'<b>','</b>');
            $value = str_replace("<b>".$key."</b>", "", $line);
            if(strlen($key)>0){
                $nrk_dagen_idag_array[$key] = $value;
                //echo '<h3>'.print_r($key,true).'</h3>';
            }
        }
        return $nrk_dagen_idag_array;
    }
    public function drawAdminPage(){


        echo '<h3>'.plugin_get_version(IBO_DAGEN_IDAG).'</h3>';
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

        echo '<h3>'.$header.'</h3>';
        echo '<h2>Navnedag: '.$navnedag.'</h2>';
        $nrk_dagen_idag_array = $this->getDagenIdagArray($dagen_idag_html_splitted);

        echo '<h2>'.$dagen_idag_html_splitted[3].'</h2>';
        echo '<ul>';
        foreach($nrk_dagen_idag_array as $year => $value){
            if(strlen($value)>0 && !is_numeric($year))echo '<li>'.$year.' => '.$value.'</li>';
        }
        echo '</ul>';
        echo '<h3>Sitatet: '.$sitatet.'</h3>';

    }

}
<?php

$dagen_idag_object = getDagenIdagObject();

class IBODAGENIDAG_AdminGeneral{
    public $options;

    function __construct(){
        $this->options = array();

        $options = get_option( 'ibrahim_plugins_ibo_dagen_idag_page' );
        if(isset($options[ 'header_id'] ))
            $this->options['header'] = $options[ 'header_id'];
        else
            $this->options['header'] = array('color'=>'#fff','font'=>'arial');

        if(isset($options[ 'sitatet_id'] ))
            $this->options['sitatet'] = $options[ 'sitatet_id'];
        else
            $this->options['sitatet'] = array('color'=>'#fff','font'=>'arial');

        if(isset($options[ 'navnedag_id'] ))
            $this->options['navnedag'] = $options[ 'navnedag_id'];
        else
            $this->options['navnedag'] = array('color'=>'#fff','font'=>'arial');

        if(isset($options[ 'date_id'] ))
            $this->options['date'] = $options[ 'date_id'];
        else
            $this->options['date'] = array('color'=>'#fff','font'=>'arial');

        if(isset($options[ 'events_id'] ))
            $this->options['events'] = $options[ 'events_id'];
        else
            $this->options['events'] = '';


    }

    public function drawAdminPage(){
        global $dagen_idag_object;
        register_setting(
            'ibrahim_plugins_ibo_dagen_idag_group', // group
            'ibrahim_plugins_ibo_dagen_idag_page', // name
            array( $this, 'sanitise' ) // sanitise method
        );

        add_settings_section(
            'ibrahim_plugins_ibo_dagen_idag_section',
            plugin_get_version(IBO_DAGEN_IDAG),
            '',
            'ibrahim_plugins_ibo_dagen_idag_page'
        );

        foreach ($dagen_idag_object as $key => $field){
            add_settings_field(
                $key.'_id', // id
                $key, // title
                array( $this, $key.'_html' ), // callback
                'ibrahim_plugins_ibo_dagen_idag_page', // page
                'ibrahim_plugins_ibo_dagen_idag_section' // section
            );
        }
    }

    public function my_field_primary_font($name,$current_font) {
        $fonts = get_dagenidag_available_fonts();
        ?>
        <select name="<?php echo $name; ?>">
            <?php foreach( $fonts as $font_key => $font ): ?>
                <option <?php selected( $font_key == $current_font ); ?> value="<?php echo $font_key; ?>"><?php echo $font['name']; ?></option>
            <?php endforeach; ?>
        </select>
        <?php
    }

    public function printHtmlFieldFor($field){
        printf(
            '<input type="text" id="dagen_idag_header_color" name="ibrahim_plugins_ibo_dagen_idag_page[%s_id][color]" style="width: 250px;" class="color-field" value="%s" />',
            $field,$this->options[$field]['color']);
        echo '<br />';
        $this->my_field_primary_font('ibrahim_plugins_ibo_dagen_idag_page[%s_id][font]',$this->options[$field]['font']);
        echo '<br />';
        /*
        printf(
            '<input type="text" id="dagen_idag_header_font" name="ibrahim_plugins_ibo_dagen_idag_page[%s_id][font]" style="width: 250px;" value="%s" />',
            $field,$this->options[$field]['font']);
        */
    }



    public function header_html(){
        global $dagen_idag_object;
        $this->printHtmlFieldFor('header');
        echo '<div>'.$dagen_idag_object->header.'</div>';
    }
    public function sitatet_html(){
        global $dagen_idag_object;
        $this->printHtmlFieldFor('sitatet');
        echo '<div>'.$dagen_idag_object->header.'</div>';

        echo $dagen_idag_object->sitatet;
    }
    public function navnedag_html(){
        global $dagen_idag_object;
        $this->printHtmlFieldFor('navnedag');
        echo $dagen_idag_object->navnedag;
    }
    public function date_html(){
        global $dagen_idag_object;
        $this->printHtmlFieldFor('date');
        echo $dagen_idag_object->date;
    }
    public function events_html(){
        global $dagen_idag_object;

        echo print_r($dagen_idag_object->events,true);
    }

}
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Template {
    var $template_data = array();

    protected $stylesheets = array();
    protected $javascripts = array();
    var $device_width = NULL;
    var $device_density = NULL;
    var $device_height = NULL;

    function set($name, $value)
    {
        $this->template_data[$name] = $value;
    }

    function load($template = '', $view = '' , $view_data = array(), $return = FALSE)
    {
        $this->CI =& get_instance();

        $view_data['stylesheets'] = $this->stylesheets ;
        $view_data['javascripts'] = $this->javascripts ;

        $this->set('contents', $this->CI->load->view($view, $view_data, TRUE));
        return $this->CI->load->view($template, $this->template_data, $return);
    }
     
    function add_css($stylesheet)
    {
        $this->stylesheets[] = $stylesheet;
    }
     
    function add_js($javascript)
    {
        $this->javascripts[] = $javascript;
    }
    function set_device_width($device_width){
        $this->device_width = $device_width;
    }
    function set_device_height($device_height){
        $this->device_height = $device_height;
    }
    function set_device_dpi($device_density){
        $this->device_density = $device_density;
    }
}

/* End of file Template.php */
/* Location: ./system/application/libraries/Template.php */
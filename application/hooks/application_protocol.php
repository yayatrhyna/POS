<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Application_protocol
{

    function protocol_method()
    {

        $this->CI =& get_instance();
//        $protocol = $this->CI->db->select('*')->from('web_setting')->get()->row();
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https" : "http";
        if ($protocol == 'https') {
            // redirecting to ssl.
            $this->CI->config->config['base_url'] = str_replace('http://', 'https://', $this->CI->config->config['base_url']);
//            if ($_SERVER['SERVER_PORT'] != 443) redirect($this->CI->uri->uri_string());
        } else {
            // redirecting with no ssl.
            $this->CI->config->config['base_url'] = str_replace('https://', 'http://', $this->CI->config->config['base_url']);
//            if ($_SERVER['SERVER_PORT'] == 443) redirect($this->CI->uri->uri_string());
        }


    }

}

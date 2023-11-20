<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('remove_space'))
{
    function remove_space($var = '')    {
       $string = str_replace(' ','-', $var);
        return preg_replace('/[^A-Za-z0-9\-]/', '', $string);
    }
}

if ( ! function_exists('remove_hyphen'))
{
    function remove_hyphen($var = '')    {
     return  $string = str_replace('-',' ', $var);
    }
}

if(!function_exists('dd')){
    function dd($data=''){
        echo "<pre>";
        print_r($data);
        echo "</pre>";
        exit();
    }
}

if(!function_exists('d')){
    function d($data=''){
        echo "<pre>";
        print_r($data);
        echo "</pre>";        
    }
}
//remove special character
function clean($string) {
    return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
}
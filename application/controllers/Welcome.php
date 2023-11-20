<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Welcome extends CI_Controller {
    function __construct()
    {
        parent::__construct();
        $this->load->library('website/Lhome');
    }
    public function index(){
        //$content = $this->lhome->home_page();
        //$this->template->full_website_html_view($content);
        redirect('/admin', 'location');
	}
}
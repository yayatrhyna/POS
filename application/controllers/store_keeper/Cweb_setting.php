<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Cweb_setting extends CI_Controller {

	function __construct() {
      	parent::__construct();
		$this->load->library('store_keeper/auth');
		$this->load->library('store_keeper/lweb_setting');
		$this->load->library('store_keeper/session');
		$this->load->model('store_keeper/Web_settings');
		$this->auth->check_admin_auth();

		if ($this->session->userdata('user_type') == '2') {
            $this->session->set_userdata(array('error_message'=>display('you_are_not_access_this_part')));
            redirect('Admin_dashboard');
        }
	  
    }

	//Default loading for Category system.
	public function index()
	{
		$content = $this->lweb_setting->setting();
		$this->template->full_admin_html_view($content);
	}

	//Default loading for Category system.
	public function contact()
	{
		$content = $this->lweb_setting->contact_form();
		$this->template->full_admin_html_view($content);
	}

	//Submit contact form 
	public function submit_contact_form()
	{
		$data=array(
			'first_name'=> $this->input->post('first_name'),
			'last_name' => $this->input->post('last_name'),
			'email' 	=> $this->input->post('email'),
			'message' 	=> $this->input->post('message'),
			);

		$this->Web_settings->submit_contact_form($data);

		$this->session->set_userdata(array('message'=>display('successfully_inserted')));
		redirect(base_url('Cweb_setting/manage_contact_form'));
	}

	//Manage contact form 
	public function manage_contact_form()
	{
		$content = $this->lweb_setting->manage_contact_form();
		$this->template->full_admin_html_view($content);
	}

	//Update contact form
	public function contact_update_form($id)
	{
		$content = $this->lweb_setting->contact_update_form($id);
		$this->template->full_admin_html_view($content);
	}

	//Update contact form 
	public function update_contact_form($id)
	{
		$data=array(
			'first_name'=> $this->input->post('first_name'),
			'last_name' => $this->input->post('last_name'),
			'email' 	=> $this->input->post('email'),
			'message' 	=> $this->input->post('message'),
			);

		$this->Web_settings->update_contact_form($id,$data);

		$this->session->set_userdata(array('message'=>display('successfully_updated')));
		redirect(base_url('Cweb_setting/manage_contact_form'));
	}

	// Contact Delete
	public function contact_delete($id)
	{	
		$this->Web_settings->delete_contact($id);
		$this->session->set_userdata(array('message'=>display('successfully_delete')));
		redirect('Cweb_setting/manage_contact_form');
	}

	//Setting 
	public function setting()
	{
		$content = $this->lweb_setting->setting();
		$this->template->full_admin_html_view($content);
	}

	//Update social link
	public function update_web_settings($id)
	{
//        $CI =& get_instance();
        $this->load->model('store_keeper/Web_settings');
        $setting = $this->Web_settings->setting();

		if ($_FILES['logo']['name']) {
			$config['upload_path']          = 'my-assets/image/logo/';
	        $config['allowed_types']        = 'gif|jpg|png|jpeg|JPEG|GIF|JPG|PNG';
	        $config['max_size']             = "1024";
	        $config['max_width']            = "*";
	        $config['max_height']           = "*";
	        $config['encrypt_name'] 		= TRUE;
            $this->upload->initialize($config);
	        $this->load->library('store_keeper/upload', $config);
	        if ( ! $this->upload->do_upload('logo'))
	        {
	            $error = array('error' => $this->upload->display_errors());
	            $this->session->set_userdata(array('error_message'=> $this->upload->display_errors()));
	            redirect(base_url('Csoft_setting'));
	        }
	        else
	        {
	        	$image =$this->upload->data();
	        	$logo = "my-assets/image/logo/".$image['file_name'];
                if(!empty($setting[0]['logo'])) {
                    unlink(FCPATH . $setting[0]['logo']);//delete present image
                }
	        }
		}

		if ($_FILES['favicon']['name']) {
			$config['upload_path']          = 'my-assets/image/logo/';
	        $config['allowed_types']        = 'gif|jpg|png|jpeg|JPEG|GIF|JPG|PNG';
	        $config['max_size']             = "1024";
	        $config['max_width']            = "*";
	        $config['max_height']           = "*";
	        $config['encrypt_name'] 		= TRUE;
            $this->upload->initialize($config);
	        $this->load->library('store_keeper/upload', $config);
	        if ( ! $this->upload->do_upload('favicon'))
	        {
	            $error = array('error' => $this->upload->display_errors());
	            $this->session->set_userdata(array('error_message'=> $this->upload->display_errors()));
	            redirect(base_url('Csoft_setting'));
	        }
	        else
	        {
	        	$image =$this->upload->data();
	        	$favicon = "my-assets/image/logo/".$image['file_name'];
                unlink(FCPATH.$setting[0]['favicon']);
	        }
		}

		if ($_FILES['invoice_logo']['name']) {
			$config['upload_path']          = 'my-assets/image/logo/';
	        $config['allowed_types']        = 'gif|jpg|png|jpeg|JPEG|GIF|JPG|PNG';
	        $config['max_size']             = "1024";
	        $config['max_width']            = "*";
	        $config['max_height']           = "*";
	        $config['encrypt_name'] 		= TRUE;
            $this->upload->initialize($config);
	        $this->load->library('store_keeper/upload', $config);
	        if ( ! $this->upload->do_upload('invoice_logo'))
	        {
	            $error = array('error' => $this->upload->display_errors());
	            $this->session->set_userdata(array('error_message'=> $this->upload->display_errors()));
	            redirect(base_url('Csoft_setting'));
	        }
	        else
	        {
	        	$image =$this->upload->data();
	        	$invoice_logo = "my-assets/image/logo/".$image['file_name'];
                unlink(FCPATH.$setting[0]['invoice_logo']);
	        }
		}

		if ($_FILES['footer_logo']['name']) {
			$config['upload_path']          = 'my-assets/image/logo/';
	        $config['allowed_types']        = 'gif|jpg|png|jpeg|JPEG|GIF|JPG|PNG';
	        $config['max_size']             = "1024";
	        $config['max_width']            = "*";
	        $config['max_height']           = "*";
	        $config['encrypt_name'] 		= TRUE;
            $this->upload->initialize($config);
	        $this->load->library('store_keeper/upload', $config);
	        if ( ! $this->upload->do_upload('footer_logo'))
	        {
	            $error = array('error' => $this->upload->display_errors());
	            $this->session->set_userdata(array('error_message'=> $this->upload->display_errors()));
	            redirect(base_url('Csoft_setting'));
	        }
	        else
	        {
	        	$image =$this->upload->data();
	        	$footer_logo = "my-assets/image/logo/".$image['file_name'];
                unlink(FCPATH.$setting[0]['footer_logo']);
	        }
		}

		$old_logo 	 = $this->input->post('old_logo');
		$old_invoice_logo = $this->input->post('old_invoice_logo');
		$old_favicon = $this->input->post('old_favicon');
		$old_footer_logo = $this->input->post('old_footer_logo');

		$data=array(
			'logo' 			    => (!empty($logo)?$logo:$old_logo),
			'invoice_logo' 	    => (!empty($invoice_logo)?$invoice_logo:$old_invoice_logo),
			'favicon' 		    => (!empty($favicon)?$favicon:$old_favicon),
			'footer_logo' 	    => (!empty($footer_logo)?$footer_logo:$old_footer_logo),
			'footer_text' 	    => $this->input->post('footer_text'),
			'footer_details' 	=> $this->input->post('footer_details'),
            'google_analytics' 	=> htmlspecialchars($this->input->post('google_analytics',false)),
            'facebook_messenger'=> htmlspecialchars($this->input->post('facebook_messenger',false)),
            'meta_keyword' 	    => $this->input->post('meta_keyword'),
            'meta_description' 	=> $this->input->post('meta_description'),
			'app_link_status' 	=> $this->input->post('app_link_status'),
			'pay_with_status'   => $this->input->post('pay_with'),
			'map_api_key' 	    => $this->input->post('map_api_key'),
			'map_latitude' 	    => $this->input->post('map_latitude'),
			'map_langitude'     => $this->input->post('map_langitude'),
			);

		$this->Web_settings->update_web_settings($id,$data);

		$this->session->set_userdata(array('message'=>display('successfully_updated')));
		redirect(base_url('Cweb_setting/setting'));
	}

	//Add Slider 
	public function add_slider()
	{
		$content = $this->lweb_setting->add_slider();
		$this->template->full_admin_html_view($content);
	}

	//Insert slider
	public function submit_slider()
	{
		$this->form_validation->set_rules('slider_link', display('slider_link'), 'trim|required');
		$this->form_validation->set_rules('slider_position', display('slider_position'), 'trim|required');

		if ($this->form_validation->run() == FALSE){
        	$data = array(
				'title' => display('add_slider')
			);
        	$content = $this->parser->parse('web_setting/add_slider',$data,true);
			$this->template->full_admin_html_view($content);
        }else{

        	//Slider image
        	if ($_FILES['slider_image']['name']) {
				
				$config['upload_path']          = 'my-assets/image/category/';
		        $config['allowed_types']        = 'gif|jpg|png|jpeg|JPEG|GIF|JPG|PNG';
		        $config['max_size']             = "1024";
		        $config['max_width']            = "*";
		        $config['max_height']           = "*";
		        $config['encrypt_name'] 		= TRUE;
                $this->upload->initialize($config);
		        $this->load->library('store_keeper/upload', $config);
		        if ( ! $this->upload->do_upload('slider_image'))
		        {
		            $this->session->set_userdata(array('error_message'=>  $this->upload->display_errors()));
		            redirect('Cweb_setting/add_slider');
		        }
		        else{
		        	$image =$this->upload->data();
		        	$image_url = "my-assets/image/category/".$image['file_name'];
		        }
			}

			$data=array(
				'slider_id' 		=> $this->auth->generator(15),
				'slider_link' 		=> $this->input->post('slider_link'),
				'slider_image'		=> $image_url,
				'slider_position'	=> $this->input->post('slider_position'),
				'status'			=> 1,
			);

			$result=$this->Web_settings->slider_entry($data);

			if ($result == TRUE) {
					
				$this->session->set_userdata(array('message'=>display('successfully_added')));

				if(isset($_POST['add-slider'])){
					redirect('Cweb_setting/manage_slider');
				}elseif(isset($_POST['add-slider-another'])){
					redirect('Cweb_setting/add_slider');
				}
			}else{
				$this->session->set_userdata(array('error_message'=>display('already_exists')));
				redirect('Cweb_setting/add_slider');
			}
        }
	}

	//Manage Slider
	public function manage_slider()
	{
        $content =$this->lweb_setting->slider_list();
		$this->template->full_admin_html_view($content);
	}

	//Slider Update Form
	public function slider_update_form($slider_id)
	{	
		$content = $this->lweb_setting->slider_edit_data($slider_id);
		$this->template->full_admin_html_view($content);
	}

	// Slider Update
	public function update_slider($slider_id=null)
	{

		$this->form_validation->set_rules('slider_link', display('slider_link'), 'trim|required');
		$this->form_validation->set_rules('slider_position', display('slider_position'), 'trim|required');

		if ($this->form_validation->run() == FALSE){
        	$data = array(
				'title' => display('manage_slider')
			);
        	$content = $this->parser->parse('web_setting/slider',$data,true);
			$this->template->full_admin_html_view($content);
        }else{

			if ($_FILES['slider_image']['name']) {
				//Chapter chapter add start
				$config['upload_path']          = 'my-assets/image/category/';
		        $config['allowed_types']        = 'gif|jpg|png|jpeg|JPEG|GIF|JPG|PNG';
		        $config['max_size']             = "1024";
		        $config['max_width']            = "*";
		        $config['max_height']           = "*";
		        $config['encrypt_name'] 		= TRUE;
                $this->upload->initialize($config);
		        $this->load->library('store_keeper/upload', $config);
		        if ( ! $this->upload->do_upload('slider_image'))
		        {
		            $this->session->set_userdata(array('error_message'=>  $this->upload->display_errors()));
		            redirect('Cweb_setting/manage_slider');
		        }
		        else
		        {
		        	$image =$this->upload->data();
		        	$image_url = "my-assets/image/category/".$image['file_name'];
		        }
			}

			$old_image = $this->input->post('old_image');
			$data=array(
				'slider_link'	  => $this->input->post('slider_link'),
				'slider_position' => $this->input->post('slider_position'),
				'slider_image' 	  => (!empty($image_url)?$image_url:$old_image),
				);

			$result = $this->Web_settings->update_slider($data,$slider_id);

			if ($result == TRUE) {
					
				$this->session->set_userdata(array('message'=>display('successfully_updated')));
				redirect(base_url('Cweb_setting/manage_slider'));
			}else{
				$this->session->set_userdata(array('error_message'=>display('already_exists')));
				redirect('Cweb_setting/manage_slider');
			}
        }
	}
	
	//Inactive slider
	public function inactive($slider_id){
		$this->db->set('status', 0);
		$this->db->where('slider_id',$slider_id);
		$this->db->update('slider');
		$this->session->set_userdata(array('error_message'=>display('successfully_inactive')));
		redirect(base_url('Cweb_setting/manage_slider'));
	}

	//Active slider
	public function active($slider_id){
		$this->db->set('status', 1);
		$this->db->where('slider_id',$slider_id);
		$this->db->update('slider');
		$this->session->set_userdata(array('message'=>display('successfully_active')));
		redirect(base_url('Cweb_setting/manage_slider'));
	}

	//Delete slider
	public function slider_delete($slider_id){
		$this->db->where('slider_id', $slider_id);
		$this->db->delete('slider');
		$this->session->set_userdata(array('message'=>display('successfully_delete')));
		redirect(base_url('Cweb_setting/manage_slider'));
	}

	#----------------Submit Add------------#
	public function submit_add(){

		$this->form_validation->set_rules('add_page', display('add_page'),'trim|required');
		$this->form_validation->set_rules('ads_position', display('ads_position'),'trim|required');
		$this->form_validation->set_rules('ad_type', display('add_type'),'trim|required');

		if ($this->form_validation->run() == FALSE):
			$data = array(
				'title' => display('add_advertise')
			);
        	$content = $this->parser->parse('web_setting/submit_add',$data,true);
			$this->template->full_admin_html_view($content); 
		else:

            $ad_type=$this->input->post('ad_type');
            if ($ad_type==1):
                $data['adv_code']=$this->input->post('add_code');
                $data['adv_code2']=$this->input->post('add_code2');
                $data['adv_code3']=$this->input->post('add_code3');
            else:
                $data['adv_code']=null;
                $data['adv_code2']=null;
                $data['adv_code3']=null;
                $data['image']=null;
                $data['image2']=null;
                $data['image3']=null;
            endif;

            if ($ad_type==2):
                //============================================
                // configure for upload
                $config = array(
                    'upload_path' => "my-assets/image/add/",
                    'allowed_types' => "gif|jpg|png|jpeg|JPEG|GIF|JPG|PNG",
                    'overwrite' => TRUE,
                    'file_size' => '2048',
                    'encrypt_name'  => true
                );
                $image_name = '';
                $image_data = array();
                $this->load->library('store_keeper/upload', $config);
                $this->upload->initialize($config);
                if ($this->upload->do_upload('add_image')) {
                    $image_data = $this->upload->data();
                    $image_name = $image_data['file_name'];
                    $config['image_library'] = 'gd2';
                    $config['source_image'] = $image_data['full_path']; //get original image
                    $config['maintain_ratio'] = TRUE;
                    $config['height'] = '*';
                    $config['width'] = '*';
                    $this->load->library('store_keeper/image_lib', $config);
                    $this->image_lib->clear();
                    $this->image_lib->initialize($config);
                    if (!$this->image_lib->resize()) {
                        echo $this->image_lib->display_errors();
                    }
                    $filepath=base_url($config['upload_path'].$image_name);
                    $add_url=$this->input->post('add_url');
                    $data['adv_code']="<a href=\"$add_url\" target=\"_blank\"><img src=\"$filepath\" alt=\"image\" class=\"img-responsive\"></a>";
                    $data['image']=$image_name;
                } else {
                    $image_name = '';
                }
                //            ========= its for file upload 2 =========
                if ($this->upload->do_upload('add_image2')) {
                    $image_dataTwo = $this->upload->data();
                    $file_upload_2 = $image_dataTwo['file_name'];
                    $filepath2=base_url($config['upload_path'].$file_upload_2);
                    $add_url2=$this->input->post('add_url2');
                    $data['adv_code2']="<a href=\"$add_url2\" target=\"_blank\"><img src=\"$filepath2\" alt=\"\" class=\"img-responsive\"></a>";
                    $data['image2']=$file_upload_2;
                } else {
                    $file_upload_2 = '';
                }
                //            ========= its for file upload 3 =========
                if ($this->upload->do_upload('add_image3')) {
                    $image_dataThree = $this->upload->data();
                    $file_upload_3 = $image_dataThree['file_name'];
                    $filepath3=base_url($config['upload_path'].$file_upload_3);
                    $add_url3=$this->input->post('add_url3');
                    $data['adv_code3']="<a href=\"$add_url3\" target=\"_blank\"><img src=\"$filepath3\" alt=\"\" class=\"img-responsive\"></a>";
                    $data['image3']=$file_upload_3;
                } else {
                    $file_upload_3 = '';

                }

            endif;

            $data['adv_id']	  	  =	$this->auth->generator(15);
            $data['add_page']	  =	$this->input->post('add_page');
            $data['adv_position'] =	$this->input->post('ads_position');
            $data['adv_type']	  =	$this->input->post('ad_type');
            $data['status']	  	  =	1;
            $result=$this->Web_settings->insert_add($data);
            if($result == TRUE):
                $this->session->set_userdata(array('message'=>display('successfully_added')));
                redirect('Cweb_setting/manage_add');
            elseif($result == FALSE):
                $this->session->set_userdata(array('error_message'=>display('ads_position_already_exists')));
                redirect('Cweb_setting/submit_add');
            endif;
        endif;
	}
	#----------------Manage Add--------------------#
	public function manage_add(){
		$content =$this->lweb_setting->add_list();
		$this->template->full_admin_html_view($content);
	}
	#---------------Edit User----------#
	public function edit_add_form($id){
		$content = $this->lweb_setting->add_edit_data($id);
		$this->template->full_admin_html_view($content);
	}
	#--------------Update Add---------------#
	public function update_add($id){

		$this->form_validation->set_rules('add_page', display('add_page'),'trim|required');
		$this->form_validation->set_rules('ads_position', display('ads_position'),'trim|required');
		$this->form_validation->set_rules('ad_type', display('add_type'),'trim|required');

		if ($this->form_validation->run() == FALSE){

			$data = array(
				'title' => display('manage_add')
			);
        	$content = $this->parser->parse('web_setting/manage_add',$data,true);
			$this->template->full_admin_html_view($content); 
		}else{

			$ad_type=$this->input->post('ad_type');
			$data['adv_code']=$this->input->post('add_code');
		
			if ($ad_type==2){
				if (($_FILES['add_image']['name'])) {
					$data=array();
					$config['upload_path'] 	 ='my-assets/image/add/';
				    $config['allowed_types'] ='gif|jpg|png|jpeg|JPEG|GIF|JPG|PNG';
				    $config['max_size']      = '100000';
				    $config['width']     	 = 10000;
					$config['height']   	 = 10000;
				    $config['overwrite']     = FALSE;
				   	$config['encrypt_name']  = true;
                    $this->upload->initialize($config);
				   	$this->load->library('store_keeper/upload', $config);
			   		$this->upload->initialize($config);

			        if (!$this->upload->do_upload('add_image')) {
			           	$this->session->set_userdata(array('error_message'=>$this->upload->display_errors()));
						redirect('Cweb_setting/manage_add'); 
			        } else {
						$view =$this->upload->data();
					   	$filepath=base_url($config['upload_path'].$view['file_name']);
					   	$add_url=$this->input->post('add_url');
			        	$data['adv_code']="<a href=\"$add_url\" target=\"_blank\"><img src=\"$filepath\" class=\"img-responsive\" alt=\"\"></a>";
			        }
				}
			}

			$data['adv_id']	  	  =	$this->auth->generator(15);
			$data['add_page']	  =	$this->input->post('add_page');
			$data['adv_position'] =	$this->input->post('ads_position');
			$data['adv_type']	  =	$this->input->post('ad_type');

			$result=$this->Web_settings->update_add($data,$id);
			
			if($result){
				$this->session->set_userdata(array('message'=> display('successfully_updated')));
			  	redirect('Cweb_setting/manage_add'); 
			}else{
				$this->session->set_userdata(array('error_message'=>display('already_exists')));
				redirect('Cweb_setting/manage_add'); 
			}
		}
	}
	#=====Delete Advertisement======#
	public function delete_add($id){
		$this->db->where('adv_id', $id);
		$this->db->delete('advertisement');
		$this->session->set_userdata(array('message'=>display('successfully_delete')));
		redirect('Cweb_setting/manage_add');
	}
	//Inactive advertisement
	public function inactive_add($id){
		$this->db->set('status', 0);
		$this->db->where('adv_id',$id);
		$this->db->update('advertisement');
		$this->session->set_userdata(array('error_message'=>display('successfully_inactive')));
		redirect(base_url('Cweb_setting/manage_add'));
	}
	//Active advertisement
	public function active_add($id){
		$this->db->set('status', 1);
		$this->db->where('adv_id',$id);
		$this->db->update('advertisement');
		$this->session->set_userdata(array('message'=>display('successfully_active')));
		redirect(base_url('Cweb_setting/manage_add'));
	}


    public function android_apps_view()
    {
        $content = $this->lweb_setting->android_apps_view();
        $this->template->full_admin_html_view($content);
    }

    public function update_android_apps_update()
    {
        $url = $this->input->post('apps_url');
        $data=['apps_url'=>$url];
        $this->db->update('web_setting',$data);
        return redirect('Cweb_setting/android_apps_view');
    }
}
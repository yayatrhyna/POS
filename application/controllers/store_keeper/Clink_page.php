<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Clink_page extends CI_Controller {

	function __construct() {
      	parent::__construct();
		$this->load->library('store_keeper/llink_page');
		$this->load->model('store_keeper/Link_pages');
		$this->auth->check_admin_auth();
    }
	//Default loading for link_page system.
	public function index()
	{
		$content = $this->llink_page->link_page_add_form();
		$this->template->full_admin_html_view($content);
	}
	//Insert link_page
	public function insert_link_page()
	{

		if ($_FILES['image']['name']) {
			$config['upload_path']          = './my-assets/image/link_page/';
	        $config['allowed_types']        = 'gif|jpg|png|jpeg|JPEG|GIF|JPG|PNG';
	        $config['max_size']             = "10240";
	        $config['max_width']            = "";
	        $config['max_height']           = "";
	        $config['encrypt_name'] 		= TRUE;
            $this->upload->initialize($config);
	        $this->load->library('store_keeper/upload', $config);
	        if ( ! $this->upload->do_upload('image'))
	        {
	            $this->session->set_userdata(array('error_message'=>  $this->upload->display_errors()));
	            redirect('Clink_page');
	        }
	        else
	        {
	        	$image =$this->upload->data();
	        	$image_url = "my-assets/image/link_page/".$image['file_name'];

	        	//Resize image config
				$config['image_library'] = 'gd2';
				$config['source_image'] = FCPATH.'my-assets/image/link_page/'.$image['file_name'];
				$config['maintain_ratio'] = FALSE;
				$config['width']         = 555;
				$config['height']       = 320;

				$this->load->library('store_keeper/image_lib', $config);
				$this->image_lib->resize();
				//Resize image config

				$thumb_image = base_url($config['source_image']);
	        }
		}

		$page_id 	 = $this->input->post('page_id');
		$lang_id 	 = $this->input->post('language_id');
		$headlines 	 = $this->input->post('headlines');
		$details 	 = $this->input->post('details');

		//Link page add
		for ($i=0, $n=count($lang_id); $i < $n; $i++) {

			$language_id = $lang_id[$i];
			$head_line 	 = $headlines[$i];
			$detail 	 = $details[$i];

			$data = array(
				'link_page_id'		=>	$this->auth->generator(15),
				'page_id'			=>	$page_id,
				'language_id' 		=> 	$language_id, 
				'headlines' 		=> 	$head_line,
				'image' 			=> 	(!empty($image_url)?$image_url:null),
				'details' 			=> 	$detail,
				'status'			=>	'1',
			);
			
			$result = $this->db->select('*')
							->from('link_page')
							->where('page_id',$data['page_id'])
							->where('language_id',$data['language_id'])
							->get()
							->num_rows();
			if ($result > 0) {
				$this->session->set_userdata(array('error_message'=>display('already_exists')));
				redirect(base_url('Clink_page/manage_link_page'));
			}else{
				$res = $this->db->insert('link_page',$data);
			}
		}

		if ($res == TRUE) {
			$this->session->set_userdata(array('message'=>display('successfully_added')));
			if(isset($_POST['add-link_page'])){
				redirect(base_url('Clink_page/manage_link_page'));
			}elseif(isset($_POST['add-link_page-another'])){
				redirect(base_url('Clink_page'));
			}
		}else{
			$this->session->set_userdata(array('error_message'=>display('already_exists')));
			redirect(base_url('Clink_page'));
		}
	}
	//Manage link_page
	public function manage_link_page()
	{
        $content =$this->llink_page->link_page_list();
		$this->template->full_admin_html_view($content);;
	}
	//link_page Update Form
	public function link_page_update_form($link_page_id)
	{	
		$content = $this->llink_page->link_page_edit_data($link_page_id);
		$this->template->full_admin_html_view($content);
	}

	// link_page Update
	public function link_page_update($link_page_id=null)
	{
		if ($_FILES['image']['name']) {
			$config['upload_path']          = './my-assets/image/link_page/';
	        $config['allowed_types']        = 'gif|jpg|png|jpeg|JPEG|GIF|JPG|PNG';
	        $config['max_size']             = "10240";
	        $config['max_width']            = "";
	        $config['max_height']           = "";
	        $config['encrypt_name'] 		= TRUE;
            $this->upload->initialize($config);
	        $this->load->library('store_keeper/upload', $config);
	        if ( ! $this->upload->do_upload('image'))
	        {
	            $this->session->set_userdata(array('error_message'=>  $this->upload->display_errors()));
	            redirect('Clink_page');
	        }
	        else
	        {
	        	$image =$this->upload->data();
	        	$image_url = "my-assets/image/link_page/".$image['file_name'];

	        	//Resize image config
				$config['image_library'] = 'gd2';
				$config['source_image'] = FCPATH.'my-assets/image/link_page/'.$image['file_name'];
				$config['maintain_ratio'] = FALSE;
				$config['width']         = 555;
				$config['height']       = 320;

				$this->load->library('store_keeper/image_lib', $config);
				$this->image_lib->resize();
				//Resize image config

				//$thumb_image = base_url($config['source_image']);
	        }
		}

		$old_image 	 = $this->input->post('old_image');
		$page_id 	 = $this->input->post('page_id');
		$lang_id 	 = $this->input->post('language_id');
		$headlines 	 = $this->input->post('headlines');
		$details 	 = $this->input->post('details');

		//Link page add
		for ($i=0, $n=count($lang_id); $i < $n; $i++) {

			$language_id = $lang_id[$i];
			$head_line 	 = $headlines[$i];
			$detail 	 = $details[$i];

			$data = array(
				'headlines' 		=> 	$head_line,
				'image' 			=> 	(!empty($image_url)?$image_url:$old_image),
				'details' 			=> 	$detail,
			);
			
			$this->db->where('page_id',$page_id);
			$this->db->where('language_id',$language_id);
			$result = $this->db->update('link_page',$data);
		}

		if ($result == TRUE) {
			$this->session->set_userdata(array('message'=>display('successfully_updated')));
			redirect('Clink_page/manage_link_page');
		}else{
			$this->session->set_userdata(array('error_message'=>display('already_exists')));
			redirect('Clink_page/manage_link_page');
		}
	}
	// link_page Delete
	public function link_page_delete($link_page_id)
	{
		$this->Link_pages->delete_link_page($link_page_id);
		$this->session->set_userdata(array('message'=>display('successfully_delete')));
		redirect('Clink_page/manage_link_page');
	}

	//Inactive
	public function inactive($id){
		$this->db->set('status', 0);
		$this->db->where('link_page_id',$id);
		$this->db->update('link_page');
		$this->session->set_userdata(array('error_message'=>display('successfully_inactive')));
		redirect(base_url('Clink_page/manage_link_page'));
	}
	//Active 
	public function active($id){
		$this->db->set('status', 1);
		$this->db->where('link_page_id',$id);
		$this->db->update('link_page');
		$this->session->set_userdata(array('message'=>display('successfully_active')));
		redirect(base_url('Clink_page/manage_link_page'));
	}
}
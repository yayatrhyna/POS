<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Cpay_with extends CI_Controller {

	function __construct() {
		parent::__construct();
		$this->load->library('lpay_with');
		$this->load->model('pay_withs');
		$this->auth->check_admin_auth();
	}
	
	//show all item list 
	public function index()
	{
		
		$content = $this->lpay_with->pay_with_list();		
		$this->template->full_admin_html_view($content);
	}


	//show insert form 
	public function create(){
		$content = $this->lpay_with->pay_with_add_form();
		$this->template->full_admin_html_view($content);
	}



	//insert pay with image in to  the database
	public function store(){
		$this->form_validation->set_rules('image', display('image'), 'required');

		if ($_FILES['image']['name']) {
			$config['upload_path']          = './my-assets/image/pay_with/';
			$config['allowed_types']        = 'gif|jpg|png|jpeg|JPEG|GIF|JPG|PNG';
			$config['max_size']             = "1024";
			$config['max_width']            = "*";
			$config['max_height']           = "*";
			$config['encrypt_name'] 		= TRUE;
            $this->upload->initialize($config);
			$this->load->library('upload', $config);
			if ( ! $this->upload->do_upload('image'))
			{
				$this->session->set_userdata(array('error_message'=> $this->upload->display_errors()));
				redirect(base_url('Cpay_with'));
			}
			else
			{
				$image =$this->upload->data();
				$pay_with_image = $image['file_name'];
			}
		}

		$data=array(
			'title' 	=> $this->input->post('title'),
			'image' 	=> $pay_with_image,
			'link' 	=> $this->input->post('link'),
			'status' 	=> $this->input->post('status')
		);

		$result = $this->db->insert('pay_withs',$data);

		if ($result == TRUE) {
			$this->session->set_userdata(array('message'=>display('successfully_added')));
			return redirect('Cpay_with');
		}
	}


	//show edit form
	public function edit($id){
		$content = $this->lpay_with->pay_with_edit_form($id);
		$this->template->full_admin_html_view($content);

	}


	public function update($id)
	{

		if ($_FILES['image']['name']) {
			$config['upload_path']          = './my-assets/image/pay_with/';
			$config['allowed_types']        = 'gif|jpg|png|jpeg|JPEG|GIF|JPG|PNG';
			$config['max_size']             = "1024";
			$config['max_width']            = "*";
			$config['max_height']           = "*";
			$config['encrypt_name'] 		= TRUE;
            $this->upload->initialize($config);
			$this->load->library('upload', $config);
			if ( ! $this->upload->do_upload('image'))
			{
				$this->session->set_userdata(array('error_message'=> $this->upload->display_errors()));
				redirect(base_url('Cpay_with'));
			}
			else
			{
				$image =$this->upload->data();
				$image = $image['file_name'];
			}
		}
		$old_image = $this->input->post('old_image');

		$data=array(	
			'title' 	=> $this->input->post('title'),
			'image' 	=> (!empty($image)?$image:$old_image),
			'link' 	=> $this->input->post('link'),
			'status' 	=> $this->input->post('status')
		);

		$result=$this->pay_withs->update($data,$id);
		if(!empty($image))
		{
			unlink(FCPATH."my-assets/image/pay_with/".$old_image);//delete current image
		}
		if ($result == TRUE) {
			$this->session->set_userdata(array('message'=>display('successfully_updated')));
			redirect('Cpay_with');
		}
	}



	public function delete($id)
	{

		$this->pay_withs->delete($id);
		$this->session->set_userdata(array('message'=>display('successfully_delete')));
		redirect('Cpay_with');	
	}

}
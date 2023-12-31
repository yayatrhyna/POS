<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Cour_location extends CI_Controller {

	function __construct() {
      	parent::__construct();
		$this->load->library('store_keeper/lour_location');
		$this->load->model('store_keeper/Our_location');
		$this->auth->check_admin_auth();
    }
	//Default loading for our_location system.
	public function index()
	{
		$content = $this->lour_location->our_location_add_form();
		$this->template->full_admin_html_view($content);
	}

	//Insert our_location
	public function insert_our_location()
	{
		$lang_id 	 = $this->input->post('language_id');
		$headlines 	 = $this->input->post('headlines');
		$details 	 = $this->input->post('details');
		$position 	 = $this->input->post('position');

		//Link page add
		for ($i=0, $n=count($lang_id); $i < $n; $i++) {

			$language_id = $lang_id[$i];
			$head_line 	 = $headlines[$i];
			$detail		 = $details[$i];

			$data = array(
				'language_id' 	=> 	$language_id, 
				'headline' 		=> 	$head_line,
				'details' 		=> 	$detail,
				'position' 		=> 	$position,
				'status'		=>	'1',
			);


			$pos_res = $this->db->select('*')
								->from('our_location')
								->where('position',$position)
								->where('language_id',$language_id)
								->get()
								->num_rows();

			if ($pos_res > 0) {
				$this->session->set_userdata(array('error_message'=>display('already_exists')));
				redirect(base_url('Cour_location'));
			}else{
				$result = $this->Our_location->our_location_entry($data);
			}
		}

		if ($result == TRUE) {
			$this->session->set_userdata(array('message'=>display('successfully_added')));

			if(isset($_POST['add-our_location'])){
				redirect(base_url('Cour_location/manage_our_location'));
			}elseif(isset($_POST['add-our_location-another'])){
				redirect(base_url('Cour_location'));
			}
			
		}else{
			$this->session->set_userdata(array('error_message'=>display('already_exists')));
			redirect(base_url('Cour_location'));
		}
	}
	//Manage our_location
	public function manage_our_location()
	{
        $content =$this->lour_location->our_location_list();
		$this->template->full_admin_html_view($content);;
	}
	//our_location Update Form
	public function our_location_update_form($content_id)
	{	
		$content = $this->lour_location->our_location_edit_data($content_id);
		$this->template->full_admin_html_view($content);
	}

	// our_location Update
	public function our_location_update($position=null)
	{

		$lang_id 	 = $this->input->post('language_id');
		$headlines 	 = $this->input->post('headlines');
		$details 	 = $this->input->post('details');
		$posi 	 	= $this->input->post('position');

		//Link page add
		for ($i=0, $n=count($lang_id); $i < $n; $i++) {

			$language_id = $lang_id[$i];
			$head_line 	 = $headlines[$i];
			$detail		 = $details[$i];

			$pos_res = $this->db->select('*')
								->from('our_location')
								->where('position',$posi)
								->get()
								->num_rows();

			if ($pos_res > 0) {
				$new = array(
						'headline' 		=> 	$head_line,
						'details' 		=> 	$detail,
					);

				$result = $this->Our_location->update_our_location($new,$position,$language_id);
			}else{
				$u_pos = array(
					'headline' 		=> 	$head_line,
					'details' 		=> 	$detail,
					'position' 		=> 	$posi,
				);
				$result = $this->Our_location->update_our_location($u_pos,$position,$language_id);
			}
		}

		if ($result == TRUE) {
			$this->session->set_userdata(array('message'=>display('successfully_updated')));
			redirect(base_url('Cour_location/manage_our_location'));
		}else{
			$this->session->set_userdata(array('error_message'=>display('position_already_exists')));
			redirect(base_url('Cour_location/manage_our_location'));
		}
	}
	// our_location Delete
	public function our_location_delete($position)
	{
		$this->Our_location->delete_our_location($position);
		$this->session->set_userdata(array('message'=>display('successfully_delete')));
		redirect('Cour_location/manage_our_location');
	}

	//Inactive
	public function inactive($position){
		$this->db->set('status', 0);
		$this->db->where('position',$position);
		$this->db->update('our_location');
		$this->session->set_userdata(array('error_message'=>display('successfully_inactive')));
		redirect(base_url('Cour_location/manage_our_location'));
	}
	//Active 
	public function active($position){
		$this->db->set('status', 1);
		$this->db->where('position',$position);
		$this->db->update('our_location');
		$this->session->set_userdata(array('message'=>display('successfully_active')));
		redirect(base_url('Cour_location/manage_our_location'));
	}
}
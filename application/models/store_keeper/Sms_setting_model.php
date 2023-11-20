<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Sms_setting_model extends CI_model {

	#------------------------------------#
	# 
	#------------------------------------#	
 	 // public function sms_gateway_list()
 	 // {
 	 // 	return $result = $this->db->select("*")
 	 // 			->from('sms_gateway')
 	 // 			->get()
 	 // 			->result();
 	 // }
 	 // #----------------------------------
 	 // public function sms_gateway_by_id($gateway_id=NULL){
 	 // 	return $result = $this->db->select("*")
 	 // 			->from('sms_gateway')
 	 // 			->where('gateway_id',$gateway_id)
 	 // 			->get()
 	 // 			->row();
 	 // }
 	 // #----------------------------------
 	 // public function save_custom_dalivery($data){
 	 // 	$this->db->insert('custom_sms_info',$data);
 	 // 	return $this->db->insert_id();
 	 // }

	//get template
 	 #-----------------------------------
	public function template_list(){
		return $data = $this->db->select('*')
		->from('sms_template')
		->get()
		->result();
	}

	public function save_sms_template($data){
		$result = $this->db->insert('sms_template',$data);
		return $result;
	}
//update template
	public function template_update($data){
		$id = $this->input->post('id');
		$result=$this->db->where('id',$id)->update('sms_template',$data);
		return $result;
	}


	public function update_sms_config($data)
	{
		$id = $this->input->post('id');
		if($id){
			$this->db->where('id',$id);
            $result = $this->db->update('sms_configuration',$data);

			$this->db->set('status',0)
            ->where('id !=', $id)
            ->update('sms_configuration');
			return $result;
		}else{
			$result = $this->db->insert('sms_configuration',$data);
			return $result;
		}

	}   
 // 	 #-----------------------------------
 // 	 public function sms_schedule_list(){
	//  	return $result = $this->db->select('sms_schedule.*,sms_teamplate.*')
	//      ->from('sms_schedule')
	//      ->join('sms_teamplate','sms_teamplate.teamplate_id=sms_schedule.ss_teamplate_id')
	//      ->get()
	//      ->result();
 // 	 }

	// #----------------------------
	// public function coustom_sms(){
	// 		return $cus_result = $this->db->select("*")
	// 	 	 	->from('custom_sms_info')
	// 	 	 	->get()
	// 	 	 	->result();

	// 	}	
	// #----------------------------
	// public function auto_sms(){
	// 	return $auto = $this->db->select("*")
	//  	 	->from('sms_delivery')
	//  	 	->get()
	//  	 	->result();
	// } 	 



} 	 
<?php

class Model_requirements extends CI_Model{
	
	public function add_requirement(){
		$data = array(
			'idRequirement' => $this->input->post('reqid'),
			'name' => $this->input->post('reqname'),
			'description' => $this->input->post('reqdesc'),
			'req_creator' => $this->session->userdata('username')
		);
		
		$query = $this->db->insert('requirement', $data);
		
		if($query)
			return true;
		 else
			return false;
		
	}
	
	public function add_temp_user($key){
		
		
	}
}
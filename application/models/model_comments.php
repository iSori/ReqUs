<?php

class Model_users extends CI_Model{
	
	public function can_log_in(){
		
		$this->db->where('username', $this->input->post('username'));
		$this->db->where('password', md5($this->input->post('password')));
		
		$query = $this->db->get('user');
		
		if ($query->num_rows() == 1){
			return true;
		} else{
			return false;
		}
	}
	
	public function add_temp_user($key){
		
		$data = array(
			'email' => $this->input->post('email'),
			'password' => md5($this->input->post('password')),
			'username' => $this->input->post('username'),
			'name' => $this->input->post('fullName'),
			'key' => $key;
		)
		
		query = $this->db->insert('temp_users', $data);
		
		if($query){
			return true;
		} else
			return false;
		}
		
	}
}
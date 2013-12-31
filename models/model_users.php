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
			'key' => $key
		);
		
		$query = $this->db->insert('temp_users', $data);
		
		if($query){
			return true;
		} else
			return false;
		
	}
	
	public function is_valid_key($key){
		$this->db->where('key', $key);
		$query = $this->db->get('temp_user');
		
		if ($query->num_rows() == 1)
			return true;
		else
			return false;
	}
	
	public function add_user($key){
		$this->db->where('key', $key);
		$temp_user = $this->db->get('temp_user');
		
		if ($temp_user){
			$row = $temp_user->row();
			
			$data = array(
				'email' => $row->email,
				'username' => $row->username,
				'password' => $row->password,
				'name' => $row->name
			);
			
			$add_user = $this->db->insert('user', $data);
		}
		
		if($add_user){
			$this->db->where('key', $key);
			$this->db->delete('temp_user');
			return true;
		}
		
		return false;
	}
	
	public function get_user($username){
		$this->db->where('username', $username);
		$user = $this->db->get('user');
		
		return $user;
	}
}
<?php

class Model_comments extends CI_Model{
	
	public function add_comment(){
		
		if($this->session->userdata('is_logged_in')){
			$data = array(
				"comment" => $this->input->post('comment'),
				"commenter" => $this->session->userdata('username'),
				"requirement" => $this->input->post('requirement')
			);
			
			$query = $this->db->insert("comment", $data);
			
			if($query)
				return true;
		} else return false;
		
		
	}
	
	public function not_commented(){
		$this->db->where('commenter', $this->session->userdata('username'));
		$this->db->where('requirement', $this->input->post('requirement'));
		
		$query = $this->db->get('comment');
		
		if ($query->num_rows() == 0){
			return true;
		} else{
			return false;
		}
	}
}

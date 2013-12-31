<?php

class Model_scores extends CI_Model{
	
	public function get_users(){
		
		$users_query = $this->db->query('user');
		
		$row = null;
		if ($users_query->num_rows() > 0){
		   $row = $users_query->row_array(); 
		}
		
		return $row;
	}
	
	public function get_user_scores($user){
		// The points for the requirements are obtained
		$this->db->where('req_creator', $user);
		$req = $this->db->get('requirement');
		
		$points = $req->num_rows() * 7;
		
		
		// The points for the comments are obtained
		$this->db->where('commenter', $user);
		$req = $this->db->get('comment');
		
		$points += $req->num_rows() * 4;
		
		// The points for evaluating requirements are obtained
		$this->db->where('assessor', $user);
		$req = $this->db->get('requirement_punctuation');
		
		$points += $req->num_rows() * 2;
		
		// The points for evaluating comments are obtained
		$this->db->where('assessor', $user);
		$req = $this->db->get('comment_punctuation');
		
		$points += $req->num_rows() * 1;
		
		return $points;
	}
}
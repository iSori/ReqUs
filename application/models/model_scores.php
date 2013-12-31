<?php

class Model_scores extends CI_Model{
	

	
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
	
		public function get_user_info($user){
			
		// The points for the requirements are obtained
		$this->db->where('req_creator', $user);
		$req = $this->db->get('requirement');
		
		$points = $req->num_rows() * 7;
		
		
		// The points for the comments are obtained
		$this->db->where('commenter', $user);
		$comm = $this->db->get('comment');
		
		$points += $comm->num_rows() * 4;
		
		// The points for evaluating requirements are obtained
		$this->db->where('assessor', $user);
		$req_scor = $this->db->get('requirement_punctuation');
		
		$points += $req_scor->num_rows() * 2;
		
		// The points for evaluating comments are obtained
		$this->db->where('assessor', $user);
		$comm_scor = $this->db->get('comment_punctuation');
		
		$points += $comm_scor->num_rows() * 1;

		$data = array(
			"requirements" => $req->num_rows(),
			'comments' => $comm->num_rows(),
			'eval_requirements' => $req_scor->num_rows(),
			'eval_comments' => $comm_scor->num_rows(),
			'score' => $points
		);
	
		
		return $data;
	}
	
}
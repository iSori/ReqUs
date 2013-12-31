<?php

class Model_requirements extends CI_Model{
	
	public function add_req(){
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
	
	public function get_requirements(){
		// We obtain all the requirements, comments and scorings from the database
		$requirements_query = $this->db->get('requirement');
		$requirements_scoring_query = $this->db->get('requirement_punctuation');
		$comments_query = $this->db->get('comment');
		$comments_scoring_query = $this->db->get('comment_punctuation');
		
		// The scoring will be stored in these 2 variables
		$req_points = null;
		$comm_points = null;
		
		// The total points of the requirements are computed here through two loops;
		// the first one goes through all the requirements, and the second one goes through
		// all the requirements punctuation table
		if ($requirements_query->num_rows() > 0){
			foreach($requirements_query->result() as $req){
				$id = $req->idRequirement;
				$this->db->where('requirement', $id);
				
				// The comments are saved according to their requirement
				$comments[$id] = $this->db->get('comment');
				
				$this->db->where('requirement', $id);
				$one_req_scoring = $this->db->get('requirement_punctuation');
				$data["points"][$id] = 0;
				foreach($one_req_scoring->result() as $req_scor){
					$data["points"][$id] += $req_scor->points;
				}
			}
		}
		
		if ($comments_query->num_rows() > 0){
			foreach($comments_query->result() as $comm){
				$id = $comm->idComment;
				$this->db->where('comment', $id);
				$one_comm_scoring = $this->db->get('comment_punctuation');
				$comm_points[$id] = 0;
				foreach($one_comm_scoring->result() as $comm_scor){
					$comm_points[$id] += $comm_scor->points;
				}
			}
		}
		
		$data["requirements"] = $requirements_query;
		$data["comments"] = $comments;
		$data["comm_points"] = $comm_points;
		
		return $data;
	}
}
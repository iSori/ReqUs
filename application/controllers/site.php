<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Site extends CI_Controller {

	public function index(){
		$data = $this->load_requirements();
		$data["message"] = "";
		$data["done"] = "";
		$this->dashboard($data);
	}
	
	public function dashboard($data){
		$data["title"] = "Dashboard";
		$this->load->view("header", $data);
		if($this->session->userdata('is_logged_in'))
			$this->load->view("loggedHeader");
		else 
			$this->load->view("notLoggedHeader");
		
		$this->load->view("menu");
		$this->load->view("content_dashboard", $data);
		$this->load->view("footer");
	}
	
	public function projectStats(){
		$data["title"] = "Project Stats";
		$this->load->view("header", $data);
		if($this->session->userdata('is_logged_in'))
			$this->load->view("loggedHeader");
		else 
			$this->load->view("notLoggedHeader");
		
		$this->load->view("menu");
		$this->load->view("content_project_stats");
		$this->load->view("footer");
	}
		
	public function about(){
		$data["title"] = "About";
		$this->load->view("header", $data);
		if($this->session->userdata('is_logged_in'))
			$this->load->view("loggedHeader");
		else 
			$this->load->view("notLoggedHeader");
		
		$this->load->view("menu");
		$this->load->view("content_about");
		$this->load->view("footer");
	}
	
	public function my_profile(){
		// The user is logged in
		if($this->session->userdata('is_logged_in')){
			$this->load->model("model_users");
			$this->load->model("model_scores");
			
			$data["title"] = "My Profile";
			$this->load->view("header", $data);
			
			$data["user"] = $this->model_users->get_user($this->session->userdata('username'));
			$data["scores"] = $this->model_scores->get_user_info($this->session->userdata('username'));
			
			$this->load->view("loggedHeader");
			$this->load->view("menu");
			$this->load->view("content_my_profile", $data);
			$this->load->view("footer");

		// The user is not logged in	
		}else{
			$this->restricted_area();
		}
	}
	
	public function scores(){
		$data["title"] = "Scores";
		$this->load->view("header", $data);
		if($this->session->userdata('is_logged_in'))
			$this->load->view("loggedHeader");
		else 
			$this->load->view("notLoggedHeader");
	
		$data = $this->get_scores();
		
		$this->load->view("menu");
		$this->load->view("content_scores", $data);	
	}
	
	public function new_requirement(){
		// The user is logged in
		if($this->session->userdata('is_logged_in')){
			$data["message"] = '';
			$data["title"] = "New Requirement";
			$this->load->view("header", $data);
			$this->load->view("loggedHeader");
			$this->load->view("menu");
			$this->load->view("content_new_requirement", $data);
			$this->load->view("footer");
		
		// The user is not logged in	
		}else{
			$this->restricted_area();
		}
	}
	
	public function restricted_area(){
		$data["title"] = "Restricted Area";
		$data["error_message"] = "Area restricted to logged in members.";
		$this->load->view("header", $data);
		$this->load->view("notLoggedHeader");
		$this->load->view("menu");
		$this->load->view("content_restricted_area");
		$this->load->view("footer");
	}
	
	public function signup(){
		$data["title"] = "Sign Up";
		$data["message"] = "";
		if($this->session->userdata('is_logged_in'))
			$this->logged_member();	
		 else{
			$this->load->view("header", $data);
			$this->load->view("notLoggedHeader");
			$this->load->view("menu");
			$this->load->view("signup");
			$this->load->view("footer");
			
		}
	}
	
	public function login(){
		$data["message"] = "";
		$data["title"] = "Log In";
		if($this->session->userdata('is_logged_in'))
			$this->logged_member();
		else{
			$this->load->view("header", $data);
			$this->load->view("notLoggedHeader");
			$this->load->view("menu");
			$this->load->view("login", $data);
			$this->load->view("footer");
		}
	}
	
	public function logged_member(){
		$data["title"] = "Restricted Area";
		$data["message"] = "";
		if($this->session->userdata('is_logged_in')){
			$data["title"] = "Restricted Area";
			$data["error_message"] = "You are already logged in";
			$this->load->view("header", $data);
			$this->load->view("notLoggedHeader");
			$this->load->view("menu");
			$this->load->view("content_restricted_area");
			$this->load->view("footer");
		}
	}
	
	
	public function logout(){
		$this->session->sess_destroy();
		$this->index();
	}
	
	public function signup_send(){
		$this->load->library("form_validation");
		$data["message"] = "";
		
		$this->form_validation->set_rules("fullName", "Full Name", "required|alpha|xss_clean");
		$this->form_validation->set_rules("email", "Email", "required|valid_email|xss_clean|is_unique[user.email]");
		$this->form_validation->set_rules("username", "Username", "required|xss_clean|is_unique[user.username]");
		$this->form_validation->set_rules("password", "Password", "required|xss_clean");
		$this->form_validation->set_rules("cpassword", "Confirm Password", "required|xss_clean|trim|matches[password]");
		$this->form_validation->set_message('is_unique', "The email address or the username already exist.");
		
		
		if ($this->form_validation->run() == FALSE){
			$data["title"] = "Sign Up";
			$this->load->view("header", $data);
			$this->load->view("notLoggedHeader");
			$this->load->view("menu");
			$this->load->view("signup", $data);
			$this->load->view("footer");
				
		} else{
			// in case the registration goes well, we send an email
			
			// generate a random key
			$key = md5(uniqid());
			
			$this->load->library('email', array('mailtype'=>'html'));
			$this->load->model('model_users');
			
			// Send the email
			$this->email->from('info@requs.webage.com', "The ReqUs Team");
			$this->email->to($this->input->post('email'));
			$this->email->subject("Confirm your account at ReqUs");
			
			$message = "<p>Thank you for signing up!</p>";
			$message .= "<p><a href='".base_url()."site/register_user/$key'>Click here</a> to confirm your account</p>";

			$this->email->message($message);
			
			if($this->model_users->add_temp_user($key)){
				if($this->email->send()){
					$data["title"] = "Success!";
					$data["message"] = "Congratulations! Check your email to validate your account!";
				} else {
					$data["title"] = "Failed";
					$data["message"] = "There was an error sending the email. Please try again.";
				}
			} else {
				$data["title"] = "Failed";
				$data["message"] = "Something went wrong. Please try again.";
			}
	
		$this->redirecting($data);
		
		}
	}
	
	public function register_user($key){
		$this->load->model('model_users');
		
		if ($this->model_users->is_valid_key($key)){
			if($this->model_users->add_user($key)){
				$data["title"] = "Account Validated";
				$data["message"] = "You have successfuly validated your account";
				$this->redirecting($data);
			}else{
				$data["title"] = "Could Not Validate";
				$data["message"] = "There was an error validating your account";
			}
		}else{
			$data["title"] = "Could Not Validate";
			$data["message"] = "There was an error validating your account";
		}
	
		$this->redirecting($data);
	}
	
	public function redirecting($data){
		$this->load->view("header", $data);
		$this->load->view("notLoggedHeader");
		$this->load->view("menu");
		$this->load->view("content_redirecting", $data);
		$this->load->view("footer");
	}
	
	public function login_send(){
		$this->load->library("form_validation");
		$data["message"] = "";
		
		$this->form_validation->set_rules("username", "Username", "required|xss_clean|trim|callback_validate_credentials");
		$this->form_validation->set_rules("password", "Password", "required|xss_clean|md5|trim");
		
		if ($this->form_validation->run() == FALSE){
			$data["title"] = "Sign Up";
			$this->load->view("header", $data);
			$this->load->view("notLoggedHeader");
			$this->load->view("menu");
			$this->load->view("login", $data);
			$this->load->view("footer");
				
		} else{
			$data = array(
				'username' => $this->input->post('username'),
				'is_logged_in' => 1
			);
			$this->session->set_userdata($data);
			
			redirect('site/my_profile');
		}
	}
	
	// This method is used for checking if there is an entry with the given user and password.
	// It is called when the user submits the data with correct parameters in the login page.
	public function validate_credentials(){
		$this->load->model('model_users');
		
		if ($this->model_users->can_log_in())
			return true;
		else{
			$this->form_validation->set_message('validate_credentials', 'Incorrect username/password.');
			return false;
		}
	}
	
	// This function is called when the user submits the data in the new requirement view
	public function add_requirement(){
		// We check if the user is logged in. If not, he is not allowed to continue.
		if($this->session->userdata('is_logged_in'!=1))
			$this->restricted_area();
			
			
		$this->load->model('model_requirements');
		
		$this->load->library("form_validation");
		$data["message"] = "";
		
		$this->form_validation->set_rules("reqid", "Requirement ID", "required|xss_clean|trim|is_unique[requirement.idRequirement]");
		$this->form_validation->set_rules("reqname", "Requirement Name", "required|xss_clean|trim|is_unique[requirement.name]");
		$this->form_validation->set_rules("reqdesc", "Description", "required|xss_clean|trim");
		$this->form_validation->set_message('is_unique', "There already exists a requirement with this ID or name");
		
		// If the validation fails the page is reloaded indicating the errors
		if ($this->form_validation->run() == FALSE){
			$data["title"] = "New Requirement";
			$this->load->view("header", $data);
			$this->load->view("loggedHeader");
			$this->load->view("menu");
			$this->load->view("content_new_requirement", $data);
		
		// If the validation is correct the model to insert the data is called		
		} else{
			// The user is informed of whether the requirement has been added or not
			if($this->model_requirements->add_req()){	
				$data["title"] = "New Requirement";
				$this->load->view("header", $data);
				$this->load->view("loggedHeader");
				$this->load->view("menu");
				$data["message"] = "<p style='color:blue'> Requirement added! You've earned 7 points!</p>";
				$this->load->view("content_new_requirement", $data);
				
			}else{
				$data["message"] = "<p style='color:red'>There was a problem uploading the new requirement </p>";
				$data["title"] = "New Requirement";
				$this->load->view("header", $data);
				$this->load->view("loggedHeader");
				$this->load->view("menu");
				$this->load->view("content_new_requirement", $data);
				$this->load->view("footer");
			}
		}
	}
	
	public function load_requirements(){
		$this->load->model('model_requirements');
		$data = $this->model_requirements->get_requirements();
		
		return $data;
	}
	
	public function add_comment(){
		$this->load->library("form_validation");
		$data["message"] = "";
		
		$this->form_validation->set_rules("comment", "Comment", "required|xss_clean|trim|callback_check_comments");
		
		if ($this->form_validation->run() == FALSE){
			$data = $this->load_requirements();
			$data["message"] = "Could not add comment.";
			$data["done"] = "";
			$this->dashboard($data);
				
		} else{
			$this->load->model("model_comments");
			if($this->model_comments->add_comment()){
				$data = $this->load_requirements();
				$data["message"] = "";
				$data["done"] = "Comment added! You've earned 4 points!";
				$this->dashboard($data);
			}else{
				$data = $this->load_requirements();
				$data["message"]= "Could not add comment";
				$data["done"] = "";
				$this->dashboard($data);
			
			}
		}
	}
	
	public function check_comments(){
		$this->load->model('model_comments');
		
		if ($this->model_comments->not_commented())
			return true;
		else{
			$this->form_validation->set_message('check_comments', 'You have already commented on this requirement');
			return false;
		}
	}
	
	
	public function get_scores(){
		$this->load->model("model_scores");
		$this->load->model("model_users");
		
		$users = $this->model_users->get_users();
		
		foreach($users->result() as $user){
			$data["scores"][$user->username] = $this->model_scores->get_user_scores($user->username);
		}
		
		$data["users"] = $users;
		
		return $data;
	}
}
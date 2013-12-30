<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Site extends CI_Controller {

	public function index(){
		$this->dashboard();
	}
	
	public function dashboard(){
		$data["title"] = "Dashboard";
		$this->load->view("header", $data);
		if($this->session->userdata('is_logged_in'))
			$this->load->view("loggedHeader");
		else 
			$this->load->view("notLoggedHeader");
		
		$this->load->view("menu");
		$this->load->view("content_dashboard");
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
	}
	
	public function my_profile(){
		// The user is logged in
		if($this->session->userdata('is_logged_in')){
			$data["title"] = "My Profile";
			$this->load->view("header", $data);
			$this->load->view("loggedHeader");
			$this->load->view("menu");
			$this->load->view("content_my_profile");

		// The user is not logged in	
		}else{
			$this->restricted_area();
		}
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
		}
	}
	
	
	public function logout(){
		$this->session->sess_destroy();
		redirect('site/dashboard');
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
				
		} else{
			// in case the registration goes well, we send an email
			
			// generate a random key
			$key = md5(uniqid());
			
			$this->load->library('email', array('mailtype'=>'html'));
			$this->load->model('model_users');
			
			// Send the email
			$this->email->from('info@requs.webage.com', "Sorin");
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
				
		} else{
			$data = array(
				'username' => $this->input->post('username'),
				'is_logged_in' => 1
			);
			$this->session->set_userdata($data);
			
			redirect('site/my_profile');
		}
		
		// echo $his->input->post('username');
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
			if($this->model_requirements->add_requirement()){
				$data["message"] = "<p style='color:blue'> The requirement has been added! </p>";
				$data["title"] = "New Requirement";
				$this->load->view("header", $data);
				$this->load->view("loggedHeader");
				$this->load->view("menu");
				$this->load->view("content_new_requirement", $data);
				
			}else
				$data["message"] = "<p style='color:red'>There was a problem uploading the new requirement </p>";
				$data["title"] = "New Requirement";
				$this->load->view("header", $data);
				$this->load->view("loggedHeader");
				$this->load->view("menu");
				$this->load->view("content_new_requirement", $data);
		
		}
	}
}
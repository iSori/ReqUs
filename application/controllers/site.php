<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Site extends CI_Controller {

	public function index(){
		$this->dashboard();
	}
	
	public function dashboard(){
		$data["title"] = "Dashboard";
	
		$this->load->view("header", $data);
		
		if(isset($_COOKIE["user"]))
			$this->load->view("loggedHeader");
		else 
			$this->load->view("notLoggedHeader");
		
		$this->load->view("menu");
		
		$this->load->view("content_dashboard");
	}
	
	public function projectStats(){
		$data["title"] = "Project Stats";
	
		$this->load->view("header", $data);
		
		if(isset($_COOKIE["username"]))
			$this->load->view("loggedHeader");
		else 
			$this->load->view("notLoggedHeader");
		
		$this->load->view("menu");
		
		$this->load->view("content_project_stats");
	}
	
	public function my_profile(){
		$data["title"] = "My Profile";
	
		$this->load->view("header", $data);
		
		if($this->session->userdata('is_logged_in'))
			$this->load->view("loggedHeader");
		else 
			$this->load->view("notLoggedHeader");
		
		$this->load->view("menu");
		
		$this->load->view("content_my_profile");
	}
	
	public function signup(){
		$data["title"] = "Sign Up";
		if(isset($_COOKIE["username"]))
			$this->index();
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
		if(isset($_COOKIE["user"]))
			$this->index();
		else{
			$this->load->view("header", $data);
			$this->load->view("notLoggedHeader");
			$this->load->view("menu");
			$this->load->view("login", $data);
			
		}
	}
	
	public function signup_send(){
		$this->load->library("form_validation");
		$data["message"] = "";
		
		$this->form_validation->set_rules("fullName", "Full Name", "required|alpha|xss_clean");
		$this->form_validation->set_rules("email", "Email", "required|valid_email|xss_clean");
		$this->form_validation->set_rules("username", "Username", "required|xss_clean");
		$this->form_validation->set_rules("password", "Password", "required|xss_clean");
		
		if ($this->form_validation->run() == FALSE){
			$data["title"] = "Sign Up";
			$this->load->view("header", $data);
			$this->load->view("notLoggedHeader");
			$this->load->view("menu");
			$this->load->view("signup", $data);
				
		} else{
			$data["message"] = "Congratulations! You can now log in with your username and password";
			// WHERE TO REDIRECT?
		}
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
	
	public function validate_credentials(){
		$this->load->model('model_users');
		
		if ($this->model_users->can_log_in())
			return true;
		else{
			$this->form_validation->set_message('validate_credentials', 'Incorrect username/password.');
			return false;
		}
	}
}
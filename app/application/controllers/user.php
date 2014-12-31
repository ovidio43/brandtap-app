<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
		
		// Loading models
		$this->load->model('model_instagram');
		$this->load->model('model_user');
	}
	
	// User profile page
	public function profile()
	{
		if( ! $this->model_user->is_user_logedin()){
			
			// Get user data and set access token
			$user_data = $this->model_instagram->get_user_data();
			
			// Check if user exists, create new if don't exists
			// If function return FALSE user don't have emali registerd
			if( $user_data != null && ! $this->model_user->user_exists_or_create($user_data)){
				redirect('user/register_email');
			}
		}
		
		// Get recent media where user is brand
		$media_brand = $this->model_instagram->get_user_recent_media(10, TRUE, 
			$this->model_user->get_username($this->session->userdata('user_id')));
			
		// Get recent media where user get discount
		$media_user = $this->model_instagram->get_user_recent_media(10, FALSE,
			$this->model_user->get_username($this->session->userdata('user_id')));
		
		$data = array(
			'media' => $media_brand,
			'media_user' => $media_user
		);
		
		$this->title = "BrandTap";
		$this->document_title = "Profile";
        $this->content = $this->view('user/profile', $data);
        $this->_show();
	}
	
	// User email registration page
	public function register_email()
	{
		
		// Page data
		$page_data = array(
			'form_atributes' => array(
				'role' => 'form',
				'class' => 'form-horizontal'
			),
			'email' => $this->model_user->get_user_email()
		);
		
		$this->title = "BrandTap";
		$this->document_title = "Register email";
        $this->content = $this->view('user/register_email', $page_data);
        $this->_show();
	}
	
	// Email activation page
	public function emali_activation($code = '')
	{
		// Activation from link sent in mali
		/*if($code != ''){
			$page_data = $this->model_user->activate_user($code);
		} else {
			$page_data = $this->model_user->send_activation_link();
		}*/
		
		$this->model_user->send_activation_link();
		
		/*$this->title = "BrandTap";
		$this->document_title = "";
        $this->content = $this->view('user/emali_activation', $page_data);
        $this->_show();*/
	}

	// Logout function
	public function logout()
	{
		$this->model_user->logout();
		redirect('');
	}
}

?>
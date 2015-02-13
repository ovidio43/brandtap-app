<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class User extends MY_Controller {

    public function __construct() {
        parent::__construct();

        // Loading models
        $this->load->model('model_instagram');
        $this->load->model('model_user');
		$this->load->model('model_paypal');
		$this->load->model('model_upload');
    }

    // User profile page
    public function profile() {
        if (!$this->model_user->is_user_logedin()) {
            // Get user data and set access token
            $user_data = $this->model_instagram->get_user_data();
            // Check if user exists, create new if don't exists
            // If function return FALSE user don't have emali registerd
            if ($user_data != null && !$this->model_user->user_exists_or_create($user_data)) {
                redirect('user/register_email');
            }
        }
		
		$user = $this->model_user->get_user($this->session->userdata('user_id'));
        		
		$this->model_upload->create_upload_dir($user->inst_id);
		
		if($user->payment_profile_id === 'Free'){
			$this->session->set_userdata('subscription_status', 'Active');
		} else {
			$this->session->set_userdata('subscription_status', 
				$this->model_paypal->get_payment_status($user->payment_profile_id));
		}
		
		if($user->brand == 1){
			 // Get recent media where user is brand
        	$media_brand = $this->model_instagram->get_user_recent_media(90, TRUE, $user->username);
			$media_user = array();
		} else {
			// Get recent media where user get discount
        	//$media_user = $this->model_instagram->get_user_recent_media(10, FALSE, $user->username);
        	$media_user = array();
			$media_brand = array();
		}
		
		// Page data
        $data = array(
            'media' => $media_brand,
            'media_user' => $media_user,
            'brand' => $user->brand,
            'username' => $user->username
        );

        $this->title = "BrandTap";
        $this->document_title = "Profile";
        $this->content = $this->view('user/profile', $data);
        $this->_show();
    }

    // User email registration page
    public function register_email() {

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
    public function email_activation($code = '') {
        // Activation from link sent in mali
        /* if($code != ''){
          $page_data = $this->model_user->activate_user($code);
          } else {
          $page_data = $this->model_user->send_activation_link();
          } */

        //$this->model_user->send_activation_link();
          $this->model_user->email_activation();

        /* $this->title = "BrandTap";
          $this->document_title = "";
          $this->content = $this->view('user/email_activation', $page_data);
          $this->_show(); */
    }

    // Logout function
    public function logout() {
        $this->model_user->logout();

        $this->content = $this->view('user/logout');
        $this->_show();
    }

    // Non-brand user will be redirected here!
    public function registration_finished() {
        $this->content = $this->view('user/registration_finished');
        $this->_show();
    }
	
	// Get email template
	public function get_email_template(){	
		echo $this->model_user->get_email_template($_POST['post_id'], TRUE);
	}
	
	// Save email template
	public function save_email_template(){
		$this->model_user->save_email_template($_POST['message'], $_POST['post_id'], $_POST['subject'], $_POST['code_lenght']);
		echo 0;
	}
	
	// Test email template
	public function test_email_template(){
		$this->model_user->add_new_winners(array($this->session->userdata('user_id')), $_POST['post_id'], '', TRUE);
		echo 0;
	}

	// Email sending status
	public function email_sending_status_change(){
		$this->model_user->email_sending_status_change($_POST['post_id'], $_POST['status']);
		if($_POST['status'] == 1){
			echo 1;
		} else {
			echo 0;
		}
	}

	// Subscribe page
	public function subscribe(){
		
		if( ! $this->model_user->is_user_logedin()){
			redirect('');
		}
		
		// Page data
        $page_data = array(
            'form_atributes' => array(
                'role' => 'form',
                'class' => 'form-horizontal'
            )
		);
		
		$this->title = "BrandTap";
        $this->document_title = "Subscribe";
        $this->content = $this->view('user/subscribe', $page_data);
        $this->_show();
	}
	
	// Free code 
	public function free_code(){
		$this->model_user->free_code_activation();
	}

}

?>
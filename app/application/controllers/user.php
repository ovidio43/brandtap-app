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
    public function profile($brand = '') {
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
		
		/*if($user->payment_profile_id === 'Free'){
			$this->session->set_userdata('subscription_status', 'Active');
		} else {
			$this->session->set_userdata('subscription_status', 
				$this->model_paypal->get_payment_status($user->payment_profile_id));
		}*/
		if($user->admin == 1){
			
			// Get selected brand info and get recent media for that brand
			if($brand != ''){
				$brand_info = $this->model_user->get_user_by_id($brand);
				if($brand_info->brand == 1){
					// Get recent media for brand
        			$media_brand = $this->model_instagram->get_user_recent_media(90, $brand_info->inst_id, $user->username);
				}
			}
			
			// Get list of brands
			$brand_oprions = $this->model_user->get_all_brands();
		}
		
		// Page data
        $data = array(
            'media' => isset($media_brand) ? $media_brand : array(),
            'admin' => $user->admin,
            'brand' => isset($brand_info->username) ? $brand_info->username : '',
            'username' => $user->username,
            'brand_options' => isset($brand_oprions) ? $brand_oprions : array()
        );
		
		if(isset($brand_info->id)){
			$this->brand_id = $brand_info->id;
		}

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
		$this->model_user->add_new_winners(array($this->session->userdata('user_id')), $_POST['post_id'], '', TRUE, array(), '');
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
	
	// Register new user from brand site
	public function new_non_brand_user($brand_id = 0){
		
		$message_type = 0;
		if($brand_id != 0){
			$user = $this->model_user->get_user_by_id($brand_id);
			if($user != null && $user->brand == 1){
				
				if(isset($_POST['submit'])){
						
					$regex = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/';
					if(preg_match($regex, $_POST['email'])){
						$old_user = $this->model_user->get_user_by_username($_POST['username']);
						if($old_user == null){
							$new_users_array = $this->model_instagram->_get_user_by_usrname($_POST['username'], $user->access_token);
						
							$found = FALSE;
							foreach($new_users_array->data as $data){
								if($data->username === $_POST['username']){
								
									$insert = array(
										'username' => $data->username,
										'image_url' => $data->profile_picture,
										'inst_id' => $data->id,
										'from_brand' => $brand_id,
										'email' => $_POST['email']
									);
								
									$this->model_user->create_user_from_brand_site($insert);
								
									$found = TRUE;
									break;
								}
							}
							if($found){
								$message_type = 1;
								$message = 'Thank you! You successfully registered.';
							} else {
								$message = 'Entered username doesn\'t exist on Instagram.';
							}
						} else{
							$message = 'User with that username already exists!';
						}	
					} else {
						$message = 'Error: Bad email format';
					}
				}
				
			} else {
				$brand_id = 0;
			}
		}
		
		// Page data
		$page_data = array(
			'brand_id' => $brand_id,
			'message' => isset($message) ? $message : '',
			'message_type' => $message_type,
			'form_atributes' => array(
				'role' => 'form',
				'class' => 'form-horizontal'
			)
		);
		
		$this->load->view('user/new_non_brand_user', $page_data);
	}

	// Preferences page
	public function preferences($brand_id = 0){
		
		if($brand_id != 0){
			$user = $this->model_user->get_user($this->session->userdata('user_id'));
			if($user->admin == 1){
				
				if(isset($_POST['submit'])){
					$this->model_user->set_frequency($brand_id);
					$this->model_user->save_multi_email_template($brand_id, $_POST['subject'], $_POST['email_body'], $_POST['post_list']);
					$message = 'Settings have been successfully saved';
				}
				
				$brand_info = $this->model_user->get_user_by_id($brand_id);
				$frequency = $brand_info->email_frequency;
				$email_template = $this->model_user->get_multi_email_template($brand_id);
				$email_subject = $email_template['subject'];
				$email_body_template = $email_template['body'];
				$post_list_template = $email_template['post_list'];
				
				$white_label_code = '<iframe src="' . site_url('user/new_non_brand_user/' . $brand_id) . '"></iframe>';
			}
		}	
		
		// Page data
		$page_data = array(
			'brand_id' => $brand_id,
			'email_subject' => $email_subject,
			'email_body_template' => $email_body_template, 
			'post_list_template' => $post_list_template,
			'email_frequency' => isset($frequency) ? $frequency : 0,
			'white_label' => $white_label_code,
			'message' => isset($message) ? $message : '',
			'brand_name' => isset($brand_info->username) ? $brand_info->username : '',
			'form_atributes' => array(
				'role' => 'form',
				'class' => 'form-horizontal'
			)
		);
		
		$this->title = "BrandTap";
        $this->document_title = "Preferences";
        $this->content = $this->view('user/preferences', $page_data);
        $this->_show();
	}

}

?>
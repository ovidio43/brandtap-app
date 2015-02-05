<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Model_user extends CI_Model {

    public function __construct() {
        
    }

    // Check if user exists if no create new user
    public function user_exists_or_create($data) {
        $this->db->from(TBL_USERS);
        $this->db->where('inst_id', $data->user->id);

        if ($this->db->count_all_results() == 0) {
            $this->create_user($data);
            return FALSE;
        } else {
            $email = $this->get_user_email();
            if ($email == '') {
                return FALSE;
            } else {
                return $this->is_user_active($this->session->userdata('user_id'));
            }
        }

        return TRUE;
    }

    // Create new user
    private function create_user($data) {
        $user_data = array(
            'username' => $data->user->username,
            'inst_id' => $data->user->id,
            'image_url' => $data->user->profile_picture,
            'brand' => 0,
            'email' => ''
        );

        $this->db->insert(TBL_USERS, $user_data);
    }

    // Get user mail
    public function get_user_email($inst_id = 0) {
        $row = $this->db->select('email')
                ->where('inst_id', $inst_id != 0 ?
                                $inst_id : $this->session->userdata('user_id'))
                ->get(TBL_USERS)
                ->row();

        return $row->email;
    }

    // Check if user activated account
    public function is_user_active($inst_id) {
        $row = $this->db->select('active')
                ->where('inst_id', $inst_id)
                ->get(TBL_USERS)
                ->row();

        return $row->active == 0 ? FALSE : TRUE;
    }

    // Send activation link
    public function send_activation_link() {
        // Load form helper
        $this->load->helper('form');

        // Check email
        $email = $this->input->post('email');
		$brand = $this->input->post('chbBrand');
        $regex = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/';

        if (preg_match($regex, $email)) {

            // Activation code
            //$code = md5($email . $this->session->userdata('user_id'));
            // Send activation link to user
            //$header = "From: BrandTap";
            //$to = $email;
            //$subject="Your confirmation link here";
            // Email message
            //$message="Your Comfirmation link \r\n";
            //$message.="Click on this link to activate your account \r\n";
            //$message.= site_url('user/emali_activation/' . $code);
            //mail($to,$subject,$message,$header);
            // Add email to database
            $this->add_email($email, $brand);

            redirect('user/profile');
            // Add activation code for user
            //$this->add_activation_code($code);
            //return $data = array('email' => $email);
        } else {
            redirect('user/register_email');
        }
    }

    public function email_activation()
    {
        $email = $this->input->post('email');
        $brand = $this->input->post('chbBrand');

        $this->load->library('form_validation');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');

        if ($this->form_validation->run() !== FALSE){
            $this->add_email($email, $brand);
            $this->send_welcome_email($email);
            redirect('user/profile');
        } else {
            redirect('user/register_email?error=Invalid+email+address');
        }
    }

    public function send_welcome_email($email)
    {
        $to = $email;
        $subject = WELCOME_EMAIL_SUBJECT;
        //$message = "<p>Hi</p><p>Welcome to the best online tool ever... TESTING...</p>";
        $message = $this->load->view('email/email_welcome', null, TRUE);

        $mail_success = send_email($to,$subject,$message);
        return $mail_success;
    }

    // User activation
    public function activate_user($code) {
        $inst_id = 0;
        $this->db->from(TBL_ACTIVATION);
        $this->db->where('code', $code);
        $query = $this->db->get();
        foreach ($query->result() as $row) {
            $inst_id = $row->inst_id;
        }

        if ($inst_id == 0) {
            if (!$this->is_user_active($inst_id)) {
                return array('error' => 'Wrong activation');
            } else {
                redirect('');
            }
        } else {
            $data = array(
                'active' => 1
            );

            $this->db->where('inst_id', $inst_id);
            $this->db->update(TBL_USERS, $data);

            // Delete activation code
            $this->delete_activation_code($code);

            return array(
                'succes' => 'Your account is activated login to start using application',
                'url' => site_url('')
            );
        }
    }

    // Adding user emeil
    private function add_email($email, $brand) {
        $data = array(
            'email' => $email,
            'brand' => $brand === 'on' ? 1 : 0
        );

        $this->db->where('inst_id', $this->session->userdata('user_id'));
        $this->db->update(TBL_USERS, $data);
    }

    // Add activation code
    private function add_activation_code($code) {
        $data = array(
            'inst_id' => $this->session->userdata('user_id'),
            'code' => $code
        );

        $this->db->insert(TBL_ACTIVATION, $data);
    }

    // Delete activation code
    private function delete_activation_code($code) {
        $this->db->where('code', $code);
        $this->db->delete(TBL_ACTIVATION);
    }

    // Is user logedin
    public function is_user_logedin() {

        if($this->session->userdata('user_id') < 1){
            return false;
        }
        
        $user = $this->model_user->get_user($this->session->userdata('user_id'));
        if( ! $user ){
            return false;
        }

        return true;
    }

    // Get all brands id-s
    public function get_all_brands() {
        $brands = array();

        $this->db->select('inst_id');
        $this->db->from(TBL_USERS);
        $this->db->where('active', 1);
        $this->db->where('brand', 1);
        $query = $this->db->get();

        foreach ($query->result() as $row) {
            $brands[] = $row->inst_id;
        }

        return $brands;
    }

    // Get asked users if they exists
    public function get_users_in($users) {
        $users_exist = array();

        if (!is_array($users) || count($users) < 1) {
            return $users_exist;
        }

        $this->db->select('inst_id');
        $this->db->from(TBL_USERS);
        $this->db->where_in('inst_id', $users);
        $query = $this->db->get();

        foreach ($query->result() as $row) {
            $users_exist[] = $row->inst_id;
        }

        return $users_exist;
    }

    // Get all users that got code for post
    public function get_post_winners($post_id) {
        $winners = array();

        $this->db->select('inst_id');
        $this->db->from(TBL_POST_WINNERS);
        $this->db->where('post_id', $post_id);
        $query = $this->db->get();

        foreach ($query->result() as $row) {
            $winners[] = $row->inst_id;
        }

        return $winners;
    }

    // Add new winners
    public function add_new_winners($users, $post_id, $brand, $test = FALSE, $user_data = array()) {
        foreach ($users as $inst_id) {
        	
			// Get email data
            $email_data = $this->get_email_template($post_id);

			$user = $this->get_user($inst_id);
				
			if(!$test){
				$code = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $email_data['code_lenght']);
					
				$data = array(
                	'post_id' => $post_id,
                	'inst_id' => $inst_id,
                	'code' => $code,
                	'type' => $user_data[$user->inst_id]
            	);

            	$this->db->insert(TBL_POST_WINNERS, $data);	
					
				// Subject
				$subject = str_replace('{name}', $user->username, $email_data['subject']);
				$subject = str_replace('{brand}', $brand, $subject);
			
				// Message
				$message = str_replace('{name}', $user->username, $email_data['message']);
				$message = str_replace('{coupon_code}', $code, $message);
				$message = str_replace('{brand}', $brand, $message);
			} else {
				$message = $email_data['message'];
				$subject = $email_data['subject'];
			}

            // Send activation link to user
//          $header = "From: " . FROM_EMAIL . "\r\n";
//          $header .= "BCC: mrvica83mm@yahoo.com,triva89@yahoo.com\r\n";
//          $header .= "MIME-Version: 1.0\r\n";
//          $header .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

			$to = $user->email;
            //log_message('error', "$to , $subject, message , $header");
//          $mail_success = mail($to, $subject, $message, $header);
			if($email_data['status'] == 1 || $test){
				$mail_success = send_email($to,$subject,$message);
           		log_message('error', 'mail success=' . print_r($mail_success, 1));	
			}
        }
    }

    // Get user username
    public function get_user($inst_id) {
        $row = $this->db->select('*')
                ->where('inst_id', $inst_id)
                ->get(TBL_USERS)
                ->row();

        return isset($row->username) ? $row : null;
    }

    // Check if user is brand
    public function is_user_brand() {

        $row = $this->db->select('brand')
                ->where('inst_id', $this->session->userdata('user_id'))
                ->get(TBL_USERS)
                ->row();

        return $row->brand == 0 ? FALSE : TRUE;
    }

    // User logout
    public function logout() {
        $this->session->unset_userdata('access_token');
        $this->session->unset_userdata('user_id');
    }
	
	// Email template
	public function get_email_template($post_id = 0, $ajax = FALSE){
		
		$email_data = $this->email_template_exists($post_id);
		
		// If message = 0 get default template
		if($email_data === 0){
			$message = 'Thanks for liking or commenting on this post. Your discount code is:<br /><br />' .
				'{coupon_code}<br /><br />' .
				'To use it, you need to show it when paying {brand} product/service.<br /><br />' .
				'Enoy and tell your friends!<br /><br />' .
				'BrandTap team<br />' .
				'Website: www.brandtap.co<br />' .
				'Email: info@brandtap.co';
			$subject = 'Hi {name}';
			$status = 0;
			$code_lenght = 8;
			$template_custom = 0;
		} else {
			$message = $email_data->template;
			$subject = $email_data->subject;
			$status = $email_data->sending_status;
			$code_lenght = $email_data->code_lenght;
			$template_custom = 1;
		}
		
		$data = array(
			'message' => $message,
			'subject' => $subject,
			'status' => $status,
			'code_lenght' => $code_lenght,
			'template_custom' => $template_custom
		);
		
		// Check if ajax call
		if($ajax){
			return json_encode($data);
		} else {
			return $data;
		}
	}
	
	// Check if email template exists
	public function email_template_exists($post_id){
		
		$row = $this->db->select('template,subject,sending_status,code_lenght')
			->where('post_id', $post_id)
			->get(TBL_EMAIL_TEMPLATE)
			->row();
		
		return isset($row->template) ? $row : 0;
	}

	// Save new template
	public function save_email_template($message, $post_id, $subject, $code_lenght){
		
		// Prepare data
		$data = array(
			'post_id' => $post_id,
			'template' => $message,
			'subject' => $subject,
			'code_lenght' => $code_lenght
		);
		
		if($this->email_template_exists($post_id) === 0){
			$this->db->insert(TBL_EMAIL_TEMPLATE, $data);	
		} else {
			$this->db->where('post_id', $post_id);
        	$this->db->update(TBL_EMAIL_TEMPLATE, $data);
		}
	}
	
	// Activate free user with code
	public function free_code_activation(){
		
		$this->load->helper('form');
		
		$code = $this->input->post('free_code');
		$stored_code = $this->get_free_code();
		
		if($stored_code != null && $stored_code->data === $code){
			$this->activate_free_user();
			redirect('user/profile');
		} else {
			redirect('user/subscribe');
		}
	}
	
	// Get free code
	public function get_free_code(){
		$row = $this->db->select('*')
			->where('name', 'free_code')
			->get(TBL_OPTIONS)
			->row();
			
		return isset($row->name) ? $row : null;
	}
	
	// Activate free user
	public function activate_free_user(){
		
		$user_id = $this->session->userdata('user_id');
		$update = array(
			'payment_profile_id' => 'Free'
		);
		$this->db->where('inst_id', $user_id);
		$this->db->update(TBL_USERS, $update);
	}
	
	// Email sending status change
	public function email_sending_status_change($post_id, $status){
		
		$data = array(
			'post_id' => $post_id,
			'sending_status' => $status
		);
		
		if($this->email_template_exists($post_id) === 0){
			$email_data = $this->get_email_template($post_id);
			$data['template'] = $email_data['message'];
			$data['subject'] = $email_data['subject'];
			$data['code_lenght'] = $email_data['code_lenght'];
			$this->db->insert(TBL_EMAIL_TEMPLATE, $data);
		} else {
			$this->db->where('post_id', $post_id);
        	$this->db->update(TBL_EMAIL_TEMPLATE, $data);
		}
	}
	
	// Database update 2015-01-30
	public function update_2015_01_30($post_id, $user_inst_id, $type){
		
		$data = array(
			'type' => $type
		);
		
		$this->db->where('post_id', $post_id);
		$this->db->where('inst_id', $user_inst_id);
		$this->db->update(TBL_POST_WINNERS, $data);
	}

}

?>
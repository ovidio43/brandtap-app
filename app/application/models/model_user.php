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
            'email' => '',
            'access_token' => $data->access_token
        );

        $this->db->insert(TBL_USERS, $user_data);
    }
	
	// Create user from brand site
	public function create_user_from_brand_site($data){
		$user_data = array(
			'username' => $data['username'],
			'inst_id' => $data['inst_id'],
			'image_url' => $data['image_url'],
			'from_brand' => $data['from_brand'],
			'email' => $data['email'],
			
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

        $this->load->library('form_validation');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');

        if ($this->form_validation->run() !== FALSE){
            $this->add_email($email);
			$this->send_welcome_email($email);
			$this->add_new_brands_count(1);	
            redirect('user/profile');
        } else {
            redirect('user/register_email?error=Invalid+email+address');
        }
    }
	
	public function add_new_brands_count($count){
		
		$row = $this->db->select('*')
				->where('name', 'new_users_emails_sent')
				->get(TBL_OPTIONS)
				->row();
		
		if(isset($row->name)){
			$update = array(
				'data' => $count + $row->data
			);
			
			$this->db->where('name', 'new_users_emails_sent');
			$this->db->update(TBL_OPTIONS, $update);
			
		} else {
			$insert = array(
				'name' => 'new_users_emails_sent',
				'data' => $count
			);
			
			$this->db->insert(TBL_OPTIONS, $insert);
		}
	}

    public function send_welcome_email($email)
    {
        $to = $email;
        $subject = WELCOME_EMAIL_SUBJECT;
        //$message = "<p>Hi</p><p>Welcome to the best online tool ever... TESTING...</p>";
        $message = $this->load->view('email/email_welcome', null, TRUE);

        $mail_success = send_email($to,$subject,$message,'');
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
    private function add_email($email) {
        $data = array(
            'email' => $email,
            'brand' => 1
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
    public function get_all_brands_inst_id() {
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
	
	// Get all brends
	public function get_all_brands(){
		$brands = array();

        $this->db->select('id,username');
        $this->db->from(TBL_USERS);
        $this->db->where('active', 1);
        $this->db->where('brand', 1);
        $query = $this->db->get();

        foreach ($query->result() as $row) {
            $brands[$row->id] = $row->username;
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
    public function add_new_winners($users, $post_id, $brand_name, $test = FALSE, $user_data = array(), $brand) {
        foreach ($users as $inst_id) {
        	
			// Get email data
            $email_data = $this->get_email_template($post_id);
			
			$sending_status = 0;

			$user = $this->get_user($inst_id);
				
			if(!$test){
				$code = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $email_data['code_lenght']);
					
				// Subject
				$subject = str_replace('{name}', $user->username, $email_data['subject']);
				$subject = str_replace('{brand}', $brand->username, $subject);
			
				// Message
				$message = str_replace('{name}', $user->username, $email_data['message']);
				$message = str_replace('{coupon_code}', $code, $message);
				$message = str_replace('{brand}', $brand->username, $message);
			} else {
				$message = $email_data['message'];
				$subject = $email_data['subject'];
			}

			$to = $user->email;

			if($email_data['status'] == 1 || $test){
				if($test || $brand->email_frequency == 1){
					$mail_success = send_email($to,$subject,$message,$brand);
           			log_message('error', 'mail success=' . print_r($mail_success, 1));
					$sending_status = 1;	
				} else {
					$sending_status = 0;
				}	
			}
			
			if(!$test){
				$data = array(
                	'post_id' => $post_id,
                	'inst_id' => $inst_id,
                	'code' => $code,
                	'type' => $user_data[$user->inst_id],
                	'sent_status' => $sending_status
            	);

            	$this->db->insert(TBL_POST_WINNERS, $data);	
			}
        }
    }

    // Get user by instagram id
    public function get_user($inst_id) {
        $row = $this->db->select('*')
                ->where('inst_id', $inst_id)
                ->get(TBL_USERS)
                ->row();

        return isset($row->username) ? $row : null;
    }
	
	// Get user by username
	public function get_user_by_username($username){
		$row = $this->db->select('*')
				->where('username', $username)
				->get(TBL_USERS)
				->row();
		
		return isset($row->username) ? $row : null;
	}

    // Check if user is brand
    public function get_user_by_id($id) {

        $row = $this->db->select('*')
                ->where('id', $id)
                ->get(TBL_USERS)
                ->row();

        return isset($row->username) ? $row : null;
    }

    // User logout
    public function logout() {
        $this->session->unset_userdata('access_token');
        $this->session->unset_userdata('user_id');
    }
	
	// Email template
	public function get_email_template($post_id = 0, $ajax = FALSE){
		
		$email_data = $this->email_template_exists($post_id);
		
		$message_custom = '<p>Thanks for liking or commenting on this post. Your discount code is:<br /><br />' .
				'{coupon_code}<br /><br />' .
				'To use it, you need to show it when paying {brand} product/service.<br /><br />' .
				'Enoy and tell your friends!<br /><br />' .
				'BrandTap team<br />' .
				'Website: www.brandtap.co<br />' .
				'Email: info@brandtap.co</p>';
		
		// If message = 0 get default template
		if($email_data === 0){
			$message = $message_custom;
			$subject = 'Hi {name}';
			$status = 0;
			$code_lenght = 8;
		} else {
			$message = $email_data->template;
			$subject = $email_data->subject;
			$status = $email_data->sending_status;
			$code_lenght = $email_data->code_lenght;
		}
		
		if(strcmp($message, $message_custom) == 0){
			$template_custom = 0;
		} else {
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
	
	// Send emails to users that are in database but didn't get email
	public function send_emails_that_are_not_sent($post_id, $brand, $brand_data){
		
		$email_data = $this->get_email_template($post_id);
		
		if($email_data['status'] == 1){
			$this->db->select(TBL_POST_WINNERS . '.*,' . TBL_USERS . '.email,' . TBL_USERS . '.username');
			$this->db->from(TBL_POST_WINNERS . ',' . TBL_USERS);
			$this->db->where(TBL_POST_WINNERS . '.post_id', $post_id);
			$this->db->where(TBL_POST_WINNERS . '.sent_status', 0);
			$this->db->where(TBL_USERS . '.inst_id = ' . TBL_POST_WINNERS . '.inst_id');
		
			$query = $this->db->get();
		
			foreach($query->result() as $row){
				$data = array(
					'sent_status' => 1
				);
			
				$this->db->where('post_id', $post_id);
				$this->db->where('inst_id', $row->inst_id);
				$this->db->update(TBL_POST_WINNERS, $data);
			
				// Subject
				$subject = str_replace('{name}', $row->username, $email_data['subject']);
				$subject = str_replace('{brand}', $brand, $subject);
			
				// Message
				$message = str_replace('{name}', $row->username, $email_data['message']);
				$message = str_replace('{coupon_code}', $row->code, $message);
				$message = str_replace('{brand}', $brand, $message);
			
				$to = $row->email;
			
				$mail_success = send_email($to,$subject,$message,$brand_data);
           		log_message('error', 'mail success=' . print_r($mail_success, 1));
			}
		}
	}

	// Add post details if post is not in database
	public function add_post($data, $brand_id){
		
		// Check if post exists
		$exists = $this->post_exists($data->id);
		
		if(!$exists){
			$hashtags = '';
			foreach($data->tags as $tag){
				$hashtags .= $tag . ',';
			}
			$hashtags = substr_replace($hashtags, "", -1);
			
			$post_details = array(
				'post_id' => $data->id,
				'brand_id' => $brand_id,
				'image_url' => $data->images->low_resolution->url,
				'caption' => $data->caption != null ? $data->caption->text : null,
				'link' => $data->link,
				'hashtags' => $hashtags,
				'date' => date('Y-m-d H:i:s', $data->created_time),
				'likes' => $data->likes->count,
				'comments' => $data->comments->count
			);
			
			$this->db->insert(TBL_POST_DETAILS, $post_details);
		} else {
			$post_details = array(
				'likes' => $data->likes->count,
				'comments' => $data->comments->count
			);
			
			$this->db->where('post_id', $data->id);
			$this->db->update(TBL_POST_DETAILS, $post_details);
		}
	}
	
	// Check if post details exists in database
	private function post_exists($post_id){
		
		$this->db->from(TBL_POST_DETAILS);
		$this->db->where('post_id', $post_id);
		
		if($this->db->count_all_results() == 0){
			return FALSE;
		}
		
		return TRUE;
	}
	
	// Set frequency for brand
	public function set_frequency($brand_id){
		
		$frequency = $this->input->post('frequency');
		
		$update = array(
			'email_frequency' => $frequency
		);
		
		$this->db->where('id', $brand_id);
		$this->db->update(TBL_USERS, $update);
		
		if($frequency > 1){
			$this->set_frequency_time($brand_id);
		}
	}
	
	// Set frequency time
	private function set_frequency_time($brand_id){
		
		$data = array(
			'date' => date('Y-m-d H:i:s')
		);
		
		if(!$this->frequency_time_exists($brand_id)){
			$data['brand_id'] = $brand_id;
			$this->db->insert(TBL_EMAIL_FREQUENCY, $data);
		} else {
			$this->db->where('brand_id', $brand_id);
			$this->db->update(TBL_EMAIL_FREQUENCY, $data);
		}
	}
	
	// Check if brand have frequency time in database
	private function frequency_time_exists($brand_id){
		
		$this->db->from(TBL_EMAIL_FREQUENCY);
		$this->db->where('brand_id', $brand_id);
		
		if($this->db->count_all_results() == 0){
			return FALSE;
		}
		
		return TRUE;
	}
	
	// Get frequency time
	public function get_frequency_time($brand_id){
		
		$row = $this->db->select('*')
				->where('brand_id', $brand_id)
				->get(TBL_EMAIL_FREQUENCY)
				->row();
				
		return isset($row->brand_id) ? $row : null;
	}
	
	// Get multi email template
	public function get_multi_email_template($brand_id){
		
		$template = $this->multi_email_template_exists($brand_id);
		
		if($template === null){
			$subject = 'Hi {user}';
			$body = '<p>' .
					'Hi {user}!<br />' .
					'You liked {post-number} of brand {brand}. Here you can see what you liked:<br /><br />' .
					'{post-list}<br /><br />' .
					'Best regards<br />' .
					'{brand}<br />' .
					'{brand-email}<br />' .
					'</p>';
			$post_list = '<p>' .
						'<div style="float: left; margin: 10px;">' .
							'{post-image}' .
						'</div>' .
						'<div style="float: left; margin: 10px;">' .
							'<div>' .
								'<p>{post-date}</p>' .
								'<p>{post-caption}</p>' .
							'</div>' .
						'</div>' .
						'<div style="float: left; margin: 10px;">' .
							'<p>{post-url}</p>' .
						'</div>' . 
						'<p/>' .
						'<div style="clear: both></div>"';
		} else {
			$subject = $template->subject;
			$body = $template->template;
			$post_list = $template->post_list_template;
		}
		
		$data = array(
			'subject' => $subject,
			'body' => $body,
			'post_list' => $post_list
		);
		
		return $data;
	}
	
	// Check if multi email template exists
	private function multi_email_template_exists($brand_id){
		
		$row = $this->db->select('*')
				->where('brand_id', $brand_id)
				->get(TBL_EMAIL_MULTI_TEMPLATE)
				->row();
				
		return isset($row->brand_id) ? $row : null;
	}

	// Save multi email template
	public function save_multi_email_template($brand_id, $subject, $email_body, $post_list_template){
		
		$template = $this->multi_email_template_exists($brand_id);
		
		$data = array(
			'subject' => $subject,
			'template' => $email_body,
			'post_list_template' => $post_list_template
		);
		
		if($template === null){
			$data['brand_id'] = $brand_id;
			$this->db->insert(TBL_EMAIL_MULTI_TEMPLATE, $data);
		} else {
			$this->db->where('brand_id', $brand_id);
			$this->db->update(TBL_EMAIL_MULTI_TEMPLATE, $data);
		}
	}
	
	// Prepare and send frequency email
	public function prepare_frequency_emails($brand){
		
		$time = $this->get_frequency_time($brand->id);
		
		if($time !== null){
			$start_date = strtotime($time->date);
			$end_date = strtotime(date('Y-m-d H:i:s', 
				strtotime($time->date . '+' . ($brand->email_frequency == 2 ? '1 days' : ($brand->email_frequency == 3 ? '7 days' : '0 days')))));
			if($start_date < $end_date){
				if($end_date < strtotime(date('Y-m-d H:i:s'))){
				
					$posts_between = $this->get_posts_between(date('Y-m-d H:i:s', $start_date), date('Y-m-d H:i:s', $end_date), $brand->inst_id);
					
					if(count($posts_between) > 0){
						$this->send_multi_part_email($posts_between, $brand);	
					}
					
					// Update time
					$this->set_frequency_time($brand->id);
				}	
			}
		}
	}
	
	// Get all posts that are liked or commented between dates
	private function get_posts_between($start_date, $end_date, $brand_id){
		
		$result = array();
		
		$this->db->select(TBL_POST_DETAILS . '.post_id,' . TBL_POST_DETAILS . '.image_url,' . TBL_POST_DETAILS . '.caption,' .
						TBL_POST_DETAILS . '.link,' . TBL_POST_DETAILS . '.date,' . TBL_POST_WINNERS . '.inst_id,' . TBL_POST_WINNERS . '.type');
		$this->db->from(TBL_POST_DETAILS);
		$this->db->join(TBL_POST_WINNERS, TBL_POST_DETAILS . '.post_id = ' . TBL_POST_WINNERS . '.post_id');
		$this->db->where(TBL_POST_DETAILS . '.brand_id', $brand_id);
		$this->db->where(TBL_POST_WINNERS . '.created <', $end_date);
		$this->db->where(TBL_POST_WINNERS . '.created >', $start_date);
		$query = $this->db->get();
		
		foreach($query->result() as $row){
			if(!array_key_exists($row->inst_id, $result)){
				$result[$row->inst_id] = array();
			} 
			array_push($result[$row->inst_id], array(
				'post_id' => $row->post_id,
				'image_url' => $row->image_url,
				'caption' => $row->caption,
				'link' => $row->link,
				'type' => $row->type,
				'date' => $row->date
			));
		}
		
		return $result;
	} 

	// Send milti part email
	private function send_multi_part_email($data, $brand){
		
		$email_template = $this->get_multi_email_template($brand->id);
		
		foreach($data as $key => $value){
			
			$user = $this->get_user($key);
			
			$to = $user->email;
			
			$subject = str_replace('{user}', $user->username, $email_template['subject']);
			
			$email_body = str_replace('{user}', $user->username, $email_template['body']);
			$email_body = str_replace('{brand}', $brand->username, $email_body);
			$email_body = str_replace('{brand-email}', $brand->email, $email_body);
			$email_body = str_replace('{post-number}', count($value), $email_body);
			
			$post_list = '';
			
			foreach($value as $post){
				
				$image = '<img src="' . $post['image_url'] . '" />';
				$url = '<a href="' . $post['link'] . '">View on Instagram</a>';
				
				$temp = str_replace('{post-image}', $image, $email_template['post_list']);
				$temp = str_replace('{post-date}', $post['date'], $temp);
				$temp = str_replace('{post-caption}', $post['caption'], $temp);
				$temp = str_replace('{post-url}', $url, $temp);
				
				$post_list .= $temp;
			}
			
			$message = str_replace('{post-list}', $post_list, $email_body);
			
			$mail_success = send_email($to,$subject,$message,$brand);
           	log_message('error', 'mail success=' . print_r($mail_success, 1));
		}
	}

}

?>
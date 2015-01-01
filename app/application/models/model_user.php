<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Model_user extends CI_Model
{
	public function __construct()
	{
		
	}
	
	// Check if user exists if no create new user
	public function user_exists_or_create($data)
	{
		$this->db->from(TBL_USERS);	
		$this->db->where('inst_id', $data->user->id);
		
		if($this->db->count_all_results() == 0){
			$this->create_user($data);
			return FALSE;	
		} else {
			$email = $this->get_user_email();
			if($email == ''){
				return FALSE;
			} else {
				return $this->is_user_active($this->session->userdata('user_id'));
			}
		}
		
		return TRUE;
	}
	
	// Create new user
	private function create_user($data)
	{
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
	public function get_user_email($inst_id = 0)
	{
		$row = $this->db->select('email')
			->where('inst_id', $inst_id != 0 ? 
				$inst_id : $this->session->userdata('user_id'))
			->get(TBL_USERS)
			->row();
			
		return $row->email;
	}
	
	// Check if user activated account
	public function is_user_active($inst_id)
	{
		$row = $this->db->select('active')
			->where('inst_id', $inst_id)
			->get(TBL_USERS)
			->row();
		
		return $row->active == 0 ? FALSE : TRUE;
	}
	
	// Send activation link
	public function send_activation_link()
	{
		// Load form helper
		$this->load->helper('form');
		
		// Check email
		$brand = $this->input->post('brand') == 1 ? 1 : 0;
		$email = $this->input->post('email');
		$regex = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/';
		
		if(preg_match($regex, $email)){
			
			// Activation code
			//$code = md5($email . $this->session->userdata('user_id'));
			
			// Send activation link to user
			//$header = "From: BrandTap";
		
			//$to = $email;
			//$subject="Your confirmation link here";
		
			// Email message
			//$message="Your Comfirmation link \r\n";
			//$message.="Click on this link to activate your account \r\n";
			//$message.= site_url('user/email_activation/' . $code);
		
			//mail($to,$subject,$message,$header);
			
			// Add email to database
			$this->add_email($email, $brand);
			
			if($brand == 1){
				redirect('user/profile');
			} else {
				redirect('user/registration_finished');
			}
			// Add activation code for user
			//$this->add_activation_code($code);
			
			//return $data = array('email' => $email);
		} else {
			redirect('user/register_email');
		}
	}
	
	// User activation
	public function activate_user($code)
	{
		$inst_id = 0;
		$this->db->from(TBL_ACTIVATION);	
		$this->db->where('code', $code);
		$query = $this->db->get();
		foreach($query->result() as $row){
			$inst_id = $row->inst_id;
		}
		
		if($inst_id == 0){
			if( ! $this->is_user_active($inst_id)){
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
	private function add_email($email, $brand = 0)
	{
		$data = array(
			'email' => $email,
			'brand' => $brand,
		);
		
		$this->db->where('inst_id', $this->session->userdata('user_id'));
		$this->db->update(TBL_USERS, $data);
	}

	// Add activation code
	private function add_activation_code($code)
	{
		$data = array(
			'inst_id' => $this->session->userdata('user_id'),
			'code' => $code
		);
		
		$this->db->insert(TBL_ACTIVATION, $data);
	}
	
	// Delete activation code
	private function delete_activation_code($code)
	{
		$this->db->where('code', $code);
		$this->db->delete(TBL_ACTIVATION);
	}
	
	// Is user logedin
	public function is_user_logedin()
	{
		return $this->session->userdata('user_id') > 0;
	}
	
	// Get all brands id-s
	public function get_all_brands()
	{
		$brands = array();
		
		$this->db->select('inst_id');
		$this->db->from(TBL_USERS);
		$this->db->where('active', 1);
		$this->db->where('brand', 1);
		$query = $this->db->get();
		
		foreach($query->result() as $row){
			$brands[] = $row->inst_id;
		}
		
		return $brands;
	}
	
	// Get asked users if they exists
	public function get_users_in($users)
	{
		$users_exist = array();
		
		if( ! is_array($users) || count($users) < 1){
			return $users_exist;
		}

		$this->db->select('inst_id');
		$this->db->from(TBL_USERS);
		$this->db->where_in('inst_id', $users);
		$query = $this->db->get();
		
		foreach($query->result() as $row){
			$users_exist[] = $row->inst_id;
		}
		
		return $users_exist;
	}
	
	// Get all users that got code for post
	public function get_post_winners($post_id)
	{
		$winners = array();
		
		$this->db->select('inst_id');
		$this->db->from(TBL_POST_WINNERS);
		$this->db->where('post_id', $post_id);
		$query = $this->db->get();
		
		foreach($query->result() as $row){
			$winners[] = $row->inst_id;
		}
		
		return $winners;
	}
	
	// Add new winners
	public function add_new_winners($users, $post_id, $brand)
	{
		foreach($users as $inst_id){
			
			$code = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 20);
			
			$data = array(
				'post_id' => $post_id,
				'inst_id' => $inst_id,
				'code' => $code
			);
			
			$this->db->insert(TBL_POST_WINNERS, $data);
			
			$email = $this->get_user_email($inst_id);
			
			// Send activation link to user
			/* MOVED TO lib_Helper !
				$header = "From: no-reply@balkanoutsource.com\r\n";
				$header .= "BCC: mrvica83mm@yahoo.com,triva89@yahoo.com\r\n";
				$header .= "MIME-Version: 1.0\r\n";
				$header .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
			*/
			$to = $email;
			$subject = "[BrandTap] Dear " . $this->get_username($inst_id) . " you got a discount for $brand";
		
			// Email message
			$message = "Thanks for liking or commenting on this post. Your discount code is:<br /><br />";
			$message .= $code . "<br /><br />";
			$message .= "To use it, you need to show it when paying " . $brand . " product/service.<br /><br />";
			$message .= "Enoy and tell your friends!<br /><br />";
			$message .= "BrandTap team<br />";
			$message .= "Website: www.brandtap.co<br />" ;
			$message .= "Email: info@brandtap.co";
		
			log_message('error', "$to , $subject, message , $header");

			$mail_success = send_mail($to,$subject,$message);

			log_message('error', 'mail success='.print_r($mail_success,1) );

			if($mail_success){
				return true;
			} 
			return false;
		}
	}
	
	// Get user username
	public function get_username($inst_id)
	{
		$row = $this->db->select('username')
			->where('inst_id', $inst_id)
			->get(TBL_USERS)
			->row();
		
		return isset($row->username) ? $row->username : null;
	}
	
	// Check if user is brand
	public function is_user_brand()
	{
		
		$row = $this->db->select('brand')
			->where('inst_id', $this->session->userdata('user_id'))
			->get(TBL_USERS)
			->row();
		
		return $row->brand == 0 ? FALSE : TRUE;
	}
	
	// User logout
	public function logout()
	{
		$this->session->unset_userdata('access_token');
		$this->session->unset_userdata('user_id');
	}
}

?>
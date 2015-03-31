<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cron extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
		
		// Load models
		$this->load->model('model_instagram');
		$this->load->model('model_user');
		$this->load->model('model_logs');
		$this->load->model('model_paypal');
	}
	
	public function index()
	{
		ob_start();
		$post_number = 0;
			
		// Get all brands
		$brands = $this->model_user->get_all_brands_inst_id();
		
		$cnt = 0;
		foreach($brands as $brand_id){
			
			// Get all posts for brend
			$posts = $this->model_instagram->_user_recent_media($brand_id, 90);
			$brand_data = $this->model_user->get_user($brand_id);
			
			$pagination = TRUE;
			$data = array();
			
			if( ! isset($posts->data) or ! is_array($posts->data)){
			
				while($pagination){
					foreach($posts->data as $post){
	
						
						$this->model_user->add_post($post, $brand_id);
// for debug only! Do not leave this active!
//$this->model_logs->log('cron', "Post: " . print_r($post,1) );		
				
					// Get all users that are commented or liked post
						$comment_users = array();
						$like_users = array();
						if($post->comments->count > 0){
							$comments = $this->model_instagram->_media_comments_list($post->id, $brand_id);
						
							foreach($comments->data as $comments_data){
								$comment_users[] = $comments_data->from->id;
								$data[$comments_data->from->id] = 'C';
							}
						}
						if($post->likes->count > 0){
							$like = $this->model_instagram->_media_likes_list($post->id, $brand_id);
							
							foreach($like->data as $like_data){
								$like_users[] = $like_data->id;
								$data[$like_data->id] = 'L';
							}
						}
					
						$post_number += count($posts);
					
						// Merge users
						$all_users = array_merge($comment_users, $like_users);
					
						// Remove duplicates
						$distinct_users = array_unique($all_users);
						
						if(count($distinct_users) > 0){
							// Get users that are registered
							$registered_users = $this->model_user->get_users_in($distinct_users);
					
							// Get users that recived code for this post
							$code_recived = $this->model_user->get_post_winners($post->id);
					
							// Keep only users that didn't recive code
							$new_users_brand = array_diff($registered_users, $code_recived);
						
							// If brand commented or liked post remove him
							$new_users = array_diff($new_users_brand, array($post->user->id));
					
							// Send email and add them to database 
							$this->model_user->add_new_winners($new_users, $post->id, $post->user->full_name, FALSE, $data, $brand_data);
	
							$cnt += count($new_users);	
						}
						
						if($brand_data->email_frequency == 1){
							$this->model_user->send_emails_that_are_not_sent($post->id, $post->user->full_name, $brand_data);	
						}
						
					}
					$posts = $this->model_instagram->pagination($posts, 90);
					if(!(is_object($posts) === TRUE)){
						$pagination = FALSE;
					}
				}
			}
	
			if($brand_data->email_frequency == 2 || $brand_data->email_frequency == 3){
				$this->model_user->prepare_frequency_emails($brand_data);
			}
		}
		$this->model_logs->log('cron', "Posts with #brandTap found:" . $post_number );
		$this->model_logs->log('cron', "new winners: $cnt" );	

		$ob = ob_get_contents();

		$this->model_logs->log('cron', "OB: $ob");		



	}
	
}

?>
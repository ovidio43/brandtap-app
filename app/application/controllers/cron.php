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
	}
	
	public function index()
	{
		ob_start();
		$data = array();
		
		// Get all post with brandtap tag
		$posts = $this->model_instagram->_recent_media_by_tag("brandtap");
$this->model_logs->log('cron', "Posts with #brandTap found:" . count($posts->data) );		
		// Get all brands
		//$brands = $this->model_user->get_all_brands();
		
		$cnt = 0;
		foreach($posts->data as $post){


// for debug only! Do not leave this active!
//$this->model_logs->log('cron', "Post: " . print_r($post,1) );		
			
			// Check if user that posted is brand
			//if(in_array($post->user->id, $brands)){
				
				// Get all users that are commented or liked post
				$comment_users = array();
				$like_users = array();
				if($post->comments->count > 0){
					foreach($post->comments->data as $user){
						$comment_users[] = $user->from->id;
					}
				}
				if($post->likes->count > 0){
					foreach($post->likes->data as $user){
						$like_users[] = $user->id;
					}
				}
				
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
					$this->model_user->add_new_winners($new_users, $post->id, $post->user->full_name);

					$cnt += count($new_users);	
				}
			//}
		}
		$this->model_logs->log('cron', "new winners: $cnt" );		

		$ob = ob_get_contents();

		$this->model_logs->log('cron', "OB: $ob");		



	}
}

?>
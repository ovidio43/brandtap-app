<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Model_instagram extends CI_Model {

    private $_clientID = '8369ea9d5dfa49c28dd6e93f20920f09';
    private $_clientSecret = 'a7e1cf8a344340199e63a91fcec7bdf3';
    private $_callBackURL = INSTAGRAM_API_REDIRECT_URI;
    private $_scopes = array('basic', 'likes', 'comments', 'relationships');
    private $_actions = array('follow', 'unfollow', 'block', 'unblock', 'approve', 'deny');

    public function __construct() {
        $this->load->model('model_logs');
    }

    // Return OAuth login URL 
    public function get_login_url($scope = array('basic')) {
        if (is_array($scope) && count(array_intersect($scope, $this->_scopes)) === count($scope)) {
            return INSTAGRAM_API_OAUTH_URL . '?client_id=' . $this->_clientID . '&redirect_uri=' .
                    urlencode($this->_callBackURL) . '&scope=' . implode('+', $scope) . '&response_type=code';
        } else {
            // Log error - Parameter is not array or unidentified permissions
        }
    }

    // Get user data 
    public function get_user_data() {
        $code = $this->session->userdata('code');
        if (isset($code)) {
            $user_data = $this->get_oauth_token($code);
            $this->session->unset_userdata('code');

            if ($user_data == null || !isset($user_data->access_token) || !isset($user_data->user->id)) {
                log_message('error', "code=$code , get_user_data() > data=" . print_r($user_data, 1));
                redirect('');
            }
			
			$acces_token = $this->get_access_token();
			$insert = array(
					'name' => 'access_token_instagram',
					'data' => $user_data->access_token
				);
			
			if($acces_token === null){
				$this->db->insert(TBL_OPTIONS, $insert);
			} else {
				$this->db->where('name', 'access_token_instagram');
        		$this->db->update(TBL_OPTIONS, $insert);
			}

            $this->session->set_userdata('access_token', $user_data->access_token);
            $this->session->set_userdata('user_id', $user_data->user->id);
            return $user_data;
        }

        return null;
    }

    // Get OAuth data of user by returned callback code
    private function get_oauth_token($code) {
        $data = array(
            'grant_type' => 'authorization_code',
            'client_id' => $this->_clientID,
            'client_secret' => $this->_clientSecret,
            'redirect_uri' => $this->_callBackURL,
            'code' => $code
        );

        return $this->_OAuth_call($data);
    }

    // OAuth call for access token
    private function _OAuth_call($data) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, INSTAGRAM_API_OAUTH_TOKEN_URL);
        curl_setopt($ch, CURLOPT_POST, count($data));
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            // Log error - CURL error - curl_error($ch)
        }
        curl_close($ch);

        return json_decode($result);
    }

    // API call function
    private function _API_call($function, $authenticated = FALSE, $params = null, $method = 'GET') {
        // Check if the call requires authentication
        $authentication_method = '';
        if ($authenticated === FALSE) {
            $authentication_method = '?client_id=' . $this->_clientID;
        } else {
            
			$access_token = $this->get_access_token();
            if (isset($access_token)) {
                $authentication_method = '?access_token=' . $access_token->data;
            } else {
                // Log error - this method requires an authenticated users access token
            }
        }

        // Check if there is aditional request parameters
        $paramString = '';
        if (isset($params) && is_array($params)) {
            $paramString = '&' . http_build_query($params);
        }

        // Prepare URL for API call
        $api_url = INSTAGRAM_API_URL . $function . $authentication_method .
                (('GET' === $method) ? $paramString : '');

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $api_url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json'));
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            // Log error - CURL error - curl_error($ch)
        }
        curl_close($ch);

        return json_decode($result);
    }

    // Get recent media by tag 
    public function _recent_media_by_tag($name, $limit = 0) {
        return $this->_API_call('tags/' . $name . '/media/recent', FALSE);
    }

    // Get user recent media
    private function _user_recent_media($id = 'self', $limit = 0) {
        return $this->_API_call('users/' . $id . '/media/recent', ($id === 'self'), array('count' => $limit));
    }
	
	// Get likes for media
	public function _media_likes_list($id){
		return $this->_API_call('media/' . $id . '/likes', true);
	}

	// Get comments for media
	public function _media_comments_list($id){
		return $this->_API_call('media/' . $id . '/comments', false);
	}

    // Get and prepare user recent media
    public function get_user_recent_media($limit = 0, $brand = FALSE, $user_name) {
        // If user is brand get his posts else get by tag
        if ($brand) {
            $data = $this->_user_recent_media($this->session->userdata('user_id'), $limit);
        } else {
            $data = $this->_recent_media_by_tag('brandtap');
        }

        $output = array();

        if( ! isset($data->data) or ! is_array($data->data)){
            return $output;
        }

        // Prepare data for page
        foreach ($data->data as $row) {

            // Check if user is brand
            if ($brand) {

                foreach ($row->tags as $tag) {
                    if ($tag === 'brandtap') {
						
						$like_comments_winers_data = $this->get_winners($row->id);
						
                        $output[] = array(
                            'image' => $row->images->low_resolution->url,
                            'date' => date('m-d-Y g:i a', $row->created_time),
                            'caption' => $row->caption->text,
                            'link' => $row->link,
                            'hashtags' => $row->tags,
                            'winners' => $like_comments_winers_data['winners'],
                            'likes' => $like_comments_winers_data['like'] . " Likes",
                            'comments' => $like_comments_winers_data['commnets'] . " Comments",
                            'id' => $row->id,
                            'sending_status' => $this->sending_status($row->id)
                        );
                    }
                }
            } else {

                // If found in coments
                $found = FALSE;

                // Check if there are comments
                if ($row->comments->count > 0) {
                    foreach ($row->comments->data as $user_data) {

                        // Check if user is in comments
                        if ($this->session->userdata('user_id') == $user_data->from->id) {
                            $output[] = array(
                                'image' => $row->images->thumbnail->url,
                                'date' => date('m-d-Y g:i a', $row->created_time),
                                'caption' => $row->caption->text,
                                'link' => $row->link,
                                'hashtags' => $row->tags,
                                'winners' => $this->get_winning_code_for_post($row->id, $user_data->from->id),
                                'likes' => $row->likes->count . " Likes",
                                'comments' => $row->comments->count . " Comments",
                                'id' => $row->id,
                                'sending_status' => $this->sending_status($row->id)
                            );
                            $found = TRUE;
                            break;
                        }
                    }
                }

                // If user didn't commented check if there is likes
                if (!$found) {
                    if ($row->likes->count > 0) {
                        foreach ($row->likes->data as $user_data) {

                            // Check if user liked post
                            if ($this->session->userdata('user_id') == $user_data->id) {
                                $output[] = array(
                                    'image' => $row->images->thumbnail->url,
                                    'date' => date('m-d-Y g:i a', $row->created_time),
                                    'caption' => $row->caption->text,
                                    'link' => $row->link,
                                    'hashtags' => $row->tags,
                                    'winners' => $this->get_winning_code_for_post($row->id, $user_data->id),
                                    'likes' => $row->likes->count . " Likes",
                                    'comments' => $row->comments->count . " Comments",
                                    'id' => $row->id,
                                    'sending_status' => $this->sending_status($row->id)
                                );
                                break;
                            }
                        }
                    }
                }
            }
        }

        return $output;
    }

    // Get post winners
    public function get_winners($post_id) {
        $this->db->select(TBL_USERS . '.username,' . TBL_USERS . '.email,' . TBL_POST_WINNERS . '.code,' . TBL_POST_WINNERS . '.type');
        $this->db->from(TBL_USERS);
        $this->db->join(TBL_POST_WINNERS, TBL_POST_WINNERS . '.inst_id = ' . TBL_USERS . '.inst_id');
        $this->db->where(TBL_POST_WINNERS . '.post_id', $post_id);
        $query = $this->db->get();

        $winners = array();
		$like = 0;
		$comments = 0;
		$i = 0;
		$j = 0;
        foreach ($query->result() as $row) {
        	$data = array(
        		'username' => '@' . $row->username . '<br />',
        		'code' => '<span data-toggle="tooltip" data-placement="top" title="' . $row->code . '">' . (substr($row->code, 0, 12)) . 
        			'...</span><br />'
			);
        	$i++;
        	if($this->session->userdata('subscription_status') == 'Inactive' && $i <= EMAIL_PREVIEW_FREE_LIMIT){
				$data['email'] = $row->email . '<br />';
				if($j == 0 && $i == EMAIL_PREVIEW_FREE_LIMIT){
					$data['email'] .= '<br />'
                        . 'As a FREE user you are limited to see only '
                        . EMAIL_PREVIEW_FREE_LIMIT.' email(s). To view all of them, please subscribe.<br>'
                        . '<a href="' . site_url('user/subscribe') . '">'
                        . '<b>Subscribe</b></a>';
					$j++;
				}
        	} else if($this->session->userdata('subscription_status') == 'Active'){
        		$data['email'] = $row->email . '<br />';
        	}
            $winners[] = $data;
			if($row->type == 'C'){
				$comments++;
			} else if($row->type == 'L'){
				$like++;
			}
        }
		
		if(empty($winners)){
			$winners[] = array(
				'username' => '',
				'code' => '',
				'email' => ''
			);
		}

        return array(
        	'winners' => $winners,
        	'like' => $like,
        	'commnets' => $comments
		);
    }

    // Get winning code for user
    public function get_winning_code_for_post($post_id, $user_id) {
        $this->db->select('code');
        $this->db->from(TBL_POST_WINNERS);
        $this->db->where('post_id', $post_id);
        $this->db->where('inst_id', $user_id);
        $query = $this->db->get();

        $code = '';
        foreach ($query->result() as $row) {
            $code = $row->code;
        }

        return $code;
    }
	
	// Return access token if exists
	private function get_access_token(){
		
		$row = $this->db->select('*')
			->where('name', 'access_token_instagram')
			->get(TBL_OPTIONS)
			->row();
			
		return isset($row->name) ? $row : null;
	}

	// Check for email template
	private function email_template_exists($post_id){
		$row = $this->db->select('*')
			->where('post_id', $post_id)
			->get(TBL_EMAIL_TEMPLATE)
			->row();
			
		return isset($row->sending_status) ? $row : FALSE;
	}
	
	private function sending_status($post_id){
		$template = $this->email_template_exists($post_id);
		
		if($template){
			return $template->sending_status == 1 ? TRUE : FALSE;
		} else {
			return FALSE;
		}
	}

}

?>
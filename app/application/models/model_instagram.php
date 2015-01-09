<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Model_instagram extends CI_Model {

    private $_clientID = 'f4355450f7174714886e39ccc4beef42';
    private $_clientSecret = '207c9b3eec124460970d51120bff7bdb';
    private $_callBackURL = 'http://brandtap.co/app';
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
            $access_token = $this->session->userdata('access_token');
            if (isset($access_token)) {
                $authentication_method = '?access_token=' . $access_token;
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

    // Get and prepare user recent media
    public function get_user_recent_media($limit = 0, $brand = FALSE, $user_name) {
        // If user is brand get his posts else get by tag
        if ($brand) {
            $data = $this->_user_recent_media('self', $limit);
        } else {
            $data = $this->_recent_media_by_tag('brandtap');
        }

        $output = array();
        // Prepare data for page
        foreach ($data->data as $row) {

            // Check if user is brand
            if ($brand) {

                foreach ($row->tags as $tag) {
                    if ($tag === 'brandtap') {

                        $output[] = array(
                            'image' => $row->images->thumbnail->url,
                            'date' => date('Y-m-d g:i a', $row->created_time),
                            'caption' => $row->caption->text,
                            'link' => $row->link,
                            'hashtags' => $row->tags,
                            'winners' => $this->get_winners($row->id),
                            'likes' => $row->likes->count . " Likes",
                            'comments' => $row->comments->count . " Comments",
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
                                'date' => date('Y-m-d g:i a', $row->created_time),
                                'caption' => $row->caption->text,
                                'link' => $row->link,
                                'hashtags' => $row->tags,
                                'winners' => $this->get_winning_code_for_post($row->id, $user_data->from->id),
                                'likes' => $row->likes->count . " Likes",
                                'comments' => $row->comments->count . " Comments",
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
                                    'date' => date('Y-m-d g:i a', $row->created_time),
                                    'caption' => $row->caption->text,
                                    'link' => $row->link,
                                    'hashtags' => $row->tags,
                                    'winners' => $this->get_winning_code_for_post($row->id, $user_data->id),
                                    'likes' => $row->likes->count . " Likes",
                                    'comments' => $row->comments->count . " Comments",
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
        $this->db->select(TBL_USERS . '.username,' . TBL_POST_WINNERS . '.code');
        $this->db->from(TBL_USERS);
        $this->db->join(TBL_POST_WINNERS, TBL_POST_WINNERS . '.inst_id = ' . TBL_USERS . '.inst_id');
        $this->db->where(TBL_POST_WINNERS . '.post_id', $post_id);
        $query = $this->db->get();

        $winners = array();
        foreach ($query->result() as $row) {
            $winners[] = '@' . $row->username . '(' . $row->code . ')';
        }

        return $winners;
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

}

?>
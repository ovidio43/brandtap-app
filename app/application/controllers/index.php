<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Index extends MY_Controller {

    public function __construct() {
        parent::__construct();
        // Load models
        $this->load->model('model_instagram');
        $this->load->model('model_user');
    }

    public function index() {
        if ($this->model_user->is_user_logedin()) {
            redirect('user/profile');
            return 1;
        }
        // Check if code is sent
        if (isset($_GET['code'])) {
            $this->session->set_userdata('code', $_GET['code']);
            redirect('user/profile');
        } else {
            $data['url'] = $this->model_instagram->get_login_url();
            $this->title = "BrandTap";
            $this->content = $this->view('index/index', $data);
            $this->_show();
        }
    }  
}

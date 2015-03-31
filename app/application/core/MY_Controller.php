<?php if (!defined('BASEPATH'))	exit('No direct script access allowed');

class MY_Controller extends CI_Controller
{
	public $content = "**no content**";
	
	public $title = "";
	public $document_title = "BrandTap";
	public $brand_id = 0;

	public function __construct()
	{
		parent::__construct();
		session_start();
		$this->load->model('model_user');
		$this->load->library('form_validation');
		$this->load->library('session');
    }
    
    public function _show()
    {
		if ($this->title != "") {
			$this->document_title = $this->title." | ".$this->document_title;
		}

		$loged_name = $this->model_user->get_user($this->session->userdata('user_id'));
		if(!isset($loged_name)){
			$loged_name = null;
		} else {
			$loged_name = $loged_name->username;
		}

	    $this->load->view('main', array('content'        => $this->content,
									    'title'          => $this->title,
									    'document_title' => $this->document_title,
									    'loged_name'     => $loged_name,
										'brand_id'       => $this->brand_id));
	}
	
	public function view($file, $data = array())
	{
		return $this->load->view($file, $data, true);
	}
	
	protected function limit_access()
	{
		if( ! isset($_SESSION['login'])){
			redirect('user/login');
		}
	}
}//class

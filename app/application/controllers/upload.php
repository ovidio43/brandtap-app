<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class upload extends MY_Controller{
	
	private $path;
	public function __construct(){
		parent::__construct();
		
		// Load models
		$this->load->model('model_upload');
	}
	
	public function upload_image($id){
		
		echo $this->model_upload->upload_file($this->input->get('CKEditor'), $this->input->get('CKEditorFuncNum'),
			$this->input->get('langCode'));
	}
}

?>
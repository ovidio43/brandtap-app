<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Model_upload extends CI_Model{
	
	public function __construct(){
		$this->load->helper(array('form', 'url'));
	}
	
	// Upload file to server
	public function upload_file($CKEditor_param, $CKEditorFuncNum, $langCode_param){
		
		$funcNum = $CKEditorFuncNum;
		$CKEditor = $CKEditor_param;
		$langCode = $langCode_param;
		$message = '';
		$url = '';
		
		$user_id = $this->session->userdata('user_id');
		if(isset($_FILES['upload'])){
			
			$i = 0;
			$found = FALSE;
			while( ! $found){
				$result = glob('./img/' . $user_id . '/img_' . $i . '.*');
				if(empty($result)){
					$found = TRUE;
				} else {
					$i++;
				}
			}
			
			$file = file_get_contents('./img/' . $user_id . '/images_list.json');
			$data = json_decode($file);
			
			$config['upload_path'] = './img/' . $user_id . '/';
			$config['allowed_types'] = 'gif|jpg|png|jpeg';
			$config['max_size']	= '100';
			$config['max_width']  = '1024';
			$config['max_height']  = '768';
			
			$this->load->library('upload', $config);
			
			$image = array(
				'name' => 'img_' . $i . '.' . pathinfo($_FILES['upload']['name'], PATHINFO_EXTENSION),
				'type' => $_FILES['upload']['type'],
				'tmp_name' => $_FILES['upload']['tmp_name'],
				'error' => $_FILES['upload']['error'],
				'size' => $_FILES['upload']['size']
			);
			
			$image_data = array(
				'image' => base_url() . 'img/' . $user_id . '/img_' . $i . '.' . pathinfo($_FILES['upload']['name'], PATHINFO_EXTENSION),
				'thumb' => base_url() . 'img/' . $user_id . '/img_' . $i . '.' . pathinfo($_FILES['upload']['name'], PATHINFO_EXTENSION),
				'folder' => ''
			);
			$url = $image_data['image'];
			$_FILES['userfile'] = $image;
			unset($_FILES['upload']);
			
			if($this->upload->do_upload()){
				array_push($data, $image_data);
				file_put_contents('./img/' . $user_id . '/images_list.json', json_encode($data));
				$message = 'Success: Click ok to see image';
			} else {
				$message .= 'Error: Image need to be in gif,png,jpg or jpeg format or file was to big';
			}
		}
		
		return "<script type='text/javascript'>window.parent.CKEDITOR.tools.callFunction($funcNum, '$url', '$message');</script>";
	}

	public function create_upload_dir($user_id){
		if( ! file_exists('./img/' . $user_id . '/')){
			mkdir('./img/' . $user_id . '/', 0777);
			$fp = fopen('./img/' . $user_id . '/images_list.json', 'w');
			fwrite($fp, json_encode(array()));
			fclose($fp);
		}
	}
}

?>
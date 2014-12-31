<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Model_logs extends CI_Model
{
	public function __construct()
	{		
	}


	public function log($key, $dump)
	{
		$this->db->insert('logs', array(
			'key' => $key,
			'dump' => $dump
		));
	}
}
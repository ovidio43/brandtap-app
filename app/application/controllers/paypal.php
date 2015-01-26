<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class paypal extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('model_user');
		$this->load->model('model_paypal');
	}

	public function index()
	{
		if ($this->session->userdata('subscription_status') == 'Active') {
			$user = $this->model_user->get_user($this->session->userdata('user_id'));
			$data['_user'] = $user->payment_profile_id;
			$this->content = $this->load->view('paypal/status', $data, true);
			$this->_show();
		} else {
			redirect('paypal/pay');
		}
	}

	public function pay()
	{
		$paypal = $this->model_paypal->create_subscription();
		$data['description'] = $paypal->get_description();
		$data['details'] = $paypal->get_subscription_string();
		$data['pp_btn'] = $paypal->print_buy_button();

		$this->content = $this->load->view('paypal/pay', $data, true);
		$this->_show();
	}

	public function cancel($payment_profile_id=0)
	{
		if($payment_profile_id == 0) {
			$user = $this->model_user->get_user($this->session->userdata('user_id'));
			$payment_profile_id = $user->payment_profile_id;
		}
		$paypal = $this->model_paypal->create_subscription();
		$result = $paypal->manage_subscription_status( $payment_profile_id, 'Cancel');
		if ($result['ACK'] == 'Success') {
			$data['content'] = 'Your subscription is canceled';
		} else {
			$data['content'] = 'Error, canceling subscription failed. Please try again';
		}
		$this->session->set_userdata('subscription_status', $paypal->get_payment_status($profile_id));
		$this->content = $this->load->view('paypal/canceled', $data, true);
		$this->_show();
	}

	public function callback()
	{
		$result = $this->input->get('paypal');

		if ($result == 'cancel') {
			$data = array();
			$this->content = $this->load->view('paypal/cancel', $data, true);
			$this->_show();
		} else if ($result == 'paid') {
			$paypal = $this->model_paypal->create_subscription();
			$response = $paypal->start_subscription();
			if ($response['ACK'] == 'Success') {
				$this->model_paypal->update_payment_profile_id($response['PROFILEID']);
				redirect('paypal');
			}
		}
	}
}

?>
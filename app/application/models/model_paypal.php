<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Model_paypal extends CI_Model
{
	public function __construct(){
		require_once APPPATH . 'third_party/paypal/paypal-digital-goods.class.php';
		require_once APPPATH . 'third_party/paypal/paypal-subscription.class.php';
		require_once APPPATH . 'third_party/paypal/paypal-purchase.class.php';
	}
	
	public function set_credentials(){
		PayPal_Digital_Goods_Configuration::username(PP_USERNAME);
		PayPal_Digital_Goods_Configuration::password(PP_PASSWORD);
		PayPal_Digital_Goods_Configuration::signature(PP_SIGNATURE);

		PayPal_Digital_Goods_Configuration::return_url(base_url('index.php/paypal/callback?paypal=paid'));
		PayPal_Digital_Goods_Configuration::cancel_url(base_url('index.php/paypal/callback?paypal=cancel'));
		PayPal_Digital_Goods_Configuration::business_name('BrandTap test');

		PayPal_Digital_Goods_Configuration::notify_url(base_url('index.php/paypal/callback?paypal=notify'));
	}
	
	public function create_subscription(){
		$this->set_credentials();

		$subscription_details = array(
			'description' => 'Your Subscription: $'.PREMIUM_PRICE.'/month',
			'start_date' => date( 'Y-m-d\TH:i:s', time() + ( 24 * 60 * 60) ),
			'amount' => PREMIUM_PRICE,
			'period' => 'Month',
			'frequency' => '1',
		);

		return new PayPal_Subscription($subscription_details);
	}

	public function update_payment_profile_id($profile_id, $user_id = 0){
		
		if($user_id == 0){
			$user_id = $this->session->userdata('user_id');
		}
		
		$this->db->where('inst_id', $user_id);
		$this->db->update(TBL_USERS, array('payment_profile_id' => $profile_id));
		$this->session->set_userdata('subscription_status', $this->get_payment_status($profile_id));
	}
	
	public function get_payment_status($profile_id){
		if(empty($profile_id)) {
			return 'Inactive';
		}
		$paypal = $this->create_subscription();
		$profile_details = $paypal->get_profile_details( $profile_id );
		
		if(isset($profile_details['STATUS']) && $profile_details['STATUS'] == 'Active') {
			return 'Active';
		}
		else {
			return 'Inactive';
		}
	}
}

?>
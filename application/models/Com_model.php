<?php if( ! defined('BASEPATH')) exit('No direct script access allowed'); 
class Com_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
        $this->load->library('session');
	}

	function set_session($id,$user_name)
	{
		$session_data = array(
			'id'=>$id,
			'user_name'=>$user_name,
		);
		$this->session->set_userdata($session_data);
	}

	function set_sessions($arr)
	{
		$this->session->set_userdata($arr);
	}
	
	function check_is_login()
	{
		if( ! $this->session->userdata('id'))
		{
			$this->destroy_session();
			redirect(site_url());
		}
	}
	
	function destroy_session()
	{
        $session_data = array(
            'id'=>'',
            'user_name'=>'',
        );
        $this->session->set_userdata($session_data);
	}

	function ajax_check_is_login()
	{
		if( ! $this->session->userdata('id'))
		{
			echo 1;
		}else{
            echo 2;
        }
	}

	
}
?>
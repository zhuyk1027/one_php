<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Homepage extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        $this->load->model('com_model');
		if($this->session->userdata('id')=='')redirect('/smg');
    }

	public function index()
	{
		$data = [
			'title'=>'Enter Management- zhuyk',
			'tpl'=>'smg/index',
		];
		$this->load->view('smg/home',$data);
	}

}

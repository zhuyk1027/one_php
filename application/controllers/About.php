<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class About extends CI_Controller {

    public function __construct(){
        parent::__construct();
    }

	public function index()
	{
        $data = array(
            'title'=>'My Blog',
            'head'=>'Zhuyk\'s  Blog',
            'design'=>'分享生活',
            'page'=>'about',
        );
        $this->load->view(HOME_TEMPLATE.'about',$data);
	}

}

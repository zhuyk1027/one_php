<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class tool extends CI_Controller {
	public function index()
	{
		$this->load->view('tool/index');
	}

	public function time_change()
	{
        $data = [
            'title'=>'日期转化'
        ];
        $this->load->view('tool/tool',$data);
	}

    public function to_time_change()
	{
        $_sjc = $_POST['shijianchuo'];
        echo json_encode(date('Y-m-d H:i:s',$_sjc));
	}
	public function to_time_change1()
	{
        $_sjc = $_POST['dates'];
        echo json_encode(strtotime($_sjc));
	}
}

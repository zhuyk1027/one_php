<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class tool extends CI_Controller {

	public function __construct(){
		parent::__construct();
        $this->load->model('com_model');
        $this->common_model->pv_count($_SERVER['PHP_SELF']);
	}

	public function index()
	{
        $data = array('user'=>$this->session->userdata('id'));
		$this->load->view('tool/index',$data);
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

    public function md5_encode()
    {
        $data = [
            'title'=>'md5加密'
        ];
        $this->load->view('tool/md5encode',$data);
    }
    public function md5_to_encode()
    {
        $str = trim($_POST['words']);
        echo md5($str);
    }

    public function json_decode()
    {
        $data = [
            'title'=>'json解析'
        ];
        $this->load->view('tool/jsondecode',$data);
    }

    public function json_to_decode()
    {
        $str = trim($_POST['words']);
//        $arr = json_decode($arr);
        $arr = json_decode($str,TRUE);

        echo "<pre>";
        print_r($arr);die;
        echo "</pre>";
    }
}

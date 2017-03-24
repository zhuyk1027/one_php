<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class send_email extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        $this->load->model('common_model');
        $this->common_model->pv_count($_SERVER['PHP_SELF']);
    }

	public function index()
	{
        $data = [
            'title'=>'邮件发送'
        ];
        $this->load->view('tool/email',$data);
	}

    function send_email()
    {
        $to = $this->input->post('to');
        $title = $this->input->post('title');
        $conts = "以下信息不具有任何法律效应，不为任何真实信息，此邮件为".WEB_URL."网站测试邮件.\n\n\n".$this->input->post('conts');

        $this->load->library('email');

        $this->email->from('zhuyaokun1027@126.com', '朱耀昆');
        $this->email->to($to);
        //$this->email->cc('another@another-example.com');
        //$this->email->bcc('them@their-example.com');

        $this->email->subject($title);
        $this->email->message($conts);

        $res = $this->email->send();
        echo $res?'您的邮件发送成功了!':'您的邮件发送失败了!';
    }
}

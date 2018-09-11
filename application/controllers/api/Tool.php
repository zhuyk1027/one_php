<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Tool extends CI_Controller {

    public function __construct(){
        parent::__construct();
    }

    public function tool(){
        $param = array(
            array('title'=>'发送邮件','url'=>'/pages/send_email/email'),
        );
        echo json_encode($param);
    }

    public function qrcode(){
        $param = isset($_REQUEST['urls'])?trim($_REQUEST['urls']):'http://www.zhuyk.cn/';
        $param = urlencode($param);
        $url = "http://qr.liantu.com/api.php?text=$param";
        echo json_encode($url);
    }

    function send_email(){
        $to = $this->input->get('text_to');
        $title = $this->input->get('text_title');
        $conts = "以下信息不具有任何法律效应，不为任何真实信息，此邮件为".WEB_URL." 网站测试邮件.\n\n\n".$this->input->get('text_cont');

        $pattern = "/^([0-9A-Za-z\\-_\\.]+)@([0-9a-z]+\\.[a-z]{2,3}(\\.[a-z]{2})?)$/i";
        if(!preg_match($pattern, $to)){
            echo json_encode("请输入正确的邮箱地址");die;
        }
        $title .= " - ".WEB_NAME." ".WEB_URL;

        $this->load->library('email');
        $this->email->from('zhuyaokun1027@126.com', 'GrayPig');
        $this->email->to($to);
        $this->email->subject($title);
        $this->email->message($conts);
        $res = $this->email->send();

        echo json_encode('success');
    }

}

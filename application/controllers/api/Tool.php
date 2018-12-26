<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Tool extends CI_Controller {

    public function __construct(){

        parent::__construct();

        //统计访问次数
        $this->common_model->pv_count($_SERVER['REQUEST_URI']);
    }

    //小公举列表
    public function tool(){
        $param = array(
            array('title'=>'发送邮件','url'=>'/pages/send_email/email'),
        );
        echo json_encode($param);
    }

    //生成二维码
    public function qrcode(){
        $param = isset($_REQUEST['urls'])?trim($_REQUEST['urls']):WEB_URL;
        $param = urlencode($param);
        $url = "http://qr.liantu.com/api.php?text=$param";
        echo json_encode($url);
    }

    //发送邮件
    function send_email(){
        $to = $this->input->get('text_to');
        $title = $this->input->get('text_title');
        $conts = "以下信息不具有任何法律效应，不为任何真实信息，此邮件为网站测试邮件".WEB_URL.".\n\n\n".$this->input->get('text_cont');

        $pattern = "/^([0-9A-Za-z\\-_\\.]+)@([0-9a-z]+\\.[a-z]{2,3}(\\.[a-z]{2})?)$/i";
        if(!preg_match($pattern, $to)){
            echo json_encode("请输入正确的邮箱地址");die;
        }
        $title .= " - ".WEB_NAME.'('.WEB_URL.')';

        $this->load->library('email');
        $this->email->from('graypig@zhuyk.cn', WEB_MASTER);    //配置发送邮箱，发送人（自定义）
        $this->email->to($to);
        $this->email->subject($title);
        $this->email->message($conts);
        $res = $this->email->send();
        echo json_encode('success');

    }

}

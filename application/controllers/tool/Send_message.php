<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class send_message extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        $this->load->model('common_model');
        $this->common_model->pv_count('/tool/send_message');
    }

	public function index()
	{
        $data = [
            'title'=>'Send Message'
        ];
        $this->load->view('tool/send_message',$data);
	}

    public function send_mobile_code($mobile_code = '',$num = 1,$from = 0)
    {
        if(!preg_match("/^1[345678]\d{9}$/",$mobile_code)){
            echo "请输入正确的手机号";exit();
        }
        if($num<1){
            echo "请输入至少为1次";exit();
        }

        //$params = array('mobile_phone'=>17620016576);
        //$this->curl_get('http://localhost:9006/mobile/register.php?act=send_mobile_code',$params);die;

        $data = $this->common_model->get_records("select * from tool_get_other_message order by message_id desc limit $from,$num");
        if(empty($data)){
            exit("已经执行$num 次,暂无其他可用信息");
        }

        foreach($data as $key=>$val){
            $params = str_replace('$mobile',$mobile_code,$val->param);
            $params = json_decode($params,true);
            if($val->type==1){
                $this->curl_get($val->url,$params);
            }

            //成功后跳转
            sleep(59);
            $num = $num-1;
            $from = $from+1;
            $url = '/tool/send_message/send_mobile_code/'.$mobile_code.'/'.$num.'/'.$from;
            echo "<script>window.location.href='".$url."'</script>";exit();
        }

    }

    public function curl_post($url,$param) {
        if(!$url || !$param){
            return false;
        }

        $xip = $cip = '125.68.54.'.mt_rand(0,254);
        $header = array(
            'CLIENT-IP:'.$cip,
            'X-FORWARDED-FOR:'.$xip,
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt ($ch,CURLOPT_HTTPHEADER, $header);          //伪造IP，避免短信ip锁定
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_USERAGENT, 'BaiYangStore/3.3.0 (iPhone; iOS 9.3; Scale/2.00)');
        // post数据
        curl_setopt($ch, CURLOPT_POST, 1);
        // post的变量
        curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
        $output = curl_exec($ch);
        curl_close($ch);

        $output = json_decode($output);
        return $output;
    }

    public function curl_get($url,$param) {
        if(!$param){
            return false;
        }

        if(strpos($url,'?')!==false){
            $url .= "&";
        }else{
            $url .= "?";
        }

        $paramstr = '';
        foreach($param as $key=>$val){
            $paramstr .= $key.'='.$val.'&';
        }
        $url .= trim($paramstr,'&');

        $xip = $cip = '125.68.54.'.mt_rand(0,254);
        $header = array(
            'CLIENT-IP:'.$cip,
            'X-FORWARDED-FOR:'.$xip,
        );

        $ch = curl_init();
        //设置选项，包括URL
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);          //伪造IP，避免短信ip锁定
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        //执行并获取HTML文档内容
        $output = curl_exec($ch);
        //释放curl句柄
        curl_close($ch);

        //打印获得的数据
        return $output;
    }

}

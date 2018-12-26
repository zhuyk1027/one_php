<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class tool extends CI_Controller {

	public function __construct(){

		parent::__construct();

        $this->load->helper('array');

		//统计访问次数
        $this->common_model->pv_count($_SERVER['REQUEST_URI']);

	}


	/**
	 * 访问工具列表
	 * 赋值：Int   用户ID
	 * */
	public function index()
	{
        $data = [
            'title'=>'工具列表',
            'user'=>$this->session->userdata('id'),
        ];

		$this->load->view('tool/index',$data);
	}


    /**
     * 目录清单
     * 1.日期转化
     * 2.Md5加密
     * 3.json解析
     * 4.base64加密
     * 5.邮件发送
     * 6.js生成二维码
     * 7.功能测试
     * 8.发送短信
     * **/

    /**
     * 日期转化页面
     * */
	public function time_change()
	{
        $data = [
            'title'=>'日期转化'
        ];
        $this->load->view('tool/time_change',$data);
	}

    /**
     * 日期转化
     * 参数：string     shijianchuo 时间戳or日期
     * 返回：String    json日期or时间戳
     * */
    public function to_time_change()
	{
        $_sjc = isset($_POST['shijianchuo'])?trim($_POST['shijianchuo']):time();
        $dos = isset($_POST['dos'])?trim($_POST['dos']):'todate';

        if($dos=='todate'){
            echo json_encode(date('Y-m-d H:i:s',$_sjc));
        }else{
            echo json_encode(strtotime($_sjc));
        }
	}


    /**
     * Md5加密页面
     * */
    public function md5_encode()
    {
        $data = [
            'title'=>'md5加密'
        ];
        $this->load->view('tool/md5encode',$data);
    }

    /**
     * Md5加密 时间戳转日期
     * 参数：String    words   需加密的字符串
     * 返回：String    加密后的字符串
     * */
    public function md5_to_encode()
    {
        $str = isset($_POST['words'])?trim($_POST['words']):'';
        echo md5($str);
    }


    /**
     * json解析页面
     * */
    public function json_decode()
    {
        $data = [
            'title'=>'json解析'
        ];
        $this->load->view('tool/jsondecode',$data);
    }

    /**
     * json解析
     * 参数：String    words   需解析的字符串
     * 返回：String    解析后的字符串
     * */
    public function json_to_decode()
    {
        $str = isset($_POST['words'])?trim($_POST['words']):'';
        $arr = json_decode($str,TRUE);

        echo "<pre>";
        print_r($arr);die;
        echo "</pre>";
    }


    /**
     * base64转化页面
     * */
    public function base64_change()
    {
        $data = [
            'title'=>'base64转化'
        ];
        $this->load->view('tool/base64',$data);
    }

    /**
     * base64数据互相转换
     * 参数：Int       shijianchuo 时间戳
     * 返回：String    json日期
     * */
    public function to_base64_change()
    {
        $strword = isset($_POST['strword'])?trim($_POST['strword']):'';
        $dos = isset($_POST['dos'])?trim($_POST['dos']):'encryption';

        if($dos=='encryption'){
            echo json_encode(base64_encode($strword));
        }else{
            echo json_encode(base64_decode($strword));
        }

    }


    /**
     * 邮件发送页面
     * */
    public function email()
    {
        $data = [
            'title'=>'邮件发送'
        ];
        $this->load->view('tool/email',$data);
    }

    /**
     * 邮件发送
     * 参数：string    to          接收人
     * 参数：string    title       标题
     * 参数：string    conts       内容
     * 返回：String    成功or失败 提示语
     * */
    function send_email()
    {
        $to = $this->input->post('to');
        $title = $this->input->post('title');
        $conts = "以下信息不具有任何法律效应，不为任何真实信息，此邮件为".WEB_URL." 网站测试邮件.\n\n\n".$this->input->post('conts');

        $pattern = "/^([0-9A-Za-z\\-_\\.]+)@([0-9a-z]+\\.[a-z]{2,3}(\\.[a-z]{2})?)$/i";
        if(!preg_match($pattern, $to)){
            echo "请输入正确的邮箱地址";die;
        }
        $title .= " - ".WEB_NAME.'('.WEB_URL.')';

        $this->load->library('email');
        $this->email->from('graypig@zhuyk.cn', WEB_MASTER);    //配置发送邮箱，发送人（自定义）
        $this->email->to($to);
        //$this->email->cc('another@another-example.com');              //抄送
        //$this->email->bcc('them@their-example.com');                  //再抄送吧
        $this->email->subject($title);
        $this->email->message($conts);
        $res = $this->email->send();
        echo $res?'发送成功!':'发送失败!';
    }


    /**
     * js生成二维码页面
     * */
    public function qrcode()
    {
        $data = [
            'title'=>'生成二维码'
        ];
        $this->load->view('tool/qrcode',$data);
    }


    /**
     * 功能测试页面
     * */
    public function test()
    {
        $data = [
            'title'=>'功能测试'
        ];
        $this->load->view('tool/test',$data);
    }

    /**
     * CI事物测试运行
     * */
    public function ci_shiwu()
    {
        echo "<pre>
            运行程序为<br />

            $ this ->db->trans_start(true);   //增加参数 true 则为测试模式，所有查询将会回滚<br />
            $ this ->db->query('select * from user_album where id=30');<br />
            $ this ->db->query(\"update user_album set title='5555' where id=30\");<br />
            $ this ->db->query(\"update user_album set title='5555' where user_id=26\");<br />
            $ this ->db->trans_complete();<br />
            </pre>
        ";

        //严格模式
        $this->db->trans_strict(FALSE);

        //禁用事务
        //$this->db->trans_off();

        $this->db->trans_start(true);   //增加参数 true 则为测试模式，所有查询将会回滚
        $this->db->query('select * from user_album where id=30');
        $this->db->query("update user_album set title='5555' where id=30");
        $this->db->query("update user_album set title='5555' where user_id=26");
        $this->db->trans_complete();

        if ($this -> db -> trans_status() === FALSE) {
            print_r(array('errno' => 500, 'errMsg' => '执行失败!'));
        } else {
            print_r(array('errno' => 0, 'errMsg' => '执行成功!'));
        }

        //手动运行事务
        /*
        $this->db->trans_begin();

        $this->db->query('AN SQL QUERY...');
        $this->db->query('ANOTHER QUERY...');
        $this->db->query('AND YET ANOTHER QUERY...');

        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
        }
        else
        {
            $this->db->trans_commit();
        }
        */
    }

    /**
     * redis数据添加操作页面
     * */
    public function add_redis_info()
    {
        $data = [
            'title'=>'redis  添加'
        ];
        $this->load->view('tool/redis_add',$data);
    }


    /**
     * 发送短信页面
     * */
    public function send_message_page()
    {
        $data = [
            'title'=>'Send Message'
        ];
        $this->load->view('tool/send_message',$data);
    }

    /**
     * 短信发送
     * 参数：string    mobile    接收人
     * 参数：string    num       几次
     * 参数：string    from      第几个
     * */
    public function send_mobile_code($mobile_code = '',$num = 1,$from = 0)
    {
        if(!preg_match("/^1[345678]\d{9}$/",$mobile_code)){
            exit("请输入正确的手机号");
        }
        if($num<1){
            exit("已执行$from 次，总次数为$from 次");
        }

        //请求demo
        //$params = array('mobile_phone'=>17620016576);
        //$this->curl_get('http://localhost:9006/mobile/register.php?act=send_mobile_code',$params);die;

        $data = $this->common_model->get_records("select * from tool_get_other_message order by message_id desc limit $from,$num");
        if(empty($data)){
            exit("已经执行$num 次,暂无其他可用发送平台信息");
        }

        foreach($data as $key=>$val){
            $params = str_replace('$mobile',$mobile_code,$val->param);
            $params = json_decode($params,true);
            if($val->type==1){
                $this->curl_get($val->url,$params);
            }

            //成功后跳转
            $num = $num-1;
            $from = $from+1;
            if($num>0){ sleep(59);}

            $url = '/tool/tool/send_mobile_code/'.$mobile_code.'/'.$num.'/'.$from;
            echo "<script>window.location.href='".$url."'</script>";exit();
        }

    }

    //curl post 请求
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
        curl_setopt ($ch,CURLOPT_HTTPHEADER, $header);          //伪造IP，避免ip锁定
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_USERAGENT, 'HaHei/3.3.0 (iPhone; iOS 9.3; Scale/2.00)');
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
        $output = curl_exec($ch);
        curl_close($ch);

        return json_decode($output);

    }

    //curl post 请求
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
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);          //伪造IP，避免ip锁定
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $output = curl_exec($ch);
        curl_close($ch);

        return json_encode($output);
    }

}

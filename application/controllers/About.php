<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class About extends CI_Controller {

    public function __construct(){
        parent::__construct();

        $this->load->helper('common');
        $this->site_info['is_login'] = $this->session->userdata('id')?1:0;
    }
    #关于本站页面
    public function index(){
        $this->common_model->pv_count('about/index');
        $station = $this->common_model->get_record('select * from blog_about where type=\'station\'');
        $data = [
            'title'=>'关于本站',
            'station'=>$station,
        ];
        $data = array_merge($data,$this->site_info);
        $this->load->view('blog/blog_station_show',$data);
    }

    #关于我
    public function user_show()
    {
        $this->common_model->pv_count('about/user_show');
        $key = $this->uri->segment(3,0);
        if(!$key){
            redirect('/');
        }
        $station = $this->common_model->get_record('select * from blog_about where `key`=\''.$key.'\'');
        $data = [
            'title'=>'我的简介',
            'station'=>$station,
        ];
        $data = array_merge($data,$this->site_info);
        $this->load->view('blog/blog_station_show',$data);
    }

    #关于本站编写页面
    public function station()
    {
        if($this->session->userdata('id')!=1){
            echo "<script>window.location.href='/'</script>";
        }

        if(isset($_POST['submit'])){
            $data = array(
                'cont'=>$_POST['content'],
                'create_time'=>time(),
            );
            $this->db->update('blog_about',$data,array('type'=>'station'));
            if($this->db->affected_rows()<1){
                $data['type'] = 'station';
                $this->db->insert('blog_about',$data);
            }
        }

        $station = $this->common_model->get_record('select * from blog_about where type=\'station\'');
        $data = [
            'station'=>$station,
        ];
        $data = array_merge($data,$this->site_info);
        $this->load->view('blog/about_station',$data);
    }

    #关于我编写页面
    public function user()
    {
        if(!$this->session->userdata('id')){
            echo "<script>window.location.href='/'</script>";
        }
        $user_id = $this->session->userdata('id');

        if(isset($_POST['submit'])){
            $data = array(
                'type'=>'user',
                'stu'=>$_POST['stu']?$_POST['stu']:2,
                'cont'=>$_POST['content'],
                'create_time'=>time(),
            );
            $this->db->update('blog_about',$data,array('user_id'=>$user_id));
            if($this->db->affected_rows()<1){
                $data['user_id'] = $user_id;
                $keys = array('Q','W','E','R','T','Y','U','I','O','P',
                    'A','S','D','F','G','H','J','K','L',
                    'Z','X','C','V','B','N','M');
                $data['key'] = $keys[rand(0,26)].$keys[rand(0,26)].$keys[rand(0,26)].$keys[rand(0,26)].$keys[rand(0,26)].$keys[rand(0,26)];
                $this->db->insert('blog_about',$data);
            }

            //如果当前用户为1，则更改头部信息
            if($this->session->userdata('id')==1){
                $this->db->update('head_switch',array('is_open'=>$_POST['stu']?$_POST['stu']:0),array('id'=>1));
            }
        }

        $station = $this->common_model->get_record('select * from blog_about where user_id='.$user_id);
        $data = [
            'station'=>$station,
        ];
        $data = array_merge($data,$this->site_info);
        $this->load->view('blog/about_user',$data);
    }

}

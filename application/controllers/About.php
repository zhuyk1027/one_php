<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class About extends CI_Controller {

    public $site_info = array(
        'website_title'=>WEB_NAME,
        'head'=>array(
            //'head_title'=>'朱耀昆博客',
            'design'=>DESIGN,
            'my_photo'=>MY_PHOTO,
            'pay_photo'=>PAY_PHOTO,
            'email'=>EMAIL,
            'this_page'=>'about',
        ),
        'tags'=>'',
        'groups'=>'',
        'ranking'=>'',
        'friendship'=>'',
    );

    public function __construct(){
        parent::__construct();
        $this->load->model('com_model');
        $this->load->helper('common');

        $this->user_id = 1;
        $this->site_info['friendship'] = $this->common_model->get_records('select fs_id,fs_title,hplink from blog_friendship where is_on=1');
        $this->site_info['groups'] = $this->common_model->get_records('select blog_group_id,group_name,(select count(*) from blog where group_id=blog_group_id ) as num from blog_group where user_id='.$this->user_id);
        $this->site_info['tags'] = $this->common_model->get_records('select tag_id,tag_name,(select count(*) from blog where tags=tag_id ) as num from blog_tag where user_id='.$this->user_id);
        $this->site_info['ranking'] = $this->common_model->get_records('select blog_id,title from blog order by click desc limit 10');
    }
    #关于本站页面
    public function index(){
        $this->common_model->pv_count('about/index');
        $station = $this->common_model->get_record('select * from about where type=\'station\'');
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
        $station = $this->common_model->get_record('select * from about where `key`=\''.$key.'\'');
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
        if(!$this->session->userdata('id')){
            echo "<script>window.location.href='/'</script>";
        }

        if(isset($_POST['submit'])){
            $data = array(
                'cont'=>$_POST['content'],
                'create_time'=>time(),
            );
            $this->db->update('about',$data,array('type'=>'station'));
            if($this->db->affected_rows()<1){
                $data['type'] = 'station';
                $this->db->insert('about',$data);
            }
        }

        $station = $this->common_model->get_record('select * from about where type=\'station\'');
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

        if(isset($_POST['submit'])){
            $data = array(
                'type'=>'user',
                'stu'=>$_POST['stu']?$_POST['stu']:2,
                'cont'=>$_POST['content'],
                'create_time'=>time(),
            );
            $this->db->update('about',$data,array('user_id'=>$this->user_id));
            if($this->db->affected_rows()<1){
                $data['user_id'] = $this->user_id;
                $keys = array('Q','W','E','R','T','Y','U','I','O','P',
                    'A','S','D','F','G','H','J','K','L',
                    'Z','X','C','V','B','N','M');
                $data['key'] = $keys[rand(0,26)].$keys[rand(0,26)].$keys[rand(0,26)].$keys[rand(0,26)].$keys[rand(0,26)].$keys[rand(0,26)];
                $this->db->insert('about',$data);
            }
        }

        $station = $this->common_model->get_record('select * from about where user_id='.$this->user_id);
        $data = [
            'station'=>$station,
        ];
        $data = array_merge($data,$this->site_info);
        $this->load->view('blog/about_user',$data);
    }

}

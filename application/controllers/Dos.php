<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Dos extends CI_Controller {

    public function __construct(){
        parent::__construct();
    }

    #首页显示
	public function index()
	{

	}

    #链接跳转
    function jump($id = 0){
        if(!$id)redirect('/');

        $friendship = $this->common_model->get_record('select * from blog_friendship where fs_id='.$id);
        if(!$friendship)redirect('/');

        $click = $this->common_model->get_record('select * from friendship_click where fs_id='.$id.' and create_time='.date('Ymd'));
        if($click){
            //更改
            $this->db->query("update friendship_click set click=click+1 where id=".$click->id);
        }else{
            //插入
            $data = array('fs_id'=>$id,'fs_title'=>$friendship->fs_title,'create_time'=>date('Ymd'),'click'=>1);
            $this->db->insert('friendship_click',$data);
        }
        echo "<script>window.location.href='".$friendship->hplink."'</script>";



    }
}

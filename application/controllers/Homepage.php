<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Homepage extends CI_Controller {

    public $site_info = array(
        'website_title'=>'朱耀昆博客',
        'head'=>array(
            'head_title'=>'朱耀昆博客',
            'design'=>'享受每一天的生活，做最精彩的自己。',
            'my_photo'=>MY_PHOTO,
            'pay_photo'=>PAY_PHOTO,
            'email'=>EMAIL,
            ),
        'tags'=>'',
        'groups'=>'',
        'ranking'=>'',
        'friendship'=>'',
    );
    public function __construct(){
        parent::__construct();
        $this->user_id = 1;
        $this->site_info['friendship'] = $this->common_model->get_records('select fs_title,hplink from blog_friendship where is_on=1');
        $this->site_info['groups'] = $this->common_model->get_records('select blog_group_id,group_name,(select count(*) from blog where group_id=blog_group_id ) as num from blog_group where user_id='.$this->user_id);
        $this->site_info['tags'] = $this->common_model->get_records('select tag_id,tag_name,(select count(*) from blog where tags=tag_id ) as num from blog_tag where user_id='.$this->user_id);
        $this->site_info['ranking'] = $this->common_model->get_records('select blog_id,title from blog order by click desc limit 10');
    }

	public function index()
	{
        #最新的博客
        $blog = $this->common_model->get_records('select * from blog where user_id='.$this->user_id.' order by create_date desc  limit 8');
        $blog_group = $this->site_info['groups'];
        foreach($blog as $key=>$row){
            $row->cont = substr($row->cont,0,666);
            $row->cont = $this->common_model->replace_tags($row->cont);
            foreach($blog_group as $line){
                if($row->group_id==$line->blog_group_id){
                    $row->group_name = $line->group_name;
                }
            }

        }

        #博客推荐文章 pc
        $blog_top = $this->common_model->get_records('select * from blog where user_id='.$this->user_id.' and is_top=1 limit 4');
        foreach($blog_top as $key=>$row){
            $row->cont = substr($row->cont,0,500);;
            $row->cont = $this->common_model->replace_tags($row->cont);
        }

        #数组赋值
        $data = array(
            'blog'=>$blog,
            'blog_top'=>$blog_top,
        );
        $data = array_merge($data,$this->site_info);
        $this->load->view(BLOG.'homepage',$data);
	}

}

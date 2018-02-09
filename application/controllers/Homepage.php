<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Homepage extends CI_Controller {


    public function __construct(){
        parent::__construct();
        $this->user_id = 1;
        $this->common_model->pv_count($_SERVER['PHP_SELF']);
    }

    #首页显示
	public function index()
	{
        #最新的博客
        $blog = $this->common_model->get_records('select * from blog where user_id='.$this->user_id.' order by create_date desc  limit 7');
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

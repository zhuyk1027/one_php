<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Blog extends CI_Controller {

    public function __construct(){
        parent::__construct();
        $this->user_id = 1;
    }

    //首页显示
    public function index()
    {

        #最新的博客
        $blog = $this->common_model->get_records('select blog_id,title,briefly,cont,group_id,tags,click,create_date from blog where user_id='.$this->user_id.' order by create_date desc  limit 8');
        $blog_group = $this->site_info['groups'];
        $tags = $this->site_info['tags'];
        foreach($blog as $key=>$row){
            $row->cont = mb_substr($row->cont, 0, 450, 'utf-8');
            $row->cont = $this->common_model->replace_tags($row->cont);
            foreach($blog_group as $line){
                if($row->group_id==$line->blog_group_id){
                    $row->group_name = $line->group_name;
                }
            }
            foreach($tags as $line){
                if($row->tags==$line->tag_id){
                    $row->tag_name = $line->tag_name;
                }
            }

            if($row->cont){
                $row->cont = @iconv("UTF-8","UTF-8//IGNORE",$row->cont);
            }

        }

        #博客推荐文章 pc
        $blog_top = $this->common_model->get_records('select blog_id,title,briefly,cont,group_id,tags,click,create_date from blog where user_id='.$this->user_id.' and is_top=1 limit 2');
        foreach($blog_top as $key=>$row){
            $row->cont = substr($row->cont,0,150);
            $row->cont = $this->common_model->replace_tags($row->cont);
        }

        #数组赋值
        $data = array(
            'blog'=>$blog,
            'blog_top'=>$blog_top,
            'ad'=>$this->site_info['ad'],
            'groups'=>$blog_group,
            'tags'=>$tags,
        );

        echo json_encode($data);
    }

    function utf8Substr($str, $from, $len)
    {
        return preg_replace('#^(?:[\x00-\x7F]|[\xC0-\xFF][\x80-\xBF]+){0,'.$from.'}'.
            '((?:[\x00-\x7F]|[\xC0-\xFF][\x80-\xBF]+){0,'.$len.'}).*#s',
            '$1',$str);
    }

    /**
     * 博文分类列表
     * */
	public function blog_list()
	{
        $sql = '';
        #分类查看
        $group_id = @$_GET['type'];
        $group_id = $group_id?$group_id:0;
        if($group_id){
            $sql = ' and group_id='.$group_id.' ';
            $title = $this->common_model->get_record('select group_name from blog_group where blog_group_id='.$group_id);
            $this->site_info['website_title']  = @$title->group_name.' - 朱耀昆博客';
            $this->site_info['head']['this_page']  = $group_id;
        }
        #标签查看
        $tag_id = @$_GET['tag'];
        $tag_id = $tag_id?$tag_id:0;
        if($tag_id){
            $sql = " and find_in_set('".$tag_id."', tags) ";
            $title = $this->common_model->get_record('select tag_name from blog_tag where tag_id='.$tag_id);
            $this->site_info['website_title']  = @$title->tag_name.' - 朱耀昆博客';
        }
        #搜索查看
        $key = $this->input->get('key');
        $key = $key?$key:'';
        if($key){
            $sql = ' and title like \'%'.$key.'%\' ';
            $this->site_info['website_title']  = @$key.' - 朱耀昆博客';
        }

        $query = $this->common_model->get_record('SELECT COUNT(*) AS total FROM '.$this->db->dbprefix('blog').' WHERE user_id='.$this->user_id.$sql);
        $total = $query->total;
        $curr_page = $this->uri->segment(4,0);
        $page_size = 10;
        if( ! $curr_page) $curr_page = 1;
        $offset = ($curr_page - 1) * $page_size;
        $blog = $this->common_model->get_records('SELECT * FROM '.$this->db->dbprefix.'blog WHERE user_id = '.$this->user_id.$sql.' LIMIT '.$offset.','.$page_size);
        $blog_group = $this->site_info['groups'];
        foreach($blog as $key=>$row){
            $row->group_name = '无分类';
            $row->cont = substr($row->cont,0,666);;
            $row->cont = $this->common_model->replace_tags($row->cont);
            foreach($blog_group as $line){
                if($row->group_id==$line->blog_group_id){
                    $row->group_name = $line->group_name;
                }
            }

        }

        $data = array(
            'blog'=>$blog,
            'paginate' =>  $this->_get_page($total,$curr_page,$page_size,$group_id,'blog_list'),
        );
        $data = array_merge($data,$this->site_info);
        $this->load->view(BLOG.'blog_list',$data);
	}

    /**
     * 博文详情
     * */
    function detail($blog_id = 0){

        if(!$blog_id)redirect('/');

        $blog_info = $this->common_model->get_record('select * from blog where blog_id='.$blog_id);
        $blog_group = $this->common_model->get_record('select * from blog_group where blog_group_id='.$blog_info->group_id);
        if($blog_group){
            $blog_info->group_info = $blog_group;
        }else{
            @$blog_info->group_info->blog_group_id = 0;
            @$blog_info->group_info->group_name = '无分类';
        }


        #PC 获取上一篇
        $before_blog = $this->common_model->get_record('select * from blog where blog_id<'.$blog_info->blog_id.' order by blog_id desc limit 1');
        #PC 获取相关文章
        $about_blog = array();
        if(isset($blog_info->group_info->blog_group_id)){
            $about_blog = $this->common_model->get_records('select * from blog where group_id='.$blog_info->group_info->blog_group_id.' order by click desc limit 3');
        }

        #若文章存在，则增加阅读量
        $this->db->query("update blog set click=click+1 where blog_id=".$blog_id);

        $this->site_info['website_title']  = @$blog_info->title.' - 朱耀昆博客';
        $data = array(
            'blog'=>$blog_info,
            'before_blog'=>$before_blog,
            'about_blog'=>$about_blog,
        );
        $data = array_merge($data,$this->site_info);
        $this->load->view(BLOG.'blog_detail',$data);
    }

}

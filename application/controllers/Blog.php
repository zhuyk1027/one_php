<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Blog extends CI_Controller {

    public $site_info = array(
        'website_title'=>WEB_NAME,
        'head'=>array(
            //'head_title'=>'朱耀昆博客',
            'design'=>DESIGN,
            'my_photo'=>MY_PHOTO,
            'pay_photo'=>PAY_PHOTO,
            'email'=>EMAIL,
            'this_page'=>'0',
        ),
        'tags'=>'',
        'groups'=>'',
        'ranking'=>'',
        'friendship'=>'',
        'head_switch'=>'',
    );

    public function __construct(){
        parent::__construct();
        $this->user_id = 1;
        $time = time();
        $this->site_info['ad'] = $this->common_model->get_records("select ad_id,ad_title,pic,hplink from blog_ad where is_on=1 and $time>=start_time and $time<=end_time");
        $this->site_info['friendship'] = $this->common_model->get_records('select fs_id,fs_title,hplink from blog_friendship where is_on=1');
        $this->site_info['groups'] = $this->common_model->get_records('select blog_group_id,group_name,(select count(*) from blog where group_id=blog_group_id ) as num from blog_group where user_id='.$this->user_id);
        $this->site_info['tags'] = $this->common_model->get_records('select tag_id,tag_name,(select count(*) from blog where tags=tag_id ) as num from blog_tag where user_id='.$this->user_id);
        $this->site_info['ranking'] = $this->common_model->get_records('select blog_id,title from blog order by click desc limit 10');
        $this->site_info['head_switch'] = $this->common_model->get_records('select * from head_switch order by sort asc');
        $this->common_model->pv_count($_SERVER['PHP_SELF']);
    }

    /*
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
            $sql = " and (tags=".$tag_id." or tags like '%,".$tag_id."' or tags like '".$tag_id.",%' or tags like '%,".$tag_id.",%' ) ";
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

    /*
     * 分页字符串
     *  t 总条数 c 当前页码  p 页码大小 博文分类 访问操作
     * */
    function _get_page($t,$c,$p,$group_id,$type)
    {
        // $type index search tag
        $str = '';
        if( ! $c) $c = 1;
        if($t > 0 && $p > 0)
        {
            $page_total = ceil($t / $p) ;
            if($c > $page_total) $c = $page_total;
            if($page_total > 1)
            {
                if(isset($_SERVER['QUERY_STRING']) && $_SERVER['QUERY_STRING']) $q_str = '?'.$_SERVER['QUERY_STRING'];
                else $q_str = '';
                if(($s = $c - 4) <= 0) $s = 1;
                if(($e = $c + 4) > $page_total) $e = $page_total;

                if($c > 1) $str .= '<a href="/blog/'.$type.'/'.$group_id.'/'.($c-1).$q_str.'">上一页</a>&nbsp;&nbsp;&nbsp;&nbsp;';
                for($i = $s; $i <= $e ; $i++)
                {
                    $str .= '<a href="/blog/'.$type.'/'.$group_id.'/'.$i.$q_str.'">'.$i.'</a>&nbsp;&nbsp;&nbsp;&nbsp;';
                }
                if($c < $page_total) $str .= '<a href="/blog/'.$type.'/'.$group_id.'/'.($c+1).$q_str.'">下一页</a>';
                $str .= ' 共计 '.$page_total.'页';
            }
        }
        return $str;
    }

    /*
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

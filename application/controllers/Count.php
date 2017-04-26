<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Count extends CI_Controller {

    public $site_info = array(
        'website_title'=>WEB_NAME,
        'head'=>array(
            //'head_title'=>'朱耀昆博客',
            'design'=>DESIGN,
            'my_photo'=>MY_PHOTO,
            'pay_photo'=>PAY_PHOTO,
            'email'=>EMAIL,
            'this_page'=>'count',
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

        if($this->session->userdata('id')!=1){
            echo "<script>window.location.href='/'</script>";
        }

        $this->user_id = $this->session->userdata('id');
        $time = time();
        $this->site_info['ad'] = $this->common_model->get_records("select ad_id,ad_title,pic,hplink from blog_ad where is_on=1 and $time>=start_time and $time<=end_time");
        $this->site_info['friendship'] = $this->common_model->get_records('select fs_id,fs_title,hplink from blog_friendship where is_on=1');
        $this->site_info['groups'] = $this->common_model->get_records('select blog_group_id,group_name,(select count(*) from blog where group_id=blog_group_id ) as num from blog_group where user_id='.$this->user_id);
        $this->site_info['tags'] = $this->common_model->get_records('select tag_id,tag_name,(select count(*) from blog where tags=tag_id ) as num from blog_tag where user_id='.$this->user_id);
        $this->site_info['ranking'] = $this->common_model->get_records('select blog_id,title from blog order by click desc limit 10');
    }

    #友情链接统计
    public function friend_count()
    {
        $this->site_info['website_title'] = $this->site_info['website_title'].' - 友链统计';
        #查询今日的访问量
        $day = date('Ymd');
        $today_count = $this->common_model->get_records('select * from friendship_click where create_time='.$day);

        #七天内访问量
        $date = date('Ymd', strtotime('-6 days'));
        $seven_count = $this->common_model->get_records('select *,sum(click) as total from friendship_click where create_time>='.$date.' group by fs_id');

        #本月访问量
        $date = date('Ym').'01';
        $month_count = $this->common_model->get_records('select *,sum(click) as total from friendship_click where create_time>='.$date.' group by fs_id');

        #上月访问量
        $start_date = date("Ym", strtotime("-1 month")).'01' ;
        $year = date("Y", strtotime("-1 month")) ;
        $month = date("m", strtotime("-1 month")) ;
        $day = 0;
        if(in_array($month,array(1,3,5,7,8,10,12))){
            $day = 31;
        }else if(in_array($month,array(4,6,9,11))){
            $day = 30;
        }else if($year%4==0 && $month==2){
            $day = 29;
        }else if($year%4!=0 && $month==2){
            $day = 28;
        }
        $end_date = $year.$month.$day;
        $last_month_count = $this->common_model->get_records('select *,sum(click) as total from friendship_click where create_time>='.$start_date.' and create_time<='.$end_date.' group by fs_id');

        #颜色表
        $color = array('#FFEC8B','#FF6A6A','#D8BFD8','#CDBA96','#FFC125','#C1FFC1','#B3B3B3','#9BCD9B','#8B8B00','#0D0D0D');

        #四张百分比图
        $percent = array();
        $count = 0;
        foreach($today_count as $row){
            $count = $count+$row->click;
        }foreach($today_count as $key=>$row){
            $percent[0][] = array('name'=>$row->fs_title,'value'=>sprintf("%.2f", $row->click/$count),'color'=>$color[$key]);
        }

        $count = 0;
        foreach($seven_count as $row){
            $count = $count+$row->total;
        }foreach($seven_count as $key=>$row){
            $percent[1][] = array('name'=>$row->fs_title,'value'=>sprintf("%.2f", $row->total/$count),'color'=>$color[$key]);
        }

        $count = 0;
        foreach($month_count as $row){
            $count = $count+$row->total;
        }foreach($month_count as $key=>$row){
            $percent[2][] = array('name'=>$row->fs_title,'value'=>sprintf("%.2f", $row->total/$count),'color'=>$color[$key]);
        }

        $count = 0;
        foreach($last_month_count as $row){
            $count = $count+$row->total;
        }foreach($last_month_count as $key=>$row){
            $percent[3][] = array('name'=>$row->fs_title,'value'=>sprintf("%.2f", $row->total/$count),'color'=>$color[$key]);
        }
        foreach($percent as $k=>$v){
            $percent[$k]=json_encode($v);
        }

        #查询今年 去年 总数 ，及最近6个月每个月总数-----------------------------------------------------------------------------------------
        $clicks = array();

        $date = date('Y').'0101';
        $this_year_count = $this->common_model->get_record('select *,sum(click) as total from friendship_click where create_time>='.$date);

        $start_date = date("Y", strtotime("-1 year")).'0101' ;
        $end_date = date("Y", strtotime("-1 year")).'1231' ;
        $last_year_count = $this->common_model->get_record('select *,sum(click) as total from friendship_click where create_time>='.$start_date.' and create_time<='.$end_date);

        $siex_month = array(date("Ym", strtotime("-1 month")),date("Ym", strtotime("-2 month")),date("Ym", strtotime("-3 month")),
            date("Ym", strtotime("-4 month")),date("Ym", strtotime("-5 month")),date("Ym", strtotime("-6 month")));
        sort($siex_month);

        $clicks[] = array('name'=>"去年",'value'=>$last_year_count->total?$last_year_count->total:0,'color'=>$color[8]);
        $clicks[] = array('name'=>"今年",'value'=>$this_year_count->total?$this_year_count->total:0,'color'=>$color[7]);
        foreach($siex_month as $k=>$row){
            $start_date = $row.'01';
            $end_date = $row.'31';
            $info = $this->common_model->get_record('select *,sum(click) as total from friendship_click where create_time>='.$start_date.' and create_time<='.$end_date);
            $clicks[] = array('name'=>$row,'value'=>$info->total?$info->total:0,'color'=>$color[$k]);
        }

        $data = [
            'today_count'=>$today_count,
            'seven_count'=>$seven_count,
            'month_count'=>$month_count,
            'last_month_count'=>$last_month_count,
            'percent'=>$percent,
            'color'=>json_encode($color),
            'clicks'=>json_encode($clicks),
        ];
        $data = array_merge($data,$this->site_info);
        $this->load->view('blog/count_friend',$data);
    }

    public function ad_count()
    {
        $this->site_info['website_title'] = $this->site_info['website_title'].' - 广告统计';
        #查询今日的访问量
        $day = date('Ymd');
        $today_count = $this->common_model->get_records('select * from ad_click where create_time='.$day);

        #七天内访问量
        $date = date('Ymd', strtotime('-6 days'));
        $seven_count = $this->common_model->get_records('select *,sum(click) as total from ad_click where create_time>='.$date.' group by ad_id');

        #本月访问量
        $date = date('Ym').'01';
        $month_count = $this->common_model->get_records('select *,sum(click) as total from ad_click where create_time>='.$date.' group by ad_id');

        #上月访问量
        $start_date = date("Ym", strtotime("-1 month")).'01' ;
        $year = date("Y", strtotime("-1 month")) ;
        $month = date("m", strtotime("-1 month")) ;
        $day = 0;
        if(in_array($month,array(1,3,5,7,8,10,12))){
            $day = 31;
        }else if(in_array($month,array(4,6,9,11))){
            $day = 30;
        }else if($year%4==0 && $month==2){
            $day = 29;
        }else if($year%4!=0 && $month==2){
            $day = 28;
        }
        $end_date = $year.$month.$day;
        $last_month_count = $this->common_model->get_records('select *,sum(click) as total from ad_click where create_time>='.$start_date.' and create_time<='.$end_date.' group by ad_id');

        #颜色表
        $color = array('#FFEC8B','#FF6A6A','#D8BFD8','#CDBA96','#FFC125','#C1FFC1','#B3B3B3','#9BCD9B','#8B8B00','#0D0D0D');

        #四张百分比图
        $percent = array();
        $count = 0;
        foreach($today_count as $row){
            $count = $count+$row->click;
        }foreach($today_count as $key=>$row){
            $percent[0][] = array('name'=>$row->ad_title,'value'=>sprintf("%.2f", $row->click/$count),'color'=>$color[$key]);
        }

        $count = 0;
        foreach($seven_count as $row){
            $count = $count+$row->total;
        }foreach($seven_count as $key=>$row){
            $percent[1][] = array('name'=>$row->ad_title,'value'=>sprintf("%.2f", $row->total/$count),'color'=>$color[$key]);
        }

        $count = 0;
        foreach($month_count as $row){
            $count = $count+$row->total;
        }foreach($month_count as $key=>$row){
            $percent[2][] = array('name'=>$row->ad_title,'value'=>sprintf("%.2f", $row->total/$count),'color'=>$color[$key]);
        }

        $count = 0;
        foreach($last_month_count as $row){
            $count = $count+$row->total;
        }foreach($last_month_count as $key=>$row){
            $percent[3][] = array('name'=>$row->ad_title,'value'=>sprintf("%.2f", $row->total/$count),'color'=>$color[$key]);
        }
        foreach($percent as $k=>$v){
            $percent[$k]=json_encode($v);
        }

        #查询今年 去年 总数 ，及最近6个月每个月总数-----------------------------------------------------------------------------------------
        $clicks = array();

        $date = date('Y').'0101';
        $this_year_count = $this->common_model->get_record('select *,sum(click) as total from ad_click where create_time>='.$date);

        $start_date = date("Y", strtotime("-1 year")).'0101' ;
        $end_date = date("Y", strtotime("-1 year")).'1231' ;
        $last_year_count = $this->common_model->get_record('select *,sum(click) as total from ad_click where create_time>='.$start_date.' and create_time<='.$end_date);

        $siex_month = array(date("Ym", strtotime("-1 month")),date("Ym", strtotime("-2 month")),date("Ym", strtotime("-3 month")),
            date("Ym", strtotime("-4 month")),date("Ym", strtotime("-5 month")),date("Ym", strtotime("-6 month")));
        sort($siex_month);

        $clicks[] = array('name'=>"去年",'value'=>$last_year_count->total?$last_year_count->total:0,'color'=>$color[8]);
        $clicks[] = array('name'=>"今年",'value'=>$this_year_count->total?$this_year_count->total:0,'color'=>$color[7]);
        foreach($siex_month as $k=>$row){
            $start_date = $row.'01';
            $end_date = $row.'31';
            $info = $this->common_model->get_record('select *,sum(click) as total from ad_click where create_time>='.$start_date.' and create_time<='.$end_date);
            $clicks[] = array('name'=>$row,'value'=>$info->total?$info->total:0,'color'=>$color[$k]);
        }
        $data = [
            'today_count'=>$today_count,
            'seven_count'=>$seven_count,
            'month_count'=>$month_count,
            'last_month_count'=>$last_month_count,
            'percent'=>$percent,
            'color'=>json_encode($color),
            'clicks'=>json_encode($clicks),
        ];
        $data = array_merge($data,$this->site_info);
        $this->load->view('blog/count_ad',$data);
    }

    public function pv_count()
    {
        $this->site_info['website_title'] = $this->site_info['website_title'].' - pv统计';
        #查询今日的访问量
        $day = date('Ymd');
        $today_count = $this->common_model->get_records('select * from pv_count where create_time='.$day);

        #七天内访问量
        $date = date('Ymd', strtotime('-6 days'));
        $seven_count = $this->common_model->get_records('select *,sum(pv_count) as total from pv_count where create_time>='.$date.' group by links');

        #本月访问量
        $date = date('Ym').'01';
        $month_count = $this->common_model->get_records('select *,sum(pv_count) as total from pv_count where create_time>='.$date.' group by links');

        #上月访问量
        $start_date = date("Ym", strtotime("-1 month")).'01' ;
        $year = date("Y", strtotime("-1 month")) ;
        $month = date("m", strtotime("-1 month")) ;
        $day = 0;
        if(in_array($month,array(1,3,5,7,8,10,12))){
            $day = 31;
        }else if(in_array($month,array(4,6,9,11))){
            $day = 30;
        }else if($year%4==0 && $month==2){
            $day = 29;
        }else if($year%4!=0 && $month==2){
            $day = 28;
        }
        $end_date = $year.$month.$day;
        $last_month_count = $this->common_model->get_records('select *,sum(pv_count) as total from pv_count where create_time>='.$start_date.' and create_time<='.$end_date.' group by links');

        #颜色表
        $color = array('#FFEC8B','#FF6A6A','#D8BFD8','#CDBA96','#FFC125','#C1FFC1','#B3B3B3','#9BCD9B','#8B8B00','#0D0D0D');

        #四张百分比图
        $percent = array();
        $count = 0;
        foreach($today_count as $row){
            $count = $count+$row->pv_count;
        }foreach($today_count as $key=>$row){
            $percent[0][] = array('name'=>$row->links,'value'=>sprintf("%.2f", $row->pv_count/$count),'color'=>$color[$key]);
        }

        $count = 0;
        foreach($seven_count as $row){
            $count = $count+$row->total;
        }foreach($seven_count as $key=>$row){
            $percent[1][] = array('name'=>$row->links,'value'=>sprintf("%.2f", $row->total/$count),'color'=>$color[$key]);
        }

        $count = 0;
        foreach($month_count as $row){
            $count = $count+$row->total;
        }foreach($month_count as $key=>$row){
            $percent[2][] = array('name'=>$row->links,'value'=>sprintf("%.2f", $row->total/$count),'color'=>$color[$key]);
        }

        $count = 0;
        foreach($last_month_count as $row){
            $count = $count+$row->total;
        }foreach($last_month_count as $key=>$row){
            $percent[3][] = array('name'=>$row->links,'value'=>sprintf("%.2f", $row->total/$count),'color'=>$color[$key]);
        }
        foreach($percent as $k=>$v){
            $percent[$k]=json_encode($v);
        }

        #查询今年 去年 总数 ，及最近6个月每个月总数-----------------------------------------------------------------------------------------
        $clicks = array();

        $date = date('Y').'0101';
        $this_year_count = $this->common_model->get_record('select *,sum(pv_count) as total from pv_count where create_time>='.$date);

        $start_date = date("Y", strtotime("-1 year")).'0101' ;
        $end_date = date("Y", strtotime("-1 year")).'1231' ;
        $last_year_count = $this->common_model->get_record('select *,sum(pv_count) as total from pv_count where create_time>='.$start_date.' and create_time<='.$end_date);

        $siex_month = array(date("Ym", strtotime("-1 month")),date("Ym", strtotime("-2 month")),date("Ym", strtotime("-3 month")),
            date("Ym", strtotime("-4 month")),date("Ym", strtotime("-5 month")),date("Ym", strtotime("-6 month")));
        sort($siex_month);

        $clicks[] = array('name'=>"去年",'value'=>$last_year_count->total?$last_year_count->total:0,'color'=>$color[8]);
        $clicks[] = array('name'=>"今年",'value'=>$this_year_count->total?$this_year_count->total:0,'color'=>$color[7]);
        foreach($siex_month as $k=>$row){
            $start_date = $row.'01';
            $end_date = $row.'31';
            $info = $this->common_model->get_record('select *,sum(pv_count) as total from pv_count where create_time>='.$start_date.' and create_time<='.$end_date);
            $clicks[] = array('name'=>$row,'value'=>$info->total?$info->total:0,'color'=>$color[$k]);
        }

        $data = [
            'today_count'=>$today_count,
            'seven_count'=>$seven_count,
            'month_count'=>$month_count,
            'last_month_count'=>$last_month_count,
            'percent'=>$percent,
            'color'=>json_encode($color),
            'clicks'=>json_encode($clicks),
        ];
        $data = array_merge($data,$this->site_info);
        $this->load->view('blog/count_pv',$data);
    }


}

<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class qrcode extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        $this->load->model('common_model');
        $this->common_model->pv_count($_SERVER['PHP_SELF']);
    }

	public function index()
	{
		$data = [
			'title'=>'生成二维码'
		];
		$this->load->view('tool/qrcode',$data);
	}

    function to_create_qrcode()
    {
        $this->load->library('phpqrcode');
        echo $this->phpqrcode->png('http://www.sina.com');

        $logo = '';
        //准备好的logo图片
        if(isset($_FILES['pic_path']['tmp_name'])){
            $logo = FILE.'img/'.md5(time()).'.png';
            $file = move_uploaded_file($_FILES['pic_path']['tmp_name'],$logo);
        }

        $url = isset($_POST['url'])?$_POST['url']:'http://zhuyk1027.blog.163.com/';

        $pic = $this->common_model->get_qrcode($logo,$url);

        echo '<img src="'.$pic.'">';

    }

    function getDistance($lat1=40.053767, $lng1=116.623458, $lat2=39.990562, $lng2=116.356094)
    {
        $earthRadius = 6367000; //approximate radius of earth in meters

        /*
        Convert these degrees to radians
        to work with the formula
        */

        $lat1 = ($lat1 * pi() ) / 180;
        $lng1 = ($lng1 * pi() ) / 180;

        $lat2 = ($lat2 * pi() ) / 180;
        $lng2 = ($lng2 * pi() ) / 180;

        /*
        Using the
        Haversine formula

        http://en.wikipedia.org/wiki/Haversine_formula

        calculate the distance
        */

        $calcLongitude = $lng2 - $lng1;
        $calcLatitude = $lat2 - $lat1;
        $stepOne = pow(sin($calcLatitude / 2), 2) + cos($lat1) * cos($lat2) * pow(sin($calcLongitude / 2), 2);  $stepTwo = 2 * asin(min(1, sqrt($stepOne)));
        $calculatedDistance = $earthRadius * $stepTwo;
        //echo $calculatedDistance." ";
        echo round($calculatedDistance);
    }


}

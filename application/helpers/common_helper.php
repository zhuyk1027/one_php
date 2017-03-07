<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
function get_real_path($url)
{
	if(!$url) return '';
	if(strpos($url,'//') === 0)
	{
		return 'http:'.$url;
	}
	else if(strpos($url,"http://") === FALSE && strpos($url,'https://') === FALSE)
	{
		return 'http://www.zhuyk.pub/'.$url;
	}
	else
	{
		return $url;
	}
}

function format_url($url)
{
	if(!$url) return '';
	if(strpos($url,'//') === 0)
	{
		return 'http:'.$url;
	}
	else if(strpos($url,"http://") === FALSE && strpos($url,'https://') === FALSE)
	{
		return 'http://'.$url;
	}
	else
	{
		return $url;
	}
}

//获取用户名
function get_user_name($id)
{
	$ci =& get_instance();
	$query =  $ci->common_model->get_record('SELECT mobile,email FROM '.$ci->db->dbprefix('sq_user').' WHERE id = '.$id);
	if($query)
	{
		if($query->mobile) return $query->mobile;
		else return $query->email;
	}
	return '';
}

//获取果篮封面
function get_album_cover($id)
{
	$ci =& get_instance();
	$query =  $ci->common_model->get_record('SELECT pic_path FROM '.$ci->db->dbprefix('sq_album').' WHERE id = '.$id);
	if($query && $query->pic_path) return get_real_path($query->pic_path);
	
	$query =  $ci->common_model->get_record('SELECT pic_path FROM '.$ci->db->dbprefix('sq_image').' WHERE aid = '.$id.' AND is_cover = 1');
	if($query)
	{
		return get_real_path($query->pic_path);
	}
	else
	{
		$query =  $ci->common_model->get_record('SELECT pic_path FROM '.$ci->db->dbprefix('sq_image').' WHERE aid = '.$id.' ORDER BY id DESC');
		if($query) return get_real_path($query->pic_path);
	}
	return '';
}

//获取querystring
function get_querystring($page=0)
{
	//继续浏览下一页的数据
	if($page > 0)
	{
		$str = create_query('refer','page');
		$str = $str? '?'.$str.'&refer=water&page='.$page:'?refer=water&page='.$page;
	}
	else
	{
		$str = create_query('refer');
		$str = $str? '?'.$str.'&refer=water':'?refer=water';
	}
	return $str;
}

function site_urls($uri = '',$prefix = 'www')
{
	if($prefix == '') return site_url($uri);
	if($prefix == 'sq') $prefix = 'www';

	$t =& get_instance();
	if (is_array($uri))
	{
		$uri = implode('/', $uri);
	}
	$index_page = $t->config->item('index_page') ? $t->config->item('index_page').'/':'';
	if ($uri == '')
	{
		return 'http://'.$prefix.'.sengo.cn/'.$index_page;
	}
	else
	{
		$suffix = ($t->config->item('url_suffix') == FALSE) ? '' : $t->config->item('url_suffix');
		return 'http://'.$prefix.'.sengo.cn/'.$index_page.trim($uri, '/').$suffix;
	}
}

function site_urls2($uri = '',$prefix = '8082')
{
	if($prefix == '') return site_url($uri);
//	if($prefix == 'sq') $prefix = 'www';
	if($prefix == 8082){
		$prefix = 8082;
	}else{
		$prefix = 8081;
	}

	$t =& get_instance();
	if (is_array($uri))
	{
		$uri = implode('/', $uri);
	}
	$index_page = $t->config->item('index_page') ? $t->config->item('index_page').'/':'';
	if ($uri == '')
	{
		return 'http://localhost:'.$prefix.'/'.$index_page;
	}
	else
	{
		$suffix = ($t->config->item('url_suffix') == FALSE) ? '' : $t->config->item('url_suffix');
		return 'http://localhost:'.$prefix.'/'.$index_page.trim($uri, '/').$suffix;
	}
}

function quote_replace($string){
	if(is_array($string)) {
		foreach($string as $key => $val) {
			$string[$key] = quote_replace($val);
		}
	} else {
		$string = trim($string);
		$f = array('"','&quot;');
		$r = array('“','“');
		$string = str_replace($f,$r,$string);
	}
	return $string;
}

function create_query($k,$ka='')
{
	$t=& get_instance();
	$get = $t->input->get();
	if(is_array($get))
	{
		foreach($get as $k1=>$v1)
		{
			if($k1 == $k || $k1 == $ka) unset($get[$k1]);
			else $get[$k1] = $k1.'='.urlencode($v1);
		}
		return implode('&',$get);
	} else return '';
}

function save_cache($id, $data)
{		
	$CI =& get_instance();
	$CI->load->helper('file');
	$path = 'cache/static/';
	if (write_file($path.$id, serialize($data)))
	{
		@chmod($path.$id, 0777);
		return TRUE;			
	}
	return FALSE;
}

function get_cache($id)
{
	$ci =& get_instance();
	$ci->load->helper('file');
	$path = 'cache/static/';
	if ( ! file_exists($path.$id))
	{
		return FALSE;
	}
	$data = read_file($path.$id);
	return unserialize($data);
}

function curl_get($url,$postFields=false,$timeout=0,$h=array())
{                    
	$response = '';
	if(function_exists('curl_init') && function_exists('curl_exec'))
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_FAILONERROR, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);  
 		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		
		if( ! empty($h)) curl_setopt($ch,CURLOPT_HTTPHEADER,$h);
		
		if($timeout > 0) curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);

		if (is_array($postFields) && 0 < count($postFields))
		{
			$postBodyString = "";
			$postMultipart = false;
			foreach ($postFields as $k => $v)
			{
				if("@" != substr($v, 0, 1))//判断是不是文件上传
				{
					$postBodyString .= "$k=" . urlencode($v) . "&"; 
				}
				else//文件上传用multipart/form-data，否则用www-form-urlencoded
				{
					$postMultipart = true;
					$postFields[$k] = new CURLFile(FCPATH.ltrim($v,'@'));
				}
			}
			unset($k, $v);
			
			curl_setopt($ch, CURLOPT_POST, true);
			if ($postMultipart)
			{
				curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
			}
			else
			{
				curl_setopt($ch, CURLOPT_POSTFIELDS, substr($postBodyString,0,-1));
			}
		}
		else 
		{
			curl_setopt($ch, CURLOPT_POST, false);
		}
		$response = curl_exec($ch);
		curl_close($ch);
	}
	return $response;
}

function my_site_url($uri = '')
{
	$my_obj =& get_instance();
	if (is_array($uri))
	{
		$uri = implode('/', $uri);
	}
	$index_page = $my_obj->config->item('index_page') ? $my_obj->config->item('index_page').'/':'';
	if ($uri == '')
	{
		return ROOT_PATH.$index_page;
	}
	else
	{
		$suffix = ($my_obj->config->item('url_suffix') == FALSE) ? '' : $my_obj->config->item('url_suffix');
		return ROOT_PATH.$index_page.trim($uri, '/').$suffix; 
	}
}

function get_curren_url()
{
	$r_uri = '';
	if(isset($_SERVER["REQUEST_URI"]) && ! empty($_SERVER["REQUEST_URI"]))
	{
		$r_uri = $_SERVER["REQUEST_URI"];
	}
	else if(isset($_SERVER['PATH_INFO']) && ! empty($_SERVER["PATH_INFO"]))
	{
		$r_uri = $_SERVER['SCRIPT_NAME'].$_SERVER["PATH_INFO"];
		if($_SERVER['QUERY_STRING']) $r_uri .= '?' . $_SERVER['QUERY_STRING'];
	}
	$r_uri='http://'.$_SERVER['SERVER_NAME'].$r_uri;
	$my_obj=& get_instance();
	if( ! $my_obj->config->item('index_page')) $r_uri = str_replace('/index.php','',$r_uri);
	return urlencode($r_uri);
}

function echo_msg($msg,$rdr='',$infotype='no',$target='_self')
{
	if(empty($rdr))
	{
		if (isset($_SERVER['HTTP_REFERER'])) $rdr=$_SERVER['HTTP_REFERER'];
		else $rdr="javascript:window.history.back();";
	}
	$tx_msg=array('infotype'=>$infotype,'infos'=>$msg,'red_url'=>$rdr,'target'=>$target);
	$my_obj=& get_instance();
	global $CI;
	if (class_exists('CI_DB') && isset($CI->db))
	{
		$CI->db->close();
	}
	die($my_obj->load->view(TPL_FOLDER.'msg',$tx_msg,true));
}

function diem($msg = '')
{
	global $CI;
	if (class_exists('CI_DB') && isset($CI->db))
	{
		$CI->db->close();
	}
	die($msg);
}

function do_upload($up_config)
{
  if(is_array($up_config))
  {
  	foreach($up_config as $key=>$value)
	{
		$$key=$value;
	}
  }
  if( ! isset($form_name) || ! isset($up_path) || ! isset($suffix))
  {
	  return array('status'=>FALSE,'upload_errors'=>"<li>配置参数有误</li>");
  }
  if ( ! is_uploaded_file($_FILES[$form_name]['tmp_name']))
  {
	 return array('status'=>TRUE,'file_path'=>'');
  }
  
  $up_path = rtrim($up_path,'/').'/';
  if ( ! file_exists($up_path))
  {
     @mkdir($up_path);
  }
 
  $config['upload_path'] = $up_path;
  $config['allowed_types'] = $suffix;
  $config['encrypt_name'] = TRUE;
  if(isset($max_size)) $config['max_size'] = $max_size;

  $t=& get_instance();
  $t->load->library('upload');
  $t->upload->initialize($config);
 
  if ( ! $t->upload->do_upload($form_name))
  {
	  return array('status'=>FALSE,'upload_errors'=>$t->upload->display_errors('<li>', '</li>'));
  } 
  else
  {
	  $data = $t->upload->data();
	  $file_data=array('status'=>TRUE,'file_path'=>$up_path.$data['file_name'],'data'=>$data);
	  return $file_data;
  }
}

function strcut($string, $length, $dot = '…',$charset='utf-8')
{
	$strlen = strlen($string);
	if($strlen <= $length) return $string;
	$strcut = '';
	if(strtolower($charset) == 'utf-8')
	{
		$n = $tn = $noc = 0;
		while($n < $strlen)
		{
			$t = ord($string[$n]);
			if($t == 9 || $t == 10 || (32 <= $t && $t <= 126)) {
				$tn = 1; $n++; $noc++;
			} 
			elseif(194 <= $t && $t <= 223) {
				$tn = 2; $n += 2; $noc += 2;
			} 
			elseif(224 <= $t && $t <= 239) {
				$tn = 3; $n += 3; $noc += 3;
			} 
			elseif(240 <= $t && $t <= 247) {
				$tn = 4; $n += 4; $noc += 4;
			} 
			elseif(248 <= $t && $t <= 251) {
				$tn = 5; $n += 5; $noc += 5;
			} 
			elseif($t == 252 || $t == 253) 
			{
				$tn = 6; $n += 6; $noc += 6;
			}
			else 
			{
				$n++;
			}
			if($noc >= $length) break;
		}
		if($noc > $length) $n -= $tn;
		$strcut = substr($string, 0, $n);
	}
	else
	{
		$dotlen = strlen($dot); 
		$maxi = $length - $dotlen - 1;
		for($i = 0; $i < $maxi; $i++)
		{
			$strcut .= ord($string[$i]) > 127 ? $string[$i].$string[++$i] : $string[$i];
		}
	}
	return $strcut;
}

function format_curren($v)
{
	if(is_numeric($v)) return '￥'.number_format($v,2);
	else return '￥'.number_format(0,2);
}

function replace_htmlAndjs($document)
{
	$document = trim($document);
	if (strlen($document) <= 0)
	{
	  return $document;
	}
	$search = array ("'<script[^>]*?>.*?</script>'si",  // 去掉 javascript
					  "'<[\/\!]*?[^<>]*?>'si",          // 去掉 HTML 标记
					  "'([\r\n\t])[\s]+'",                // 去掉空白字符
					  "'&(quot|#34);'i",                // 替换 HTML 实体
					  "'&(amp|#38);'i",
					  "'&(lt|#60);'i",
					  "'&(gt|#62);'i",
					  "'&(nbsp|#160);'i"
			  );// 作为 PHP 代码运行
	
	$replace = array ("", "","","\"","&","<",">"," ");
	return @preg_replace ($search, $replace, $document);
}

function get_remote_img($url,$save_path)
{
	if($url == '') return false;

    $resp = '';
    $img = '';
    $fp2 = '';

	$ext = strrchr($url,".");
	$ext = strtolower($ext);
	$filename = md5(uniqid(mt_rand()));
    $t =& get_instance();
	if($t->session->userdata("sq_user_id")){
        $filename = $t->session->userdata("sq_user_id").$filename;
	}
	$save_path = rtrim($save_path,'/').'/';
	if( ! file_exists($save_path))
	{
		@mkdir($save_path);
	}

	$save_path .= $filename.$ext;
	ob_start(); 
	$resp = @readfile($url);
	if( ! $resp) return false;
    $img = ob_get_contents();
    ob_end_clean();

	$fp2 = @fopen($save_path, FOPEN_WRITE_CREATE_DESTRUCTIVE);
	fwrite($fp2,$img);
	fclose($fp2);

	return $save_path;
}

function pick_remote_img($url,$save_path)
{
    $pic = '';
	$t =& get_instance();
    $pic = get_remote_img($url,$save_path);
	if($pic)
	{
        $size = getimagesize($pic);

        $type = explode('.',$pic);
        $pic_type = $type[1];
        $from =explode('/',$url);
        $from_url = $from[2];
        $file_name = md5(uniqid(mt_rand())).'.'.$pic_type;
		if($t->session->userdata("sq_user_id")){
            $file_name = $t->session->userdata("sq_user_id").$file_name;
		}

        $res = array();

        require_once('alimedia/alimage.class.php');
        $ak = '23318394';								// 用户的AK (user app key)
        $sk = 'efd78203beb9a7e97ee5c5180b03a795';		// 用户的SK (user secret key)
        $namespace = 'sengo';						// 空间名称  (user namespace)
        $aliImage  = new AlibabaImage($ak, $sk);		//设置AK和SK
        $uploadPolicy = new UploadPolicy( $namespace );	// 上传策略。并设置空间名
        $uploadOption = new UploadOption();
        $uploadOption->dir = '/up/imgs/'.date('Ymd').'/';	// 文件路径，(可选，默认根目录"/")
        $uploadOption->name = $file_name;			// 文件名，(可选，不能包含"/"。若为空，则默认使用文件名)

        /*第三步：（必须）进行文件上传*/
        $res = $aliImage->upload( $pic, $uploadPolicy, $uploadOption );

//        echo "<img src='http://www.sengo.cn/".$pic."'>";
//        echo "<img src='".$res['url']."'>";
//        $res['old_pic'] = $pic;
//        print_r($res);return false;

        if($res['isSuccess']==true){

            //加入到用户图片中
            $save_data['size_width'] = $size[0];
            $save_data['size_height'] = $size[1];
            $save_data['user_id'] = $t->session->userdata("sq_user_id");
            $save_data['title'] = '网址采集';
            $save_data['big_pic_path'] = $res['url'];
            $save_data['create_date'] = time();
            $save_data['f_site'] = $from_url;
			$save_data['from_type'] = 3;
			$save_data['from_terminal'] = 1;

            if($save_data['size_width']>500 && $save_data['size_height']>4000){
                $height= ceil($save_data['size_height']/($save_data['size_width']/500));

                if($height>4000){
                    $height = 4000;
                    $save_data['pic_path'] = $save_data['big_pic_path'].'@'.$height.'h';
                }else{
                    $save_data['pic_path'] =  $save_data['big_pic_path'].'@'.$height.'h_500w';
                }
            }else  if($save_data['size_width']>500 && $save_data['size_height']>1920){
                $height= ceil($save_data['size_height']/($save_data['size_width']/500));

                if($height>1920){
                    $height = 1920;
                    $save_data['pic_path'] = $save_data['big_pic_path'].'@'.$height.'h';
                }else{
                    $save_data['pic_path'] =  $save_data['big_pic_path'].'@'.$height.'h_500w';
                }
            }else if($save_data['size_width']>500 && $save_data['size_height']>1360){
                $height= ceil($save_data['size_height']/($save_data['size_width']/500));

                if($height>1360){
                    $height = 1360;
                    $save_data['pic_path'] = $save_data['big_pic_path'].'@'.$height.'h';
                }else{
                    $save_data['pic_path'] =  $save_data['big_pic_path'].'@'.$height.'h_500w';
                }
            }else if($save_data['size_width']>500){
                $save_data['pic_path'] =  $save_data['big_pic_path'].'@500w';
            }else if($save_data['size_height']>800){
                $save_data['pic_path'] =  $save_data['big_pic_path'].'@800h';
            }else{
                $save_data['pic_path'] = $save_data['big_pic_path'];
            }

            $t->db->insert('sq_image',$save_data);
            $id = $t->db->insert_id();

            $data['success'] = 101;
            $data['pic_path'] = $res['url'];
            $data['id'] = $id;

        }else{
            $data['success'] = 100;
        }

        unlink($pic);

		//原网址上传图片
//		$resp = curl_get(site_urls('img_manage/add','img'),array('user_id' => $t->session->userdata("sq_user_id"),'pic' => '@'.$pic,'sign' => md5($t->session->userdata("sq_user_id").$t->config->item('md5_encode_key'))));
//		@unlink($pic);
//		if(strpos($resp,'http://') === 0)
//		{
//			return $resp;
//		}
	}else{
        $data['success'] = 100;
    }
//	return $url;
    return json_encode($data);
}
	
function send_email($to,$sb,$mg)
{
	$my_obj=& get_instance();
	$my_obj->config->load('email_config', FALSE, TRUE);
	$my_obj->config->load('site_config', FALSE, TRUE);
	$config['smtp_host'] = $my_obj->config->item('smtp_host');
	$config['smtp_user'] = $my_obj->config->item('smtp_user');
	$config['smtp_pass'] = $my_obj->config->item('smtp_pass');
	$config['protocol'] = 'smtp';
	$config['charset'] = 'utf-8';
	$config['mailtype'] = 'html';
	$config['wordwrap'] = TRUE;
	
	$my_obj->load->library('email',$config);
	$my_obj->email->from($my_obj->config->item('smtp_email'), $my_obj->config->item('sys_site_name'));
	$my_obj->email->to($to); 
	
	$my_obj->email->subject($sb);
	$my_obj->email->message($mg); 
	if($my_obj->email->send())
	{
		return TRUE;
	}
	else
	{
		return FALSE;
	}
}

function get_ads($id)
{
	$my_obj=& get_instance();
	$row = $my_obj->common_model->get_record('SELECT id,title,hplink,file_path,width,height,js_code FROM '.$my_obj->db->dbprefix.'sq_ads WHERE id = '.$id.' AND is_close = 0');
	if($row)
	{
		if($row->js_code)
		{
			return $row->js_code;
		}
		else
		{
			return $my_obj->load->view(TPL_FOLDER.'ads_img',array('ads'=>$row),true);
		}
	}
	return ;
}

function filter_script($document)
{
	$document = trim($document);
	if (strlen($document) <= 0)
	{
	  return $document;
	}
	$search = array ("'<script[^>]*?>.*?</script>'si",  // 去掉 javascript
					"'<vbscript[^>]*?>.*?</vbscript>'si",  // 去掉 vbscript
					  "'<style[^>]*?>.*?</style>'si",          // style
					  "'<link[^>]*?/>'si",          // style
					   "'<meta[^>]*?/>'si",          // meta
					  "'<iframe[^>]*?>.*?</iframe>'si",          // iframe
					  "'<frameset[^>]*?>.*?</frameset>'si",          // frameset
					  "'<frame[^>]*?>.*?</frame>'si",          // iframe
					  "'<form[^>]*?>.*?</form>'si",          // form
					  "'<applet[^>]*?>.*?</applet>'si",          // form
					  "'<body[^>]*?>.*?</body>'si",          // body
					  "'<html[^>]*?>.*?</html>'si",          // html
					  "'<head[^>]*?>.*?</head>'si"          // head
			  );// 作为 PHP 代码运行
	
	$replace = array ('', '', '', '', '', '', '', '', '', '', '', '', '');
	return @preg_replace ($search, $replace, $document);
}

function format_num($n)
{
	if( ! is_numeric($n)) return $n;
	if($n >= 10000) $n = round($n / 10000,1).'万';
	return $n;
}

function alido($case,$up_img,$name=NULL,$files = NULL,$rand = 0){

	require_once('alimedia/alimage.class.php');

	$ak = '23302076';								// 用户的AK (user app key)
	$sk = 'bede9e60f311c9848a9c978b3581640f';		// 用户的SK (user secret key)
	$namespace = 'happylife';						// 空间名称  (user namespace)

	$aliImage  = new AlibabaImage($ak, $sk);		//设置AK和SK

	/*
     * 1.上传本地照片
     * 2.删除图片
     * 3.视频上传（商品视频）
     * 4.获取图片大小
     * */

    echo $rand;

	if($case==1){

		$uploadPolicy = new UploadPolicy( $namespace );	// 上传策略。并设置空间名
		$uploadOption = new UploadOption();
		$uploadOption->dir = $files;	// 文件路径，(可选，默认根目录"/")
		$uploadOption->name = $name;			// 文件名，(可选，不能包含"/"。若为空，则默认使用文件名)

		/*第三步：（必须）进行文件上传*/
		$re_data = $aliImage->upload( $up_img, $uploadPolicy, $uploadOption );

	}
	else if($case==2)
	{
		if($up_img==''){
			$re_data['success'] = 101;return $re_data;die;
		}

		$arr = explode('/',$up_img);
		$filename = end($arr);

		unset($arr[count($arr)-1]);
		unset($arr[0]);
		unset($arr[1]);
		unset($arr[2]);

		$dir = '';
		foreach($arr as $row){
			$dir.= '/'.$row;
		}

		$res = $aliImage->deleteFile($namespace, $dir, $filename );
		if($res['isSuccess']==true){
			$re_data['success'] = 101;
		}else{
			$re_data['success'] = 100;
		}

	}
	else if($case==3){
		$type = explode('.',$up_img['name']);
		$filename = md5(time().rand(1111,9999)).'.'.$type[1];

		/*第二步：（必须）在上传策略UploadPolicy中指定用户空间名。也可以根据需要设置其他参数*/
		$uploadPolicy = new UploadPolicy( $namespace );	// 上传策略。并设置空间名
		$uploadPolicy->dir = "/up/$files/".date('Ymd').'/';;	// 文件路径，(可选，默认根目录"/")
		$uploadPolicy->name = $filename;			// 文件名，(可选，不能包含"/"。若为空，则默认使用文件名)
		/*（可选）开发者可以在UploadOption中设置文件分片的大小（范围100K < size < 10M）。如果不指定分块大小，则为默认值2M。*/
		$uploadOption = new UploadOption();
		$uploadOption->blockSize = 1*1024*1024;		//设置分块大小为1M

		/*第三步：（必须）创建分片上传任务，完成初始化*/
		$filePath = $up_img['tmp_name'];
		$httpRes = $aliImage->multipartInit( $filePath, $uploadPolicy, $uploadOption );
		//return $httpRes;die;

		if( $httpRes ['isSuccess'] ) {
			/*第四步：（如果初始化成功）继续分片上传任务，完成文件上传*/
			$fileSize = filesize ( iconv('UTF-8','GB2312',$filePath) );		// 文件大小
			$blockSize = $uploadOption->blockSize;	// 文件分片大小
			$blockNum = intval ( ceil ( $fileSize / $blockSize ) ); // 文件分片后的块数
			for($i = 2; $i <= $blockNum; $i ++) {
				$uploadOption->setPartNumber($i);	//待上传的文件块编号
				$httpRes = $aliImage->multipartUpload( $filePath, $uploadPolicy, $uploadOption );
				//var_dump($httpRes);
				if ( !$httpRes ['isSuccess'] ) {
					/*如果分片上传失败，则取消整个任务*/
					//var_dump("文件块上传失败，立即取消分片上传任务");
					$httpRes = $aliImage->multipartCancel( $uploadPolicy, $uploadOption );
					//var_dump($httpRes);
					$re_data['success'] = 100;
					$re_data['error'] = '分片上传失败';
					break;
				}
				if($i == $blockNum) {
					/*如果上传最后一个文件块，则完成整个任务*/
					$uploadOption->setMd5(md5_file ( iconv('UTF-8','GB2312',$filePath) ));
					$httpRes = $aliImage->multipartComplete( $uploadPolicy, $uploadOption );
					//return($httpRes);
					$re_data['success'] = 101;
					$re_data['message'] = $httpRes['url'];
				}
			}
		}
	}
	else if($case==4){
		$img = explode('/',$up_img);
		$filename = $img[count($img)-1];
		unset($img[count($img)-1]);
		unset($img[0]);
		unset($img[1]);
		unset($img[2]);
		$dir = implode('/',$img);
		$res = $aliImage->getFileInfo($namespace, $dir, $filename);
		$re_data = ['height'=>$res['imageHeight'],'width'=>$res['imageWidth']];
	}

	return $re_data;
}

function alido1($case,$up_img,$files = NULL,$is_cun = NULL){

	$CI =& get_instance();
	if($user_id = $CI->session->userdata('user_id'))
	{
		$CI->user_id = (int)$user_id;
	}else{
		$CI->user_id = (int)0;
	}

	require_once('alimedia/alimage.class.php');

	$ak = '23302076';								// 用户的AK (user app key)
	$sk = 'bede9e60f311c9848a9c978b3581640f';		// 用户的SK (user secret key)
	$namespace = 'happylife';						// 空间名称  (user namespace)

	$aliImage  = new AlibabaImage($ak, $sk);		//设置AK和SK

	/*
     * 1.上传本地照片
     * 2.单路径上传（商品主图）
	 * 3.删除图片
     * 4.视频上传（商品视频）
     * 5.上传图片的$_files信息，返回成功，路径
     * */

	if($case==1){

		$arr = explode('/',$up_img['file_path']);
		$filename = $arr[count($arr)-1];

		$uploadPolicy = new UploadPolicy( $namespace );	// 上传策略。并设置空间名
		$uploadOption = new UploadOption();
		$uploadOption->dir = "/up/$files/".date('Ymd').'/';	// 文件路径，(可选，默认根目录"/")
		$uploadOption->name = $filename;			// 文件名，(可选，不能包含"/"。若为空，则默认使用文件名)

		/*第三步：（必须）进行文件上传*/
		$res = $aliImage->upload( $up_img['file_path'], $uploadPolicy, $uploadOption );

		@unlink($up_img['file_path']);//删除图片

		if($res['isSuccess']==true)
		{
			$data = array(
				'user_id' => $CI->user_id,
				'title' => $up_img['data']['orig_name'],
				'pic_path' => $res['url'],
				'sizes' => $up_img['data']['file_size'] * 1024,
				'pix_w' => $up_img['data']['image_width'],
				'pix_h' => $up_img['data']['image_height'],
				'create_date' => time()
			);
			$CI->db->insert('shop_upload',$data);
		}

		$re_data['success'] = 101;
		$re_data['message'] = $res['url'];

	}
	else if($case==2){
		$arr = explode('/',$up_img);
		$filename = $arr[count($arr)-1];

		$new = explode('.',$filename);
		$title = $new[1];

		$uploadPolicy = new UploadPolicy( $namespace );	// 上传策略。并设置空间名
		$uploadOption = new UploadOption();
		$uploadOption->dir = "/up/$files/".date('Ymd').'/';	// 文件路径，(可选，默认根目录"/")
		$uploadOption->name = $filename;			// 文件名，(可选，不能包含"/"。若为空，则默认使用文件名)

		/*第三步：（必须）进行文件上传*/
		$res = $aliImage->upload( $up_img, $uploadPolicy, $uploadOption );

		@unlink($up_img);//删除图片

		$re_data = '';

		if($res['isSuccess']==true)
		{
			$data = array(
				'user_id' => $CI->user_id,
				'title' => $title,
				'pic_path' => $res['url'],
				'sizes' => $res['fileSize'],
				'pix_w' => 0,
				'pix_h' => 0,
				'create_date' => time()
			);
			$CI->db->insert('shop_upload',$data);
			$re_data = $res['url'];
		}

	}
    else if($case==3)
    {
        if($up_img==''){
            $re_data['success'] = 101;return $re_data;die;
        }

        $arr = explode('/',$up_img);
        if(!isset($arr[2])){ $re_data['success'] = 101;return $re_data;die; }
        if($arr[2]=='img.sengo.cn' || $arr[2] !='sengo.image.alimmdn.com'){
            $re_data['success'] = 101;return $re_data;die;
        }

        $filename = end($arr);

        unset($arr[count($arr)-1]);
        unset($arr[0]);
        unset($arr[1]);
        unset($arr[2]);

        $dir = '';
        foreach($arr as $row){
            $dir.= '/'.$row;
        }

        $res = $aliImage->deleteFile($namespace, $dir, $filename );
        if($res['isSuccess']==true){
            $re_data['success'] = 101;
        }else{
            $re_data['success'] = 100;
        }

    }
	else if($case==4){
        $type = explode('.',$up_img['name']);
		$filename = md5(time().rand(1111,9999)).'.'.$type[1];

		/*第二步：（必须）在上传策略UploadPolicy中指定用户空间名。也可以根据需要设置其他参数*/
		$uploadPolicy = new UploadPolicy( $namespace );	// 上传策略。并设置空间名
		$uploadPolicy->dir = "/up/$files/".date('Ymd').'/';;	// 文件路径，(可选，默认根目录"/")
		$uploadPolicy->name = $filename;			// 文件名，(可选，不能包含"/"。若为空，则默认使用文件名)
		/*（可选）开发者可以在UploadOption中设置文件分片的大小（范围100K < size < 10M）。如果不指定分块大小，则为默认值2M。*/
		$uploadOption = new UploadOption();
		$uploadOption->blockSize = 1*1024*1024;		//设置分块大小为1M

		/*第三步：（必须）创建分片上传任务，完成初始化*/
		$filePath = $up_img['tmp_name'];
		$httpRes = $aliImage->multipartInit( $filePath, $uploadPolicy, $uploadOption );
		//return $httpRes;die;

		if( $httpRes ['isSuccess'] ) {
			/*第四步：（如果初始化成功）继续分片上传任务，完成文件上传*/
			$fileSize = filesize ( iconv('UTF-8','GB2312',$filePath) );		// 文件大小
			$blockSize = $uploadOption->blockSize;	// 文件分片大小
			$blockNum = intval ( ceil ( $fileSize / $blockSize ) ); // 文件分片后的块数
			for($i = 2; $i <= $blockNum; $i ++) {
				$uploadOption->setPartNumber($i);	//待上传的文件块编号
				$httpRes = $aliImage->multipartUpload( $filePath, $uploadPolicy, $uploadOption );
				//var_dump($httpRes);
				if ( !$httpRes ['isSuccess'] ) {
					/*如果分片上传失败，则取消整个任务*/
					//var_dump("文件块上传失败，立即取消分片上传任务");
					$httpRes = $aliImage->multipartCancel( $uploadPolicy, $uploadOption );
					//var_dump($httpRes);
					$re_data['success'] = 100;
					$re_data['error'] = '分片上传失败';
					break;
				}
				if($i == $blockNum) {
					/*如果上传最后一个文件块，则完成整个任务*/
					$uploadOption->setMd5(md5_file ( iconv('UTF-8','GB2312',$filePath) ));
					$httpRes = $aliImage->multipartComplete( $uploadPolicy, $uploadOption );
					//return($httpRes);
					$re_data['success'] = 101;
					$re_data['message'] = $httpRes['url'];
				}
			}
		}
	}
	else if($case==5){
        $type = explode('.',$up_img['name']);

		$uploadPolicy = new UploadPolicy( $namespace );	// 上传策略。并设置空间名
		$uploadOption = new UploadOption();
		$uploadOption->dir = "/up/$files/".date('Ymd').'/';	// 文件路径，(可选，默认根目录"/")
		$uploadOption->name = md5(time().rand(1111,9999)).'.'.$type[1];			// 文件名，(可选，不能包含"/"。若为空，则默认使用文件名)

		/*第三步：（必须）进行文件上传*/
		$res = $aliImage->upload( $up_img['tmp_name'], $uploadPolicy, $uploadOption );
		@unlink($up_img['tmp_name']);//删除图片

        if($res['isSuccess']){
            $re_data['success'] = 101;
            $re_data['message'] = $res['url'];
        }else{
            $re_data['success'] = 100;
            $re_data['error'] = '上传失败';
        }
	}

	return $re_data;
}

function up_img_size($img){
    foreach($img as $k=>$row){
        if(isset($row->pic_path)){
            $img_url = explode('/',$row->pic_path);
            if(isset($row->size_width) && $row->size_width>200 && $img_url[2]==IMG_URL){
                $pic = explode('@',$row->pic_path);
                $img[$k]->pic_path = $pic[0].'@200w';
            }
        }
    }
    return $img;
}

if ( ! function_exists( 'exif_imagetype' ) ) {
    function exif_imagetype ($filename ) {
        if ( (list($width, $height, $type, $attr) = getimagesize( $filename ) ) !== false ) {
           return $type;
        }
    return false;
    }
}
?>

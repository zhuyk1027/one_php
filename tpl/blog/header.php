<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=10,IE=9,IE=8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">
    <title><?=$website_title?></title>
    <link rel="shortcut icon" type="image/x-icon" href="/tpl/blog/img/favicon.ico" media="screen" />

    <meta name="author" content="朱耀昆 zhuyk zhuyaokun 朱耀昆PHP开发工程师 <?=$website_title?>">
    <meta name="keywords" content="PHP程序员，朱耀昆博客-优秀PHP技术博客，朱耀昆，zhuyk，zhuyaokun，百洋商城，乐生活，森果，学乎网">
    <meta name="description" content="朱耀昆，资深PHP工程师，优秀PHP技术博客。分享原创教程，技术文档与其他实用分享资源，坚持做优质资源: 写好代码，让代码更扎实稳固，自己才会有质的飞跃">
    <meta name="copyright" content="朱耀昆 Zhuyk">

    <meta property="og:type" content="article">
    <meta property="og:url" content="<?=WEB_URL?>">
    <meta property="article:published_time" content="2016-10-27T14:03:04+08:00">
    <meta property="article:author" content="朱耀昆 zhuyk zhuyaokun 朱耀昆PHP开发工程师 <?=$website_title?>">
    <meta property="article:published_first" content="朱耀昆博客<?=WEB_URL?>">
    <meta property="og:image" content="<?=WEB_URL?>logo.jpg">
    <meta property="og:release_date" content="2016-10-27T14:03:04+08:00">
    <meta property="og:title" content="PHP程序员，朱耀昆博客-优秀PHP技术博客，朱耀昆 zhuyk zhuyaokun 朱耀昆PHP开发工程师 <?=$website_title?>">
    <meta property="og:description" content="<?=$website_title?> 朱耀昆优秀PHP技术博客。分享原创教程，技术文档与其他实用分享资源，坚持做优质资源: 写好代码，让代码更扎实稳固，自己才会有质的飞跃">
    <meta name="robots" content="all">
    <meta name="generator" content="php">
    <meta http-equiv="Content-Language" content="zh-cn">
    <meta http-equiv="Content-Script-Type" content="text/javascript">
    <meta http-equiv="Cache-Control" content="no-siteapp">
    <meta name="HandheldFriendly" content="true">
    <meta name="screen-orientation" content="portrait">
    <meta name="x5-fullscreen" content="true">
    <meta name="full-screen" content="yes">
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <script>
        window._deel = {
            name: '<?=$website_title?>',
            url: '<?=WEB_URL?>',
            rss: '<?=WEB_URL?>',
            ajaxpager: 'on',
            maillist: '',
            maillistCode: '',
            commenton: 0,
            roll: [1,3]
        }
    </script>
    <link rel="stylesheet" id="style-css" href="<?=BLOG_CSS?>style.css" type="text/css" media="all">
    <style type="text/css">
        .sfs-subscriber-count { width: 88px; overflow: hidden; height: 26px; color: #424242; font: 9px Verdana, Geneva, sans-serif; letter-spacing: 1px; }
        .sfs-count { width: 86px; height: 17px; line-height: 17px; margin: 0 auto; background: #ccc; border: 1px solid #909090; border-top-color: #fff; border-left-color: #fff; }
        .sfs-count span { display: inline-block; height: 11px; line-height: 12px; margin: 2px 1px 2px 2px; padding: 0 2px 0 3px; background: #e4e4e4; border: 1px solid #a2a2a2; border-bottom-color: #fff; border-right-color: #fff; }
        .sfs-stats { font-size: 6px; line-height: 6px; margin: 1px 0 0 1px; word-spacing: 2px; text-align: center; text-transform: uppercase; }
    </style>
    <script type="text/javascript" src="<?=BLOG_JS?>shCore.js"></script>
    <link type="text/css" rel="stylesheet" href="<?=BLOG_CSS?>shCoreDefault.css">
    <!--[if lt IE 9]><script src="<?=BLOG_JS?>html5.js"></script><![endif]-->
    <script type="text/javascript" src="<?=BLOG_JS?>jquery.js?ver=3.0"></script>
    <script type="text/javascript" src="<?=BLOG_JS?>widget.js"></script>
</head>
<body class="home blog"  youdao="bind">
<header class="header">
    <div class="navbar">
        <h1 class="logo"><a href="/" title="<?=$website_title?>"><?=$website_title?></a></h1>
        <ul class="nav">
            <li id="menu-item-51"
                class="menu-item menu-item-type-custom menu-item-object-custom
                current-menu-item current_page_item menu-item-home menu-item-51">
                <a href="/">首页</a>
            </li>
            <?php
                foreach($head_switch as $row) {
                    if($row->is_open==1){
                        echo '<li id="menu-item-'.$row->sort.'" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-'.$row->sort.'"><a href="'.$row->url.'">'.$row->name.'</a></li>';
                    }
                }
            ?>
        </ul>
        <div class="screen-mini">
            <button data-type="screen-nav" class="btn btn-inverse screen-nav"><i class="icon-tasks icon-white"></i></button>
        </div>
        <div class="menu pull-right">
            <form method="get" class="dropdown search-form" action="/blog/blog_list">
                <input class="search-input" name="key" type="text" placeholder="输入关键字搜索" autofocus="" x-webkit-speech="" value="<?=@$_GET['key']?>">
                <input class="btn btn-success search-submit" type="submit" value="搜索">
                <ul class="dropdown-menu search-suggest"></ul>
            </form>
            <div class="btn-group pull-left">
                <!--<button class="btn btn-primary" data-toggle="modal" data-target="#feed">订阅</button>-->
                <button class="btn btn-primary dropdown-toggle" data-toggle="dropdown">关注 <i class="caret"></i></button>
                <ul class="dropdown-menu pull-right">
                    <li><a href="<?=WEIBO?>" target="_blank">新浪微博</a></li>														</ul>
            </div>
        </div>
    </div>
    <div class="speedbar">
         <div class="pull-right"><a href="/login" style="color:#F7F7F7;">登录</a></div>
        <div class="toptip"><strong class="text-success">最新消息：</strong><?=$head['design']?></div>
    </div>
</header>
<section class="container">
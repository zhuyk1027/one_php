<?php  $this->load->view(BLOG.'header'); ?>

    <div class="content-wrap">
        <div class="content">

            <?php  $this->load->view(BLOG.'user_home_head'); ?>

            <h2 class="title">今日AD访问量：</h2>
            <article class="excerpt excerpt-nothumbnail">
                <ul style="float:left;width: 30%;">
                <?php foreach($today_count as $key=>$row){ ?>
                    <li style="padding: 5px;">
                        <?=$row->ad_title?>
                        <span style="width: 30px;background-color: <?php echo json_decode($color)[$key]; ?>;float: right;height: 15px;"></span>
                        <span style="float: right"><?=$row->click?> 次</span>
                    </li>
                <?php  }?>
                </ul>
                <p style="float:right;">
                    <div id='today'></div>
                </p>
            </article>

            <h2 class="title">七天AD访问量：</h2>
            <article class="excerpt excerpt-nothumbnail">
                <ul style="float:left;width: 30%;">
                <?php foreach($seven_count as $key=>$row){ ?>
                    <li style="padding: 5px;">
                        <?=$row->ad_title?>
                        <span style="width: 30px;background-color: <?php echo json_decode($color)[$key]; ?>;float: right;height: 15px;"></span>
                        <span style="float: right"><?=$row->click?> 次</span>
                    </li>
                <?php  }?>
                </ul>
                <p style="float:right;">
                    <div id='seven'></div>
                </p>
            </article>

            <h2 class="title">本月AD访问量：</h2>
            <article class="excerpt excerpt-nothumbnail">
                <ul style="float:left;width: 30%;">
                <?php foreach($month_count as $key=>$row){ ?>
                    <li style="padding: 5px;">
                        <?=$row->ad_title?>
                        <span style="width: 30px;background-color: <?php echo json_decode($color)[$key]; ?>;float: right;height: 15px;"></span>
                        <span style="float: right"><?=$row->click?> 次</span>
                    </li>
                <?php  }?>
                </ul>
                <p style="float:right;">
                    <div id='month'></div>
                </p>
            </article>

            <h2 class="title">上月AD访问量：</h2>
            <article class="excerpt excerpt-nothumbnail">
                <ul style="float:left;width: 30%;">
                <?php foreach($last_month_count as $key=>$row){ ?>
                    <li style="padding: 5px;">
                        <?=$row->ad_title?>
                        <span style="width: 30px;background-color: <?php echo json_decode($color)[$key]; ?>;float: right;height: 15px;"></span>
                        <span style="float: right"><?=$row->click?> 次</span>
                    </li>
                <?php  }?>
                </ul>
                <p style="float:right;">
                    <div id='last_month'></div>
                </p>
            </article>

            <h2 class="title">最近6月AD访问图：</h2>
            <article class="excerpt excerpt-nothumbnail">
                <div id="six_month"></div>
            </article>


        </div>
    </div>
<?php  $this->load->view(BLOG.'right'); ?>
<script type="text/javascript" src="<?=BLOG_JS?>ichart.1.2.1.min.js"></script>
<script>
    $(function(){
//        var data = [
//            {name : 'IE',value : 35.75,color:'#9d4a4a'},
//            {name : 'Chrome',value : 29.84,color:'#5d7f97'}
//        ];

        var data = <?php print_r($percent[0]); ?>;
        var seven_data = <?php print_r($percent[1]); ?>;
        var month_data = <?php print_r($percent[2]); ?>;
        var last_month_data = <?php print_r($percent[3]); ?>;

        new iChart.Pie2D({
            render : 'today',
            data: data,
            title : '今日AD访问量',
            legend : {
                enable : true
            },
            showpercent:true,
            decimalsnum:2,
            width : 600,
            height : 300,
            radius:140
        }).draw();
        new iChart.Pie2D({
            render : 'seven',
            data: seven_data,
            title : '七日AD访问量',
            legend : {
                enable : true
            },
            showpercent:true,
            decimalsnum:2,
            width : 600,
            height : 300,
            radius:140
        }).draw();
        new iChart.Pie2D({
            render : 'month',
            data: month_data,
            title : '本月AD访问量',
            legend : {
                enable : true
            },
            showpercent:true,
            decimalsnum:2,
            width : 600,
            height : 300,
            radius:140
        }).draw();
        new iChart.Pie2D({
            render : 'last_month',
            data: last_month_data,
            title : '上月AD访问量',
            legend : {
                enable : true
            },
            showpercent:true,
            decimalsnum:2,
            width : 600,
            height : 300,
            radius:140
        }).draw();

    });
</script>
<script>
    $(function(){
        //条形图
        var six_data = <?php print_r($clicks); ?>;

        var chart = new iChart.Column2D({
            render : 'six_month',
            data : six_data,
            title : {
                text : '近期AD统计',
                color : '#3e576f'
            },
            footnote : {
                text : 'zhuyk.pub',
                color : '#909090',
                fontsize : 11,
                padding : '0 38'
            },
            width : 800,
            height : 400,
            label : {
                fontsize:11,
                color : '#666666'
            },
            shadow : true,
            shadow_blur : 2,
            shadow_color : '#aaaaaa',
            shadow_offsetx : 1,
            shadow_offsety : 0,
            column_width : 62,
            sub_option : {
                listeners : {
                    parseText : function(r, t) {
                        return t + "次";
                    }
                },
                label : {
                    fontsize:11,
                    fontweight:600,
                    color : '#4572a7'
                },
                border : {
                    width : 2,
                    //radius : '5 5 0 0',//上圆角设置
                    color : '#ffffff'
                }
            },
            coordinate : {
                background_color : null,
                grid_color : '#c0c0c0',
                width : 680,
                axis : {
                    color : '#c0d0e0',
                    width : [0, 0, 1, 0]
                },
                scale : [{
                    position : 'left',
                    start_scale : 0,
                    end_scale : 60,
                    scale_space : 10,
                    scale_enable : false,
                    label : {
                        fontsize:11,
                        color : '#666666'
                    }
                }]
            }
        });

        /**
         *利用自定义组件构造左侧说明文本。
         */
        chart.plugin(new iChart.Custom({
            drawFn:function(){
                /**
                 *计算位置
                 */
                var coo = chart.getCoordinate(),
                    x = coo.get('originx'),
                    y = coo.get('originy'),
                    H = coo.height;
                /**
                 *在左侧的位置，设置逆时针90度的旋转，渲染文字。
                 */
                chart.target.textAlign('center')
                    .textBaseline('middle')
                    .textFont('600 13px Verdana')
                    .fillText('Total percent market share',x-40,y+H/2,false,'#6d869f', false,false,false,-90);

            }
        }));

        chart.draw();
    })
</script>
<?php  $this->load->view(BLOG.'footer'); ?>

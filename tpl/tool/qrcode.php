
<?php include_once('header.php');?>

<script src="<?=PUB_PATH?>js/jquery_qrcode_master/jquery.qrcode.min.js"></script>

<form >
<ul class="clear">
    <li class="backcolor">
        <p class="text_center">生成二维码</p>
        <table>
            <tr>
                <td class="right">链接：</td>
                <td><input type="text" name="url" placeholder="请输入网址..." size="25"></td>
            </tr><tr>
                <td colspan="2" class="text_center">
                    <input type="button" class="create_qrcode button" value="生成二维码">
                    <input type="reset" value="重置" class="button">
                </td>
            </tr><tr style="line-height: 128px;">
                <td class="right">二维码：</td>
                <td>
                    <div id="qrcode" style="line-height: 128px;"></div>
                </td>
            </tr>
        </table>
    </li>
</ul>
</form>

<?php include_once('footer.php');?>
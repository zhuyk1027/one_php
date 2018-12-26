
<?php include_once('header.php');?>

<ul class="clear">
    <li class="backcolor">
        <p class="text_center"><?php echo $title ?></p>
        <table>
            <tr>
                <td class="right">推荐人：</td>
                <td><input type="text" name="recommender" placeholder="推荐人"  size="20" value=""></td>
            </tr><tr >
                <td class="right">生成密码：</td>
                <td>
                    <input type="text" name="pass" placeholder="生成密码"  size="20" value="q12345">
                </td>
            </tr><tr >
                <td class="right">注册人数：</td>
                <td>
                    <input type="text" name="num" placeholder="注册人数"  size="20" value="1">
                </td>
            </tr><tr>
                <td colspan="2" class="text_center">
                    <input type="button" value="sign" onclick="julaibao_signup()" class="button">
                </td>
            </tr>
        </table>
    </li>
</ul>

<?php include_once('master_footer.php');?>
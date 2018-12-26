
<?php include_once('header.php');?>

<ul class="clear">
    <li class="backcolor">
        <p class="text_center"><?php echo $title;?></p>
        <table>
            <tr>
                <td class="right" style="width: 80px;">字符：</td>
                <td><input type="text" name="words" placeholder="字符串" ></td>
            </tr>
            <tr>
                <td colspan="2" class="text_center">
                    <input type="button" class="button" value="加密" onclick="tomd5()">
                </td>
            </tr>
            <tr >
                <td class="right">密文：</td>
                <td>
                    <span name="froms"></span>
                </td>
            </tr>
        </table>
    </li>
</ul>

<?php include_once('footer.php');?>


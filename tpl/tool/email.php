
<?php include_once('header.php');?>

<ul class="clear">
    <li class="backcolor">
        <p class="text_center">邮件发送</p>
        <table>
            <tr>
                <td class="right">收件人：</td>
                <td><input type="text" name="to" placeholder="收件人" ></td>
            </tr><tr >
                <td class="right">标题：</td>
                <td>
                    <input type="text" name="title" placeholder="标题">
                </td>
            </tr><tr >
                <td class="right">内容：</td>
                <td>
                    <textarea name="conts" cols="30" rows="8"
                              placeholder="请输入您要发送的内容（ps：本邮件由本站公共邮箱发出，不承诺任何法律效益，不承担任何责任）"></textarea>
                </td>
            </tr><tr>
                <td colspan="2" class="text_center">
                    <input type="button" onclick="send_email()" class="button" value="发送">
                </td>
            </tr><tr >
                <td class="right">状态：</td>
                <td>
                    <span name="froms"></span>
                </td>
            </tr>
        </table>
    </li>
</ul>

<?php include_once('footer.php');?>
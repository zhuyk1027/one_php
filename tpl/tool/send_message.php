<?php include_once('header.php');?>

    <ul class="clear">
        <li class="backcolor">
            <p>Mobile message send</p>
            <table>
                <tr>
                    <td class="right">Mobile：</td>
                    <td><input name="mobile_code" value=""></td>
                </tr><tr>
                    <td class="right">Number：</td>
                    <td><input name="mobile_message_number" value="1"></td>
                </tr><tr>
                    <td colspan="2" class="text_center"><input type="button" class="button" value="sign" onclick="jump_send_mobile_message()"></td>
                </tr>
            </table>
        </li>
    </ul>

</div>
<?php include_once('master_footer.php');?>

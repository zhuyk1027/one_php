<?php include_once('header.php');?>

    <!--批量签到-->
    <ul class="clear">
        <li class="backcolor">
            <p>All Sign</p>
            <table>
                <tr>
                    <td class="right">pay密码：</td>
                    <td><input type="radio" name="pay_pass_sign" value="1" checked>是
                        <input type="radio" name="pay_pass_sign" value="2">否
                        <input type="radio" name="pay_pass_sign" value="3">全部</td>
                </tr><tr>
                    <td class="right">page：</td>
                    <td><select id="sign_page">
                            <?php for($i=1;$i<100;$i++){
                                echo ' <option value="'.$i.'">'.$i.'</option>';
                            } ?>
                        </select></td>
                </tr><tr>
                    <td class="right">pagesize：</td>
                    <td><input name="sign_page_size" value="100"></td>
                </tr><tr>
                    <td colspan="2" class="text_center"><input type="button" class="button" value="sign" onclick="jump_sign()"></td>
                </tr>
            </table>
        </li>
    </ul>

    <!--批量抽奖-->
    <ul class="clear">
        <li class="backcolor">
            <p>All Lottery</p>
            <table>
                <tr>
                    <td class="right">活动ID：</td>
                    <td><input type="text" name="act_id" placeholder="活动ID"  size="19" value=""></td>
                </tr><tr>
                    <td class="right">pay密码：</td>
                    <td><input type="radio" name="pay_pass" value="1" checked>是
                        <input type="radio" name="pay_pass" value="2">否
                        <input type="radio" name="pay_pass" value="3">全部</td>
                </tr><tr>
                    <td class="right">page：</td>
                    <td><select id="lottery_sign_page">
                            <?php for($i=1;$i<100;$i++){
                                echo ' <option value="'.$i.'">'.$i.'</option>';
                            } ?>
                        </select></td>
                </tr><tr>
                    <td class="right">pagesize：</td>
                    <td><input name="lottery_page_size" value="100"></td>
                </tr><tr>
                    <td colspan="2" class="text_center"><input type="button" class="button" value="sign" onclick="jump()"></td>
                </tr>
            </table>
        </li>
    </ul>

    <!--批量注册-->
    <ul class="clear">
        <li class="backcolor">
            <p>Register</p>
            <table>
                <tr>
                    <td class="right">随机邀请码：</td>
                    <td><input type="radio" name="is_auto" value="1" checked>是
                        <input type="radio" name="is_auto" value="2">否</td>
                </tr><tr>
                    <td class="right">指定邀请码：</td>
                    <td><input type="text" name="invite_code" value="1" ></td>
                </tr><tr>
                    <td class="right">注册数量：</td>
                    <td><select id="register_num">
                            <?php for($i=1;$i<100;$i++){
                                echo ' <option value="'.$i.'">'.$i.'</option>';
                            } ?>
                        </select></td>
                </tr><tr>
                    <td colspan="2" class="text_center">
                        <input type="button" class="button" value="sign" onclick="jump_register()">
                        <input type="button" class="button" value="清除TOKEN" onclick="clear_session()">
                    </td>
                </tr>
            </table>
        </li>
    </ul>

    <!--单个注册-->
    <ul class="clear">
        <li class="backcolor">
            <p>Register</p>

            <table>
                <tr>
                    <td class="right">手机：</td>
                    <td><input type="text" name="phone1"  ></td>
                </tr><tr>
                    <td class="right">验证码：</td>
                    <td><input type="text" name="code1" ></td>
                </tr><tr>
                    <td class="right">邀请码：</td>
                    <td><input type="text" name="invite_code1"></td>
                </tr><tr>
                    <td colspan="2" class="text_center">
                        <input type="button" class="button" value="sign" onclick="register()">
                    </td>
                </tr>
            </table>
        </li>
    </ul>

    <!--查看返利-->
    <ul class="clear">
        <li class="backcolor">
            <p>查看返利金额</p>

            <table>
                <tr>
                    <td class="right">时间：</td>
                    <td><input type="radio" name="datetime" value="1" checked>本月
                        <input type="radio" name="datetime" value="2">上月</td>
                </tr><tr>
                    <td colspan="2" class="text_center">
                        <input type="button" class="button" value="查看" onclick="show_backmoney()">
                    </td>
                </tr>
            </table>
        </li>
    </ul>

<?php include_once('master_footer.php');?>
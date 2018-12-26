
<?php include_once('header.php');?>

<ul class="clear">
    <li class="backcolor">
        <p class="text_center"><?php echo $title;?></p>
        <table>
            <tr>
                <td><textarea name="words" cols="40" rows="8"></textarea></td>
            </tr><tr>
                <td colspan="2" class="text_center">
                    <input type="button" class="button" value="解析" onclick="tojsondecode()">
                </td>
            </tr>
        </table>
    </li>

    <li class="backcolor clear">
        <span name="froms" ></span>
    </li>
</ul>

<?php include_once('footer.php');?>
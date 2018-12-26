
<?php include_once('header.php');?>

<ul class="clear">
    <li class="backcolor">
        <p>base64数据转化:</p>
        <input type="text" name="strword" placeholder="base64数据 or 加密数据" style="width: 100%;" size="20" onblur="this.v();"><br />
        <p>
            <input type="button" class="button" value="加密" onclick="base_64change('encryption')">
            <input type="button" class="button" value="解密" onclick="base_64change('Decrypt')">
        </p>
        <span name="froms" class="htmlspan"></span>
    </li>
</ul>

<?php include_once('footer.php');?>
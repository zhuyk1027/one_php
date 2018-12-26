
<?php include_once('header.php');?>

<ul class="clear">
    <li class="backcolor">
        <p>时间戳转化为日期:</p>
        <input type="text" name="dateinfo" placeholder="时间戳 or 日期" style="width: 100%;" size="20"><br />
        <p>
            <input type="button" value="转化日期" class="button" onclick="time_changes('todate')">
            <input type="button" class="button" value="转化时间戳" onclick="time_changes('totimenum')">
        </p>
        <span name="time_change"></span>
    </li>
</ul>

<?php include_once('footer.php');?>


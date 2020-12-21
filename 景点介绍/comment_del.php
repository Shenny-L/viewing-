<?php
include_once '../inc/mysql.inc.php';
include_once '../inc/config.inc.php';

if(!isset($_GET['id']) || !is_numeric($_GET['id'])){
	exit();
}
$link=connect();
$query="delete from content where id={$_GET['id']}";
$result=execute($link,$query);
$url=$_GET['URL'];
$url=str_replace('"','',$url);
$skip=<<<A
<script type="text/javascript">
window.location.href='{$url}';
</script>
A;
//echo $skip;
header("location:$url?view='comment'");
?>
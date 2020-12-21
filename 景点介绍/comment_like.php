<?php
include_once '../inc/mysql.inc.php';
include_once '../inc/config.inc.php';
if(!isset($_GET['id']) || !is_numeric($_GET['id'])){
	exit();
}
$link=connect();
$user=$_COOKIE['member']['name'];
$query="select * from member where name='$user'";
$result=execute($link,$query);
$data=mysqli_fetch_assoc($result);
$memberID=$data['id'];
$query="select * from content where id={$_GET['id']}";
$result=execute($link,$query);
$data=mysqli_fetch_assoc($result);
$likesID=$data['likes_id'];
$idStr=strval($memberID);
if(preg_match("~\b{$idStr}\b~",$likesID)&&$data['likes']>0)
{
    $query="UPDATE content SET likes=likes-1 where id={$_GET['id']}";
    execute($link,$query);
    $newLikesID=preg_replace("~\b{$idStr}\b~",'',$likesID);
    echo $newLikesID;
    $query="UPDATE content SET likes_id='{$newLikesID}' where id={$_GET['id']}";
    execute($link,$query);
    $id=$_GET['id'];
}
else
{  
    $query="UPDATE content SET likes=likes+1 where id={$_GET['id']}";
    $result=execute($link,$query);
    $likesID=$likesID." ".$idStr." ";
    $query="UPDATE content SET likes_id='{$likesID}' where id={$_GET['id']}";
    execute($link,$query);
}
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

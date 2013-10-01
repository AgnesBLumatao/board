<table width="100%">
<tr>
<td>
<h1>ALL THREADS</h1>
</td>
<td align="right">
<h5>WELCOME <?php eh($user['username']) ?> <a class="btn btn-small btn-primary" href="<?php eh(url('thread/index')) ?>">LOG OUT</a></h5>
</td>
</tr>
</table>
<br />

<?php
$limit=5;
$totalThread=count($threads);
$totalPage=ceil($totalThread/$limit);
if($page==0){
$page=$totalPage;
}?>
<h5>PAGE <?php eh($page)?> OF <?php
if($totalPage>0)
{
eh($totalPage);
}
else
{
eh('1');
}?>
</h5>
<?php
$tmp=array();
foreach ($threads as $v):
$tmp[]=array('id'=>$v->id,'title'=>$v->title);
endforeach;

$start=($page*$limit)-$limit;
if($page!=$totalPage){
$end=($page*$limit)-1;
}
else{
$end=$totalThread-1;
}
?>
<ul>
<?php
if($totalThread>0){
for($x=$start;$x<=$end;$x++){?>
<li>
<a href="<?php eh(url('thread/view', array('page'=>1,'thread_id' => $tmp[$x]['id'],'user_id'=>$user['user_id'], 'username'=>$user['username']))) ?>">
<?php eh($tmp[$x]['title']) ?>
</a>
</li>
<?php
}}
?>
</ul>
<?php
$pageNext=$page+1;
$pagePrev=$page-1;
if($totalPage>1){
?>
<table>
<tr>
<?php if($page!=1){

?>
<td>
<a href="<?php eh(url('thread/home', array('page'=>$pagePrev,'user_id' => $user['user_id'], 'username'=>$user['username']))) ?>">
&larr; previous
</a>
</td>
<?php } if($totalPage>10){
$nums=10;
}
else{
$nums=$totalPage;
}
for($x=1;$x<=$nums;$x++){
if($x!=$page){
?>
<td>
<a href="<?php eh(url('thread/home', array('page'=>$x,'user_id' => $user['user_id'], 'username'=>$user['username']))) ?>">
<?php eh($x) ?>
</a>
</td>
<?php
}
else{?>
<td>
<?php eh($x) ?>
</td>
<?php
}
}
?>
<td>
<?php
if($page!=$totalPage){ ?>
<a href="<?php eh(url('thread/home', array('page'=>$pageNext,'user_id' => $user['user_id'], 'username'=>$user['username']))) ?>">
next &rarr;
</a>
<?php }
?>
</td>
</tr>
</table>
<?php }
?>




<a class="btn btn-large btn-primary" href="<?php eh(url('thread/create',array('user_id'=>$user['user_id'],'username'=>$user['username']))) ?>">Create</a>
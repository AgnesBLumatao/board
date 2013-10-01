<table width="100%">
<tr>
<td>
<h1><?php eh($thread->title) ?></h1>
</td>
<td align="right">
<h5>WELCOME <?php eh($user) ?> <a class="btn btn-small btn-primary" href="<?php eh(url('thread/index')) ?>">LOG OUT</a></h5>
</td>
</tr>
<tr align="right">
<td>
</td>
<td>
<a href="<?php eh(url('thread/home', array('page'=>1,'user_id' => $user_id, 'username'=>$user))) ?>">
back to thread
</a>
</td>
</tr>
</table>
<br />
<hr>
<?php
$limit=5;
$totalComment=count($comments);
$totalPage=ceil($totalComment/$limit);
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
foreach ($comments as $k => $v):
$tmp[]=array('created'=>$v->created,'body'=>$v->body);
endforeach;


$start=($page*$limit)-$limit;
if($page!=$totalPage){
$end=($page*$limit)-1;
}
else{
$end=$totalComment-1;
}

for($x=$start;$x<=$end;$x++){?>
<div class="comment">
<div class="meta">
<?php eh($x+1) ?>:<?php eh($tmp[$x]['created']) ?>
</div>
<div>
<?php eh($tmp[$x]['body']) ?>
</div>
<br />
</div>
<?php
}
$pageNext=$page+1;
$pagePrev=$page-1;
if($totalPage>1){
?>
<table>
<tr>
<?php if($page!=1){

?>
<td>
<a href="<?php eh(url('thread/view', array('page'=>$pagePrev,'thread_id' => $thread->id, 'username'=>$user,'user_id'=>$user_id))) ?>">
&larr; previous
</a>
</td>
<?php }
if($totalPage>10){
$nums=10;
}
else{
$nums=$totalPage;
}
for($x=1;$x<=$nums;$x++){
if($x!=$page){
?>
<td>
<a href="<?php eh(url('thread/view', array('page'=>$x,'thread_id' => $thread->id, 'username'=>$user,'user_id'=>$user_id))) ?>">
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

if($page!=$totalPage){

?>
<td>
<a href="<?php eh(url('thread/view', array('page'=>$pageNext,'thread_id' => $thread->id, 'username'=>$user,'user_id'=>$user_id))) ?>">
next &rarr;
</a>
</td>
<?php } ?>
</tr>
</table>
<?php } ?>

<hr>
<form class="well" method="post" action="<?php eh(url('thread/write', array('page'=>0,'user_id'=>$user_id))) ?>">
<label>Comment</label>
<textarea name="body"><?php eh(Param::get('body')) ?></textarea>
<br />
<input type="hidden" name="thread_id" value="<?php eh($thread->id) ?>">
<input type="hidden" name="page_next" value="write_end">
<button type="submit" class="btn btn-primary">Submit</button>
</form>
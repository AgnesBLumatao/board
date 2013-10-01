<h2><?php eh($thread->title) ?></h2>
<p class="alert alert-success">
You successfully wrote this comment.
</p>
<a href="<?php eh(url('thread/view', array('page'=>0,'thread_id' => $thread->id, 'username'=>$comment->username, 'user_id'=>$user_id))) ?>">
&larr; Back to thread
</a>
<h2><?php eh($thread->title) ?></h2>
<p class="alert alert-success">
You successfully created.
</p>
<a href="<?php eh(url('thread/view', array('page'=>0,'thread_id' => $thread->id, 'username'=>$user['username'],'user_id'=>$user['user_id']))) ?>">
&larr; Go to thread
</a>
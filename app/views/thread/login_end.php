<h2>WELCOME <?php eh($username) ?></h2>

<p class="alert alert-success">
LOGIN SUCCESSFUL!
</p>

<a href="<?php eh(url('thread/home', array('page'=>1, 'user_id'=>$login['id'], 'username'=>$login['username']))) ?>">
&larr; Go to THREAD
</a>
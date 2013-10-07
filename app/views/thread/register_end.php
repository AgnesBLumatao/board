<h2>WELCOME <?php eh($account->username) ?></h2>

<p class="alert alert-success">
You successfully registered.
</p>

<a href="<?php eh(url('thread/home', array('page'=>1, 'user_id'=>$user_id, 'username'=>$account->username))) ?>">
&larr; Go to THREAD
</a>
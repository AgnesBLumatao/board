<table width="100%">
	<tr>
		<td>
		<h1>REGISTRATION</h1>
		</td>
		
		<td align="right">
		<a class="btn btn-large btn-primary" href="<?php eh(url('thread/index')) ?>">LOGIN</a>
		</td>
	</tr>
</table>

<hr>
<?php
if ($account->hasError()):?>
	<div class="alert alert-block">
	<h4 class="alert-heading">Registration error!</h4>
<?php
	if (!empty($account->validation_errors['username']['length'])):?>
		<div>Your <em>username</em> must be between
		<?php eh($account->validation['username']['length'][1]) ?> and
		<?php eh($account->validation['username']['length'][2]) ?> characters in length.
		</div>
<?php
	endif;
	if (!empty($account->validation_errors['password']['length'])):?>
		<div>Your <em>Password</em> must be
		between
		<?php eh($account->validation['password']['length'][1]) ?> and
		<?php eh($account->validation['password']['length'][2]) ?> characters in length.
		</div>
<?php
	endif;?>
	</div>
<?php
elseif($password!=$repassword):?>
	<div class="alert alert-block">
	<h4 class="alert-heading">Registration error!</h4>
	<div>Your <em>password</em> did not match.</div>
	</div>
<?php
elseif($check==1):?>
	<div class="alert alert-block">
	<h4 class="alert-heading">Registration error!</h4>
	<div>Account already exist!</div>
	</div>
<?php
endif;?>

<form method="post" action="<?php eh(url('')) ?>">
	<label>USERNAME:</label>
	<input type="text" class="span3" name="username" value="<?php eh(Param::get('username')) ?>">
	
	<label>PASSWORD:</label>
	<input type="password" class="span3" name="password" value="<?php eh(Param::get('password')) ?>">
	
	<label>REPASSWORD:</label>
	<input type="password" class="span3" name="repassword" value="<?php eh(Param::get('repassword')) ?>">
	
	<br />
	<input type="hidden" name="page_next" value="register_end">
	<button type="submit" class="btn btn-primary">Submit</button>
</form>
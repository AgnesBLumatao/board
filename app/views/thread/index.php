<table width="100%">
	<tr>
		<td>
		    <h1>LOG IN</h1>
		</td>
		
		<td align="right">
            <a class="btn btn-large btn-primary" href="<?php eh(url('thread/register')) ?>">SIGN UP</a>
		</td>
	</tr>
</table>

<?php
if ($invalid) : ?>
	<div class="alert alert-block">
        <h4 class="alert-heading">Login error!</h4>
        <div>Invalid Account!</div>
	</div>
<?php
endif ?>

<hr>
<form method="post" action="#">
	<label>USERNAME:</label>
	<input type="text" class="span3" name="username" value="<?php eh(Param::get('username')) ?>"/>

	<label>PASSWORD:</label>
	<input type="password" class="span3" name="password" value="<?php eh(Param::get('password')) ?>"/>

	<br />
	<input type="hidden" name="page_next" value="login_end">
	<button type="submit" class="btn btn-primary">LOGIN</button>
</form>
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
<hr>
<form method="post" action="<?php eh($login) ?>">
<label>USERNAME:</label>
<input type="text" class="span3" name="username" value="<?php eh(Param::get('username')) ?>"/>
<label>PASSWORD:</label>
<input type="text" class="span3" name="password" value="<?php eh(Param::get('password')) ?>"/>
<br />
<button type="submit" class="btn btn-primary">LOGIN</a>
</form>
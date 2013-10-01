<?php
class Thread extends AppModel
{

public $validation = array(
'title' => array(
'length' => array(
'validate_between', 1, 30,
),
),
);

public function register(Account $account)
{
if (!$account->validate() || $account->password!=$account->repassword) {
throw new ValidationException('invalid account');
}
$db = DB::conn();
$db->query(
'INSERT INTO account SET username = ?, password = ?',
array($account->username, $account->password)
);
return $user_id = $db->lastInsertId();
}

public static function get($id)
{
$db = DB::conn();
$row = $db->row('SELECT * FROM thread WHERE id = ?', array($id));
return new self($row);
}

public static function getUser($username,$password)
{
$db = DB::conn();
$row = $db->row('SELECT * FROM account WHERE username = ? && password = ?', array($username,$password));
if(!empty($row)){
return 1;
}
}

public static function getAccount($username, $password)
{
		
		$db = DB::conn();
		$login = $db->row("SELECT * FROM account WHERE username =? && password =?", array($username,$password));
		if(!empty($login)){
		return $link=url('thread/home', array('page'=>1,'user_id'=>$login['id'],'username'=>$login['username']));
		}else{
		return $link=url('thread/index');
				
	}
}

public static function getAll($user_id)
{
$threads = array();
$db = DB::conn();
$rows = $db->rows('SELECT * FROM thread where user_id=?', array($user_id));
foreach ($rows as $row) {
$threads[] = new Thread($row);
}
return $threads;
}

public static function checkThread($title,$user_id)
{
$threads = array();
$db = DB::conn();
$rows = $db->rows('SELECT * FROM thread where user_id=? && title=?', array($user_id,$title));

if(!empty($rows)){
return 1;
}
}

public function getComments()
{
$comments = array();
$db = DB::conn();
$rows = $db->rows(
'SELECT * FROM comment WHERE thread_id = ? ORDER BY created ASC',
array($this->id)
);

foreach ($rows as $row) {
$comments[] = new Comment($row);
}
return $comments;
}

public function create(Comment $comment)
{
$this->validate();
$comment->validate();
if ($this->hasError() || $comment->hasError()) {
throw new ValidationException('invalid thread or comment');
}
$db = DB::conn();
$db->begin();
$db->query('INSERT INTO thread SET user_id= ?, title = ?, created = NOW()', array($comment->user_id, $this->title));
$this->id = $db->lastInsertId();
// write first comment at the same time
$this->write($comment);
$db->commit();
}

public function write(Comment $comment)
{

if (!$comment->validate()) {
throw new ValidationException('invalid comment');
}

$db = DB::conn();
$db->query(
'INSERT INTO comment SET thread_id = ?, body = ?, created = NOW()',
array($this->id, $comment->body)
);
}
}
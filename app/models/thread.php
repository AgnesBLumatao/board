<?php
class Thread extends AppModel
{
	//title must be 1-30 characters of length
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
	$db->query('INSERT INTO account SET username = ?, password = ?',array($account->username, $account->password));
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
		//if account not found
		if(!empty($row)){
		return 1;
		}
}

public static function getAccount($username, $password)
{
		
	$db = DB::conn();
	$login = $db->row("SELECT * FROM account WHERE username =? && password =?", array($username,$password));	
	return $login;
	
}

public static function getAll($user_id,$page)
{
	$threads = array();
	$db = DB::conn();
	$rows = $db->rows('SELECT * FROM thread where user_id=?', array($user_id));

		foreach ($rows as $v) {
		$threads[] = array('id'=>$v['id'],'title'=>$v['title']);
		}

	$limit=5;
	$totalThread=count($threads);
	$totalPage=ceil($totalThread/$limit);
	$start=($page*$limit)-$limit;
	if($page!=$totalPage){
	$end=($page*$limit)-1;
	}
	else{
	$end=$totalThread-1;
	}
	
	//no page option for pages greater than 10
	//use the next link to access pages greater than 10
	if($totalPage>10){
	$nums=10;
	}
	else{
	$nums=$totalPage;
	}

	return array($threads,$limit,$totalPage,$start,$end,$nums,$page,$totalThread);
}

public static function checkThread($title,$user_id)
{
	$threads = array();
	$db = DB::conn();
	$rows = $db->rows('SELECT * FROM thread where user_id=? && title=?', array($user_id,$title));
		//check if thread title exists on the same account
		if(!empty($rows)){
		return 1;
		}
}

public function getComments($page)
{
	$comments = array();
	$db = DB::conn();
	$rows = $db->rows('SELECT * FROM comment WHERE thread_id = ? ORDER BY created ASC', array($this->id));
		foreach ($rows as $k => $v) {
		$comments[] = array('created'=>$v['created'],'body'=>$v['body']);
		}

	$limit=5;
	$totalComment=count($comments);
	$totalPage=ceil($totalComment/$limit);
		//thread goes to page where new comment is inserted
		if($page==0){
		$page=$totalPage;
		}

	$start=($page*$limit)-$limit;
	if($page!=$totalPage){
	$end=($page*$limit)-1;
	}
	else{
	$end=$totalComment-1;
	}

	//no page option for pages greater than 10
	//use the next link to access pages greater than 10
	if($totalPage>10){
	$nums=10;
	}
	else{
	$nums=$totalPage;
	}

	return array($comments,$limit,$totalPage,$start,$end,$nums,$page);
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
	$db->query('INSERT INTO comment SET thread_id = ?, body = ?, created = NOW()',array($this->id, $comment->body));
}

}
?>
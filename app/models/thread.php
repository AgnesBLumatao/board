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
	$limit=5;
	$offset=($page-1)*$limit;
	$threads = array();
	$db = DB::conn();
	$allThreads= $db->rows("SELECT * FROM thread where user_id=?", array($user_id));
	$query="SELECT * FROM thread where user_id=? LIMIT ".$limit." OFFSET ".$offset;
	$rows = $db->rows($query, array($user_id));

		foreach ($rows as $v) {
		$threads[] = array('id'=>$v['id'],'title'=>$v['title']);
		}

	//for pagination
	$countThread=count($allThreads);
	$totalThread=count($rows);
	$totalPage=ceil($countThread/$limit);

	//maximum links to page number
	$max=10;
	
	//number of pages to skip
	$x=(floor($page/$max))*$max;

	$remaining=$totalPage-$x;
	//get the remaining pages

	if($page==$x || $remaining>10){
	$remaining=10;
	}

	//get the start of page numbers
	if($page==$x){
	$start=$x-9;
	}

	else{
	$start=$x+1;
	}
	
	
	//get the end of page numbers
	if($remaining>0){
	$nums=$start+$remaining;

	}
	else{
	$nums=$start+10;
	}
	
	return array($threads,$totalThread,$totalPage,$nums,$start);
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
	$limit=5;
	$comments = array();
	$db = DB::conn();
	$allComments = $db->rows('SELECT * FROM comment WHERE thread_id = ? ORDER BY created ASC', array($this->id));
	
	$countComment=count($allComments);
	$totalPage=ceil($countComment/$limit);
	//thread goes to page where new comment is inserted
	if($page==0){
	$page=$totalPage;
	}
	
	
	$offset=($page-1)*$limit;
	$query="SELECT * FROM comment WHERE thread_id = ? ORDER BY created ASC LIMIT ".$limit." OFFSET ".$offset;
	$rows = $db->rows($query, array($this->id));
		foreach ($rows as $k => $v) {
		$comments[] = array('created'=>$v['created'],'body'=>$v['body']);
		}

	//for pagination
	$totalComment=count($comments);

	//maximum links to page number
	$max=10;

	//number of pages to skip
	$x=(floor($page/$max))*$max;
	
	$remaining=$totalPage-$x;
	//get the remaining pages

	if($page==$x || $remaining>10){
	$remaining=10;
	}
	
	
	//get the start of page numbers
	if($page==$x){
	$start=$x-9;
	}

	else{
	$start=$x+1;
	}

	//get the end of page numbers
	if($remaining>0){
	$nums=$start+$remaining;

	}
	else{
	$nums=$start+10;
	}

	return array($comments,$totalComment,$totalPage,$nums,$page,$start);
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
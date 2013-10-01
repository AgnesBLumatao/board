<?php
class ThreadController extends AppController
{

public function register()
{

$repassword = Param::get('repassword');
$username=Param::get('username');
$password=Param::get('password');
$message='';
$thread = new Thread;
$account = new Account;
$check=$thread->getUser($username,$password);
$page = Param::get('page_next', 'register');
switch ($page) {
case 'register':
break;
case 'register_end':
$account->username = $username;
$account->password = $password;
$account->repassword = $repassword;
try {
$user_id=$thread->register($account);
} catch (ValidationException $e) {
$page = 'register';
}
break;
default:
throw new NotFoundException("{$page} is not found");
break;
}
$this->set(get_defined_vars());
$this->render($page);

}


public function index()
{
$username=Param::get('username');
$password=Param::get('password');
$login = Thread::getAccount($username, $password);

$this->set(get_defined_vars());
}


public function home()
{
$page=Param::get('page');
$user=array('user_id'=>Param::get('user_id'), 'username'=>Param::get('username'));
$threads=Thread::getAll($user['user_id']);
$this->set(get_defined_vars());

}



public function write()
{
$page=Param::get('page');
$thread = Thread::get(Param::get('thread_id'));
$user_id = Param::get('user_id');
$comment = new Comment;
$page = Param::get('page_next', 'write');
switch ($page) {

case 'write':
break;

case 'write_end':

$comment->username = Param::get('username');
$comment->body = Param::get('body');

try {
$thread->write($comment);
} catch (ValidationException $e) {
$page = 'write';
}
break;
default:
throw new NotFoundException("{$page} is not found");
break;
}
$this->set(get_defined_vars());
$this->render($page);
}

public function create()
{
$user=array('user_id'=>Param::get('user_id'), 'username'=>Param::get('username'));
$title=Param::get('title');
$thread = new Thread;
$comment = new Comment;

$check=Thread::checkThread($title,Param::get('user_id'));

$page = Param::get('page_next', 'create');
switch ($page) {
case 'create':
break;
case 'create_end':
$thread->title = $title;
$comment->user_id =$user['user_id'];
$comment->body = Param::get('body');
try {
$thread->create($comment);
} catch (ValidationException $e) {
$page = 'create';
}
break;
default:
throw new NotFoundException("{$page} is not found");
break;
}
$this->set(get_defined_vars());
$this->render($page);
}


public function view()
{
$user=Param::get('username');
$user_id=Param::get('user_id');
$page=Param::get('page');
$thread = Thread::get(Param::get('thread_id'));
$comments = $thread->getComments();
$this->set(get_defined_vars());
}
}
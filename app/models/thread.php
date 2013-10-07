<?php
class Thread extends AppModel
{
    const THREAD_COMMENT_LIMIT = 5;
    const PAGE_MAX = 10;
    const START_ONE = 9;
    const START_TWO = 1;
    const NEW_CREATE = 0;

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
        if (!$account->validate() OR $account->password != $account->repassword) {
            throw new ValidationException('invalid account');
        }
    
        $db = DB::conn();
        $params = array(
            "username"=>$account->username,
            "password"=>md5($account->password)
        );

        $db->insert("account", $params);
        return $db->lastInsertId();
    }

    public static function get($id)
    {
        $db = DB::conn();
        $row = $db->row('SELECT * FROM thread WHERE id = ?', array($id));
        return new self($row);
    }

    public static function isUserExisting($username, $password)
    {
        $db = DB::conn();
        $row = $db->row(
            'SELECT id FROM account WHERE username = ? AND password = ?',
            array($username, md5($password))
        );

        //if account exist
        return $row? TRUE : FALSE;
    }

    public static function getAccount($username, $password)
    {
        $db = DB::conn();
        
        $login = $db->row(
            "SELECT id,username FROM account WHERE username = ? AND password = ?",
            array($username, md5($password))
        );
        
        return $login;
    }

    public static function getAll($user_id, $page)
    {
        $offset = ($page - 1) * Thread::THREAD_COMMENT_LIMIT;
        $threads = array();
        $db = DB::conn();
        $countThread = $db->value(
            'SELECT count(id) FROM thread where user_id=?',
            array($user_id)
        );
    
        $query="SELECT * FROM thread where user_id=? LIMIT "
            . Thread::THREAD_COMMENT_LIMIT . " OFFSET " . $offset;
        
        $rows = $db->rows($query, array($user_id));

        foreach ($rows as $v) {
            $threads[] = array('id'=>$v['id'], 'title'=>$v['title']);
        }

        $totalPage = self::getTotalPage ($countThread);
        return self::pagination ($page, $totalPage, $threads);
    }

    public static function isThreadExisting($title, $user_id)
    {
        $threads = array();
        $db = DB::conn();
        
        $row = $db->row(
            'SELECT id FROM thread where user_id = ? AND title = ?',
            array($user_id, $title)
        );

        //check if thread title exists on the same account
        return $row? TRUE : FALSE;
    }

    public function getComments($page)
    {
        $comments = array();
        $db = DB::conn();
        
        $countComment = $db->value(
            'SELECT count(id) from comment where thread_id = ?',
            array($this->id)
        );

        $totalPage = self::getTotalPage($countComment);
    
        //thread goes to page where new comment is inserted
        if ($page == Thread::NEW_CREATE) {
            $page = $totalPage;
        }

        $offset = ($page - 1) * Thread::THREAD_COMMENT_LIMIT;
        $query = "SELECT * FROM comment WHERE thread_id = ?
            ORDER BY created ASC LIMIT " . Thread::THREAD_COMMENT_LIMIT . " OFFSET " . $offset;
        $rows = $db->rows($query, array($this->id));

        foreach ($rows as $k => $v) {
            $comments[] = array('created'=>$v['created'], 'body'=>$v['body']);
        }

    
        return self::pagination($page, $totalPage, $comments);

    }

    public function create(Comment $comment)
    {
        $this->validate();
        $comment->validate();

        if ($this->hasError() OR $comment->hasError()) {
            throw new ValidationException('invalid thread or comment');
        }
        
        date_default_timezone_set('Asia/Manila');
        $date = date('Y-m-d H:i:s');

        $db = DB::conn();
        $db->begin();

        $params = array("user_id"=>$comment->user_id, "title"=>$this->title, "created"=>$date);
        $db->insert("thread", $params);;
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
        date_default_timezone_set('Asia/Manila');
        $date = date('Y-m-d H:i:s');

        $db = DB::conn();
        $params = array("thread_id"=>$this->id, "body"=>$comment->body, "created"=>$date);
        $db->insert("comment", $params);
    }


    public static function getTotalPage($rowCount){
        return ceil($rowCount / Thread::THREAD_COMMENT_LIMIT);
    }

    public static function pagination($page, $totalPage, $array)
    {
        $totalRow = count($array);

        //number of pages to skip
        $skip_page_count = (floor($page / Thread::PAGE_MAX)) * Thread::PAGE_MAX;

        //get the remaining pages
        $remaining = $totalPage - $skip_page_count;

        if ($page == $skip_page_count OR $remaining > Thread::PAGE_MAX) {
            $remaining = Thread::PAGE_MAX;
        }

        //get the start of page numbers
        if ($page == $skip_page_count) {
            $start = $skip_page_count - Thread::START_ONE;
        } else {
            $start = $skip_page_count + Thread::START_TWO;
        }

        //get the end of page numbers
        if ($remaining > 0) {
            $end = $start + $remaining;
        } else {
            $end = $start + Thread::PAGE_MAX;
        }

        return array($array, $totalRow, $totalPage, $end, $start, $page);
    }
}
?>
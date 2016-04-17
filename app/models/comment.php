<?php
class Comment extends BaseModel {
    public $commentId, $postId, $userId, $content, $date;
    
    public function __construct($attributes) {
        parent::__construct($attributes);
        $this->validators = array('validate_content', 'validate_login');
    }
    
    public static function all() {
        return self::queryAndCollect('SELECT * FROM Comment;');
    }
    
    public static function allInPost($postId) {
        return self::queryAndCollect('SELECT * FROM Comment WHERE postId = ?', array($postId));       
    }
    
    public static function find($commentId) {
        $list = self::queryAndCollect('SELECT * FROM Comment WHERE commentId = ? LIMIT 1;', array($commentId));
        if (empty($list)) return NULL;
        return $list[0];
    }
    
    public static function queryAndCollect($q, $args = array()) {       
        $query = DB::query($q, $args);   
        $rows = $query->fetchAll();
        $comments = array();
          
        foreach ($rows as $row) {
            $comments[] = new Comment(array(
                'commentId' => $row['commentid'],
                'postId' => $row['postid'],
                'userId' => $row['userid'],
                'content' => $row['content'],
                'date' => $row['date']
            ));
        }
        
        return $comments;
    }
    
    public function update() {
        DB::query('UPDATE Comment SET content = ? WHERE commentId = ?',
            array($this->content, $this->commentId));
    }

    //date still not valid
    public function save() {
        $query = DB::query('INSERT INTO Comment (postId, userId, content) 
            VALUES (:postId, :userId, :content) RETURNING commentId', 
            array('postId' => $this->postId, 'userId' => $this->userId, 'content' => $this->content));
        $row = $query->fetch();
        $this->commentId = $row['commentid'];
    }
    
    public static function destroy($commentId) {
        DB::query('DELETE FROM Comment WHERE commentId = ?;', array($commentId));
    }
    
    // Validators
    
    public function validate_content(){
        $errors = array();
        if($this->content == '' || $this->content == null){
            $errors[] = 'Sisältö puuttuu!';
        }

        return $errors;
    }
    
    public function validate_login(){
        $errors = array();
        if($this->userId == null){
            $errors[] = 'Kirjaudu ensin sisään!';
        }

        return $errors;
    }
}
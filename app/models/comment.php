<?php
class Comment extends BaseModel {
    public $commentId, $postId, $userId, $content, $date;
    
    public function __construct($attributes) {
        parent::__construct($attributes);
    }
    
    public static function all() {
        return self::queryAndCollect('SELECT * FROM Comment;');
    }
    
    public static function allInPost($postId) {
        return self::queryAndCollect('SELECT * FROM Comment WHERE postId = ?', $postId);       
    }
    
    public static function find($commentId) {
        $list = self::queryAndCollect('SELECT * FROM Comment WHERE commentId = ? LIMIT 1;', $commentId);
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

    //date still not valid
    public function save() {
        $query = DB::connection()->prepare('INSERT INTO Comment (postId, userId, content) 
            VALUES (:postId, :userId, :content) RETURNING commentId', 
            array('postId' => $this->postId, 'userId' => $this->userId, 'content' => $this->content));
        $row = $query->fetch();
        $this->commentId = $row['commentid'];
    }
    
}
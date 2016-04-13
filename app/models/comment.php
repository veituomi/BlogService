<?php
class Comment extends BaseModel {
    public $commentId, $postId, $userId, $content, $date;
    
    public function __construct($attributes) {
        parent::__construct($attributes);
    }
    
    public static function all() {
        return self::queryAndCollect('SELECT * FROM Comment;');
    }
    
    public static function find($commentId) {
        return self::queryAndCollect('SELECT * FROM Comment WHERE commentId = ? LIMIT 1;', $commentId);
    
    }
    
    public static function queryAndCollect($q) {       
        $query = DB::connection()->prepare($q);
        $query->execute(func_get_args());
        
        $rows = $query->fetchAll();
        $comments = array();
          
        foreach ($rows as $row) {
            $comments[] = new Comment(array(
                'commentId' => $row['commentId'],
                'postId' => $row['postId'],
                'userId' => $row['userId'],
                'content' => $row['content'],
                'date' => $row['date']
            ));
        }
        
        return $comments;
    }

    //date still not valid
    public function save() {
        $query = DB::connection()->prepare('INSERT INTO Comment (postId, userId, content) 
            VALUES (:postId, :userId, :content) RETURNING commentId');
        $query->execute(array('postId' => $this->postId, 'userId' => $this->userId, 'content' => $this->content);
        $row = $query->fetch();
        $this->commentId = $row['commentId'];
    }
    
}
<?php
class Comment extends BaseModel {
    public $commentId, $postId, $userId, $content, $date;
    
    public function __construct($attributes) {
        parent::__construct($attributes);
    }
    
    public static function all() {
        $query = DB::connection()->prepare('SELECT * FROM Comment;');
        $query->execute();
        
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
    
    public static function find($commentId) {
        $query = DB::connection()->prepare('SELECT * FROM Comment WHERE commentId = ? LIMIT 1;');
        $query->execute(array($commentId));    
        $row = $query->fetch();
        
        if ($row) {
            return new Comment(array(
                'commentId' => $row['commentId'],
                'postId' => $row['postId'],
                'userId' => $row['userId'],
                'content' => $row['content'],
                'date' => $row['date']
            ));
        }
        
        return null;
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
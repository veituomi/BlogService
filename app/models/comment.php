<?php
class Comment extends BaseModel {
    public $comment_id, $post_id, $user_id, $content, $date;
    
    public function __construct($attributes) {
        parent::__construct($attributes);
    }
    
    public static function all() {
        return self::queryAndCollect('SELECT * FROM Comment;');
    }
    
    public static function find($comment_id) {
        return self::queryAndCollect('SELECT * FROM Comment WHERE comment_id = ? LIMIT 1;', $comment_id);
    
    }
    
    public static function queryAndCollect($q, $args = array()) {       
        $query = DB::query($q, $args);   
        $rows = $query->fetchAll();
        $comments = array();
          
        foreach ($rows as $row) {
            $comments[] = new Comment(array(
                'comment_id' => $row['comment_id'],
                'post_id' => $row['post_id'],
                'user_id' => $row['user_id'],
                'content' => $row['content'],
                'date' => $row['date']
            ));
        }
        
        return $comments;
    }

    //date still not valid
    public function save() {
        $query = DB::connection()->prepare('INSERT INTO Comment (post_id, user_id, content) 
            VALUES (:post_id, :user_id, :content) RETURNING comment_id', 
            array('post_id' => $this->post_id, 'user_id' => $this->user_id, 'content' => $this->content));
        $row = $query->fetch();
        $this->comment_id = $row['comment_id'];
    }
    
}
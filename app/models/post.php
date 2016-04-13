<?php
class Post extends BaseModel {
    public $postId, $blogId, $author, $title, $content;
    
    public function __construct($attributes) {
        parent::__construct($attributes);
    }  
    
    public static function all() {
        return self::queryAndCollect('SELECT * FROM BlogPost;');
    }
    
    public static function allInBlog($blogId) {
        return self::queryAndCollect('SELECT * FROM BlogPost WHERE blogId = ?', $blogId);
    }
    
    public static function allByUser($userId) {
        return self::queryAndCollect('SELECT * FROM BlogPost WHERE author = ?;', $userId);
    }
    
    public static function find($postId) {   
        return self::queryAndCollect('SELECT * FROM BlogPost WHERE postId = ? LIMIT 1;', $postId);
    }
    
   public static function queryAndCollect($q) {       
        $query = DB::connection()->prepare($q);
        $query->execute(func_get_args());
        
        $rows = $query->fetchAll();
        $posts = array();
        
        foreach ($rows as $row) {
            $posts[] = new Post(array(
                'postId' => $row['postid'],
                'blogId' => $row['blogid'],
                'author' => $row['author'],
                'title' => $row['title'],
                'content' => $row['content']
            ));
        }
        
        return $posts;
    }
    
    public function save() {
        $query = DB::connection()->prepare('INSERT INTO BlogPost (blogId, author, title, content) VALUES (:blogId, :author, :title, :content) RETURNING postId');
        $query->execute(array('blogId' => $this->blogId, 'author' => $this->author, 'title' => $this->title, 'content' => $this->content));
        $row = $query->fetch();
        $this->postId = $row['postId'];
    }
    
}
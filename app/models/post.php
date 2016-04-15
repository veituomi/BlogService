<?php
class Post extends BaseModel {
    public $post_id, $blog_id, $author, $title, $content;
    
    public function __construct($attributes) {
        parent::__construct($attributes);
    }  
    
    public static function all() {
        return self::queryAndCollect('SELECT * FROM BlogPost;');
    }
    
    public static function allInBlog($blog_id) {
        return self::queryAndCollect('SELECT * FROM BlogPost WHERE blog_id = ?', array($blog_id));
    }
    
    public static function allByUser($userId) {
        return self::queryAndCollect('SELECT * FROM BlogPost WHERE author = ?;', array($userId));
    }
    
    public static function find($post_id) {   
        $list = self::queryAndCollect('SELECT * FROM BlogPost WHERE post_id = ? LIMIT 1;', array($post_id));
        if (empty($list)) return $list[0];
        return null;
    }
    
   public static function queryAndCollect($q, $args = array()) {       
        $query = DB::query($q, $args);
        $rows = $query->fetchAll();
        $posts = array();
        
        foreach ($rows as $row) {
            $posts[] = new Post(array(
                'post_id' => $row['post_id'],
                'blog_id' => $row['blog_id'],
                'author' => $row['author'],
                'title' => $row['title'],
                'content' => $row['content']
            ));
        }
        
        return $posts;
    }
    
    public function save() {
        $query = DB::connection()->prepare('INSERT INTO BlogPost (blog_id, author, title, content) VALUES (:blog_id, :author, :title, :content) RETURNING post_id', 
            array('blog_id' => $this->blog_id, 'author' => $this->author, 'title' => $this->title, 'content' => $this->content));
        $row = $query->fetch();
        $this->post_id = $row['post_id'];
    }
    
}
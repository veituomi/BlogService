<?php
class Post extends BaseModel {
    public $postId, $blogId, $author, $title, $content;
    
    public function __construct($attributes) {
        parent::__construct($attributes);
        $this->validators = array();
    }  
    
    public static function all() {
        return self::queryAndCollect('SELECT * FROM BlogPost ORDER BY postId DESC;');
    }
    
    public static function allInBlog($blogId) {
        return self::queryAndCollect('SELECT * FROM BlogPost WHERE blogId = ?', array($blogId));
    }
    
    public static function allByUser($userId) {
        return self::queryAndCollect('SELECT * FROM BlogPost WHERE author = ?;', array($userId));
    }
    
    public static function find($postId) {   
        $list = self::queryAndCollect('SELECT * FROM BlogPost WHERE postId = ? LIMIT 1;', array($postId));
        if (empty($list)) return NULL;
        return $list[0];
    }

    public static function getLikeCount($postId) {
        $query = DB::query('SELECT COUNT(*) FROM Likes WHERE postId = ?', $postId);
        $count = $query->fetch();
        if (empty($count)) return 0;
        return $count[0];
    }
    
    public static function queryAndCollect($q, $args = array()) {       
        $query = DB::query($q, $args);
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
        $query = DB::query('INSERT INTO BlogPost (blogId, author, title, content) VALUES (:blogId, :author, :title, :content) RETURNING postId', 
            array('blogId' => $this->blogId, 'author' => $this->author, 'title' => $this->title, 'content' => $this->content));
        $row = $query->fetch();
        $this->postId = $row['postid'];
    }
    
    public static function destroy($postId) {
        DB::query('DELETE FROM BlogPost WHERE postId = ?;', array($postId));
    }
    
}
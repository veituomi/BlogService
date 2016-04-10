<?php
class Post extends BaseModel {
    public $postId, $blogId, $author, $title, $content;
    
    public function __construct($attributes) {
        parent::__construct($attributes);
    }
    
    public static function all($blogId = -1) {
        $q = 'SELECT * FROM BlogPost;';
        if ($blogId != -1) {
            $q = 'SELECT * FROM BlogPost WHERE blogId = ?;';
        }
        $query = DB::connection()->prepare($q);
        if ($blogId != -1) {
            $query->execute(array($blogId));
        } else {
            $query->execute();
        }
        
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
    
    public static function find($postId) {
        $query = DB::connection()->prepare('SELECT * FROM BlogPost WHERE postId = ? LIMIT 1;');
        $query->execute(array($postId));
        
        $row = $query->fetch();
        if ($row) {
            return new Post(array(
                'postId' => $row['postid'],
                'blogId' => $row['blogid'],
                'title' => $row['title'],
                'content' => $row['content']
            ));
        }
        
        return null;
    }
}
<?php
class Post extends BaseModel {
    public $postId, $blogId, $author, $title, $content;
    
    public function __construct($attributes) {
        parent::__construct($attributes);
    }
    
    //all or all in blog or all by user, a good design decision? Only in PHP
    public static function all($blogId = -1, $userId = -1) {
        if ($blogId != -1 && $userId != -1) {
            return null;
        }
        
        $q = 'SELECT * FROM BlogPost;';
        
        if ($blogId != -1) {
            $q = 'SELECT * FROM BlogPost WHERE blogId = ?;';
        } elseif ($userId != -1) {
            $q = 'SELECT * FROM BlogPost WHERE author = ?;';
        }
        
        $query = DB::connection()->prepare($q);
        if ($blogId != -1 || $userId != -1) {
            $query->execute(array(max($blogId, $userId)));
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
    
    public function save() {
        $query = DB::connection()->prepare('INSERT INTO BlogPost (blogId, author, title, content) VALUES (:blogId, :author, :title, :content) RETURNING postId');
        $query->execute(array('blogId' => $this->blogId, 'author' => $this->author, 'title' => $this->title, 'content' => $this->content));
        $row = $query->fetch();
        $this->postId = $row['postId'];
    }
    
}
<?php
class Post extends BaseModel {
    public $postId, $blogId, $author, $title, $content;
    
    public function __construct($attributes) {
        parent::__construct($attributes);
        $this->validators = array('validate_content');
    }
    
    public static function canEdit($postId) {
        if (empty($_SESSION['user'])) return false;
        $query = DB::query('SELECT * FROM BlogPost WHERE postId = ? AND author = ? LIMIT 1;',
            array($postId, $_SESSION['user']));
        if ($query->fetch()) return true;
        return false;
    }
    
    public static function canDestroy($postId) {
        return isset($_SESSION['is_admin']) || self::canEdit($postId);
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
    
    public static function update($id){
        // TODO
    }
    
    public static function destroy($postId) {
        DB::query('DELETE FROM Comment WHERE postId = ?;', array($postId));
        DB::query('DELETE FROM BlogPost WHERE postId = ?;', array($postId)); //must delete TagCloud/Likes linked with these posts?
    }
    
    public function validate_content() {
        $errors = array();
        
        if (empty($this->title)) {
            $errors[] = 'Lisää kirjoitukseen otsikko!';
        }
        
        if (empty($this->content)) {
            $errors[] = 'Lisää kirjoitukseen sisältö!';
        }
        
        return $errors;
    }
    
}
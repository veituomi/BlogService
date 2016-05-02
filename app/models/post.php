<?php
class Post extends BaseModel {
    public $postId, $blogId, $author, $title, $content, $likes;
    
    public function __construct($attributes) {
        parent::__construct($attributes);
        $this->validators = array('validate_content');
    }
    
    public static function canEdit($postId) {
        if (!isset($_SESSION['user'])) return false;
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
    
    public static function allOrderedByLikes($limit) {
        return self::queryAndCollect('SELECT *, (SELECT COUNT(*) FROM Likes WHERE Likes.postId = BlogPost.postId)
            AS likes FROM BlogPost ORDER BY likes DESC LIMIT ?',
            array($limit));
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
            $likes = 0;
            if (isset($row['likes'])) $likes = $row['likes'];
            $posts[] = new Post(array(
                'postId' => $row['postid'],
                'blogId' => $row['blogid'],
                'author' => $row['author'],
                'title' => $row['title'],
                'content' => $row['content'],
                'likes' => $likes
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
    
    public function update() {
        DB::query('UPDATE BlogPost SET author = ?, title = ?, content = ? WHERE postId = ?',
            array($this->author, $this->title, $this->content, $this->postId));
    }
    
    public static function destroy($postId) {
        DB::query('DELETE FROM Comment WHERE postId = ?;', array($postId));
        DB::query('DELETE FROM TagCloud WHERE postId = ?;', array($postId));
        DB::query('DELETE FROM Likes WHERE postId = ?;', array($postId));
        DB::query('DELETE FROM BlogPost WHERE postId = ?;', array($postId));
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
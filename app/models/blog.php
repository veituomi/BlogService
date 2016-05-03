<?php
class Blog extends BaseModel {
    public $blogId, $name, $description;
    
    public function __construct($attributes) {
        parent::__construct($attributes);
        $this->validators = array('validate_content');
    }
    
    public static function canEdit($blogId) {
        if (empty($_SESSION['user'])) return false;
        $query = DB::query('SELECT * FROM BlogOwner WHERE blogId = ? AND userId = ? LIMIT 1;',
            array($blogId, $_SESSION['user']));
        if ($query->fetch()) return true;
        return false;
    }
    
    public static function canDestroy($blogId) {
        return isset($_SESSION['is_admin']) || self::canEdit($blogId);
    }
    
    public static function all() {
        return self::queryAndCollect('SELECT * FROM Blog;');
    }
    
    public static function allByUser($userId) {
        return self::queryAndCollect('SELECT b.blogId, b.name, b.description FROM Blog b, BlogOwner bo, 
            Blogger bl WHERE bl.userId = ? AND bo.userId = bl.userId AND bo.blogId = b.blogId', array($userId));
    }
    
    public static function find($blogId) {
        $list = self::queryAndCollect('SELECT * FROM Blog WHERE blogId = ? LIMIT 1;', array($blogId));
        if (empty($list)) return NULL;
        return $list[0];
    }
    
    public function update() {
        DB::query('UPDATE Blog SET name = ?, description = ? WHERE blogId = ?',
            array($this->name, $this->description, $this->blogId));
    }
    
    public static function destroy($blogId) {
        $posts = Post::allInBlog($blogId);
        
        foreach ($posts as $post) {
            Post::destroy($post->postId);
        }
        
        DB::query('DELETE FROM BlogOwner WHERE blogId = ?;', array($blogId));
        DB::query('DELETE FROM Blog WHERE blogId = ?;', array($blogId));
    }
    
    private static function queryAndCollect($q, $args = array()) {
        $query = DB::query($q, $args);  
        $rows = $query->fetchAll();
        $blogs = array();
        
        foreach ($rows as $row) {
            $blogs[] = new Blog(array(
                'blogId' => $row['blogid'],
                'name' => $row['name'],
                'description' => $row['description']
            ));
        }
        
        return $blogs;
    }
    
    public function save() {
        $query = DB::query('INSERT INTO Blog (name, description) VALUES (:name, :description) RETURNING blogId',
            array('name' => $this->name, 'description' => $this->description));
        $row = $query->fetch();
        $this->blogId = $row['blogid'];
        
        $query = DB::query('INSERT INTO BlogOwner (blogId, userId) VALUES (?, ?);',
            array($this->blogId, $_SESSION['user']));
    }
    
    public function validate_content() {
        $errors = array();
        
        if (empty($this->name)) {
            $errors[] = 'Anna blogille nimi!';
        }
        
        if (empty($this->description)) {
            $errors[] = 'Anna blogille kuvaus!';
        }
        
        return $errors;
    }
    
}
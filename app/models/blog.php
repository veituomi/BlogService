<?php
class Blog extends BaseModel {
    public $blogId, $name, $description;
    
    public function __construct($attributes) {
        parent::__construct($attributes);
        $this->validators = array();
    }
    
    public static function all() {
        return self::queryAndCollect('SELECT * FROM Blog;');
    }
    
    public static function allByUser($userId) {
        return self::queryAndCollection('SELECT b.blogId, b.name, b.description FROM Blog b, BlogOwner bo, 
            Blogger bl WHERE bl.userId = ? AND bo.userId = bl.userId AND bo.blogId = b.blogId');
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
    }
    
}
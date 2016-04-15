<?php
class Blog extends BaseModel {
    public $blog_id, $name, $description;
    
    public function __construct($attributes) {
        parent::__construct($attributes);
    }
    
    public static function all() {
        return self::queryAndCollect('SELECT * FROM Blog;');
    }
    
    public static function find($blog_id) {
        $list = self::queryAndCollect('SELECT * FROM Blog WHERE blog_id = ? LIMIT 1;', array($blog_id));
        if (empty($list)) return $list[0];
        return null;
    }
    
    public static function destroy($blog_id) {
        DB::query('DELETE FROM Blog WHERE blog_id = ?;', array($blog_id));
    }
    
    private static function queryAndCollect($q, $args = array()) {
        $query = DB::query($q, $args);  
        $rows = $query->fetchAll();
        $blogs = array();
        
        foreach ($rows as $row) {
            $blogs[] = new Blog(array(
                'blog_id' => $row['blog_id'],
                'name' => $row['name'],
                'description' => $row['description']
            ));
        }
        
        return $blogs;
    }
    
    public function save() {
        $query = DB::query('INSERT INTO Blog (name, description) VALUES (:name, :description) RETURNING blog_id',
            array('name' => $this->name, 'description' => $this->description));
        $row = $query->fetch();
        $this->blog_id = $row['blog_id'];
    }
    
}
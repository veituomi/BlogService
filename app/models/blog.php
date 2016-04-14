<?php
class Blog extends BaseModel {
    public $blogId, $name, $description;
    
    public function __construct($attributes) {
        parent::__construct($attributes);
    }
    
    public static function all() {
        return self::queryAndCollect('SELECT * FROM Blog;');
    }
    
    public static function find($blogId) {
        $list = self::queryAndCollect('SELECT * FROM Blog WHERE BlogId = ? LIMIT 1;', array($blogId));
        if (isset($list[0])) return $list[0];
        return null;
    }
    
    private static function queryAndCollect($q, $args = array()) {
        $query = DB::connection()->prepare($q);
        $query->execute($args);
        
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
        $query = DB::connection()->prepare('INSERT INTO Blog (name, description) VALUES (:name, :description) RETURNING blogId');
        $query->execute(array('name' => $this->name, 'description' => $this->description));
        $row = $query->fetch();
        $this->blogId = $row['blogid'];
    }
    
}
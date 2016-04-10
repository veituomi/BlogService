<?php
class Blog extends BaseModel {
    public $blogId, $name, $description;
    
    public function __construct($attributes) {
        parent::__construct($attributes);
    }
    
    public static function all() {
        $query = DB::connection()->prepare('SELECT * FROM Blog;');
        $query->execute();
        
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
    
    public static function find($blogId) {
        $query = DB::connection()->prepare('SELECT * FROM Blog WHERE BlogId = ? LIMIT 1;');
        $query->execute(array($blogId));
        
        $row = $query->fetch();
        if ($row) {
            return new Blog(array(
                'blogId' => $row['blogid'],
                'name' => $row['name'],
                'description' => $row['description']
            ));
        }
        
        return null;
    }
}
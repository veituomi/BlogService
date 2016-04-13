<?php
class Tag extends BaseModel {
    public $tagId, $name;
    
    public function __construct($attributes) {
        parent::__construct($attributes);
    }
        
    public static function all() {
        return self::queryAndCollect('SELECT * FROM Tag;');
    }
    
    public static function allInPost($postId) {
        return self::queryAndCollect('SELECT * FROM Tag t, TagCloud tc, BlogPost p 
                WHERE t.tagId = tc.tagId AND tc.postId = p.postId AND p.postId = ?;', $postId);
    }
    
    public static function find($tagId) {
        return self::queryAndCollect('SELECT * FROM Tag WHERE tagId = ? LIMIT 1;', $tagId);
    }
    
    private static function queryAndCollect($q) {
        $query = DB::connection()->prepare($q);
        $query->execute(func_get_args());    
           
        $rows = $query->fetchAll();
        $tags = array();
          
        foreach ($rows as $row) {
            $tags[] = new Tag(array(
                'tagId' => $row['tagId'],
                'name' => $row['name']
            ));
        }
        
        return $tags;
    }
    
    public function save() {
        $query = DB::connection()->prepare('INSERT INTO Tag (name) VALUES(:name) RETURNING tagId');
        $query->execute(array('name' => $this->name));
        $row = $query->fetch();
        $this->tagId = $row['tagId'];
    }
    
}
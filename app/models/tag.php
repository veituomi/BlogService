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
                WHERE t.tagId = tc.tagId AND tc.postId = p.postId AND p.postId = ?;', array($postId));
    }
    
    public static function find($tagId) {
        $list = self::queryAndCollect('SELECT * FROM Tag WHERE tagId = ? LIMIT 1;', array($tagId));
        if (empty($list)) return NULL;
        return $list[0];
    }
    
    public static function findByName($tagName) {
        $list = self::queryAndCollect('SELECT * FROM Tag WHERE name = ? LIMIT 1;', array($tagName));
        if (empty($list)) return NULL;
        return $list[0];
    }
    
    private static function queryAndCollect($q, $args = array()) {
        $query = DB::query($q, $args);             
        $rows = $query->fetchAll();
        $tags = array();
          
        foreach ($rows as $row) {
            $tags[] = new Tag(array(
                'tagId' => $row['tagid'],
                'name' => $row['name']
            ));
        }
        
        return $tags;
    }
    
    public function save() {
        $query = DB::query('INSERT INTO Tag (name) VALUES(:name) RETURNING tagId', 
            array('name' => $this->name));
        $row = $query->fetch();
        $this->tagId = $row['tagid'];
    }
    
}
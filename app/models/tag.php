<?php
class Tag extends BaseModel {
    public $tag_id, $name;
    
    public function __construct($attributes) {
        parent::__construct($attributes);
    }
        
    public static function all() {
        return self::queryAndCollect('SELECT * FROM Tag;');
    }
    
    public static function allInPost($postId) {
        return self::queryAndCollect('SELECT * FROM Tag t, TagCloud tc, BlogPost p 
                WHERE t.tag_id = tc.tag_id AND tc.postId = p.postId AND p.postId = ?;', $postId);
    }
    
    public static function find($tag_id) {
        return self::queryAndCollect('SELECT * FROM Tag WHERE tag_id = ? LIMIT 1;', $tag_id);
    }
    
    private static function queryAndCollect($q, $args = array() {
        $query = DB::query($q, $args)             
        $rows = $query->fetchAll();
        $tags = array();
          
        foreach ($rows as $row) {
            $tags[] = new Tag(array(
                'tag_id' => $row['tag_id'],
                'name' => $row['name']
            ));
        }
        
        return $tags;
    }
    
    public function save() {
        $query = DB::query('INSERT INTO Tag (name) VALUES(:name) RETURNING tag_id', 
            array('name' => $this->name));
        $row = $query->fetch();
        $this->tag_id = $row['tag_id'];
    }
    
}
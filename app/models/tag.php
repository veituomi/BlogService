<?php
class Tag extends BaseModel {
    public $tagId, $name;
    
    public function __construct($attributes) {
        parent::__construct($attributes);
    }
        
    //all or all in post
    public static function all($postId = -1) {
        $q = 'SELECT * FROM Tag;';
        
        if ($postId != -1) {
            $q = 'SELECT * FROM Tag t, TagCloud tc, BlogPost p 
                WHERE t.tagId = tc.tagId AND tc.postId = p.postId AND p.postId = ?;';
        }
        
        $query = DB::connection()->prepare(q);
        
        if ($postId != -1) {
            $query.execute(array($postId));
        } else {
            $query->execute();
        }
        
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
    
    public static function find($tagId) {
        $query = DB::connection()->prepare('SELECT * FROM Tag WHERE tagId = ? LIMIT 1;');
        $query->execute(array($tagId));    
        $row = $query->fetch();
        
        if ($row) {
            return new Tag(array(
                'tagId' => $row['tagId'],
                'name' => $row['name']
            ));
        }
        
        return null;
    }
    
    public function save() {
        $query = DB::connection()->prepare('INSERT INTO Tag (name) VALUES(:name) RETURNING tagId');
        $query->execute(array('name' => $this->name);
        $row = $query->fetch();
        $this->tagId = $row['tagId'];
    }
    
}
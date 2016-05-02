<?php
class TagCloud extends BaseModel {
    public static function setTag($postId, $tagId) {
        DB::query('INSERT INTO TagCloud (postId, tagId) VALUES (?, ?)', array($postId, $tagId));
    }
    
    public static function setTags($postId, $tags = array()) {
        $query = DB::query('DELETE FROM TagCloud WHERE postId = ?', array($postId));
        
        foreach ($tags as $tagName) {
            $tag = Tag::findByName($tagName);
            if ($tag == null) {
                $tag = new Tag(array('name' => $tagName));
                $tag->save();
            }
            TagCloud::setTag($postId, $tag->tagId);
        }
    }
    
    public static function getTags($postId) {
        $tags = array();
        
        $query = DB::query('SELECT * FROM TagCloud NATURAL JOIN Tag WHERE TagCloud.postId = ?', array($postId));
        $rows = $query->fetchAll();
          
        foreach ($rows as $row) {
            $tags[] = $row['name'];
        }
        
        return $tags;
    }
}
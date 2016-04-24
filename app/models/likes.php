<?php
class Likes extends BaseModel {
    public $userId, $postId;
    
    public function __construct($attributes) {
        parent::__construct($attributes);
        $this->validators = array();
    }
    
    public function find() {
        $query = DB::query('SELECT * FROM Likes WHERE userId = ? AND postId = ?', 
            array($this->userId, $this->postId));
        
        if ($query->fetch()) {
            return $this;
        }
        return null;
    }
    
    public function save() {
        $query = DB::query('INSERT INTO Likes (userId, postId) VALUES (?, ?)', 
            array($this->userId, $this->postId));
    }
    
    public function destroy() {
        DB::query('DELETE FROM Likes WHERE userId = ? AND postId = ?', array($this->userId, $this->postId));
    }
}
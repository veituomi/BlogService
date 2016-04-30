<?php
class Follows extends BaseModel {
    public $follower, $followee;
    
    public function __construct($attributes) {
        parent::__construct($attributes);
        $this->validators = array();
    }
    
    public function find() {
        $query = DB::query('SELECT * FROM Follows WHERE follower = ? AND followee = ?', 
            array($this->follower, $this->followee));
        
        if ($query->fetch()) {
            return $this;
        }
        return null;
    }
    
    public function save() {
        $query = DB::query('INSERT INTO Follows (follower, followee) VALUES (?, ?)', 
            array($this->follower, $this->followee));
    }
    
    public function destroy() {
        DB::query('DELETE FROM Follows WHERE follower = ? AND followee = ?', array($this->follower, $this->followee));
    }
}
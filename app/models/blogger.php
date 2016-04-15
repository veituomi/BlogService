<?php
class Blogger extends BaseModel {
    public $userId, $username, $password, $joinDate, $profileDescription;
    
    public function __construct($attributes) {
        parent::__construct($attributes);
    }
    
    public static function all() {
        return self::queryAndCollect('SELECT * FROM Blogger;');
    }
    
    public static function find($userId) {
        $list = self::queryAndCollect('SELECT * FROM Blogger WHERE userId = ? LIMIT 1;', $userId);
        if (empty($list)) return NULL;
        return $list[0];
    }
    
    public static function isAdmin($userId) {
        $list = self::queryAndCollect('SELECT * FROM Admin WHERE userId = ? LIMIT 1;', $userId);
        if (empty($list)) return false;
        return true;
    }
    
    public static function getFollowers($userId) {
        return self::queryAndCollect('SELECT * FROM Blogger b, Follows f WHERE f.followee = ? AND
            f.follower = b.userId', $userId);
    }
    
    public static function getFollowees($userId) {
        return self::queryAndCollect('SELECT * FROM Blogger b, Follows f WHERE f.follower = ? AND
            f.followee = b.userId', $userId);
    }
    
    //getLikedPosts
    
    private static function queryAndCollect($q, $args = array()) {
        $query = DB::query($q, $args);        
        $rows = $query->fetchAll();
        $bloggers = array();
        
        foreach ($rows as $row) {
            $bloggers[] = new Blogger(array(
                'userId' => $row['userid'],
                'username' => $row['username'],
                'password' => $row['password'],
                'joinDate' => $row['joindate'],
                'profileDescription' => $row['profiledescription']
            ));
        }
        
        return $bloggers;
    }
    
    //$joinDate not valid after save
    public function save() {
        $query = DB::query('INSERT INTO Blogger (username, email, password, profileDescription) 
            VALUES (:username, :email, :password, :profileDescription) RETURNING userId',
            array('username' => $this->username, 'email' => $this->email, 'password' => $this->password, 
            'profileDescription' => $this->profileDescription));
        $row = $query->fetch();
        $this->userId = $row['userid'];
    }
    
}
<?php
class Blogger extends BaseModel {
    public $userId, $username, $password, $email, $joinDate, $profileDescription;
    
    public function __construct($attributes) {
        parent::__construct($attributes);
        $this->validators = array('validate_content');
    }
    
    public static function all() {
        return self::queryAndCollect('SELECT * FROM Blogger;');
    }
    
    public static function find($userId) {
        $user = self::queryAndCollect('SELECT * FROM Blogger WHERE userId = ? LIMIT 1;', array($userId));
        if (empty($user)) return NULL;
        return $user[0];
    }
    
    public static function isAdmin($userId) {
        $user = self::queryAndCollect('SELECT * FROM Admin WHERE userId = ? LIMIT 1;', array($userId));
        if (empty($user)) return false;
        return true;
    }
    
    public static function getFollowers($userId) {
        return self::queryAndCollect('SELECT * FROM Blogger b, Follows f WHERE f.followee = ? AND
            f.follower = b.userId', array($userId));
    }
    
    public static function getFollowees($userId) {
        return self::queryAndCollect('SELECT * FROM Blogger b, Follows f WHERE f.follower = ? AND
            f.followee = b.userId', array($userId));
    }
    
    //getLikedPosts
    
    public static function authenticate($username, $password) {
        $user = self::queryAndCollect('SELECT * FROM Blogger WHERE username = ? AND 
            password = ? LIMIT 1;', array($username, $password));
        if (empty($user)) return NULL;
        return $user[0];
    }

    
    private static function queryAndCollect($q, $args = array()) {
        $query = DB::query($q, $args);        
        $rows = $query->fetchAll();
        $bloggers = array();
        
        foreach ($rows as $row) {
            $bloggers[] = new Blogger(array(
                'userId' => $row['userid'],
                'username' => $row['username'],
                'password' => $row['password'],
                'email' => $row['email'],
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
    
    // Validator
    public function validate_content(){
        $errors = array();
        
        if(!filter_var($this->email, FILTER_VALIDATE_EMAIL)){
            $errors[] = 'Käytä validia sähköpostia!';
        }
        
        if (strlen($this->password) < 6) {
            $errors[] = 'Käytä salasanassa vähintään kuusi merkkiä!';
        }

        return $errors;
    }
    
}
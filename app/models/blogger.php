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
        return self::queryAndCollect('SELECT * FROM Blogger WHERE userId = ? LIMIT 1;', $userId);
    }
    
    private static function queryAndCollect($q) {
        $query = DB::connection()->prepare($q);
        $query->execute(func_get_args());
        
        $rows = $query->fetchAll();
        $bloggers = array();
        
        foreach ($rows as $row) {
            $bloggers[] = new Blogger(array(
                'userId' => $row['userId'],
                'username' => $row['username'],
                'password' => $row['password'],
                'joinDate' => $row['joinDate'],
                'profileDescription' => $row['profileDescription']
            );
        }
        
        return $bloggers;
    }
    
    //$joinDate not valid after save
    public function save() {
        $query = DB::connection()->prepare('INSERT INTO Blogger (username, email, password, profileDescription) 
            VALUES (:username, :email, :password, :profileDescription) RETURNING userId');
        $query->execute(array('username' => $this->username, 'email' => $this->email, 'password' => $this->password, 'profileDescription' => $this->profileDescription);
        $row = $query->fetch();
        $this->userId = $row['userId'];
    }
    
}
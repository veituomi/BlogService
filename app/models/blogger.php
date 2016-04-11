<?php
class Blogger extends BaseModel {
    public $userId, $username, $password, $joinDate, $profileDescription;
    
    public function __construct($attributes) {
        parent::__construct($attributes);
    }
    
    public static function all() {
        $query = DB::connection()->prepare('SELECT * FROM Blogger;');
        $query->execute();
        
        $rows = $query->fetchAll();
        $bloggers = array();
        
        foreach ($rows as $row) {
            $bloggers[] = new Blogger(array(
                'userId' => $row['userId'],
                'username' => $row['username'],
                'password' => $row['password'],
                'joinDate' => $row['joinDate'],
                'profileDescription' => $row['profileDescription']
            ));
        }
        return $bloggers;
    }
    
    public static function find($userId) {
        $query = DB::connection()->prepare('SELECT * FROM Blogger WHERE userId = ? LIMIT 1;');
        $query->execute(array($userId));    
        $row = $query->fetch();
        
        if ($row) {
            return new Blogger(array(
                'userId' => $row['userId'],
                'username' => $row['username'],
                'password' => $row['password'],
                'joinDate' => $row['joinDate'],
                'profileDescription' => $row['profileDescription']
            ));
        }
        
        return null;
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
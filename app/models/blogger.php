<?php
class Blogger extends BaseModel {
    public $user_id, $username, $password, $join_date, $profile_description;
    
    public function __construct($attributes) {
        parent::__construct($attributes);
    }
    
    public static function all() {
        return self::queryAndCollect('SELECT * FROM Blogger;');
    }
    
    public static function find($user_id) {
        return self::queryAndCollect('SELECT * FROM Blogger WHERE user_id = ? LIMIT 1;', $user_id);
    }
    
    private static function queryAndCollect($q, $args = array()) {
        $query = DB::query($q, $args);        
        $rows = $query->fetchAll();
        $bloggers = array();
        
        foreach ($rows as $row) {
            $bloggers[] = new Blogger(array(
                'user_id' => $row['user_id'],
                'username' => $row['username'],
                'password' => $row['password'],
                'join_date' => $row['join_date'],
                'profile_description' => $row['profile_description']
            ));
        }
        
        return $bloggers;
    }
    
    //$join_date not valid after save
    public function save() {
        $query = DB::query('INSERT INTO Blogger (username, email, password, profile_description) 
            VALUES (:username, :email, :password, :profile_description) RETURNING user_id',
            array('username' => $this->username, 'email' => $this->email, 'password' => $this->password, 
            'profile_description' => $this->profile_description));
        $row = $query->fetch();
        $this->user_id = $row['user_id'];
    }
    
}
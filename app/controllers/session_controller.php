<?php

  class SessionController extends BaseController{

    public static function login(){
        View::make('login.html');
    }
    public static function handle_login() {
        $params = $_POST;
        $user = Blogger::authenticate($params['username'], $params['password']);

        if(!$user){
            View::make('login.html', array('error' => 'Wrong password', 'username' => $params['username']));
        } else {
            $_SESSION['user'] = $user->userId;
            Redirect::to('/');
        }
    }

    public static function register() {
        View::make('register.html');
    }
    
    public static function handle_register() {
        $params = $_POST;
        $email = $params['email'];
        $username = $params['username'];
        $password = $params['password']; //crypt($params['password']);
        
        $user = new Blogger(array('username' => $username, 'password' => $password, 'email' => $email));
            
        if ($user->save()) {
            //redirect to change profileDescription
            Redirect::to('/');
        } 
    }
  }

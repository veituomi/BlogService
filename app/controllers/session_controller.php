<?php

  class SessionController extends BaseController{

    public static function login(){
        View::make('login.html');
    }
    public static function handle_login() {
        $user = Blogger::authenticate($_POST['username'], $_POST['password']);

        if(!$user){
            View::make('login.html', array('errors' => array('Virheelliset tunnistetiedot!'), 'username' => $_POST['username']));
        } else {
            $_SESSION['user'] = $user->userId;
            Redirect::to('/');
        }
    }

    public static function register() {
        View::make('register.html');
    }
    
    public static function handle_register() {
        if (empty($_POST['email']) || empty($_POST['username']) || empty($_POST['password'])) {
            Redirect::to('/register', array('errors' => array('Täytä kaikki kentät ennen lähettämistä!')));
            return;
        }
        
        $email = $_POST['email'];
        $username = $_POST['username'];
        $password = $_POST['password']; //crypt($params['password']);
        
        $user = new Blogger(array('username' => $username, 'password' => $password, 'email' => $email));
        
        $errors = $user->errors();
        
        if (count($errors) == 0) {
            try {
                $user->save();
                $_SESSION['user'] = $user->userId;
                Redirect::to('/user/'. $user->userId . '/edit');
            } catch (PDOException $e) {
                $error = $e->getMessage();
                
                if (strpos($error, 'blogger_email_key') !== FALSE) {
                    $error = 'Sähköposti on jo varattu!';
                } elseif (strpos($error, 'blogger_username_key') !== FALSE) {
                    $error = 'Käyttäjätunnus on jo varattu!';
                } else {
                    $error = 'Tuntematon virhe';
                }
                
                Redirect::to('/register', array('errors' => array($error)));
            }
        } else {
            Redirect::to('/register', array('errors' => $errors));
        }
    }
    
    public static function handle_logout() {
        unset($_SESSION['user']);
        Redirect::to('/');
    }
}

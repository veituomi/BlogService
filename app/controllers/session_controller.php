<?php

  class SessionController extends BaseController{

    public static function login(){
        View::make('login.html');
    }
    public static function handle_login() {
        $user = Blogger::findByName(trim($_POST['username']));
            
        if($user && $user->password === crypt($_POST['password'], $user->password)) {
            $_SESSION['user'] = $user->userId;
            if ($user->isAdmin()) {
                $_SESSION['is_admin'] = true;
            }
            Redirect::to('/');
        } else {
           View::make('login.html', array('errors' => array('Virheelliset tunnistetiedot!'), 'username' => $_POST['username']));
        }
    }

    public static function register() {
        View::make('register.html');
    }
    
    public static function handle_register() {
        $email = trim($_POST['email']);
        $username = trim($_POST['username']);
        $password = crypt($_POST['password']);
        
        $user = new Blogger(array('username' => $username, 'password' => $password, 'email' => $email));
        
        $errors = $user->errors();
        
        if (strlen($_POST['password']) < 6) {
            $errors[] = 'Käytä salasanassa vähintään kuusi merkkiä!';
        }
        
        if (empty($errors)) {
            try {
                $user->save();
                $_SESSION['user'] = $user->userId;
                Redirect::to('/');
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
        unset($_SESSION['is_admin']);
        Redirect::to('/');
    }
}

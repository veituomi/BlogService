<?php
  class HomeController extends BaseController {
      
    public static function index(){      
      $posts = Post::all();
   	  View::make('home.html', array('posts' => $posts));
    }
    
     public static function sandbox(){
      echo 'Hello World!';
    }
  }
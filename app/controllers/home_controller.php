<?php
  class HomeController extends BaseController {
      
    public static function index(){      
      $posts = Post::allOrderedByLikes(5);
   	  View::make('home.html', array('posts' => $posts));
    }
    
     public static function sandbox(){
      echo 'Hello World!';
    }
  }
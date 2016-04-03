<?php

  class PostController extends BaseController{

    public static function index(){
   	  View::make('post_list.html');
    }
    
    public static function show(){
   	  View::make('post_show.html');
    }
    
    public static function edit(){
   	  View::make('post_edit.html');
    }

  }

<?php

  class BlogController extends BaseController{

    public static function index(){
   	  View::make('blog_list.html');
    }
    
    public static function show(){
   	  View::make('blog_show.html');
    }

  }

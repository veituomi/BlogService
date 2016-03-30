<?php

  class BlogController extends BaseController{

    public static function index(){
   	  View::make('blog_list.html');
    }

  }

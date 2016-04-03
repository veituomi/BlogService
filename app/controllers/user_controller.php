<?php

  class UserController extends BaseController{

    public static function index(){
   	  View::make('user_list.html');
    }
    
    public static function show(){
   	  View::make('user_show.html');
    }
    
    public static function edit(){
   	  View::make('user_edit.html');
    }

  }

<?php

  class UserController extends BaseController{

    public static function index(){
   	  View::make('user/list.html');
    }
    
    public static function show(){
   	  View::make('user/show.html');
    }
    
    public static function edit(){
   	  View::make('user/edit.html');
    }

  }

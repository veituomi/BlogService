<?php

class UserController extends BaseController {

    public static function index() {
        $bloggers = Blogger::all();
        View::make('user/list.html', array('bloggers' => $bloggers));
    }
    
    public static function show($id) {
        $blogger = Blogger::find($id);
        $viewer = new Blogger(array('userId' => BaseController::get_user_logged_in()));
        
        View::make('user/show.html', array('blogger' => $blogger, 
            'can_edit' => ($id == $viewer->userId || $viewer->isAdmin())));
    }
    
    public static function edit($id) {
        $blogger = Blogger::find($id);
        View::make('user/edit.html', array('blogger' => $blogger));
    }
   
}

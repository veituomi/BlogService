<?php

class UserController extends BaseController {

    public static function index() {
        $bloggers = Blogger::all();
        View::make('user/list.html', array('bloggers' => $bloggers));
    }
    
    public static function show($id) {
        $blogger = Blogger::find($id);
        $blogs = Blog::allByUser($id);
        $followees = Blogger::getFollowees($id);
        $followers = Blogger::getFollowers($id);
        $viewer = new Blogger(array('userId' => BaseController::get_user_logged_in()));
        
        View::make('user/show.html', array('blogger' => $blogger,
            'followees' => $followees, 'followers' => $followers,
            'blogs' => $blogs, 'can_edit' => ($id == $viewer->userId || $viewer->isAdmin())));
    }
    
    public static function edit($id) {
        $blogger = Blogger::find($id);
        View::make('user/edit.html', array('blogger' => $blogger));
    }
    
    public static function update($id){
        if (!Blogger::canEdit($id)) {
            Redirect::to('/user/' . $id, array('errors' => array('Ei ole vaadittavia oikeuksia.')));
            return;
        }
        
        $oldBlogger = Blogger::find($id);
        
        $password = crypt($_POST['newPassword1']);
        if ($_POST['newPassword1'] == '') {
            $password = $oldBlogger->password;
        }
        
        $blogger = new Blogger(array(
                'userId' => $id,
                'username' => $oldBlogger->username,
                'password' => $password,
                'email' => $oldBlogger->email,
                'joinDate' => $oldBlogger->joinDate,
                'profileDescription' => $_POST['profileDescription']
        ));
        
        $errors = $blogger->errors();
        
        if ($password != $oldBlogger->password && crypt($_POST['oldPassword'], $oldBlogger->password) != $oldBlogger->password) {
            $errors[] = 'Vanha salasana on virheellinen!';
        }
        
        if ($_POST['newPassword1'] != $_POST['newPassword2']) {
            $errors[] = 'Uuden salasanan kaksoiskappale ei täsmää!';
        }

        if (empty($errors)) {
            $blogger->update();
            Redirect::to('/user/' . $id, array('message' => 'Tietoja on muokattu!'));
        } else {
            Redirect::to('/user/' . $id . "/edit", array('errors' => $errors, 'blogger' => $blogger));
        }
    }
    
    public static function destroy($id) {
        if (!Blogger::canDestroy($id)) {
            Redirect::to('/user/' . $id, array('errors' => array('Ei ole vaadittavia oikeuksia.')));
            return;
        }
        
        Blogger::destroy($id);
        View::make('user/list.html', array('message' => 'Käyttäjä on tuhottu!'));
    }
   
}

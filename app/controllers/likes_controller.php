<?php
class LikesController extends BaseController {
    public static function like($postId) {
        $userId = BaseController::get_user_id_logged_in();
        
        if ($userId == null) {
            Redirect::to('/post/' . $postId, array('errors' => array('Kirjaudu ensin sisään!')));
            return;
        }
        
        $like = new Likes(array('userId' => $userId, 'postId' => $postId));
        
        if ($like->find() == null) {
            $like->save();
            Redirect::to('/post/' . $postId, array('message' => 'Tykkäsit kirjoituksesta.'));
        } else {
            $like->destroy();
            Redirect::to('/post/' . $postId, array('message' => 'Et tykännytkään kirjoituksesta.'));
        }
    }
}
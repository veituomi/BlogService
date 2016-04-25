<?php
class LikesController extends BaseController {
    public static function like($postId) {
        $userId = BaseController::get_user_logged_in();
        
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
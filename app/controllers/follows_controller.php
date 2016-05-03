<?php
class FollowsController extends BaseController {
    public static function follow($postId) {
        $post = Post::find($postId);
        
        $userId = BaseController::get_user_logged_in();
        
        $follows = new Follows(array('follower' => $userId, 'followee' => $post->author));
        
        $errors = $follows->errors();
        
        if (empty($errors)) {
            if ($follows->find() == null) {
                $follows->save();
                Redirect::to('/post/' . $postId, array('message' => 'Seuraat käyttäjää.'));
            } else {
                $follows->destroy();
                Redirect::to('/post/' . $postId, array('message' => 'Lopetit käyttäjän seuraamisen.'));
            }
        } else {
            Redirect::to('/post/' . $postId, array('errors' => $errors));
        }
    }
}
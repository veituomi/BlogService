<?php
class CommentController extends BaseController{
    
    public static function edit($commentId) {
        $comment = Comment::find($commentId);
   	    View::make('comment/edit.html', array('comment' => $comment));
    }
    
    public static function update($id){
        $comment = new Comment(array(
                'commentId' => $_POST['commentId'],
                'postId' => $_POST['postId'],
                'userId' => $_POST['userId'],
                'content' => trim($_POST['content']),
                'date' => $_POST['date'] //hmm
        ));
        
        $errors = $comment->errors();

        if (empty($errors)){
            $comment->update();
            Redirect::to('/post/' . $comment->postId, array('message' => 'Kommenttia on muokattu!'));
        } else {
            View::make('comment/edit.html', array('errors' => $errors));
        }
    }
    
    public static function destroy($id) {
        $message = array('message' => 'Kommentti on poistettu!');
        $comment = Comment::find($id);
        $postId = $comment->postId;
        
        if ($comment->canDestroy($id)) {
            Comment::destroy($id);
        } else {
            $message['message'] = 'Kommenttia ei voitu poistaa!';
        }
        
        Redirect::to('/post/' . $postId, $message);
    }
    
    public static function store() {
        $userId = BaseController::get_user_logged_in();
        
        $comment = new Comment(array(
                'postId' => $_POST['postId'],
                'userId' => $userId,
                'content' => trim($_POST['content'])
        ));
        
        $errors = $comment->errors();
        
        if (empty($errors)) {
            $comment->save();
            Redirect::to('/post/' . $comment->postId, array('message' => 'Kommentti on lisätty!'));
        } else {
            Redirect::to('/post/' . $comment->postId, array('errors' => $errors));
        }
    }
}

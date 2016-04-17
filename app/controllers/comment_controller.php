<?php
class CommentController extends BaseController{
    
    public static function edit($commentId) {
        $comment = Comment::find($commentId);
   	    View::make('comment/edit.html', array('comment' => $comment));
    }
    
    public static function update($id){
        $params = $_POST;
        $comment = new Comment(array(
                'commentId' => $params['commentId'],
                'postId' => $params['postId'],
                'userId' => $params['userId'],
                'content' => $params['content'],
                'date' => $params['date']
        ));
        
        $errors = $comment->errors();

        if (!empty($errors)){
            View::make('comment/edit.html', array('errors' => $errors));
        } else {
            $comment->update();
            Redirect::to('/post/' . $comment->postId, array('message' => 'Kommenttia on muokattu!'));
        }
    }
    
    public static function destroy($id) {
        $postId = Comment::find($id)->postId;
        Comment::destroy($id);
        Redirect::to('/post/' . $postId, array('message' => 'Kommentti on poistettu!'));
    }
    
    public static function store() {
        $params = $_POST;
        $comment = new Comment(array(
                'postId' => $params['postId'],
                'userId' => 1,
                'content' => $params['content']
        ));
        
        $comment->save();
        Redirect::to('/post/' . $comment->postId);
    }

}

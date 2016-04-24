<?php
class PostController extends BaseController{
      
    public static function index() {
        $posts = Post::all();
        View::make('post/all.html', array('posts' => $posts));
    }

    // For testing purposes only
    public static function listed($blogId) {
        $posts = Post::allInBlog($blogId);
   	    View::make('post/list.html', array('posts' => $posts));
    }
    
    public static function show($postId) {
        $post = Post::find($postId);
        $comments = Comment::allInPost($postId);
        
        $like = new Likes(array('userId' => BaseController::get_user_logged_in(), 'postId' => $postId));
        $liked = $like->find() != null;
        
   	    View::make('post/show.html', array('post' => $post, 'comments' => $comments, 'liked' => $liked));
    }
    
    public static function create() {
   	    View::make('post/create.html', array('blogId' => $_POST['blogId']));
    }
    
    public static function edit($postId) {
        $post = Post::find($postId);
   	    View::make('post/edit.html', array('post' => $post));
    }
    
    public static function update($id){
        $post = new Post(array(
                'postId' => $_POST['postId'],
                'author' => $_POST['author'],
                'title' => trim($_POST['title']),
                'content' => trim($_POST['content'])
        ));
        
        $errors = $post->errors();

        if (empty($errors)) {
            $post->update();
            Redirect::to('/post/' . $post->postId, array('message' => 'Kirjoitusta on muokattu!'));
        } else {
            View::make('post/edit.html', array('errors' => $errors, 'post' => $post));
        }
    }
    
    public static function destroy($id) {
        $post = Post::find($id);
        Post::destroy($id);
        Redirect::to('/blog/' . $post->blogId, array('message' => 'Kirjoitus on tuhottu!'));
    }
    
    public static function store() {
        $post = new Post(array(
                'blogId' => $_POST['blogId'],
                'title' => trim($_POST['title']),
                'author' => $_SESSION['user'],
                'content' => trim($_POST['content'])
        ));
        
        $errors = $post->errors();
        
        if (empty($errors)) {
            $post->save();
            Redirect::to('/post/' . $post->postId, array('message' => 'Uusi kirjoitus on rekisteröity järjestelmään onnistuneesti!'));
        } else {
            View::make('post/create.html', array('blogId' => $_POST['blogId'], 'errors' => $errors));
            //Redirect::to('/post/' . $post->postId, array('errors' => $errors));
        }
    }

}

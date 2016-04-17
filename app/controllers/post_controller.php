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
   	    View::make('post/show.html', array('post' => $post, 'comments' => $comments));
    }
    
    public static function create() {
   	    View::make('post/create.html', array('blogId' => $_POST['blogId']));
    }
    
    public static function edit($postId) {
        $post = Post::find($postId);
   	    View::make('post/edit.html', array('post' => $post));
    }
    
    public static function update($id){
        $params = $_POST;
        $post = new Post(array(
                'postId' => $params['postId'],
                'author' => $params['author'],
                'title' => $params['title'],
                'content' => $params['content']
        ));
        
        $errors = $post->errors();

        if (!empty($errors)) {
            View::make('post/edit.html', array('errors' => $errors, 'post' => $post));
        } else {
            $post->update();
            Redirect::to('/post/' . $post->postId, array('message' => 'Kirjoitusta on muokattu!'));
        }
    }
    
    public static function destroy($id) {
        Post::destroy($id);
        Redirect::to('/post');
    }
    
    public static function store() {
        $params = $_POST;
        $post = new Post(array(
                'blogId' => $params['blogId'],
                'title' => $params['title'],
                'author' => 1,                      // This should match the logged user
                'content' => $params['content']
        ));
        
        $post->save();
        
        Redirect::to('/post/' . $post->postId);
    }

}

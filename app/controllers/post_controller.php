<?php
class PostController extends BaseController{
      
    public static function index() {
        $posts = Post::all();
        View::make('post_all.html', array('posts' => $posts));
    }

    // For testing purposes only
    public static function listed($blogId) {
        $posts = Post::allInBlog($blogId);
   	    View::make('post_list.html', array('posts' => $posts));
    }
    
    public static function show($postId) {
        $post = Post::find($postId);
   	    View::make('post_show.html', array('post' => $post));
    }
    
    public static function edit($postId) {
        $post = Post::find($postId);
   	    View::make('post_edit.html', array('post' => $post));
    }

  }

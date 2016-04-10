<?php

class BlogController extends BaseController{

    public static function index() {
        $blogs = Blog::all();
        
        View::make('blog_list.html', array('blogs' => $blogs));
    }
    
    public static function show($id) {
        $blog = Blog::find($id);
        $posts = Post::all($id);
        
   	    View::make('blog_show.html', array('blog' => $blog, 'posts' => $posts));
    }
    
    public static function edit($id) {
        $blog = Blog::find($id);
   	    View::make('blog_edit.html', array('blog' => $blog));
    }

  }

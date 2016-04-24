<?php

class BlogController extends BaseController{

    public static function index() {
        $blogs = Blog::all();    
        View::make('blog/list.html', array('blogs' => $blogs));
    }
    
    public static function show($id) {
        $blog = Blog::find($id);
        $posts = Post::allInBlog($id);       
   	    View::make('blog/show.html', array('blog' => $blog, 'posts' => $posts));
    }
    
    public static function edit($id) {
        $blog = Blog::find($id);
   	    View::make('blog/edit.html', array('blog' => $blog));
    }
    
    public static function create() {
   	    View::make('blog/create.html');
    }
    
    public static function update($id){
        $blog = new Blog(array(
                'blogId' => $_POST['blogid'],
                'name' => $_POST['name'],
                'description' => $_POST['description']
        ));
        
        $errors = $blog->errors();

        if (!empty($errors)){
            View::make('blog/edit.html', array('errors' => $errors));
        } else {
            $blog->update();
            Redirect::to('/blog/' . $blog->blogId, array('message' => 'Blogia on muokattu!'));
        }
    }

    
    public static function destroy($id) {
        Blog::destroy($id);
        Redirect::to('/blog');
    }
    
    public static function store() {     
        $blog = new Blog(array(
                'name' => trim($_POST['name']),
                'description' => trim($_POST['description'])
        ));
        
        $errors = $blog->errors();
        
        if (count($errors) == 0) {    
            $blog->save();    
            Redirect::to('/blog/' . $blog->blogId);
        } else {
            Redirect::to('/blog/new', array('errors' => $errors));
        }
    }

  }

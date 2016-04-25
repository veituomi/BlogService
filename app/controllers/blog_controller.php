<?php

class BlogController extends BaseController{

    public static function index() {
        $blogs = Blog::all();    
        View::make('blog/list.html', array('blogs' => $blogs));
    }
    
    public static function show($id) {
        $blog = Blog::find($id);
        $posts = Post::allInBlog($id);
   	    View::make('blog/show.html', array('blog' => $blog, 'posts' => $posts,
           'can_destroy' => Blog::canDestroy($blog->blogId), 'can_edit' => Blog::canEdit($blog->blogId)));
    }
    
    public static function edit($id) {
        $blog = Blog::find($id);
   	    View::make('blog/edit.html', array('blog' => $blog, 'can_destroy' => Blog::canDestroy($blog->blogId),
           'can_edit' => Blog::canEdit($blog->blogId)));
    }
    
    public static function create() {
   	    View::make('blog/create.html');
    }
    
    public static function update($id){
        $blog = new Blog(array(
                'blogId' => $_POST['blogid'],
                'name' => trim($_POST['name']),
                'description' => trim($_POST['description'])
        ));
        
        if (!Blog::canEdit($blog->blogId)) {
            Redirect::to('/blog/' . $blog->blogId, array('errors' => array('Ei ole vaadittavia oikeuksia.')));
            return;
        }
        
        $errors = $blog->errors();

        if (empty($errors)) {
            $blog->update();
            Redirect::to('/blog/' . $blog->blogId, array('message' => 'Blogia on muokattu!'));
        } else {
            Redirect::to('/blog/' . $blog->blogId . '/edit', array('errors' => $errors));
        }
    }

    
    public static function destroy($id) {
        if (!Blog::canDestroy($id)) {
            Redirect::to('/blog/' . $blog->blogId);
            return;
        }
        
        Blog::destroy($id);
        Redirect::to('/blog');
    }
    
    public static function store() {
        $blog = new Blog(array(
                'name' => trim($_POST['name']),
                'description' => trim($_POST['description'])
        ));
        
        $errors = $blog->errors();
        
        if (empty($errors)) {    
            $blog->save();    
            Redirect::to('/blog/' . $blog->blogId);
        } else {
            Redirect::to('/blog/new', array('errors' => $errors));
        }
    }

  }

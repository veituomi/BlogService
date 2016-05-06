<?php
class PostController extends BaseController{
    
    public static function index() {
        $posts = Post::allFollowedByUser(BaseController::get_user_logged_in());
        View::make('post/followed.html', array('posts' => $posts));
    }

    // For testing purposes only
    public static function listed($blogId) {
        $posts = Post::allInBlog($blogId);
   	    View::make('post/list.html', array('posts' => $posts));
    }
    
    public static function show($postId) {
        $post = Post::find($postId);
        $comments = Comment::allInPost($postId);
        $tags = TagCloud::getTags($postId);
        
        $like = new Likes(array('userId' => BaseController::get_user_logged_in(), 'postId' => $postId));
        $liked = $like->find() != null;
        
        $follows = new Follows(array('follower' => BaseController::get_user_logged_in(), 'followee' => $post->author));
        $followed = $follows->find() != null;
        
   	    View::make('post/show.html', array('post' => $post, 'comments' => $comments,
           'tags' => $tags, 'liked' => $liked, 'followed' => $followed,
           'can_destroy' => Post::canDestroy($post->postId),
           'can_edit' => Post::canEdit($post->postId), 'author' => Blogger::find($post->author)));
    }
    
    public static function create() {
   	    View::make('post/create.html', array('blogId' => $_POST['blogId']));
    }
    
    public static function edit($postId) {
        $post = Post::find($postId);
        $tags = implode(' ', TagCloud::getTags($postId));
   	    View::make('post/edit.html', array('post' => $post, 'tags' => $tags,
           'can_destroy' => Post::canDestroy($post->postId),
           'can_edit' => Post::canEdit($post->postId)));
    }
    
    public static function update($id){
        $post = new Post(array(
                'postId' => $id,
                'author' => $_SESSION['user'],
                'title' => trim($_POST['title']),
                'content' => trim($_POST['content'])
        ));
        
        $tags = explode(' ', trim($_POST['tags']), 6);
        if (count($tags) == 6) {
            array_pop($tags);
        }
        $tags = array_unique($tags);
        
        if (!Post::canEdit($post->postId)) {
            Redirect::to('/post/' . $post->postId, array('errors' => array('Ei ole vaadittavia oikeuksia.')));
            return;
        }
        
        $errors = $post->errors();

        if (empty($errors)) {
            $post->update();
            TagCloud::setTags($post->postId, $tags);
            Redirect::to('/post/' . $post->postId, array('message' => 'Kirjoitusta on muokattu!'));
        } else {
            Redirect::to('/post/' . $post->postId . "/edit", array('errors' => $errors, 'post' => $post));
        }
    }
    
    public static function destroy($id) {
        if (!Post::canDestroy($id)) {
            Redirect::to('/post/' . $id, array('errors' => array('Ei ole vaadittavia oikeuksia.')));
            return;
        }
        
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
        
        $tags = explode(' ', trim($_POST['tags']), 6);  //not perfect
        if (count($tags) == 6) {
            array_pop($tags);
        }
        $tags = array_unique($tags);
        
        $errors = $post->errors();
        
        if (empty($errors)) {
            $post->save();
            TagCloud::setTags($post->postId, $tags);
            Redirect::to('/post/' . $post->postId, array('message' => 'Uusi kirjoitus on rekisteröity järjestelmään onnistuneesti!'));
        } else {
            View::make('post/create.html', array('blogId' => $_POST['blogId'], 'errors' => $errors));
            //Redirect::to('/post/' . $post->postId, array('errors' => $errors));
        }
    }

}

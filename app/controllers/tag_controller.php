<?php

class TagController extends BaseController {
    
    public static function show($name) {
        $tag = Tag::findByName($name);
        if ($tag != null) {
            $posts = Post::allByTag($tag->tagId);
            View::make('tag/show.html', array('posts' => $posts, 'tag' => $tag));
        } else {
            View::make('tag/show.html', array('message' => 'Valitettavasti haulla ei löytynyt mitään. :('));
        }
    }
   
}

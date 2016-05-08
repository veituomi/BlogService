<?php

class SearchController extends BaseController {
    
    public static function show($query) {
        $keywords = explode('-', $query);      
        $blogs = array();
        $posts = array();

        foreach ($keywords as $word) {
            $blogs = array_merge($blogs, Blog::search($word));
            $posts = array_merge($posts, Post::search($word));
        }
        
        foreach ($keywords as $word) {
            $tag = Tag::findByName($word);
            if (!$tag) continue;
            $posts = array_merge($posts, Post::allByTag($tag->tagId));
        }
        
        $blogs = array_unique($blogs, SORT_REGULAR);
        $posts = array_unique($posts, SORT_REGULAR);
        
        View::make('search.html', array('query' => $query, 'blogs' => $blogs, 'posts' => $posts));
    }
    
}

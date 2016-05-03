<?php

    \Slim\Route::setDefaultConditions(array('id' => '[0-9]{1,}'));

    function login_first() {
        BaseController::require_logged_in();
    }

    $routes->get('/', function() {
        HomeController::index();
    });

    $routes->get('/sandbox', function() {
        HomeController::sandbox();
    });

    $routes->get('/login', function() {
        SessionController::login();
    });
    
    $routes->post('/login', function() {
        SessionController::handle_login();
    });
    
    $routes->get('/register', function() {
        SessionController::register();
    });
    
    $routes->post('/register', function() {
        SessionController::handle_register();
    });
    
    $routes->get('/logout', function() {
        SessionController::handle_logout();
    });
    
    $routes->get('/blog', function() {
        BlogController::index();
    });
    
    $routes->post('/blog', 'login_first', function() {
        BlogController::store();
    });
    
    $routes->get('/blog/new', function() {
        BlogController::create();
    });
    
    $routes->get('/blog/:id', function($id) {
        BlogController::show($id);
    });
    
    $routes->get('/blog/:id/edit', function($id) {
        BlogController::edit($id);
    });
    
    $routes->post('/blog/:id/edit', 'login_first', function($id) {
        BlogController::update($id);
    });
    
    $routes->post('/blog/:id/destroy', 'login_first', function($id) {
        BlogController::destroy($id);
    });
    
    $routes->get('/post', 'login_first', function() {
        PostController::index();
    });
    
    $routes->post('/post', 'login_first', function() {
        PostController::store();
    });
    
    $routes->post('/post/new', 'login_first', function() {
        PostController::create();
    });
    
    // All posts related to a certain blog
    $routes->get('/post/b/:id', function($id) {
        PostController::listed($id);
    });
    
    $routes->get('/post/:id', function($id) {
        PostController::show($id);
    });
    
    $routes->get('/post/:id/edit', function($id) {
        PostController::edit($id);
    });
    
    $routes->post('/post/:id/edit', 'login_first', function($id) {
        PostController::update($id);
    });
    
    $routes->post('/post/:id/destroy', 'login_first', function($id) {
        PostController::destroy($id);
    });
    
    $routes->get('/post/:id/like', 'login_first', function($id) {
        LikesController::like($id);
    });
    
    $routes->get('/post/:id/follow', 'login_first', function($id) {
        FollowsController::follow($id);
    });
    
    $routes->get('/tag/:name', function($name) {
        TagController::show($name);
    });
    
    $routes->get('/user', function() {
        UserController::index();
    });
    
    $routes->get('/user/:id', function($id) {
        UserController::show($id);
    });

    $routes->get('/user/:id/edit', function($id) {
        UserController::edit($id);
    });

    $routes->post('/user/:id/edit', function($id) {
        UserController::update($id);
    });

    $routes->post('/user/:id/destroy', function($id) {
        UserController::destroy($id);
    });

    $routes->post('/comment', 'login_first', function() {
        CommentController::store(); 
    });
    
    $routes->post('/comment/:id/destroy', 'login_first', function($id) {
        CommentController::destroy($id); 
    });
    
    $routes->get('/comment/:id/edit', function($id) {
        CommentController::edit($id);
    });

    $routes->post('/comment/:id/edit', 'login_first', function($id) {
        CommentController::update($id);
    });
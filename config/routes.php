<?php

  $routes->get('/', function() {
    HomeController::index();
  });

  $routes->get('/sandbox', function() {
    HomeController::sandbox();
  });

  $routes->get('/login', function(){
    SessionController::login();
  });
  
  $routes->post('/login', function(){
    SessionController::handle_login();
  });
  
  $routes->get('/register', function(){
    SessionController::register();
  });
  
  $routes->post('/register', function(){
    SessionController::handle_register();
  });
  
  $routes->post('/blog', function() {
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
  
  $routes->post('/blog/:id/edit', function($id) {
    BlogController::update($id);
  });
  
  $routes->post('/blog/:id/destroy', function($id) {
    BlogController::destroy($id);
  });
  
  $routes->get('/post', function() {
    PostController::index();
  });
  
  $routes->post('/post', function() {
    PostController::store();
  });
  
  $routes->post('/post/new', function() {
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
  
  $routes->get('/user', function() {
    UserController::index();
  });
  
  $routes->get('/user/:id', function($id) {
    UserController::show($id);
  });

  $routes->get('/user/:id/edit', function($id) {
    UserController::edit($id);
  });

  $routes->post('/comment', function() {
     CommentController::store(); 
  });
  
  $routes->post('/comment/:id/destroy', function($id) {
     CommentController::destroy($id); 
  });
  
  $routes->get('/comment/:id/edit', function($id) {
    CommentController::edit($id);
  });

  $routes->post('/comment/:id/edit', function($id) {
    CommentController::update($id);
  });
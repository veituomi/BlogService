<?php

  $routes->get('/', function() {
    HomeController::index();
  });

  $routes->get('/sandbox', function() {
    HomeController::sandbox();
  });

  $routes->get('/login', function() {
    SessionController::index();
  });

  $routes->get('/blog', function() {
    BlogController::index();
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
  
  $routes->post('/blog/:id/destroy', function($id) {
    BlogController::destroy($id);
  });
  
  $routes->get('/post', function() {
    PostController::index();
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

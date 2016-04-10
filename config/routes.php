<?php

  $routes->get('/', function() {
    HelloWorldController::index();
  });

  $routes->get('/hiekkalaatikko', function() {
    HelloWorldController::sandbox();
  });

  $routes->get('/login', function() {
    SessionController::index();
  });

  $routes->get('/blog', function() {
    BlogController::index();
  });
  
  $routes->get('/blog/:id', function($id) {
    BlogController::show($id);
  });
  
  $routes->get('/blog/edit/:id', function($id) {
    BlogController::edit($id);
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
  
  $routes->get('/post/edit/:id', function($id) {
    PostController::edit($id);
  });
  
  $routes->get('/user', function() {
    UserController::index();
  });
  
  $routes->get('/user/1', function() {
    UserController::show();
  });

  $routes->get('/user/edit/1', function() {
    UserController::edit();
  });

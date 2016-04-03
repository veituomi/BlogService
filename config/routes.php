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
  
  $routes->get('/blog/1', function() {
    BlogController::show();
  });
  
  $routes->get('/blog/edit/1', function() {
    BlogController::edit();
  });
  
  $routes->get('/post', function() {
    PostController::index();
  });
  
  $routes->get('/post/1', function() {
    PostController::show();
  });
  
  $routes->get('/post/edit/1', function() {
    PostController::edit();
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

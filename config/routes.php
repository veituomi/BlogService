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

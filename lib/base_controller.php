<?php

  class BaseController{
    public static function get_user_logged_in() {
      if (!isset($_SESSION['user'])) return NULL;
      return Blogger::find($_SESSION['user']);
    }

    public static function check_logged_in() {
        return isset($_SESSION['user']);
    }
  }

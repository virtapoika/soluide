<?php

class AuthController {
  public function __construct() {
    require_once('model.php');
    $this->model = new AuthModel();
  }

  public function register() {
    try {
      $return = $this->model->register();
      echo json_encode(array(true, $return));
    } catch(Exception $e) {
      echo json_encode(array(false, $e->getMessage()));
    }
  }

  public function login() {
    try {
      $return = $this->model->login();
      echo json_encode(array(true, $return));
    } catch(Exception $e) {
      echo json_encode(array(false, $e->getMessage()));
    }
  }

  public function validateToken($token) {
    try {
      $return = $this->model->validateToken();
      return array($return, "Valid token");
    } catch(Exception $e) {
      return array(false, "Invalid token");
    }
  }

  public function getUserByToken($token) {
    try {
      return $this->model->getUserByToken($token);
    } catch(Exception $e) {
      throw new Exception($e);
    }
  }
}

?>

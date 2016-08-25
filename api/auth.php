<?php

require(__DIR__ . "/application/auth/controller.php");
$auth = new AuthController();

if(!isset($_POST['action'])) {
  echo json_encode(array(
    "boolean" => false,
    "msg" => "Invalid API REQUEST"
  ));
  exit();
}

if ($_POST['action'] == 'login') {
  $auth->login();
} else if ($_POST['action'] == 'register') {
  $auth->register();
} else if ($_POST['action'] == 'validateToken')  {
  echo json_encode($auth->validateToken());
}
?>

<?php

require(__DIR__ . "/application/files/controller.php");
try {
  $files = new FilesController();
} catch (Exception $e) {
  echo "User authentication failure";
  error_log("user authentication failure: " . $e->getMessage());
  die();
}

if(!isset($_POST['action'])) {
  invalid();
}

function invalid() {
  echo json_encode(array(
    "boolean" => false,
    "msg" => "Invalid API REQUEST"
  ));
  exit();
}

if ($_POST['action'] == 'createProject') {
  $files->createProject();
} else if ($_POST['action'] == 'listProjects') {
  $files->listProjects();
}

//---------------------------

else if($_POST['action'] == 'createFile') {
  $files->createFile();
} else if ($_POST['action'] == 'listFiles') {
  $files->listFiles();
} else if ($_POST['action'] == 'getFile') {
  $files->getFile();
}else if ($_POST['action'] == 'updateFile') {
  $files->updateFile();
}else if ($_POST['action'] =='deleteFile') {
  $files->deleteFile();
} else {
  invalid();
}
?>

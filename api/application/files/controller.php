<?php

class FilesController {
  public function __construct() {
    require 'model.php';
    $this->model = new FilesModel();
  }

  //-----------------PROJECTS--------------

  public function createProject() {
    try {
      $return = $this->model->createProject();
      echo json_encode(array(true, $return));
    } catch (Exception $e) {
      echo json_encode(array(false, $e->getMessage()));
    }
  }
  public function listProjects() {
    try {
      $return = $this->model->listProjects();
      echo json_encode(array(true, $return));
    } catch (Exception $e) {
      echo json_encode(array(false, $e->getMessage()));
    }
  }

  //-------------------FILES------------------

  public function createFile() {
    try {
      $return = $this->model->createFile();
      echo json_encode(array(true, $return));
    } catch (Exception $e) {
      echo json_encode(array(false, $e->getMessage()));
    }
  }

  public function listFiles() {
    try {
      $return = $this->model->listFiles();
      echo json_encode(array(true, $return));
    } catch (Exception $e) {
      echo json_encode(array(false, $e->getMessage()));
    }
  }

  public function getFile() {
    try {
      $return = $this->model->getFile();
      echo json_encode(array(true, $return));
    } catch (Exception $e) {
      echo json_encode(array(false, $e->getMessage()));
    }
  }

  public function updateFile() {
    try {
      $return = $this->model->updateFile();
      echo json_encode(array(true, $return));
    } catch (Exception $e) {
      echo json_encode(array(false, $e->getMessage()));
    }
  }

  public function deleteFile() {
    try {
      //error_log("WTF??");
      $return = $this->model->deleteFile();
      echo json_encode(array(true, $return));
    } catch (Exception $e) {
      echo json_encode(array(false, $e->getMessage()));
    }
  }
}
?>

<?php
class FilesModel {
  private $path;

  public function __construct() {
    $this->path = "/var/www/soluide.sovellus.design/public_root/api/application/projects/";

    //check token
    require(__DIR__ . "/../db.php");
    require_once(dirname(__FILE__) . "/../auth/controller.php");
    $auth = new AuthController();
    if(!isset($_POST['token']) || $auth->validateToken($_POST['token'])[0] == false) {
      throw new Exception($auth->validateToken($_POST['token'])[0]);
    }

    //check user has access to project he is trying to interfere with
    if(isset($_POST['name'])) { //if user tries to access project
      if(!isset($_POST['token'])) {
        throw new Exception("API authentication error");
      }
      /*
      check that user owns the project he is trying to
      operate

      OR

      that the project he is operate with
      is free
      */
      $token = $_POST['token'];
      $name = $_POST['name'];
      $email = $auth->getUserByToken($token);

      //check that user owns the project
      $query = $connection->prepare("SELECT COUNT(*) FROM projects WHERE owner = ? AND name = ?");
      $query->execute(array($email, $_POST['name']));
      $res = $query->fetchColumn();
      //error_log("HELVETTI: " . $res);
      if($res == 0) {
        //user does now own the project

        //Check that project name is free
        $query = $connection->prepare("SELECT COUNT(*) FROM projects WHERE name = ?");
        $query->execute(array($name));
        $res = $query->fetchColumn();
        //error_log("SAATANA: " . $query->fetchColumn());
        if ($res != 0) {
          throw new Exception("Project already exists. Projects with this name: " . $res);
        }
      }
    }
  }
  /**
  Creates new project

  params:
    $_POST['name'] = project name
  */
  public function createProject() {
    $token = $_POST['token'];
    if(!isset($_POST['name'])) {
      throw new Exception("Invalid api request: not all required parameters supplied");
    }

    $name = $_POST['name'];

    //create project folder
    $this->absolutePath($_POST['name']);
    $path = $this->path . $name . "/";
    mkdir($path);

    //get user email by token
    require_once(dirname(__FILE__) . "/../auth/controller.php");
    $auth = new AuthController();
    $email = $auth->getUserByToken($token);

    //check that project name is free
    require(__DIR__ . "/../db.php");
    $query = $connection->prepare("SELECT COUNT(*) FROM projects WHERE name = ?");
    $query->execute(array($name));
    if($query->fetchColumn() != 0) {
      throw new Exception("Project name is not free");

    }

    //create database record about the project
    $query = $connection->prepare("INSERT INTO projects (owner, name) VALUES(?, ?)");
    $query->execute(array($email, $name));

    //create default file index.html
    $this->writeFile($name, 'index.html');

    //return project name when successful
    return $name;
  }

  /**
  Returns list of projects
  */
  public function listProjects()
  {
    $token = $_POST['token'];

    require(__DIR__ . "/../db.php");

    require_once(dirname(__FILE__) . "/../auth/controller.php");
    $auth = new AuthController();
    $email = $auth->getUserByToken($token);

    $query = $connection->prepare("SELECT name FROM projects WHERE owner = ?");
    $query->execute(array($email));
    return $query->fetchAll();
  }


  /**
  function creates new file

  parameters:
    $_POST['name'] = Project name
    $_POST['path'] = relative path from project roott
    $_POST['data'] (optional) = data we want to write
  */
  public function createFile() {
    if(!isset($_POST['name']) || !isset($_POST['path'])) {
      throw new Exception("Invalid api request: not all required parameters supplied");
    }
    if(isset($_POST['data'])) {
      $data = $_POST['data'];
    } else {
      $data = null;
    }
    $entirePath = $this->absolutePath($_POST['name'], $_POST['path']);
    if(file_exists($entirePath)) {
      throw new Exception("File already exists");

    }

    $this->writeFile($_POST['name'], $_POST['path'], $data);
    return $_POST['path'];
  }

  /**
  parameters:
    $_POST['name'] = Project name
  */
  public function listFiles() {

    /*
    Recursive function that returns array tree of folder structure
    */
    function listing($directory) {
      $files = array_slice(scandir($directory), 2);
      foreach($files as $key => $fileOrDirectory) {
        if (is_dir($directory . "/" . $fileOrDirectory)) {
          $files[$key] = listing($directory . "/" . $fileOrDirectory);
        }
        //error_log($fileOrDirectory);
      }
      return $files;
    }

    if (!isset($_POST['name'])) {
      throw new Exception("Invalid api request: not all required parameters supplied");
    }
    $projectPath = $this->absolutePath($_POST['name']);

    $files = listing($projectPath);

    return $files;
  }

  public function getFile() {
    $entirePath = $this->absolutePath($_POST['name'], $_POST['path']);
    $file = file_get_contents($entirePath);

    return $file;
  }
  public function updateFile(){
    if((!isset($_POST['name'])) || (!isset($_POST['path']) || (!isset($_POST['data'])))) {
      throw new Exception("Invalid api request: not all required parameters supplied");
    }

    $this->writeFile($_POST['name'], $_POST['path'], $_POST['data']);
    return "homma toimii";
  }

  public function deleteFile() {
    $entirePath = $this->absolutePath($_POST['name'], $_POST['path']);
    if(!file_exists($entirePath)) {
      throw new Exception("File not found");
    }
    unlink($entirePath);
    return "homma toimii";
  }

  private function absolutePath($project, $filepath = "/") {
    return "/var/www/soluide.sovellus.design/public_root/api/application/projects/" . $project . "/" . $filepath;
  }

  /**
  project = project name
  $filepath = relative path from project root including filename

    ex. if we want to create new file called lol.txt inside src/
    $filepath would then be src/lol.txt
  */
  private function writeFile($project, $filepath, $data =
      "<!DOCTYPE html> \n<html>\n<head>\n\n</head>\n<body>\n\n\n</body>\n</html>"
  ) {
    $savePath = $this->absolutePath($project, $filepath);
    file_put_contents($savePath, $data);
  }
}
?>

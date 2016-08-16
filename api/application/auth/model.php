<?php
class AuthModel {

  private $options; //password hashing options

  public function __construct() {
    $this->options = array(
      'cost' => 12
    );
  }
  //--------------------------------------------------------------

  private function getNewTokenFromDB($email) {
    require(__DIR__ . "/../db.php");

    //create token.
    $token = uniqid();

    //Insert user into database
    $query = $connection->prepare("UPDATE users SET token=? WHERE email=?");
    $query->execute(array($token, $email));

    return $token;
  }
  //--------------------------------------------------------------

  public function validateToken($token = null) {
    if(!isset($token)) {
      if(!isset($_POST['token'])) {
        throw new Exception("No token supplied");
      }
      $token = $_POST['token'];
    }

    require(__DIR__ . "/../db.php");

    $query = $connection->prepare("SELECT token FROM users WHERE token = ?");
    $query->execute(array($token));

    if($query->rowCount() == 0) {
      throw new Exception("Token is not valid");
    }

    return true;
  }
  //--------------------------------------------------------------

  public function login() {
    //database connection
    require(__DIR__ . "/../db.php");

    //make sure that post is valid
    if(!isset($_POST['email']) || !isset($_POST['password'])) {
      throw new Exception("fields must be filled");
    }

    //make sure that there is user account with that email
    $query = $connection->prepare("SELECT password FROM users WHERE email = ?");
    $query->execute(array($_POST['email']));

    if ($query->rowCount() == 0) {
      throw new Exception("no user found");
    }

    //get password from database
    $password_in_database = $query->fetchColumn();

    //check if password is correct
    if(!password_verify($_POST['password'], $password_in_database)) {
      throw new Exception("Password is incorrect");
    }

    //WERE IN YAYY!!

    //check if password needs rehash because of changed hash settings
    if(password_needs_rehash($password_in_database, PASSWORD_DEFAULT, $this->options)) {
      $query = $connection->prepare("UPDATE users SET password = ? WHERE email = ?");
      $query->execute(array(
        password_hash($_POST['password'], PASSWORD_BCRYPT, $this->options),
        $_POST['email']
      ));
    }

    return $this->getNewTokenFromDB($_POST['email']);
  }

  //--------------------------------------------------------------


  public function register() {

    //database connection
    require(__DIR__ . "/../db.php");

    //make sure that post is valid
    if(!isset($_POST['email']) || !isset($_POST['password1']) || !isset($_POST['password2'])) {
      throw new Exception("fields must be filled");
    }

    $email = $_POST["email"];
    $password1 = $_POST["password1"];
    $password2 = $_POST["password2"];

    //validate email
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      throw new Exception('You have to give a correct email address!');
    }

    //check passwords
    if($password1 != $password2)
    {
      throw new Exception('You have to write the same password twice!');
    }

    //check that password matches with requirements
    if(mb_strlen($password1) < 8 || mb_strlen($password1) > 64)
    {
      throw new Exception('The password has to be atleast 8 marks long');
    }

    //check that email is free
    $query = $connection->prepare("SELECT * FROM users WHERE email = ?");
    $query->execute(array($email));
    $number_of_rows = $query->rowCount();
    if($number_of_rows != 0)
    {
      throw new Exception('E-mail has been already taken');
    }

    //hash password
    $hashedpassword = password_hash($password1, PASSWORD_BCRYPT, $this->options);

    //create token.
    $token = uniqid();

    //Insert user into database
    $query = $connection->prepare("INSERT INTO users (email, password, token) VALUES (?,?,?);");
    $query->execute(array($email, $hashedpassword, $token));

    return $token;
  }

  public function getUserByToken($token) {
    try {
      $this->validateToken($token);
    } catch (Exception $e) {
      throw new Exception($e);
    }
    //TOKEN IS VALID, GET USER FROM DB

    require(__DIR__ . "/../db.php");

    $query = $connection->prepare("SELECT * FROM users WHERE token = ?");
    $query->execute(array($token));
    $result = $query->fetch();
    $result = $result["email"];
    return $result;
  }
}
?>

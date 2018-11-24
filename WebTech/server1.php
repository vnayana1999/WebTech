<?php
session_start();

// initializing variables
$username = "root";
$email    = "";
$mno="";
$college="";
$errors = array(); 

// connect to the database
$db = mysqli_connect('localhost', 'root', '', 'wtproject1');

// REGISTER USER
if (isset($_POST['reg_user'])) {
  // receive all input values from the form
  $username = mysqli_real_escape_string($db, $_POST['username']);
  $password_l = mysqli_real_escape_string($db, $_POST['password_l']);
  $email = mysqli_real_escape_string($db, $_POST['email']);
  $mno = mysqli_real_escape_string($db, $_POST['mno']);
  $college = mysqli_real_escape_string($db, $_POST['college']);
  

  // form validation: ensure that the form is correctly filled ...
  // by adding (array_push()) corresponding error unto $errors array
  if (empty($username)) { array_push($errors, "Username is required"); }
  if (empty($password_1)) { array_push($errors, "Password is required"); }
  if (empty($email)) { array_push($errors, "Email is required"); }
  if (empty($mno)) { array_push($errors, "Mobile number is required"); }
  if (empty($college)) { array_push($errors, "College is required"); }
  

  // first check the database to make sure 
  // a user does not already exist with the same username and/or email
  $user_check_query = "SELECT * FROM student WHERE username='$username' OR email='$email' LIMIT 1";
  $result = mysqli_query($db, $user_check_query);
  $user = mysqli_fetch_assoc($result);
  
  if ($user) { // if user exists
    if ($user['username'] === $username) {
      array_push($errors, "Username already exists");
    }

    if ($user['email'] === $email) {
      array_push($errors, "email already exists");
    }
  }

  // Finally, register user if there are no errors in the form
  if (count($errors) == 0) {
    $password = md5($password_1);//encrypt the password before saving in the database

    $query = "INSERT INTO student (username, password, email, mno , college) 
          VALUES('$username', '$password','$email', '$mno', '$college')";
    mysqli_query($db, $query);
    $_SESSION['username'] = $username;
    $_SESSION['success'] = "You Have now registered";
    header('location: stu.php');//
  }
}




// LOGIN USER
if (isset($_POST['login_user'])) {
  $username = mysqli_real_escape_string($db, $_POST['username']);
  $password = mysqli_real_escape_string($db, $_POST['password']);

  if (empty($username)) {
    array_push($errors, "Username is required");
  }
  if (empty($password)) {
    array_push($errors, "Password is required");
  }

  if (count($errors) == 0) {
    $password = md5($password);
    $query = "SELECT * FROM users WHERE username='$username' AND password='$password'";
    $results = mysqli_query($db, $query);
    if (mysqli_num_rows($results) == 1) {
      $_SESSION['username'] = $username;
      $_SESSION['success'] = "You are now logged in";
      $check = mysqli_fetch_assoc($results);
      if($check['username']=="Prathiba")
      {
        header('location: student_dashb1.php');
      }//Logging in is success.Go to the student dashboard of Prathiba
      if($check['username']=="Krithi")
      {
        header('location: student_dashb2.php');
      }
      if($check['username']=="Nayana")
      {
        header('location: student_dashb3.php');
      }
    }else {
      array_push($errors, "Wrong username/password combination");
    }
  }
}

?>

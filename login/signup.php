<!DOCTYPE html>
<?php
  require("connection.php");

  if(isset($_POST['submit'])){

    $username = $_POST['username'];
    $password = PASSWORD_HASH($_POST['password'], PASSWORD_DEFAULT);
    $email = $_POST['email'];
    
    $stmt = $conn->prepare("SELECT * FROM users WHERE Username=:username");
    $stmt->bindParam(':username', $username);
    $stmt->execute();
  
    $userExists = $stmt->fetchColumn();

    if(!$userExists){
      registerUser($username, $password, $email);
      
      $stmt = $conn->prepare("SELECT * FROM users WHERE username=:username");
      $stmt->bindParam(':username', $username);
      $stmt->execute();
      $userExists = $stmt->fetchAll();

      session_start();
      $_SESSION['id'] = $userExists[0]['id'];

      header('Location: http://localhost/WeatherCalender/application/index.php');
      
      
      
    }else{
      
    }
  }

    function registerUser($username, $password, $email){
      global $conn;
      $stmt = $conn->prepare("INSERT INTO users(Username, Password, Email) VALUES(:username, :password, :email)");
      $stmt->bindParam(':username', $username);
      $stmt->bindParam(':password', $password);
      $stmt->bindParam(':email', $email);
      $stmt->execute();
    }

?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
</head>
<body>
        <h1>Registrieren</h1>
    <form action="signup.php" method="POST">
      <input type="text" name="username" placeholder="Username" required><br>
      <input type="email" name="email" placeholder="Email" required><br>
      <input type="password" name="password" placeholder="Passwort" required><br>

      <button type="submit" name="submit">Registrieren</button>
    </form>
    <br>
    <a href="login.php">Du hast schon einen Acc?</a>
    

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.3/dist/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
</body>
</html>
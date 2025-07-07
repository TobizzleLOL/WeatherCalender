<!DOCTYPE html>

<?php
require("connection.php");
require("calender.php");
require("overview.php");

session_start();

$stmt = $conn->prepare("SELECT * FROM users WHERE UserID=:id");
$stmt->bindParam(':id', $_SESSION['id']);
$stmt->execute();

$userExists = $stmt->fetchAll();

if (!is_array($userExists)) {
    $_SESSION['Username'] = $userExists[0]['Username'];
}

$calender = new Calender($_SESSION['id']);
$overview = new Overview($_SESSION['id']);
?>

<html lang="en">
<head>
    <link rel="stylesheet" type="text/css" href="style.css">
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kalender</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
</head>
<body>
    <div id='nav'>
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <a class="navbar-brand" href="#"><?php echo $_SESSION['Username'] ?></a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item active">
                        <a href='/./WeatherCalender/login/logout.php' class="nav-link" href="#">Logout </a>
                    </li>
                    <li class="nav-item">
                        <a href='/./WeatherCalender/application/index.php?mode=calender' class="nav-link">Calender </a>
                    </li>
                    <li class="nav-item">
                        <a href='/./WeatherCalender/application/index.php?mode=overview' class="nav-link">Overview </a>
                    </li>
                </ul>
            </div>
        </nav>
    <div id='Calender'>
        <?php
            if ($_GET['mode'] == 'calender') {
                $calender->loadEvents();
                $calender->show();
            }
            if ($_GET['mode'] == 'overview') {
                $overview->loadTimespans();
                $overview->show();
            }
        ?>
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.3/dist/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
</body>
</html>
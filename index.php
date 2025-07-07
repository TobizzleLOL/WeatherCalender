<!DOCTYPE html>
<?php
session_start();

if( $_SESSION['id'] == NULL){
    header('Location: http://localhost/WeatherCalender/login/login.php');
}else{
    header('Location: http://localhost/WeatherCalender/application/index.php');
}


?>
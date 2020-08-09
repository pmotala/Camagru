<?php
require_once "database.php";

$DB_NAME = "camagru";

try
{
    $conn = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connection Successful<br>";

    $sql = "CREATE DATABASE {$DB_NAME}";
    $conn->exec($sql);
    echo "Database Created Succesfully<br>";

    $sql = "USE {$DB_NAME}";
    $conn->exec($sql);
    echo "Database Selected Succesfully<br>";

    $sql = "CREATE TABLE users (
        `ID` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
        `USERNAME` varchar(22) NOT NULL,
        `EMAIL` varchar(64) NOT NULL,
        `PASSWORD` varchar(64) NOT NULL,
        `JOINED` datetime NOT NULL,
        `ACTIVATED` int(2) NOT NULL DEFAULT 0,
        `ACTIVATION_CODE` varchar(64) NOT NULL,
        `TOKENS` varchar(64) NULL,
        `RECEIVE` int(11) NOT NULL DEFAULT 1)";
    $conn->exec($sql);
    echo "Table Created Succesfully<br>";

    $sql = "CREATE TABLE uploads (
        `ID` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
        `USERID` int(11) NOT NULL,
        `USERNAME` varchar(22) NOT NULL,
        `COMM_ID` varchar(40) NOT NULL,
        `IMAGE` longblob NOT NULL,
        `TITLE` TEXT NOT NULL,
        `TYPE` varchar(22) NOT NULL DEFAULT 'image/jpeg',
        `DATE` datetime NOT NULL)";
    $conn->exec($sql);
    echo "Table Created Succesfully<br>";

    $sql = "CREATE TABLE user_session (
        `ID` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
        `USERID` int(11) NOT NULL,
        `HASH` varchar(64) NOT NULL)";
    $conn->exec($sql);
    echo "Table Created Succesfully<br>";

    $sql = "CREATE TABLE comments (
        `ID` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
        `USERID` int(11) NOT NULL,
        `USERNAME` varchar(22) NOT NULL,
        `COMM_ID` varchar(64) NOT NULL,
        `COMMENT` varchar(200) NOT NULL)";
    $conn->exec($sql);
    echo "Table Created Succesfully<br>";

    $sql = "CREATE TABLE likes (
        `ID` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
        `USERID` int(11) NOT NULL,
        `COMM_ID` varchar(64) NOT NULL,
        `LIKES` int(11) NOT NULL DEFAULT 0,
        `DISLIKES` int(11) NOT NULL DEFAULT 0)";
    $conn->exec($sql);
    echo "Table Created Succesfully<br>";

}
catch(PDOException $e)
{
    echo $sql." Failed.<br> ".$e->getMessage();
}

$conn = null;
?>
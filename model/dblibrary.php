<?php
    $dsn ='mysql:host=localhost; dbname=library_db';
    $username = 'INFO_3135';
    $password = 'library';
    try{
        $db = new PDO($dsn, $username, $password);
    }
    catch(PDOException $error){
        $errormessage = $error -> getMessage();

        include(__DIR__ . '/../errors/dblibraryerror.php');
        exit();
    }



?>
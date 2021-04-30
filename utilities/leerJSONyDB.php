<?php 

    // Varibale donde se recibe el JSON
    $data = json_decode(file_get_contents("php://input"));

    // Instanciar una nueva Database y conectarse
    $database = new Database();
    $db = $database->connect();
<?php

require_once("models/user.php");
require_once("models/Message.php");
require_once("globals.php");
require_once("dao/UserDAO.php");
require_once("db.php");

$message = new Message($BASE_URL);

$userDao = new UserDAO($conn, $BASE_URL);

//Resgata o tipo de formulário
$type = filter_input(INPUT_POST, "type");

//Atualizar usuário
if ($type === "update") {

  //Resgata dados do usuário
  $userData = $userDao->verifyToken();

  //resgata dados do usuário
  $name = filter_input(INPUT_POST, "name");
  $lastname = filter_input(INPUT_POST, "lastname");
  $email = filter_input(INPUT_POST, "email");
  $bio = filter_input(INPUT_POST, "bio");

  //Criar um novo objeto de usuário
  $user = new User();

  //Preencher os dados do usuário
  $userData->name = $name;
  $userData->lastname = $lastname;
  $userData->email = $email;
  $userData->bio = $bio;

  // Upload da imagem
  if (isset($_FILES["image"]) && !empty($_FILES["image"]["tmp_name"])) {

    $image = $_FILES["image"];
    $imageTypes = ["image/jpeg", "image/jpg", "image/png"];
    $jpgArray = ["image/jpeg", "image/jpg"];

    // Checagem de tipo de imagem
    if (in_array($image["type"], $imageTypes)) {

      // Checar se jpg
      if (in_array($image["type"], $jpgArray)) {

        $imageFile = imagecreatefromjpeg($image["tmp_name"]);

        // Imagem é png
      } else {

        $imageFile = imagecreatefrompng($image["tmp_name"]);

      }

      $imageName = $user->imageGenerateName();

      imagejpeg($imageFile, "./img/users/" . $imageName, 100);

      $userData->image = $imageName;

    } else {

      $message->setMessage("Tipo inválido de imagem, insira png ou jpg!", "error", "back");

    }

  }

  $userDao->update($userData);

  //Atualizar senhas do usuário
} else if ($type === "changepassword") {

  $password = filter_input(INPUT_POST, "password");
  $confirmpassword = filter_input(INPUT_POST, "confirmpassword");
  
  //Resgata id do usuário
  $userData = $userDao->verifyToken();
  $id = $userData->id;

  if ($password == $confirmpassword) {

    //Criar um novo objeto de usuário
    $user = new User();

    $finalPassword = $user->generatePassword($password);
    $user->password = $finalPassword;
    $user->id = $id;
    $userDao->changePassword($user);

  } else {

    $message->setMessage("Senhas não correspondem!", "error", "back");

  }


} else {

  $message->setMessage("informações inválidas!", "error", "index.php");

}
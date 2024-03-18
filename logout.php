<?php

require_once("template/header.php");

if ($userDAO) {
  $userDAO->destroyToken();
}
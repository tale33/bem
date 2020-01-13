<?php

require_once ("../src/UserForm.php");

if($_POST && count($_POST)) {
    $info = UserForm::addUser($_POST);
    echo json_encode($info);
} else {
    UserForm::displayUserForm();
}

<?php

    require_once("models/user.php");
    require_once("dao/UserDAO.php");
    require_once("globals.php");
    require_once("db.php");
    require_once("models/Message.php");

    $message = new Message($BASE_URL);

    $userDAO = new UserDAO($conn, $BASE_URL);

    // Resgata o tipo do formulario
    $type = filter_input(INPUT_POST, "type");

    //Verificação o tipo do formulario
    if ($type === "register") {

        $name = filter_input(INPUT_POST, "name");
        $lastname = filter_input(INPUT_POST, "lastname");
        $email = filter_input(INPUT_POST, "email");
        $password = filter_input(INPUT_POST, "password");
        $confirmpassword = filter_input(INPUT_POST, "confirmpassword");

        //Verificação de dados minimos
        if ($name && $lastname && $email && $password) {

            //Verificar se as senhas batem
            if ($password === $confirmpassword) {

                //Verificar se o e-mail já esta cadastrado
                if ($userDAO->findByEmail($email) === false) {

                    $user = new User();

                    //Criação de token e senha
                    $userToken = $user->generateToken();
                    $finalPassword = $user->generatePassword($password);

                    $user->name = $name;
                    $user->lastname = $lastname;
                    $user->email = $email;
                    $user->password = $finalPassword;
                    $user->token = $userToken;
                    
                    $auth = true;

                    $userDAO->create($user, $auth);

                } else {
                    //Enviar uma msg de erro, e-mail já existe cadastrado
                    $message->setMessage("Usuário já cadastrado, tente outro e-mail.", "error", "back");
                }

            } else {
                //Enviar uma msg de erro, senhas não são iguais
                $message->setMessage("As senhas não são iguais.", "error", "back");
            }

        } else {
            //Enviar uma msg de erro
            $message->setMessage("Por favor, preencha todos os campos.", "error", "back");
        }


    } elseif ($type === "login") {

        $email = filter_input(INPUT_POST, "email");
        $password = filter_input(INPUT_POST, "password");

        //Tenta autenticar usuario
        if ($userDAO->authenticateUser($email, $password)) {

            $message->setMessage("Seja bem-vindo!", "success", "editprofile.php");

        //Redirecionar o usuario, caso não consiga autenticar
        } else {
            $message->setMessage("Usuário e/ou senha incorretos", "error", "back");
        }

    } else {
        $message->setMessage("Informações invalidas", "error", "index.php");
    }
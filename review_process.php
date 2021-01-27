<?php 

    require_once("models/movie.php");
    require_once("dao/UserDAO.php");
    require_once("globals.php");
    require_once("db.php");
    require_once("models/Message.php");
    require_once("models/Review.php");
    require_once("dao/MovieDAO.php");
    require_once("dao/ReviewDAO.php");

    $message = new Message($BASE_URL);
    $userDAO = new UserDAO($conn, $BASE_URL);
    $movieDAO = new MovieDAO($conn, $BASE_URL);
    $reviewDAO = new ReviewDAO($conn, $BASE_URL);

    //Recebendo o tipo do formulario
    $type = filter_input(INPUT_POST, "type");

    //Resgatar dados do usuario
    $userData = $userDAO->verifyToken();

    if($type === "create") {

        //Recebendo dados do post
        $rating = filter_input(INPUT_POST, "rating");
        $review = filter_input(INPUT_POST, "review");
        $movies_id = filter_input(INPUT_POST, "movies_id");
        $users_id = $userData->id;

        $reviewObject = new Review();

        $movieData = $movieDAO->findById($movies_id);

        //Validando se o filme existe
        if($movieData) {

            //Verificar dados minimos
            if(!empty($rating) && !empty($review) && !empty($movies_id)) {

                $reviewObject->rating = $rating;
                $reviewObject->review = $review;
                $reviewObject->movies_id = $movies_id;
                $reviewObject->users_id = $users_id;

                $reviewDAO->create($reviewObject);

            } else {
                $message->setMessage("Você precisa inserir a nota e o review!", "error", "back");
            }

        } else {
            $message->setMessage("Informações inválidas!", "error", "index.php");
        } 

    } else {
        $message->setMessage("Informações inválidas!", "error", "index.php");
    }



?>
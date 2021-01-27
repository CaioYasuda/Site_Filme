<?php 

    require_once("models/movie.php");
    require_once("dao/UserDAO.php");
    require_once("globals.php");
    require_once("db.php");
    require_once("models/Message.php");
    require_once("dao/MovieDAO.php");

    $message = new Message($BASE_URL);
    $userDAO = new UserDAO($conn, $BASE_URL);
    $movieDAO = new MovieDAO($conn, $BASE_URL);

    //Resgatar dados do usuario
    $userData = $userDAO->verifyToken();

    // Resgata o tipo do formulario
    $type = filter_input(INPUT_POST, "type");

    if ($type === "create") {

        //Receber os dados dos imputs
        $title = filter_input(INPUT_POST, "title");
        $description = filter_input(INPUT_POST, "description");
        $trailer = filter_input(INPUT_POST, "trailer");
        $category = filter_input(INPUT_POST, "category");
        $length = filter_input(INPUT_POST, "length");

        $movie = new Movie();

        // Validação minima de dados
        if (!empty($title) && !empty($description) && !empty($category)) {

            $movie->title = $title;
            $movie->description = $description;
            $movie->trailer = $trailer;
            $movie->category = $category;
            $movie->length = $length;
            $movie->users_id = $userData->id;

            //Upload de imagem do filme
            if (isset($_FILES["image"]) && !empty($_FILES["image"]["tmp_name"])) {

                $image = $_FILES["image"];
                $imageTypes = ["image/jpeg", "image/jpg", "image/png"];
                $jpgArray = ["image/jpeg", "image/jpg"];

                //Chegando o tipo da imagem
                if (in_array($image["type"], $imageTypes)) {

                    //checa se imagem é jpg
                    if (in_array($image["type"], $jpgArray)) {
                        $imageFile = imagecreatefromjpeg($image["tmp_name"]);
                    } else {
                        $imageFile = imagecreatefrompng($image["tmp_name"]);
                    }

                    //Gerando o nome da imagem
                    $imageName = $movie->imageGenerateName();

                    imagejpeg($imageFile, "./img/movies/" . $imageName, 100);

                    $movie->image = $imageName;

                } else {
                    $message->setMessage("Tipo invalido de imagem, insira png ou jpeg!", "error", "back");
                } 
            } 

            $movieDAO->create($movie);

        } else {
            $message->setMessage("Você precisa adicionar pelo menos: título, descrição e categoria", "error", "back");
        }

    } elseif ($type === "delete"){

        //Receber dados do formulario
        $id = filter_input(INPUT_POST, "id");

        $movie = $movieDAO->findById($id);

        if($movie){

            //Verificar se o filme é do usuario
            if ($movie->users_id === $userData->id) {

                $movieDAO->destroy($movie->id);

            } else {
                $message->setMessage("ERROR", "error", "index.php");
            }

        } else {
            $message->setMessage("ERROR", "error", "index.php");
        }

    } elseif ($type === "update") {
    
        //Receber os dados dos imputs
        $title = filter_input(INPUT_POST, "title");
        $description = filter_input(INPUT_POST, "description");
        $trailer = filter_input(INPUT_POST, "trailer");
        $category = filter_input(INPUT_POST, "category");
        $length = filter_input(INPUT_POST, "length");
        $id = filter_input(INPUT_POST, "id");

        $movieData = $movieDAO->findById($id);

        //Verifica se encontrou o filme
        if($movieData) {

            //Verificar se o filme é do usuario
            if ($movieData->users_id === $userData->id) {

                // Validação minima de dados
                if (!empty($title) && !empty($description) && !empty($category)) {
    
                    // Edição do filme
                    $movieData->title = $title;
                    $movieData->description = $description;
                    $movieData->trailer = $trailer;
                    $movieData->category = $category;
                    $movieData->length = $length;

                    //Upload de imagem do filme
                    if (isset($_FILES["image"]) && !empty($_FILES["image"]["tmp_name"])) {

                        $image = $_FILES["image"];
                        $imageTypes = ["image/jpeg", "image/jpg", "image/png"];
                        $jpgArray = ["image/jpeg", "image/jpg"];

                        //Chegando o tipo da imagem
                        if (in_array($image["type"], $imageTypes)) {

                            //checa se imagem é jpg
                            if (in_array($image["type"], $jpgArray)) {
                                $imageFile = imagecreatefromjpeg($image["tmp_name"]);
                            } else {
                                $imageFile = imagecreatefrompng($image["tmp_name"]);
                            }

                            //Gerando o nome da imagem
                            $movie = new Movie();
                            
                            $imageName = $movie->imageGenerateName();

                            imagejpeg($imageFile, "./img/movies/" . $imageName, 100);

                            $movieData->image = $imageName;

                        } else {
                            $message->setMessage("Tipo invalido de imagem, insira png ou jpeg!", "error", "back");
                        } 
                    }    

                    $movieDAO->update($movieData);

                } else {
                    $message->setMessage("Você precisa adicionar pelo menos: título, descrição e categoria", "error", "back");
                }


            } else {
                $message->setMessage("ERROR", "error", "index.php");
            }

        } else {
            $message->setMessage("ERROR", "error", "index.php");
        }


    } else {
        $message->setMessage("Informações inválidas!", "error", "index.php");
    }
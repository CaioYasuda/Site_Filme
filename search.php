<?php
    require_once("templates/header.php");

    require_once("dao/MovieDAO.php");

    //DAO dos filmes
    $movieDAO = new MovieDAO($conn, $BASE_URL);

    // Regasta busca do usuario
    $q = filter_input(INPUT_GET, "q");

    $movies = $movieDAO->findByTitle($q);

?>
    
    <div id="main-container" class="container-fluid">
        <h2 class="section-title">Você está buscando por: <span id="search-result"><?= $q ?></span></h2>
        <P class="section-description">Resultados encontrados</P>
        <div class="movies-container">
            <?php foreach($movies as $movie): ?>
                <?php require("templates/movie_card.php"); ?>
            <?php endforeach; ?> 
            <?php if(count($movies) === 0): ?>
                <p class="empty-list">Não há filmes para esta busca! <a href="<?= $BASE_URL ?>" class="back-link">Voltar</a>.</p>
            <?php endif; ?>
        </div>           
    </div>
<?php
    require_once("templates/footer.php");
?>
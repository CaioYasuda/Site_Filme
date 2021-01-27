<?php
    require_once("templates/header.php");

    require_once("dao/MovieDAO.php");

    //DAO dos filmes
    $movieDAO = new MovieDAO($conn, $BASE_URL);

    $lastestMovies = $movieDAO->getLastestMovies();

    $actionMovies = $movieDAO->getMoviesByCategory("Ação");

    $ComedyMovies = $movieDAO->getMoviesByCategory("Comedia");

?>
    
    <div id="main-container" class="container-fluid">
        <h2 class="section-title">Filmes novos</h2>
        <P class="section-description">Veja as críticas dos últimos filmes adicionados no MovieStar</P>
        <div class="movies-container">
            <?php foreach($lastestMovies as $movie): ?>
                <?php require("templates/movie_card.php"); ?>
            <?php endforeach; ?> 
            <?php if(count($lastestMovies) === 0): ?>
                <p class="empty-list">Ainda não há filmes cadastrados!</p>
            <?php endif; ?>
        </div>

        <h2 class="section-title">Ação</h2>
        <P class="section-description">Veja os melhores filmes de ação</P>
        <div class="movies-container">
            <?php foreach($actionMovies as $movie): ?>
                <?php require("templates/movie_card.php"); ?>
            <?php endforeach; ?> 
            <?php if(count($actionMovies) === 0): ?>
                <p class="empty-list">Ainda não há filmes cadastrados!</p>
            <?php endif; ?>
        </div>

        <h2 class="section-title">Comédia</h2>
        <P class="section-description">Veja os melhores filmes de comédia</P>
        <div class="movies-container">
            <?php foreach($ComedyMovies as $movie): ?>
                <?php require("templates/movie_card.php"); ?>
            <?php endforeach; ?> 
            <?php if(count($ComedyMovies) === 0): ?>
                <p class="empty-list">Ainda não há filmes cadastrados!</p>
            <?php endif; ?>
        </div>
    </div>

<?php
    require_once("templates/footer.php");
?>
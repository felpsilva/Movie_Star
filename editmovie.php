<?php
require_once("template/header.php");

//Verifica se usuário está autenticado
require_once("models/User.php");
require_once("dao/UserDAO.php");
require_once("dao/MovieDAO.php");

$user = new User();
$userDAO = new UserDAO($conn, $BASE_URL);
$movieDao = new MovieDAO($conn, $BASE_URL);
$userData = $userDAO->verifyToken(true);
$id = filter_input(INPUT_GET, "id");

if (empty($id)) {
  $message->setMessage("O filme não foi encontrado!", "error", "index.php");
} else {

  $movie = $movieDao->findById($id);

  //Verifica se o filme existe
  if (!$movie) {

    $message->setMessage("O filme não foi encontrado!", "error", "index.php");

  }

}
//Checar se o filme tem imagem
if ($movie->image == "") {
  $movie->image = "movie_cover.jpg";
}


?>
<div class="container-fluid" id="main-container">
  <div class="col-md-12">
    <div class="row">
      <div class="col-md-6 offset-md-1">
        <h1>
          <?= $movie->title ?>
        </h1>
        <p class="page-description">Altere os dados do filme no formulário abaixo:</p>
        <form id="edit-movie-form" action="<?= $BASE_URL ?>movie_process.php" method="POST"
          enctype="multipart/form-data">
          <input type="hidden" value="update" name="type">
          <input type="hidden" value="<?= $movie->id ?>" name="id">
          <div class="form-group">
            <label for="title">Título:</label>
            <input type="text" name="title" id="title" class="form-control" placeholder="Digite o título do seu filme"
              value="<?= $movie->title ?>">
          </div>
          <div class="form-group">
            <label for="image">Imagem:</label>
            <input type="file" name="image" id="image" class="form-control-file">
          </div>
          <div class="form-group">
            <label for="length">Duração:</label>
            <input type="text" name="length" id="length" class="form-control" placeholder="Digite a duração do filme"
              value="<?= $movie->length ?>">
          </div>
          <div class="form-group">
            <label for="category">Categoria:</label>
            <select name="category" id="category" class="form-control">
              <option value="">Selecione</option>
              <option value="Ação" <?= $movie->category === "Ação" ? "selected" : "" ?>>Ação</option>
              <option value="Drama" <?= $movie->category === "Drama" ? "selected" : "" ?>>Drama</option>
              <option value="Comédia" <?= $movie->category === "Comédia" ? "selected" : "" ?>>Comédia</option>
              <option value="Romance" <?= $movie->category === "Romance" ? "selected" : "" ?>>Romance</option>
              <option value="Fantasia / Ficção" <?= $movie->category === "Fantasia / Ficção" ? "selected" : "" ?>>Fantasia
                /
                Ficção</option>
            </select>
          </div>
          <div class="form-group">
            <label for="trailer">Trailer:</label>
            <input type="text" name="trailer" id="trailer" class="form-control" placeholder="Insira o link do trailer"
              value="<?= $movie->trailer ?>">
          </div>
          <div class="form-group">
            <label for="description">Descrição</label>
            <textarea name="description" id="description" cols="5" class="form-control"
              placeholder="Descrava um filme..."><?= $movie->description ?></textarea>
          </div>
          <input type="submit" value="Atualizar filme" class="btn card-btn">
        </form>
      </div>
      <div class="col-md-3">
        <div class="movie-image-container"
          style="background-image: url('<?= $BASE_URL ?>img/movies/<?= $movie->image ?>')"></div>
      </div>
    </div>
  </div>
</div>

<?php
require_once("template/footer.php")
  ?>
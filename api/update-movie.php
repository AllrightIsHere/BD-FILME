<?php
    require_once "../includes/bd.php";

    $title_text = $_POST["titleText"];
    $id_movie = $_POST["idMovie"];
    $movie_source = $_POST["movieSource"];
    $director_name = $_POST["directorName"];
    $cover_url = $_POST["coverUrl"];

    $sql = "update filme set NomeFilme='$title_text', Source='$movie_source', Diretor='$director_name', Capa='$cover_url' where filme.CodFilme='$id_movie'";
    if($banco->query($sql) === TRUE) {
        echo "Filme atualizado";
    } else {
        echo "Houve um erro" . $banco->error;
    }
    $banco->close();
?>

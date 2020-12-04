<?php
    require_once "../includes/bd.php";

    $id_movie = $_POST["idMovie"];

    $sql = "delete from filme where filme.CodFilme='$id_movie'";
    if($banco->query($sql) === TRUE) {
        echo "Filme deletado com sucesso";
    } else {
        echo "Houve um erro" . $banco->error;
    }
    $banco->close();
?>

<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <title>Quartel dos Filmes</title>
        <meta charset="UTF-8"/>
        <link rel="stylesheet" href="style.css"/>
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <script src="js/jquery.js"></script>
    </head>

    <body>

        <?php
            require_once "includes/bd.php";
        ?>

        <div id="body">

            <?php 
                    $queries = array();
                    parse_str($_SERVER['QUERY_STRING'], $queries);
                    $cod_filme = $queries['f'];
                    $buscaFilme = $banco->query("select * from filme where CodFilme=$cod_filme");
                    $buscaElenco = $banco->query("select * from elenco where CodFilme=$cod_filme order by Papel asc");
                    $elenco = array();

                    while($item = $buscaElenco->fetch_object()){
                        $buscaAtor = $banco->query("select * from ator where CodAtor=$item->CodAtor");
                        $ator = $buscaAtor->fetch_object();
                        array_push($elenco, "<p>$ator->NomeAtor ($item->Papel)</p>");
                    }

                    $elenco_view = join('', $elenco);

                    $generos = array();
                    $buscaGeneros = $banco->query("Select g.Nome from filme f left outer join filme_genero g on f.CodFilme=g.CodFilme where f.CodFilme=$cod_filme");

                    while($reg = $buscaGeneros->fetch_assoc()){
                        array_push($generos, "<p>{$reg['Nome']}</p>");
                    }

                    $generos_view = join('', $generos);

                    if(!$buscaFilme){
                        echo "<tr><td>Houve um erro ao acessar o banco de Filmes :(";
                    }else{
                        if($buscaFilme->num_rows == 0){
                            echo "<tr><td>Nenhum filme encontrado :/";
                        }else{
                            $reg = $buscaFilme->fetch_object();
                            $date = new DateTime($reg->AnoProd);
                            $year = $date->format('Y');

                            $buscaEstudio = $banco->query("select * from estudio where CodEst=$reg->CodEst");
                            $estudio = $buscaEstudio->fetch_object();

                            echo
                            "
                            <div class=edit-mode-flag>
                            Salvar alterações
                            <div id=done-movie-title-bt>
                                <i class='material-icons' style='font-size:25px;color:white'>done</i>
                            </div>
                            </div>
                            
                            <div class=movie-name-head>
                                <h1 id=title-input contentEditable=true>$reg->NomeFilme</h1><h1>($year)</h1>
                            </div>";
                            echo "<h4>Url youtube:</h4>";
                            echo "<h4 id=video-url contentEditable=true>https://www.youtube.com/watch?v=$reg->Source</h4>";
                            echo "<iframe id=movie-frame width='789' height='444' src='https://www.youtube.com/embed/$reg->Source' frameborder='0' allow='accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture' allowfullscreen></iframe>";
                            echo "<table class='list' style='table-layout:fixed; width:900px'>";
                                echo "<tr>
                                        <td class='wrapword'>
                                            <img id=cover-img src=$reg->Capa>
                                            <p id=cover-src contentEditable=true>$reg->Capa</p>
                                        </td>
                                        <td class='wrapword'>
                                            <h4>Elenco:</h4>
                                            $elenco_view
                                        </td>
                                        <td class='wrapword'>
                                            <h4>Diretor:</h4>
                                            <p id=director-name contentEditable=true>$reg->Diretor</p>
                                        </td>
                                        <td>
                                            <h4>Gêneros:</h4>
                                            $generos_view
                                        </td>
                                        <td class='wrapword'>
                                            <h4>Estúdio:</h4>
                                            <img src=$estudio->Logo>
                                        </td>
                                    </tr>";
                        }
                    }

                ?>
            </table>

        </div>

        <?php $banco->close(); ?>
        
        <script src="./js/edit-filme.js"></script>
    </body>
</html>
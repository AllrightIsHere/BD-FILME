<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <title>Quartel dos Filmes</title>
        <meta charset="UTF-8"/>
        <link rel="stylesheet" href="style.css"/>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
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
                            <div class=movie-name-head>
                                <h1>$reg->NomeFilme ($year)</h1>
                                <a href='./edit-filme.php?f=$reg->CodFilme'>
                                    <i class='fa fa-pencil' style='font-size:25px;color:white'></i>
                                </a>
                            </div>";

                            echo "<iframe width='789' height='444' src='https://www.youtube.com/embed/$reg->Source' frameborder='0' allow='accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture' allowfullscreen></iframe>";
                            echo "<table class='list'>";
                                echo "<tr>
                                        <td>
                                            <img src=$reg->Capa>
                                        </td>
                                        <td>
                                            <h4>Elenco:</h4>
                                            $elenco_view
                                        </td>
                                        <td>
                                            <h4>Diretor:</h4>
                                            <p>$reg->Diretor</p>
                                        </td>
                                        <td>
                                            <h4>Gêneros:</h4>
                                            $generos_view
                                        </td>
                                        <td>
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

    </body>
</html>
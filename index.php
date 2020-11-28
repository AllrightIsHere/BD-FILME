<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <title>Quartel dos Filmes</title>
        <meta charset="UTF-8"/>
        <link rel="stylesheet" href="style.css"/>
    </head>

    <body>

        <?php
            require_once "includes/bd.php";
        ?>

        <div id="body">

            <h1>Filmes</h1>
            <table class="list">
                <?php 
                    $buscaFilme = $banco->query("select * from filme order by AnoProd desc");

                    if(!$buscaFilme){
                        echo "<tr><td>Houve um erro ao acessar o banco de Filmes :(";
                    }else{
                        if($buscaFilme->num_rows == 0){
                            echo "<tr><td>Nenhum filme encontrado :/";
                        }else{
                            while($reg = $buscaFilme->fetch_object()){ 
                                echo "<tr><td><a href='./filme.php?f=$reg->CodFilme'><img src=$reg->Capa></a></td><td>$reg->NomeFilme</td><td>$reg->Diretor</td>";
                                $date = new DateTime($reg->AnoProd);
                                $aux = $date->format('Y');
                                echo "<td>$aux</td></tr>";
                            }
                        }
                    }

                ?>
            </table>

        </div>

        <?php $banco->close(); ?>

    </body>
</html>
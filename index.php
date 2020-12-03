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
            <h4>Pesquisar:</h4>

            <?php
                $estudios = array();
                $generos = array();
                $anos = array();

                $formatData = "YYYY";

                $todosFilmes = $banco->query("select * from filme order by AnoProd desc");
                
                while($reg = $todosFilmes->fetch_object()){
                    
                    $date = new DateTime($reg->AnoProd);
                    $year = $date->format('Y');
                    
                    if(!in_array("<option value='$year'>$year</option>", $anos)){
                        array_push($anos, "<option value='$year'>$year</option>");
                    }

                    $buscaEstudio = $banco->query("select * from estudio where CodEst=$reg->CodEst");

                    $estudio = $buscaEstudio->fetch_object();
                    $estudioName = $estudio->Nome;

                    if(!in_array("<option value='$estudioName'>$estudioName</option>", $estudios)){
                        array_push($estudios, "<option value='$estudioName'>$estudioName</option>");
                    }

                    $buscaGenero = $banco->query("select * from filme_genero where CodFilme=$reg->CodFilme");

                    while($g = $buscaGenero->fetch_object()){
                        $generoNome = $g->Nome;
                        if(!in_array("<option value='$generoNome'>$generoNome</option>", $generos)){
                            array_push($generos, "<option value='$generoNome'>$generoNome</option>");
                        }
                    }

                }

                $anosSelecionados = join('', $anos);
                $estudiosSelecionados = join('', $estudios);
                $generosSelecionados = join('', $generos);
            

            echo "<form>
                    <label>Nome:</label>
                    <input type='text' name='f_name'/>


                    <label for='estudio'>Estúdio:</label>
                    <select id='estudio' name='f_estudio'>
                        <option value=''>Todos</option>
                        $estudiosSelecionados
                    </select>

                    <label for='genero'>Gênero:</label>
                    <select id='genero' name='f_genero'>
                        <option value=''>Todos</option>
                        $generosSelecionados
                    </select>

                    <label for='ano'>Ano:</label>
                    <select id='ano' name='f_ano'>
                        <option value=''>Todos</option>
                        $anosSelecionados
                    </select>
                    <br>
                    <label for='ordem'>Ordem:</label>
                    <select id='ordem' name='f_ordem'>
                        <option value='desc'>Mais recentes</option>
                        <option value='asc'>Menos recentes</option>
                    </select>

                    <input type='submit' value='Procurar'/>
                    
                </form>
                <br>";
            ?>
            
            <?php

                $nomeFilme = "";
                $estudioFilme = "";
                $generoFilme = "";
                $anoFilme = "";
                $ordemFilme = "";

                $queries = array();
                parse_str($_SERVER['QUERY_STRING'], $queries);

                if(isset($queries['f_name'])){
                    $nomeFilme = $queries['f_name'];
                }

                if(isset($queries['f_estudio'])){
                    $estudioFilme = $queries['f_estudio'];
                }

                if(isset($queries['f_genero'])){
                    $generoFilme = $queries['f_genero'];
                }

                if(isset($queries['f_ano'])){
                    $anoFilme = $queries['f_ano'];
                }

                if(isset($queries['f_ordem'])){
                    $ordemFilme = $queries['f_ordem'];
                }
                else {
                    $ordemFilme = "desc";
                }

                $query = "Select * from filme f order by f.AnoProd {$ordemFilme}";

                if(empty($nomeFilme) && empty($estudioFilme) && empty($generoFilme) && empty($anoFilme)){
                    $query = "Select * from filme f order by f.AnoProd {$ordemFilme}";
                }

                else if(!empty($nomeFilme) && empty($estudioFilme) && empty($generoFilme) && empty($anoFilme)){
                    $query = "Select * from filme f where f.NomeFilme like '%$nomeFilme%' order by AnoProd {$ordemFilme}";
                }

                else if(empty($nomeFilme) && !empty($estudioFilme) && empty($generoFilme) && empty($anoFilme)){
                    $query = "Select * from filme f join estudio e on f.CodEst=e.CodEst and e.Nome='$estudioFilme' order by AnoProd {$ordemFilme}";
                }

                else if(empty($nomeFilme) && empty($estudioFilme) && !empty($generoFilme) && empty($anoFilme)){
                    $query = "Select * from filme f join filme_genero g on f.CodFilme=g.CodFilme and g.Nome='{$generoFilme}' order by AnoProd {$ordemFilme}";
                }

                else if(empty($nomeFilme) && empty($estudioFilme) && empty($generoFilme) && !empty($anoFilme)){
                    $query = "Select * from filme f where f.AnoProd>='{$anoFilme}-01-01' and f.AnoProd<='{$anoFilme}-12-31' order by AnoProd {$ordemFilme}";
                }

                else if(!empty($nomeFilme) && !empty($estudioFilme) && empty($generoFilme) && empty($anoFilme)){
                    $query = "Select * from filme f join estudio e on f.CodEst=e.CodEst and e.Nome='$estudioFilme' where f.NomeFilme like '%$nomeFilme%' order by AnoProd {$ordemFilme}";
                }

                else if(!empty($nomeFilme) && empty($estudioFilme) && !empty($generoFilme) && empty($anoFilme)){
                    $query = "Select * from filme f join filme_genero g on f.CodFilme=g.CodFilme and g.Nome='{$generoFilme}' where f.NomeFilme like '%$nomeFilme%' order by AnoProd {$ordemFilme}";
                }

                else if(!empty($nomeFilme) && empty($estudioFilme) && empty($generoFilme) && !empty($anoFilme)){
                    $query = "Select * from filme f where f.NomeFilme like '%$nomeFilme%' and f.AnoProd>='{$anoFilme}-01-01' and f.AnoProd<='{$anoFilme}-12-31' order by f.AnoProd {$ordemFilme}";
                }

                else if(!empty($nomeFilme) && !empty($estudioFilme) && !empty($generoFilme) && empty($anoFilme)){
                    $query = "Select * from filme f join estudio e on f.CodEst=e.CodEst and e.Nome='$estudioFilme' join filme_genero g on f.CodFilme=g.CodFilme and g.Nome='{$generoFilme}' where f.NomeFilme like '%$nomeFilme%' order by AnoProd {$ordemFilme}";
                }

                else if(!empty($nomeFilme) && !empty($estudioFilme) && empty($generoFilme) && !empty($anoFilme)){
                    $query = "Select * from filme f join estudio e on f.CodEst=e.CodEst and e.Nome='$estudioFilme' where f.NomeFilme like '%$nomeFilme%' and f.AnoProd>='{$anoFilme}-01-01' and f.AnoProd<='{$anoFilme}-12-31' order by AnoProd {$ordemFilme}";
                }

                else if(!empty($nomeFilme) && empty($estudioFilme) && !empty($generoFilme) && !empty($anoFilme)){
                    $query = "Select * from filme f join filme_genero g on f.CodFilme=g.CodFilme and g.Nome='{$generoFilme}' where f.NomeFilme like '%$nomeFilme%' and f.AnoProd>='{$anoFilme}-01-01' and f.AnoProd<='{$anoFilme}-12-31' order by AnoProd {$ordemFilme}";
                }

                else if(empty($nomeFilme) && !empty($estudioFilme) && !empty($generoFilme) && empty($anoFilme)){
                    $query = "Select * from filme f join estudio e on f.CodEst=e.CodEst and e.Nome='$estudioFilme' join filme_genero g on f.CodFilme=g.CodFilme and g.Nome='{$generoFilme}' order by AnoProd {$ordemFilme}";
                }

                else if(empty($nomeFilme) && !empty($estudioFilme) && empty($generoFilme) && !empty($anoFilme)){
                    $query = "Select * from filme f join estudio e on f.CodEst=e.CodEst and e.Nome='$estudioFilme' where f.AnoProd>='{$anoFilme}-01-01' and f.AnoProd<='{$anoFilme}-12-31' order by AnoProd {$ordemFilme}";
                }

                else if(empty($nomeFilme) && !empty($estudioFilme) && !empty($generoFilme) && !empty($anoFilme)){
                    $query = "Select * from filme f join estudio e on f.CodEst=e.CodEst and e.Nome='$estudioFilme' join filme_genero g on f.CodFilme=g.CodFilme and g.Nome='{$generoFilme}' where f.AnoProd>='{$anoFilme}-01-01' and f.AnoProd<='{$anoFilme}-12-31' order by AnoProd {$ordemFilme}";
                }

                else if(empty($nomeFilme) && empty($estudioFilme) && !empty($generoFilme) && !empty($anoFilme)){
                    $query = "Select * from filme f join filme_genero g on f.CodFilme=g.CodFilme and g.Nome='{$generoFilme}' where f.AnoProd>='{$anoFilme}-01-01' and f.AnoProd<='{$anoFilme}-12-31' order by AnoProd {$ordemFilme}";
                }

                $buscaFilme = $banco->query($query);

                $queryReplace = str_replace("*", "f.CodFilme", $query);

                $totalQuery = "Select count(*) AS Total from filme t where t.CodFilme in ({$queryReplace})";

                $totalFilme = $banco->query($totalQuery);

                if($totalFilme){
                    $totalResultado = $totalFilme->fetch_object();
                    $total = $totalResultado->Total;
                    echo "<h4>Foram encontrados {$total} filme(s). <a href='./cadastrar.php'><button>Cadastrar novo filme</button></a></h4>";
                }

                if(!$buscaFilme){
                    echo "<table class='list'><tr><td>Houve um erro ao acessar o banco de Filmes :(</td></tr></table>";
                }else{
                    if($buscaFilme->num_rows == 0){
                        echo "<table class='list'><tr><td>Nenhum filme encontrado :/</td></tr></table>";
                    }else{
                        echo "<table class='list'>";
                        while($reg = $buscaFilme->fetch_object()){ 
                            echo "<tr><td><a href='./filme.php?f=$reg->CodFilme'><img src=$reg->Capa></a></td><td>$reg->NomeFilme</td><td>$reg->Diretor</td>";
                            $date = new DateTime($reg->AnoProd);
                            $aux = $date->format('Y');
                            echo "<td>$aux</td></tr>";
                        }
                        echo "</table>";
                    }
                }

            ?>

        </div>

        <?php $banco->close(); ?>

    </body>
</html>
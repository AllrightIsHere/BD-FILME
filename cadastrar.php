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
            <h1>Cadastrar</h1>
            <?php 
                //required='required'
                echo "<form>
                        <label>Nome do filme:</label>
                        <input type='text' name='f_nome' required='required'/>
                        
                        <br>

                        <label>Link do trailer:</label>
                        <input type='text' name='f_source' required='required'/>
                        
                        <br>

                        <label>Ano de Produção:</label>
                        <input type='date' name='f_ano' required='required'/>
                        
                        <br>
                        
                        <label>Diretor:</label>
                        <input type='text' name='f_diretor' required='required'/>

                        <br>

                        <label>Link da capa:</label>
                        <input type='text' name='f_capa' required='required'/>

                        <br>

                        <label>Nome do estúdio:</label>
                        <input type='text' name='e_nome' required='required'/>

                        <br>

                        <label>Link para logo do estúdio:</label>
                        <input type='text' name='e_logo' required='required'/>

                        <br>

                        <label>Gêneros: (separe com ';')</label>
                        <br>
                        <textarea type='text' name='f_generos' rows='5' cols='50' required='required'></textarea>

                        <br>
                        
                        <label>Ator(es): (separe com ';')</label>
                        <br>
                        <textarea type='text' name='f_atores' rows='5' cols='50' required='required'></textarea>

                        <br>

                        <label>Papel(is): (separe com ';')</label>
                        <br>
                        <textarea type='text' name='f_papeis' rows='5' cols='50' required='required'></textarea>

                        <br>

                        <input type='submit' value='Cadastrar'/>
                        
                    </form>
               <br>"; 

                //atributos
                $nomeFilme = "";
                $sourceFilme = "";
                $anoFilme = "";
                $diretorFilme = "";
                $filmeCapa = "";
                $estudioNome = "";
                $estudioLogo = "";
                $generosFilme = "";
                $atoresFilme = "";
                $atoresPapeis = "";

                //chaves
                $codFilme = "";
                $codEstudio = "";

                $queries = array();
                parse_str($_SERVER['QUERY_STRING'], $queries);

                if(isset($queries['e_logo'])){
                    $estudioLogo = $queries['e_logo'];
                }

                if(isset($queries['e_nome'])){
                    $estudioNome = $queries['e_nome'];

                    $buscaEstudio = $banco->query("Select CodEst from estudio where Nome like '$estudioNome'");

                    while ($row = $buscaEstudio->fetch_assoc()) {
                        $codEstudio = $row['CodEst'];
                    }

                    if(empty($codEstudio) && strcmp($codEstudio, "0") != 0){
                        $ultimoEstudio = $banco->query("Select CodEst from estudio order by CodEst desc");
                        while($row = $ultimoEstudio->fetch_assoc()){
                            $codEstudio = $row['CodEst'] + 1;
                            break;
                        }
                        
                        //cria o estúdio
                        $banco->query("Insert into estudio values ($codEstudio, '$estudioNome', '$estudioLogo')");
                        //$banco->query("commit");
                    }
                }

                if(isset($queries['f_nome'])){
                    $nomeFilme = $queries['f_nome'];
                }

                if(isset($queries['f_source'])){
                    $sourceFilme = $queries['f_source'];
                }

                if(isset($queries['f_ano'])){
                    $anoFilme = $queries['f_ano'];
                }

                if(isset($queries['f_diretor'])){
                    $diretorFilme = $queries['f_diretor'];
                }

                if(isset($queries['f_capa'])){
                    $filmeCapa = $queries['f_capa'];
                }

                if(!empty($nomeFilme) && !empty($sourceFilme) && !empty($anoFilme) && !empty($diretorFilme) && !empty($filmeCapa) && (!empty($codEstudio) || strcmp($codEstudio, "0") == 0)){
                    $pesquisaFilme = $banco->query("Select CodFilme from filme order by CodFilme desc");
                    while ($row = $pesquisaFilme->fetch_assoc()) {
                        $codFilme = $row['CodFilme']+1;
                        break;
                    }

                    $banco->query("Insert into filme values ($codFilme, '$nomeFilme', '$anoFilme', $codEstudio, '$diretorFilme', '$filmeCapa', '$sourceFilme')");
                    //$banco->query("commit");
                }

                if(isset($queries['f_generos'])){
                    $generosFilme = $queries['f_generos'];
                }

                if(!empty($codFilme) && !empty($generosFilme)){
                    $generos = explode(";", $generosFilme);
                    foreach($generos as &$reg){
                        $banco->query("Insert into filme_genero values ($codFilme, '$reg')");
                    }
                }

                if(isset($queries['f_atores'])){
                    $atoresFilme = $queries['f_atores'];
                }

                if(isset($queries['f_papeis'])){
                    $atoresPapeis = $queries['f_papeis'];
                }

                if(!empty($atoresPapeis) && !empty($atoresFilme)){
                    $atores = explode(";", $atoresFilme);
                    $papeis = explode(";", $atoresPapeis);

                    if(count($atores) == count($papeis)){
                        for($i = 0; $i < count($atores); $i++){
                            // busca se o ator já existe
                            $codAtor = "";
                            $buscaAtor = $banco->query("Select CodAtor from ator where NomeAtor like '$atores[$i]'");

                            while ($row = $buscaAtor->fetch_assoc()) {
                                $codAtor = $row['CodAtor'];
                            }

                            if(empty($codAtor) && strcmp($codAtor, "0") != 0){
                                $ultimoAtor = $banco->query("Select CodAtor from ator order by CodAtor desc");
                                while($row = $ultimoAtor->fetch_assoc()){
                                    $codAtor = $row['CodAtor'] + 1;
                                    break;
                                }

                                //cria ator
                                $banco->query("Insert into ator values ($codAtor, '$atores[$i]')");
                            }

                            // cria o elenco
                            $banco->query("Insert into elenco values ($codFilme, $codAtor, '$papeis[$i]')");
                        }
                    }
                }

            ?>

        </div>

        <?php $banco->close(); ?>

    </body>
</html>
<?php
session_start(0);
require_once("LotoFacil.php");
require_once("MegaSena.php");

$btn = (isset($_POST['btn_enviar'])) ? $_POST['btn_enviar'] : '';
$qtd_jogos = (isset($_POST['qtd_jogos'])) ? $_POST['qtd_jogos'] : '';
$tipo_escolha = (isset($_POST['tipo_escolha'])) ? $_POST['tipo_escolha'] : '';
$tipo_sorteio = (isset($_POST['tipo_sorteio'])) ? $_POST['tipo_sorteio'] : '';
$jogo = (isset($_POST['jogo'])) ? $_POST['jogo'] : '';

if($btn == '' || $btn == 'Reiniciar'){
    unset($_SESSION['numeros']);
}

if($btn == 'Adicionar jogos'){
    $_SESSION['tipo_escolha'] = $tipo_escolha;
}

if($tipo_sorteio == "megasena"){
    $loteria = new MegaSena();
}elseif($tipo_sorteio == "lotofacil"){
    $loteria = new LotoFacil();
}



if($btn == 'Adicionar jogos'){
    if($qtd_jogos != '' && !is_numeric($qtd_jogos)){
        echo "<br><br>Preencha uma quantidade numerica<br><br>";
        $btn = '';
    }

    if($tipo_escolha == 2){
        $numeros = $loteria->gerarNumerosAleatorios($qtd_jogos);

        if(isset($_SESSION['numeros'])){
            $array = $_SESSION['numeros'];
            foreach ($numeros as $numero){
                array_push($array, $numero);
            }
            $_SESSION['numeros'] = $array;
        }else{
            $_SESSION['numeros'] = $numeros;
        }
    }
}

if($btn == 'Adicionar'){
    if($jogo != ""){
        $numeros = [];

        foreach ($jogo as $j){
            $j = explode(",", $j);
            $loteria->setNumeros($j);
            $num = $loteria->getNumeros();
            $num['dat_sorteio'] = $loteria->getDatSorteio();
            array_push($numeros, $num);
        }
        if(isset($_SESSION['numeros'])){
            $array = $_SESSION['numeros'];
            foreach ($numeros as $numero){
                array_push($array, $numero);
            }
            $_SESSION['numeros'] = $array;
        }else{
            $_SESSION['numeros'] = $numeros;
        }

    }
}

if($btn == "Sortear"){
    $jogoSorteado = $loteria->gerarNumerosAleatorios();
    $a = "";
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Loteria</title>
</head>
<body>
<form name="form1" action="index.php" method="post">
    <table>
        <tr>
            <td>Tipo de sorteio:</td>
            <td><select name="tipo_sorteio" onchange="validaSorteio(this.value)">
                    <option value="megasena" <?php if($tipo_sorteio == "megasena"){ ?> selected <?php } ?>>MegaSena</option>
                    <option value="lotofacil" <?php if($tipo_sorteio == "lotofacil"){ ?> selected <?php } ?>>LotoFacil</option>
                </select></td>
        </tr>
        <tr>
            <td>Quantidade de jogos:</td>
            <td><input type="text" name="qtd_jogos" value="<?php echo $qtd_jogos?>" /></td>
        </tr>
        <tr>
            <td>Escolha dos numeros:</td>
            <td><input type="radio" name="tipo_escolha" value="1" <?php if($tipo_escolha == "1" || $tipo_escolha == ""){ ?> checked <?php } ?>> Manual <input type="radio" name="tipo_escolha" value="2" <?php if($tipo_escolha == "2"){ ?> checked <?php } ?>> Automatico</td>
        </tr>
        <tr>
            <td></td>
            <td><input type="submit" name="btn_enviar" value="Adicionar jogos"> <input type="submit" name="btn_enviar" value="Sortear"> <input type="submit" name="btn_enviar" value="Reiniciar"></td>
        </tr>
    </table>
</form>
<?php
if($btn == "Adicionar jogos" && $tipo_escolha == "1"){
?>
    <form name="form2" action="index.php" method="post">
        <input type="hidden" name="tipo_sorteio" value="<?php echo $tipo_sorteio ?>" />
        <table>
            <?php
                if($tipo_escolha == "2"){
                    $cont = 1;
                    foreach ($numeros as $numero) {
                        asort($numero);
                        ?>
                        <tr>
                            <td>Jogo <?php echo $cont?>:</td>
                            <td><?php foreach($numero as $n){ echo $n.", "; }; ?></td>
                        </tr>
                        <?php
                        $cont ++;
                    }
                }else{
                    for ($i=0; $i<$qtd_jogos; $i++){
                        ?>
                        <tr>
                            <td>Jogo <?php echo $i+1?>:</td>
                            <td><input type="text" name="jogo[]" /> * preencha <?php echo $loteria->qtd_numeros; ?> numeros separados por virgula(,) </td>
                        </tr>
                        <?php
                    }
                }
            ?>
            <tr>
                <td></td>
                <td><input type="submit" name="btn_enviar" value="Adicionar"> </td>
            </tr>
        </table>
    </form>

<?php
}

if(isset($_SESSION['numeros'])){
    ?>
    <table>
        <tr>
            <td>Jogos adicionados:</td>
            <td></td>
        </tr>
        <?php
        $cont = 0;
        foreach ($_SESSION['numeros'] as $numero) { $cont++; ?>
            <tr>
                <td>Jogo <?php echo $cont?></td>
                <td><?php
                    foreach ($numero as $n) {
                        echo $n . ", ";
                    };
                    ?></td>
            </tr>
            <?php
        }
        ?>
    </table>
    <?php
}

if($btn == "Sortear"){

    if(!isset($_SESSION['numeros'])){
        echo "<br><br>Preencha algum jogo antes do sorteio.";
    }else {
        ?>
        <br><br>
        <form name="form3" action="index.php" method="post">
            <input type="hidden" name="tipo_sorteio" value="<?php echo $tipo_sorteio ?>" />
            <table>
                <tr>
                    <td><b>Jogo sorteado:</b></td>
                    <td><b><?php foreach ($jogoSorteado[0] as $n) {
                            echo $n . ", ";
                        }; ?></b></td>
                </tr>
                <?php
                $cont = 0;
                foreach ($_SESSION['numeros'] as $numero) {
                    $cont++;
                    $qtdNumeros = 0;
                    foreach ($jogoSorteado[0] as $n) {
                        if (in_array($n, $numero)) {
                            $qtdNumeros++;
                        }
                    }
                    ?>
                    <tr>
                        <td>Jogo <?php echo $cont?></td>
                        <td><?php
                            foreach ($numero as $n) {
                                echo $n . ", ";
                            };
                            echo " - " . $qtdNumeros . " acertos";
                            ?></td>
                    </tr>
                    <?php
                }
                ?>
                <tr>
                    <td></td>
                    <td><input type="submit" name="btn_enviar" value="Reiniciar"></td>
                </tr>
            </table>
        </form>
        <?php
    }
}
?>

</body>
</html>

<script>
    function validaSorteio(tipo_sorteio){

    }
</script>

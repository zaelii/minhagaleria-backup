<?php
// pasta onde estão as fotos
$pasta = "backup"; 

// obter o ano a partir dos parâmetros da URL
$ano = $_GET['ano'];

// obter a lista de meses para o ano especificado
$meses = glob("$pasta/$ano/*", GLOB_ONLYDIR);
$othersy = array_filter(glob("$pasta/*"), 'is_dir');

// cabeçalho da página com o título
echo "<h1 style='text-align: center;'>Galeria de Fotos</h1>";

// cabeçalho com os anos
foreach ($othersy as $a) {
    $a = basename($a);
    // grid
    echo "<div style='display: inline-block; width: 10%; text-align: center;'>";
    // link como um botao
    echo "<a href='meses.php?ano=$a'>";
    echo "<br>";
    echo "<span style='font-size: 1.5em;'><button>$a</button></span>";
    // estilizando o botao
    echo "<style>button {background: #red; border: 1px solid; border-radius: 10px; padding: 10px;}</style>";
    echo "</a>";
    echo "</div>";
}


// mostra o ano com margem
echo "<h2 style='text-align: center; margin-top: 50px;'>$ano</h2>";

// 2.1 - exibir um cabeçalho
echo "<h2 style='text-align: center;'>Selecione o mês:</h2>";

// exibir a lista de meses em botoes centralizados
foreach ($meses as $mes) {
    $mes = basename($mes);
    echo "<p style='text-align: center;'><a href='dias.php?ano=$ano&mes=$mes'><button>$mes</button></a></p>";
}

// exibir um rodapé centralizado
echo "<p style='text-align: center;'><a href='index.php'>Voltar</a></p>";

// ESTILIZAÇÃO DA PÁGINA
// fundo de página cinza
echo "<style>body {background: #cccccc;}</style>";
?>
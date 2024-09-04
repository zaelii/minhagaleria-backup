<?php
// 0 - configurar o PHP
ini_set('display_errors', 1);
error_reporting(E_ALL);

// pasta onde estão as fotos
$pasta = "backup"; 

// 1 - obter a lista de anos
$ano = array_filter(glob("$pasta/*"), 'is_dir');

// cabeçalho da página com o título
echo "<h1 style='text-align: center;'>Galeria de Fotos</h1>";

// 2.1 - exibir um cabeçalho
echo "<h2 style='text-align: center;'>Selecione o ano:</h2>";


// 3 - exibir a lista de anos em botoes
foreach ($ano as $a) {
    $a = basename($a);
    // grid
    echo "<div style='display: inline-block; width: 20%; text-align: center;'>";
    // link como um botao
    echo "<a href='meses.php?ano=$a'>";
    echo "<br>";
    echo "<span style='font-size: 1.5em;'><button>$a</button></span>";
    // estilizando o botao
    echo "<style>button {background: #red; border: 1px solid; border-radius: 10px; padding: 10px;}</style>";
    echo "</a>";
    echo "</div>";
}


// ESTILIZAÇÃO DA PÁGINA
// fundo de página cinza
echo "<style>body {background: #cccccc;}</style>";
?>



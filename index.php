<?php
// 0 - Configurar o PHP
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Pasta onde estão as fotos
$pasta = "backup"; 

// Função para obter o nome do mês com base no número
function getNomeMes($mes) {
    $meses = [
        '01' => 'Janeiro', '02' => 'Fevereiro', '03' => 'Março', '04' => 'Abril',
        '05' => 'Maio', '06' => 'Junho', '07' => 'Julho', '08' => 'Agosto',
        '09' => 'Setembro', '10' => 'Outubro', '11' => 'Novembro', '12' => 'Dezembro'
    ];
    return $meses[$mes] ?? $mes; // Retorna o nome ou o número do mês se não for encontrado
}

// Obter o ano e o mês a partir dos parâmetros da URL
$anoSelecionado = $_GET['ano'] ?? null;
$mesSelecionado = $_GET['mes'] ?? null;

// Obter a lista de anos disponíveis
$anos = array_filter(glob("$pasta/*"), 'is_dir');

// Obter os nomes dos anos disponíveis
$anosDisponiveis = array_map('basename', $anos);

// Ordenar os anos em ordem decrescente (mais recente primeiro)
rsort($anosDisponiveis);

// Se nenhum ano foi selecionado, selecionar o primeiro disponível (mais recente)
if (!$anoSelecionado && !empty($anosDisponiveis)) {
    $anoSelecionado = reset($anosDisponiveis);
}

// Cabeçalho da página com o título
echo "<h1 style='text-align: center;'>Galeria de Fotos</h1>";

// 1. Exibir os anos disponíveis
echo "<div style='text-align: center;'>";
foreach ($anosDisponiveis as $anoBasename) {
    // Aplicar estilo especial para o ano selecionado
    $estiloAno = $anoBasename == $anoSelecionado ? "background-color: #707070;" : "background-color: #e0e0e0;";
    echo "<div style='display: inline-block; width: 10%; text-align: center; margin: 5px;'>";
    echo "<a href='?ano=$anoBasename'>";
    echo "<button style='$estiloAno'>$anoBasename</button>";
    echo "</a>";
    echo "</div>";
}
echo "</div>";

// 2. Exibir os meses do ano selecionado (se houver)
if ($anoSelecionado) {
    $meses = glob("$pasta/$anoSelecionado/*", GLOB_ONLYDIR);
    if ($meses) {
        // Obter os nomes dos meses disponíveis
        $mesesDisponiveis = array_map('basename', $meses);
        // Ordenar os meses em ordem decrescente (mais recente primeiro)
        rsort($mesesDisponiveis);

        // Se nenhum mês foi selecionado, selecionar o primeiro disponível (mais recente)
        if (!$mesSelecionado && !empty($mesesDisponiveis)) {
            $mesSelecionado = reset($mesesDisponiveis);
        }

        echo "<hr><h2 style='text-align: center; margin-top: 20px;'>$anoSelecionado</h2>";
        echo "<div style='text-align: center;'>";
        foreach ($mesesDisponiveis as $mes) {
            $nomeMes = getNomeMes($mes); // Obter o nome do mês
            // Aplicar estilo especial para o mês selecionado
            $estiloMes = $mes == $mesSelecionado ? "background-color: #707070;" : "background-color: #e0e0e0;";
            echo "<div style='display: inline-block; width: 10%; text-align: center; margin: 5px;'>";
            echo "<a href='?ano=$anoSelecionado&mes=$mes'>";
            echo "<button style='$estiloMes'>$nomeMes</button>";
            echo "</a>";
            echo "</div>";
        }
        echo "</div>";
    }
}

// 3. Exibir os dias e arquivos do mês selecionado (se houver)
if ($anoSelecionado && $mesSelecionado) {
    // Obter a lista de pastas de dia no mês especificado
    $dias = array_filter(glob("$pasta/$anoSelecionado/$mesSelecionado/*"), 'is_dir');

    // Ordenar os dias em ordem crescente
    sort($dias);

    // Exibir cada pasta de dia em uma seção separada
    foreach ($dias as $diaDir) {
        $dia = basename($diaDir);
        echo "<hr><h2 style='text-align: center;'>Dia $dia</h2>";
        // Obter a lista de arquivos de imagem e vídeo na pasta do dia especificado
        $arquivos = glob("$pasta/$anoSelecionado/$mesSelecionado/$dia/*.{jpg,png,gif,mp4}", GLOB_BRACE);
        // Exibir cada arquivo
        echo "<div style='text-align: center;'>";
        foreach ($arquivos as $arquivo) {
            if (pathinfo($arquivo, PATHINFO_EXTENSION) == "mp4") {
                // Exibir o vídeo
                echo "<a href='$arquivo' target='_blank'><video src='$arquivo' width='320' height='240' controls></video></a><br>";
            } else {
                // Exibir a imagem lado a lado com borda
                echo "<a href='$arquivo' target='_blank'><img src='$arquivo' width='320' height='240' style='margin: 5px; border: 1px solid black; border-radius: 5px;'></a>";        
            }
        }
        echo "</div>";
    }
}

// Exibir um rodapé centralizado
echo "<hr><p style='text-align: center;'><a href='index.php'>Voltar</a></p>";

// ESTILIZAÇÃO DA PÁGINA
echo "<style>
    body {background: #cccccc;}
    button {background: #e0e0e0; border: 1px solid; border-radius: 10px; padding: 10px;}
    button:hover {cursor: pointer; opacity: 0.8;}
    hr {border: 1px solid black;}
</style>";
?>

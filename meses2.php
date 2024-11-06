<?php
// pasta onde estão as fotos
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

// 1. Buscar o primeiro ano disponível caso o ano não seja passado na URL
$anos = array_filter(glob("$pasta/*"), 'is_dir');
$anoSelecionado = $_GET['ano'] ?? basename(reset($anos));

// 2. Buscar o primeiro mês disponível do ano selecionado, caso o mês não seja passado na URL
$meses = glob("$pasta/$anoSelecionado/*", GLOB_ONLYDIR);
$mesSelecionado = $_GET['mes'] ?? basename(reset($meses));

// cabeçalho da página com o título
echo "<h1 style='text-align: center;'>Galeria de Fotos</h1>";

// 3. Exibir os anos disponíveis
echo "<div style='text-align: center;'>";
foreach ($anos as $ano) {
    $ano = basename($ano);
    // aplicar estilo especial para o ano selecionado
    $estiloAno = $ano == $anoSelecionado ? "background-color: #707070;" : "background-color: #e0e0e0;";
    echo "<div style='display: inline-block; width: 10%; text-align: center;'>";
    echo "<a href='?ano=$ano'>";
    echo "<button style='$estiloAno'>$ano</button>";
    echo "</a>";
    echo "</div>";
}
echo "</div>";

// 4. Exibir os meses do ano selecionado
if ($meses) {
    echo "<hr><h2 style='text-align: center; margin-top: 20px;'>$anoSelecionado</h2>";
    echo "<div style='text-align: center;'>";
    foreach ($meses as $mesDir) {
        $mes = basename($mesDir);
        $nomeMes = getNomeMes($mes); // Obter o nome do mês
        // aplicar estilo especial para o mês selecionado
        $estiloMes = $mes == $mesSelecionado ? "background-color: #707070;" : "background-color: #e0e0e0;";
        echo "<div style='display: inline-block; width: 10%; text-align: center;'>";
        echo "<a href='?ano=$anoSelecionado&mes=$mes'>";
        echo "<button style='$estiloMes'>$nomeMes</button>";
        echo "</a>";
        echo "</div>";
    }
    echo "</div>";
}

// 5. Exibir os dias e arquivos do mês selecionado (se houver)
if ($anoSelecionado && $mesSelecionado) {
    // obter a lista de pastas de dia no mês especificado
    $dias = scandir("$pasta/$anoSelecionado/$mesSelecionado");

    // exibir cada pasta de dia em uma seção separada
    foreach ($dias as $dia) {
        if ($dia == "." || $dia == "..") {
            continue;
        }
        echo "<hr><h2 style='text-align: center;'>Dia $dia</h2>";
        // obter a lista de arquivos de imagem e vídeo na pasta do dia especificado
        $arquivos = glob("$pasta/$anoSelecionado/$mesSelecionado/$dia/*.{jpg,png,gif,mp4}", GLOB_BRACE);
        // exibir cada arquivo
        echo "<div style='text-align: center;'>";
        foreach ($arquivos as $arquivo) {
            if (pathinfo($arquivo, PATHINFO_EXTENSION) == "mp4") {
                // exibir o vídeo
                echo "<a href='$arquivo' target='_blank'><video src='$arquivo' width='320' height='240' controls></video></a><br>";
            } else {
                // exibir a imagem lado a lado com borda
                echo "<a href='$arquivo' target='_blank'><img src='$arquivo' width='320' height='240' style='margin: 5px; border: 1px solid black; border-radius: 5px;'></a>";        
            }
        }
        echo "</div>";
    }
}

// exibir um rodapé centralizado
echo "<hr><p style='text-align: center;'><a href='

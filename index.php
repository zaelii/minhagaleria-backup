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
    return $meses[$mes] ?? $mes;
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

?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Galeria de Fotos</title>
    <style>
        body {
            background: #cccccc;
            font-family: Arial, sans-serif;
            display: flex;
        }

        #sidebar {
            width: 15%;
            background: #444;
            color: white;
            padding: 20px;
            height: 100vh;
            position: fixed;
            overflow-y: auto;
        }

        #content {
            margin-left: 18%;
            width: 80%;
            padding: 20px;
            text-align: center;
        }

        button {
            background: #e0e0e0;
            border: none;
            border-radius: 10px;
            padding: 10px;
            margin: 5px;
            width: 100%;
        }

        button:hover {
            cursor: pointer;
            opacity: 0.8;
        }

        .selected {
            background: #707070;
            color: white;
        }

        hr {
            border: 1px solid black;
            width: 100%;
        }

        .image-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
        }

        .image-container img,
        .image-container video {
            margin: 10px;
            border: 2px solid black;
            border-radius: 5px;
            width: 300px;
            height: 240px;
        }

        .titulo-ano {
        font-size: 28px; /* Tamanho maior */
        font-weight: bold; /* Negrito */
        color: #ff9800; /* Laranja forte */
        background-color: #333; /* Fundo escuro */
        padding: 10px;
        border-radius: 10px;
        display: inline-block;
        box-shadow: 3px 3px 10px rgba(0, 0, 0, 0.3);
        }

        .meses-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 10px;
        }
    </style>
</head>
<body>

<div id="sidebar">
    <h2 style="text-align: center;">Anos</h2>
    <?php foreach ($anosDisponiveis as $anoBasename): ?>
        <a href="?ano=<?= $anoBasename ?>">
            <button class="<?= ($anoBasename == $anoSelecionado) ? 'selected' : '' ?>"><?= $anoBasename ?></button>
        </a>
    <?php endforeach; ?>
</div>

<div id="content">
    <h1>Galeria de Fotos</h1>

    <?php
    // Exibir os meses do ano selecionado
    if ($anoSelecionado) {
        $meses = glob("$pasta/$anoSelecionado/*", GLOB_ONLYDIR);
        if ($meses) {
            // Obter os nomes dos meses disponíveis
            $mesesDisponiveis = array_map('basename', $meses);
            // Ordenar os meses em ordem decrescente
            rsort($mesesDisponiveis);

            // Se nenhum mês foi selecionado, selecionar o primeiro disponível (mais recente)
            if (!$mesSelecionado && !empty($mesesDisponiveis)) {
                $mesSelecionado = reset($mesesDisponiveis);
            }

            echo "<h2 class='titulo-ano'>$anoSelecionado</h2>";
            echo "<div class='meses-container'>";
            foreach ($mesesDisponiveis as $mes) {
                $nomeMes = getNomeMes($mes);
                $estiloMes = $mes == $mesSelecionado ? "selected" : "";
                echo "<a href='?ano=$anoSelecionado&mes=$mes'>";
                echo "<button class='$estiloMes'>$nomeMes</button>";
                echo "</a>";
            }
            echo "</div>";
        }
    }

    // Exibir os dias e arquivos do mês selecionado
    if ($anoSelecionado && $mesSelecionado) {
        $dias = array_filter(glob("$pasta/$anoSelecionado/$mesSelecionado/*"), 'is_dir');
        sort($dias);

        foreach ($dias as $diaDir) {
            $dia = basename($diaDir);
            echo "<hr><h2>Dia $dia</h2>";
            $arquivos = glob("$pasta/$anoSelecionado/$mesSelecionado/$dia/*.{jpg,png,gif,mp4}", GLOB_BRACE);

            echo "<div class='image-container'>";
            foreach ($arquivos as $arquivo) {
                if (pathinfo($arquivo, PATHINFO_EXTENSION) == "mp4") {
                    echo "<a href='$arquivo' target='_blank'><video src='$arquivo' controls></video></a>";
                } else {
                    echo "<a href='$arquivo' target='_blank'><img src='$arquivo'></a>";
                }
            }
            echo "</div>";
        }
    }
    ?>

    <hr>
    <p><a href="index.php">Voltar</a></p>
</div>

</body>
</html>

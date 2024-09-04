<?php
// pasta onde estão as fotos
$pasta = "backup"; 

// obter o ano e o mês a partir dos parâmetros da URL
$ano = $_GET['ano'];
$mes = $_GET['mes'];

// obter a lista de pastas de dia no mês especificado
$dias = scandir("$pasta/$ano/$mes");

// exibir cada pasta de dia em uma seção separada
foreach ($dias as $dia) {
    if ($dia == "." || $dia == "..") {
        continue;
    }
    echo "<h2>Dia $dia</h2>";
    // obter a lista de arquivos de imagem e vídeo na pasta do dia especificado
    $arquivos = glob("$pasta/$ano/$mes/$dia/*.{jpg,png,gif,mp4}", GLOB_BRACE);
    // exibir cada arquivo
    foreach ($arquivos as $arquivo) {
        if (pathinfo($arquivo, PATHINFO_EXTENSION) == "mp4") {
            // exibir o vídeo
            echo "<a href='$arquivo'?arquivo=$arquivo' target='_blank'><video src='$arquivo' width='320' height='240' controls></video></a><br>";
        } else {
            // exibir a imagem lado do outro com espaçamento com um link para o arquivo original
            // com borda preta e border radius de 5px
            echo "<a href='$arquivo'?arquivo=$arquivo' target='_blank'><img src='$arquivo' width='320' height='240' style='margin: 5px; border: 1px solid black; border-radius: 5px;'></a>";        
        }
    }
}

// exibir um rodapé centralizado
echo "<hr><p style='text-align: center;'><a href='javascript:history.back()'>Voltar</a></p>";

// ESTILIZAÇÃO DA PÁGINA
// fundo de página cinza
echo "<style>body {background: #cccccc;}</style>";
?>
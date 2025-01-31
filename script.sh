#!/bin/bash

# -------------------------
# Funções
# -------------------------



# Função 1: Função para criar a estrutura de diretórios para uma foto
function criar_estrutura() {
    local caminho="$diretorio_destino/$(date -d "$1" +%Y/%m/%d)"
    mkdir -p "$caminho"
    cp -p "$2" "$caminho" 
}

# Função 2: Função para organizar as fotos
function organizar_fotos() {
    # Itera sobre os arquivos de imagem
    shopt -s nullglob
    
    for foto in "$1"/*.{jpg,JPG,jpeg,JPEG,png,PNG,gif,GIF,mp4,MP4,bmp,BMP,mov,MOV,avi,AVI,wmv,WMV,flv,FLV}; do
        nome=$(basename "$foto")

        ((cont++))  # Incrementa o contador
        echo "Processando arquivo $cont: $nome"

        # Extrai o nome do arquivo dos metadados EXIF usando exiftool
        nome=$(exiftool -FileName -s3 "$foto")

        # Formato 2016-01-01
        if [[ $nome =~ ^[0-9]{4}-[0-9]{2}-[0-9]{2} ]]; then
            data=$(echo "$nome" | cut -d'-' -f1-3)
            criar_estrutura "$data" "$foto"

        # Formato 20141225_123456
        elif [[ $nome =~ ^[0-9]{8}_[0-9]{6} ]]; then
            data=$(echo "$nome" | cut -d'_' -f1)
            criar_estrutura "$data" "$foto"

        # Formato IMG-20160101-WA0001
        elif [[ $nome =~ ^IMG-[0-9]{8}-WA[0-9]{4} ]]; then
            data=$(echo "$nome" | cut -d'-' -f2)
            criar_estrutura "$data" "$foto"

        # Formato IMG_20160101_123456789_TOP
        elif [[ $nome =~ ^IMG_[0-9]{8}_[0-9]{9}_TOP ]]; then
            data=$(echo "$nome" | cut -d'_' -f2)
            criar_estrutura "$data" "$foto"

        # Formato 20150110_123456
        elif [[ $nome =~ ^[0-9]{8}_[0-9]{6} ]]; then
            data=$(echo "$nome" | cut -d'_' -f1)
            criar_estrutura "$data" "$foto"

        # Formato VID_20160101_123456789
        elif [[ $nome =~ ^VID_[0-9]{8}_[0-9]{9} ]]; then
            data=$(echo "$nome" | cut -d'_' -f2)
            criar_estrutura "$data" "$foto"

        # Formato VID_20160101_123456789
        elif [[ $nome =~ ^VID_[0-9]{8}_[0-9]{9} ]]; then
            data=$(echo "$nome" | cut -d'_' -f2)
            criar_estrutura "$data" "$foto"

        # Formato VID_20160101-WA0001
        elif [[ $nome =~ ^VID_[0-9]{8}-WA[0-9]{4} ]]; then
            data=$(echo "$nome" | cut -d'-' -f2)
            criar_estrutura "$data" "$foto"

        # Formato IMG-2013-07-20-WA0001
        elif [[ $nome =~ ^IMG-[0-9]{4}-[0-9]{2}-[0-9]{2}-WA[0-9]{4} ]]; then
            data=$(echo "$nome" | cut -d'-' -f2-4)
            criar_estrutura "$data" "$foto"

        # Formato PXL_20230101_123456789
        elif [[ $nome =~ ^PXL_[0-9]{8}_[0-9]{9} ]]; then
            data=$(echo "$nome" | cut -d'_' -f2)
            criar_estrutura "$data" "$foto"

        # Formato IMG-20101201-WA0005
        elif [[ $nome =~ ^IMG-[0-9]{8}-WA[0-9]{4} ]]; then
            data=$(echo "$nome" | cut -d'-' -f2)
            criar_estrutura "$data" "$foto"

        # Formato VID-20160730-WA0050
        elif [[ $nome =~ ^VID-[0-9]{8}-WA[0-9]{4} ]]; then
            data=$(echo "$nome" | cut -d'-' -f2)
            criar_estrutura "$data" "$foto"

        # Formato 2012-10-13-15-09-45-875.jpg
        elif [[ $nome =~ ^[0-9]{4}-[0-9]{2}-[0-9]{2}-[0-9]{2}-[0-9]{2}-[0-9]{2}-[0-9]{3} ]]; then
            data=$(echo "$nome" | cut -d'-' -f1-3)
            criar_estrutura "$data" "$foto"

        # Formato IMG_20160207_172749630.jpg
        elif [[ $nome =~ ^IMG_[0-9]{8}_[0-9]{9} ]]; then
            data=$(echo "$nome" | cut -d'_' -f2)
            criar_estrutura "$data" "$foto"

        # Formato IMG_20150802_143559.jpg
        elif [[ $nome =~ ^IMG_[0-9]{8}_[0-9]{6} ]]; then
            data=$(echo "$nome" | cut -d'_' -f2)
            criar_estrutura "$data" "$foto"

        # Formato Imagem 001 (formato padrão do celular) pegar a data de criação do arquivo
        elif [[ $nome =~ ^Imagem[[:space:]]+[0-9]{3} ]]; then
            data=$(stat -c %y "$foto" | cut -d' ' -f1)
            if [[ -z "$data" ]]; then
                data=$(exiftool -DateTimeOriginal -d '%Y-%m-%d' -s3 "$foto")
            fi
            criar_estrutura "$data" "$foto"

        # Formato IMG_2013 (formato padrão do celular) pegar a data de criação do arquivo
        elif [[ $nome =~ ^IMG_[0-9]{4} ]]; then
            ano=$(echo "$nome" | cut -d'_' -f2 | cut -d'.' -f1)
            mes_dia=$(stat -c %y "$foto" | cut -d' ' -f1 | cut -d'-' -f2-3)
            data="$ano-$mes_dia"
            if [[ -z "$data" ]]; then
                data=$(exiftool -DateTimeOriginal -d '%Y-%m-%d' -s3 "$foto")
            fi
            criar_estrutura "$data" "$foto"

        # Adicionem novos formatos de nome de arquivo aqui abaixo

        fi
    done
    shopt -u nullglob
}

# Função 3: Função para iterar sobre as pastas e organiza todas as fotos
function iterar_pastas() {
    local diretorio_atual="$1"
    organizar_fotos "$diretorio_atual"
    for item in "$diretorio_atual"/*; do
        if [ -d "$item" ]; then
            iterar_pastas "$item"
        fi
    done
}

# -------------------------
# Código principal 
# -------------------------

cont=0

# Verficia se os argumentos foram passados
if [ $# -ne 2 ]; then
    echo "Uso: $0 <diretório de origem> <diretório de destino(Backup)>"
    echo "Caso não tenha as libs exiftool e php instaladas, instale-as com:"
    echo "sudo apt-get install libimage-exiftool-perl php"
    exit 1
fi

# Atribui os argumentos as variáveis
diretorio_origem="$1"
diretorio_destino="$2"


# Executar o script
echo "Iniciando backup das fotos..."
echo "Diretório de fotos: $diretorio_origem"
echo "Diretório de backup: $diretorio_destino"

iterar_pastas "$diretorio_origem"

echo "Backup realizado com sucesso! Total de arquivo(s) processado(s): $cont"

# -------------------------
# Código final (galeria web)
# -------------------------

# Perguntar se quer executar servidor web
echo ""
read -p "Deseja iniciar o servidor web para visualizar as fotos? (s/n) " resposta
if [ "$resposta" == "s" ]; then
    echo ""
    echo "Iniciando servidor web..."
    xdg-open http://localhost:8000
    php -S localhost:8000
fi

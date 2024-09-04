# Perguntar se quer executar servidor web
echo ""
read -p "Deseja iniciar o servidor web para visualizar as fotos? (s/n) " resposta
if [ "$resposta" == "s" ]; then
    echo ""
    echo "Iniciando servidor web..."
    xdg-open http://localhost:8000
    php -S localhost:8000
fi

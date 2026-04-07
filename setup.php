<?php
/**
 * Script de Setup Automatizado - Raphael RDG
 * Foco: Padronização de Temas WordPress
 */

if (php_sapi_name() !== 'cli') exit("Acesso negado. Rode via terminal.\n");

echo "\n🚀 Iniciando Setup do Tema Base...\n";

// Função para perguntar no terminal
function ask($question) {
    echo $question . ": ";
    return trim(fgets(STDIN));
}

$projectName = ask("Qual o nome do Projeto/Cliente? (Ex: Clinica Sorriso)");
$prefixoRaw  = ask("Qual o prefixo/slug? (Ex: rdg)");
$themeUrl = ask("Qual a URL do tema?");
$themeDescription = ask("Qual a descrição do tema?");
$author  = ask("Quem é o autor do tema?");
$authorUrl  = ask("Qual a URL do autor?");

if (!$projectName || !$prefixoRaw || !$themeUrl || !$author || !$authorUrl) {
    exit("❌ Erro: Você precisa informar todas as respostas.\n");
}

if (empty($themeDescription)) {
    $themeDescription = "Tema desenvolvido para a " . $projectName;
}

$prefixoLower = strtolower($prefixoRaw);
$prefixoUpper = strtoupper($prefixoRaw);
$themeUrlLower = strtolower($themeUrl);
$authorUrlLower = strtolower($authorUrl);
$packageSlug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $projectName), '-'));
$packageDescription = $themeDescription;

// Lista de arquivos para atualizar
// Adicione aqui qualquer arquivo novo que precise de substituição
$files = [
    'style.css',
    'functions.php',
    'inc/Core/Setup.php',
    'package.json'
];

foreach ($files as $file) {
    if (file_exists($file)) {
        $content = file_get_contents($file);
        
        // placeholders
        $content = str_replace('{{THEME_NAME}}', $projectName, $content);
        $content = str_replace('{{THEME_PREFIX}}', $prefixoLower, $content);
        $content = str_replace('{{THEME_URL}}', $themeUrlLower, $content);
        $content = str_replace('{{THEME_DESCRIPTION}}', $themeDescription, $content);
        $content = str_replace('{{AUTHOR_NAME}}', $author, $content);
        $content = str_replace('{{AUTHOR_URL}}', $authorUrlLower, $content);

        $content = str_replace('rdg', $prefixoLower, $content);
        $content = str_replace('rdg-', $prefixoLower . '-', $content);
        $content = str_replace('rdg-temp-slug', $themeSlug, $content);
        
        // Namespaces e Constantes PHP
        // Procura por RDG\ e RDG_ e troca pelo seu novo prefixo
        $content = str_replace('RDG\\', $prefixoUpper . '\\', $content);
        $content = str_replace('RDG_', $prefixoUpper . '_', $content);
        $content = str_replace('namespace RDG;', 'namespace ' . $prefixoUpper . ';', $content);
        
        file_put_contents($file, $content);
        echo "✅ $file atualizado.\n";
    }
}

echo "\n🔥 Tudo pronto, Raphael! O tema '$projectName' foi configurado.";
echo "\n🗑️  Removendo script de setup para limpar o projeto...\n";

unlink(__FILE__);

echo "✨ Sucesso! Agora é só rodar o 'npm install' e começar o Gulp.\n\n";
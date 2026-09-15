<?php

/**
 * Script de Setup Automatizado - Raphael RDG
 * Foco: Padronização e automação de temas WordPress sem page builders
 */

if (php_sapi_name() !== 'cli') exit("Acesso negado. Rode via terminal.\n");

echo "\n🚀 Iniciando Setup Interativo do Tema Base...\n";

function ask($question, $default = '')
{
    $prompt = $default ? "{$question} [Padrão: {$default}]: " : "{$question}: ";
    echo $prompt;
    $input = trim(fgets(STDIN));
    return $input !== '' ? $input : $default;
}

// 1. Coleta interativa de dados com fallbacks
$projectName = ask("Qual o nome do Projeto/Cliente? (Ex: Clinica Sorriso)");
if (!$projectName) exit("❌ Erro: O nome do projeto é obrigatório.\n");

$prefixoRaw       = ask("Qual o prefixo/slug?", "rdg");
$author           = ask("Quem é o autor do tema?", "Raphael Rodrigues");
$authorUrl        = ask("Qual a URL do autor?", "https://raphaelrdg.com.br");
$namespaceRaw     = ask("Qual o Namespace PHP (Composer)?", strtoupper($prefixoRaw));
$themeDescription = ask("Qual a descrição do tema?", "Tema customizado de alta performance desenvolvido do zero para " . $projectName);
$themeUrl         = ask("Qual a URL do tema?", $authorUrl);

// 2. Sanitização e padronização das variáveis
$prefixoLower   = strtolower($prefixoRaw);
$prefixoUpper   = strtoupper($prefixoRaw);
$namespaceUpper = strtoupper($namespaceRaw);
$themeUrlLower  = strtolower($themeUrl);
$authorUrlLower = strtolower($authorUrl);
$packageSlug    = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $projectName), '-'));

// 3. Atualização / Criação dinâmica do composer.json
$composerFile = 'composer.json';
$composerData = file_exists($composerFile)
    ? json_decode(file_get_contents($composerFile), true)
    : [];

$composerData['name']        = "{$prefixoLower}/" . $packageSlug;
$composerData['description'] = $themeDescription;
$composerData['type']        = "wordpress-theme";
$composerData['authors']     = [
    [
        "name"     => $author,
        "homepage" => $authorUrlLower
    ]
];
$composerData['require']     = $composerData['require'] ?? ["php" => ">=7.4"];
$composerData['autoload']    = [
    "psr-4" => [
        "{$namespaceUpper}\\" => "inc/"
    ]
];

file_put_contents($composerFile, json_encode($composerData, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
echo "✅ composer.json atualizado com o namespace '{$namespaceUpper}\\'.\n";

// 4. Substituição de Placeholders nos arquivos do tema
$files = [
    'style.css',
    'functions.php',
    'inc/Core/Setup.php',
    'package.json'
];

foreach ($files as $file) {
    if (file_exists($file)) {
        $content = file_get_contents($file);

        // Placeholders de texto e links
        $content = str_replace('{{THEME_NAME}}', $projectName, $content);
        $content = str_replace('{{THEME_PREFIX}}', $prefixoLower, $content);
        $content = str_replace('{{THEME_URL}}', $themeUrlLower, $content);
        $content = str_replace('{{THEME_DESCRIPTION}}', $themeDescription, $content);
        $content = str_replace('{{AUTHOR_NAME}}', $author, $content);
        $content = str_replace('{{AUTHOR_URL}}', $authorUrlLower, $content);

        // Nomes temporários e slugs
        $content = str_replace('rdg-temp-name', $packageSlug, $content);
        $content = str_replace('rdg-temp-description', $themeDescription, $content);
        $content = str_replace('rdg-slug', $prefixoLower, $content);
        $content = str_replace('rdg-', $prefixoLower . '-', $content);

        // Namespaces PHP, Classes e Constantes
        $content = str_replace('RDG\\', $namespaceUpper . '\\', $content);
        $content = str_replace('RDG_', $namespaceUpper . '_', $content);
        $content = str_replace('namespace RDG;', 'namespace ' . $namespaceUpper . ';', $content);

        file_put_contents($file, $content);
        echo "✅ $file atualizado.\n";
    }
}

// 5. Regenerar o Autoload caso o Composer já esteja instalado na máquina
if (shell_exec('which composer')) {
    echo "🔄 Executando 'composer dump-autoload'...\n";
    system('composer dump-autoload');
}

echo "\n🔥 O tema '$projectName' foi configurado com sucesso!";
echo "\n🗑️  Removendo script de setup para manter a estrutura limpa...\n";

unlink(__FILE__);

echo "✨ Tudo pronto! Agora é só rodar 'npm install' e iniciar o Gulp.\n\n";
<?php

$inputDir = __DIR__ . '/originals'; // 原始 HTML 的資料夾
$outputDir = __DIR__ . '/headers/originals'; // 匯出 header 的資料夾

if (!is_dir($outputDir)) {
    mkdir($outputDir, 0777, true);
}

// 幫註解變檔名用
function slugify($text)
{
    $text = strtolower(trim($text));
    $text = preg_replace('/[^a-z0-9]+/i', '-', $text);
    return trim($text, '-');
}

function getUniqueFileName(string $baseName, string $ext, string $dir): string
{
    $i = 0;
    $filename = "{$baseName}.{$ext}";

    while (file_exists("{$dir}/{$filename}")) {
        $i++;
        $filename = "{$baseName}-{$i}.{$ext}";
    }

    return $filename;
}

function extractHeaderBlock(string $html): ?array
{
    // 強化正則，抓「包含註解」的 header 區塊（允許註解在上下一行）
    if (preg_match(
        '/<!--([\s\S]{0,200}?)-->\s*<header[\s\S]*?<\/header>\s*<!--([\s\S]{0,200}?)-->/i',
        $html,
        $matches
    )) {
        return [
            'start_comment' => strip_tags(trim($matches[1])),
            'header_html'   => $matches[0]
        ];
    }

    return null;
}

$files = glob($inputDir . '/*.html');

foreach ($files as $file) {
    $html = file_get_contents($file);
    $headerBlock = extractHeaderBlock($html);

    if ($headerBlock) {
        $comment = $headerBlock['start_comment'];
        $slug = slugify($comment);
        $uniqueFile = getUniqueFileName($slug, 'blade.php', $outputDir);

        file_put_contents("{$outputDir}/{$uniqueFile}", $headerBlock['header_html']);

        echo "✔ Extracted from {$file} → saved as: {$uniqueFile}\n";
    } else {
        echo "✘ No header block found in: {$file}\n";
    }
}

<?php
/**
 * Check PHP Upload Limits
 * This file displays current PHP upload configuration
 */

function convertToBytes($value) {
    $value = trim($value);
    $last = strtolower($value[strlen($value)-1]);
    $value = (int)$value;
    
    switch($last) {
        case 'g':
            $value *= 1024;
        case 'm':
            $value *= 1024;
        case 'k':
            $value *= 1024;
    }
    
    return $value;
}

function formatBytes($bytes, $precision = 2) {
    $units = ['B', 'KB', 'MB', 'GB'];
    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow = min($pow, count($units) - 1);
    $bytes /= (1 << (10 * $pow));
    return round($bytes, $precision) . ' ' . $units[$pow];
}

$upload_max = ini_get('upload_max_filesize');
$post_max = ini_get('post_max_size');
$memory_limit = ini_get('memory_limit');
$max_execution = ini_get('max_execution_time');

$upload_bytes = convertToBytes($upload_max);
$post_bytes = convertToBytes($post_max);

?>
<!DOCTYPE html>
<html>
<head>
    <title>PHP Upload Limits</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .card {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            margin-top: 0;
        }
        .limit-item {
            padding: 15px;
            margin: 10px 0;
            background: #f9f9f9;
            border-left: 4px solid #2563eb;
            border-radius: 4px;
        }
        .limit-label {
            font-weight: bold;
            color: #555;
            display: block;
            margin-bottom: 5px;
        }
        .limit-value {
            font-size: 24px;
            color: #2563eb;
        }
        .warning {
            background: #fff3cd;
            border-left-color: #ffc107;
            padding: 15px;
            border-radius: 4px;
            margin-top: 20px;
        }
        .info {
            background: #d1ecf1;
            border-left: 4px solid #0dcaf0;
            padding: 15px;
            border-radius: 4px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="card">
        <h1>📊 PHP Upload Limits</h1>
        
        <div class="limit-item">
            <span class="limit-label">Maximum Upload File Size</span>
            <span class="limit-value"><?= $upload_max ?> (<?= formatBytes($upload_bytes) ?>)</span>
        </div>

        <div class="limit-item">
            <span class="limit-label">Maximum POST Data Size</span>
            <span class="limit-value"><?= $post_max ?> (<?= formatBytes($post_bytes) ?>)</span>
        </div>

        <div class="limit-item">
            <span class="limit-label">Memory Limit</span>
            <span class="limit-value"><?= $memory_limit ?></span>
        </div>

        <div class="limit-item">
            <span class="limit-label">Max Execution Time</span>
            <span class="limit-value"><?= $max_execution ?> seconds</span>
        </div>

        <?php if($upload_bytes < 5242880): // Less than 5MB ?>
        <div class="warning">
            ⚠️ <strong>Warning:</strong> Upload limit is quite small. Consider increasing it for image uploads.
        </div>
        <?php endif; ?>

        <div class="info">
            <strong>ℹ️ Note:</strong> The actual upload limit is the <strong>smaller value</strong> between 
            <code>upload_max_filesize</code> and <code>post_max_size</code>.
            <br><br>
            <strong>Current Effective Limit:</strong> <?= formatBytes(min($upload_bytes, $post_bytes)) ?>
        </div>

        <div class="info" style="margin-top: 15px;">
            <strong>💡 To increase limits:</strong>
            <ol style="margin: 10px 0;">
                <li>Edit <code>php.ini</code> file</li>
                <li>Or create/edit <code>.htaccess</code> in project root with:
                    <pre style="background: #f5f5f5; padding: 10px; margin-top: 10px; border-radius: 4px;">php_value upload_max_filesize 10M
php_value post_max_size 10M
php_value memory_limit 128M
php_value max_execution_time 300</pre>
                </li>
            </ol>
        </div>
    </div>
</body>
</html>

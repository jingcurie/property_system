<?php

if (!function_exists('formatBytes')) {
    function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        if ($bytes <= 0) return '0 B';

        $power = floor(log($bytes, 1024));
        $power = min($power, count($units) - 1);

        $bytes /= pow(1024, $power);

        return round($bytes, $precision) . ' ' . $units[$power];
    }
}

if (!function_exists('getIconByType')) {
    function getIconByType($filename)
    {
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        $map = [
            'image' => ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'],
            'pdf' => ['pdf'],
            'word' => ['doc', 'docx'],
            'excel' => ['xls', 'xlsx', 'csv'],
            'ppt' => ['ppt', 'pptx'],
            'text' => ['txt', 'md'],
            'zip' => ['zip', 'rar', '7z'],
            'play' => ['mp4', 'avi', 'mov', 'webm'],
            'audio' => ['mp3', 'wav', 'aac'],
            'code' => ['js', 'php', 'py', 'html', 'css', 'json'],
        ];

        foreach ($map as $type => $exts) {
            if (in_array($ext, $exts)) {
                return "bi-file-earmark-$type";
            }
        }

        return 'bi-file-earmark'; // 默认图标
    }
}



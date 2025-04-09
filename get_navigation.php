<?php
header('Content-Type: application/json');

$filePath = 'data/navigation.json';
if (file_exists($filePath)) {
    $navigationData = file_get_contents($filePath);
    echo $navigationData;
} else {
    echo json_encode(['success' => false, 'message' => '文件不存在']);
}
?>
<?php
// 读取JSON文件中的导航数据
$jsonFilePath = __DIR__ . '/../php/navigation.json';
$navigation = json_decode(file_get_contents($jsonFilePath), true);

// 获取所有分类
$categories = array_keys($navigation);

// 返回分类列表
echo json_encode($categories);
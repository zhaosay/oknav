<?php
// 获取POST请求中的分类名称
$category = filter_input(INPUT_POST, 'category', FILTER_SANITIZE_STRING);

// 检查分类名称是否为空
if (empty($category)) {
    echo json_encode(['success' => false, 'message' => '分类名称不能为空']);
    exit;
}

// 读取JSON文件中的导航数据
$jsonFilePath = __DIR__ . '/../php/navigation.json';
$navigation = json_decode(file_get_contents($jsonFilePath), true);

// 检查分类是否已存在
if (array_key_exists($category, $navigation)) {
    echo json_encode(['success' => false, 'message' => '分类已存在']);
    exit;
}

// 添加新分类
$navigation[$category] = [];

// 写回JSON文件
file_put_contents($jsonFilePath, json_encode($navigation, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));

// 返回成功信息
echo json_encode(['success' => true, 'message' => '分类添加成功']);
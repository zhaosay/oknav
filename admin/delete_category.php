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

// 检查分类是否存在
if (!array_key_exists($category, $navigation)) {
    echo json_encode(['success' => false, 'message' => '分类不存在']);
    exit;
}

// 检查分类中是否有数据
if (!empty($navigation[$category])) {
    echo json_encode(['success' => false, 'message' => '请先删除该分类中的所有导航']);
    exit;
}

// 删除分类
unset($navigation[$category]);

// 写回JSON文件
file_put_contents($jsonFilePath, json_encode($navigation, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));

// 返回成功信息
echo json_encode(['success' => true, 'message' => '分类删除成功']);
<?php
// 获取POST请求中的参数
$category = filter_input(INPUT_POST, 'category', FILTER_SANITIZE_STRING);
$name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
$url = filter_input(INPUT_POST, 'url', FILTER_SANITIZE_URL);

// 检查参数是否为空
if (empty($category) || empty($name) || empty($url)) {
    echo json_encode(['success' => false, 'message' => '所有字段都不能为空']);
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

// 检查链接是否存在
if (!array_key_exists($name, $navigation[$category])) {
    echo json_encode(['success' => false, 'message' => '链接不存在']);
    exit;
}

// 更新链接信息
$navigation[$category][$name] = [
    'url' => $url,
    'add_time' => date('Y-m-d H:i:s')
];

// 写回JSON文件
if (file_put_contents($jsonFilePath, json_encode($navigation, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)) === false) {
    echo json_encode(['success' => false, 'message' => '写入文件失败']);
    exit;
}

// 返回成功信息
echo json_encode(['success' => true, 'message' => '数据修改成功']);
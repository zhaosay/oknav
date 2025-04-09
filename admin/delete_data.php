<?php
// 获取POST请求中的参数
$name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);

// 检查参数是否为空
if (empty($name)) {
    echo json_encode(['success' => false, 'message' => '名称不能为空']);
    exit;
}

// 读取JSON文件中的导航数据
$jsonFilePath = __DIR__ . '/../php/navigation.json';
$navigation = json_decode(file_get_contents($jsonFilePath), true);

// 查找并删除链接
$found = false;
foreach ($navigation as $category => $items) {
    if (array_key_exists($name, $items)) {
        unset($navigation[$category][$name]);
        $found = true;
        break;
    }
}

if (!$found) {
    echo json_encode(['success' => false, 'message' => '链接不存在']);
    exit;
}

// 写回JSON文件
file_put_contents($jsonFilePath, json_encode($navigation, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));

// 返回成功信息
echo json_encode(['success' => true, 'message' => '数据删除成功']);
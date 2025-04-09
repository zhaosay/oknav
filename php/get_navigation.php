<?php
$category = filter_input(INPUT_GET, 'category', FILTER_SANITIZE_STRING); // 使用filter_input获取GET请求中的category参数，并进行字符串过滤

// 读取JSON文件中的导航数据
$jsonFilePath = __DIR__ . '/navigation.json';
$navigation = json_decode(file_get_contents($jsonFilePath), true);

if ($category == '全部展示') {
    // 返回所有分类的数据
    echo json_encode($navigation);
} else {
    // 返回特定分类的数据
    if (array_key_exists($category, $navigation)) {
        echo json_encode($navigation[$category]);
    } else {
        echo json_encode(array()); // 如果分类不存在，返回空数组
    }
}
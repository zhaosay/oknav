<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
    if (empty($name)) {
        echo json_encode(['success' => false, 'message' => '分类名称不能为空']);
        exit;
    }

    // 这里添加添加分类的逻辑
    // ...

    echo json_encode(['success' => true, 'message' => '分类添加成功']);
} else {
    echo json_encode(['success' => false, 'message' => '请求方法不正确']);
}
?>
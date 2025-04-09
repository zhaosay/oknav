<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
    if (empty($id)) {
        echo json_encode(['success' => false, 'message' => '分类ID不能为空']);
        exit;
    }

    // 这里添加删除分类的逻辑
    // ...

    echo json_encode(['success' => true, 'message' => '分类删除成功']);
} else {
    echo json_encode(['success' => false, 'message' => '请求方法不正确']);
}
?>
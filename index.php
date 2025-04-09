<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <title>导航分类网</title>
    <link rel="stylesheet" href="./layui/css/layui.css"> <!-- 引入layui的CSS文件 -->
    <link rel="stylesheet" href="./css/style.css"> <!-- 引入自定义的CSS文件 -->
</head>
<body>
    <div class="layui-layout layui-layout-admin">
        <!-- 头部 -->
        <div class="layui-header">
            <div class="layui-logo">导航分类网</div> <!-- 网站logo -->
            <a href="/" class="layui-nav-item nav-link">回到首页</a> <!-- 回到首页的导航 -->
            <ul class="layui-nav layui-layout-right">
                 <li class="layui-nav-item">
                     <a href="javascript:;" class="nav-link" data-category="管理" onclick="location.href='/admin/'">管理链接</a> <!-- 修改为使用JavaScript跳转 -->
                 </li> <!-- 新增的链接 -->
            </ul>
        </div>

        <!-- 左侧导航 -->
        <div class="layui-side layui-bg-black">
            <div class="layui-side-scroll">
                <ul class="layui-nav layui-nav-tree" lay-filter="nav"> <!-- 导航菜单 -->
                    <li class="layui-nav-item">
                        <a href="javascript:;" class="nav-link" data-category="全部展示">分类导航</a> <!-- 新增的全部展示导航 -->
                    </li>
                    <?php
                    // 读取JSON文件中的导航数据
                    $jsonFilePath = __DIR__ . '/php/navigation.json';
                    $navigation = json_decode(file_get_contents($jsonFilePath), true);

                    // 生成导航菜单项
                    foreach ($navigation as $category => $items) {
                        echo '<li class="layui-nav-item">';
                        echo '<a href="javascript:;" class="nav-link" data-category="' . htmlspecialchars($category) . '">' . htmlspecialchars($category) . '</a>';
                        echo '</li>';
                    }
                    ?>
                </ul>
            </div>
        </div>

        <!-- 右侧内容 -->
        <div class="layui-body">
            <div class="layui-container" id="content">
                <!-- 动态加载的内容将显示在这里 -->
            </div>
        </div>
    </div>

    <script src="./layui/layui.js"></script> <!-- 引入layui的JS文件 -->
    <script src="./js/jquery-3.7.1.min.js"></script> <!-- 引入jQuery库 -->
    <script src="./js/script.js"></script> <!-- 引入自定义的JS文件 -->
    <script>
layui.use(['form', 'element', 'layer', 'table'], function() {
    var form = layui.form;
    var element = layui.element;
    var layer = layui.layer;
    var table = layui.table;
    var $ = layui.$;

    // 默认加载全部展示内容
    document.addEventListener('DOMContentLoaded', function() {
        var category = '全部展示';
        $.ajax({
            url: 'php/get_navigation.php', // 请求的PHP文件路径
            type: 'GET', // 请求类型为GET
            data: { category: category }, // 发送的数据
            success: function(response){
                response = JSON.parse(response); // 解析返回的JSON数据
                var content = '';
                if (category == '全部展示') {
                    for (var category in response) {
                        content += '<h2>' + category + '</h2>';
                        content += '<div class="layui-row">';
                        for (var item in response[category]) {
                            content += '<div class="layui-col-md2"><a href="' + response[category][item] + '">' + item + '</a></div>'; // 修改为layui-col-md2
                        }
                        content += '</div>';
                    }
                } else {
                    content += '<div class="layui-row">';
                    for (var item in response) {
                        content += '<div class="layui-col-md2"><a href="' + response[item] + '">' + item + '</a></div>'; // 修改为layui-col-md2
                    }
                    content += '</div>';
                }
                $('#content').html(content); // 将返回的内容显示在id为content的元素中
            }
        });
    });

    // 提交表单
    form.on('submit(formDemo)', function(data) {
        $.ajax({
            url: 'add_data.php',
            type: 'POST',
            data: data.field,
            success: function(response) {
                response = JSON.parse(response);
                if (response.success) {
                    layer.msg('数据添加成功');
                    loadData();
                    $('#addDataForm')[0].reset();
                } else {
                    layer.msg('数据添加失败: ' + response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error('提交表单失败:', error);
            }
        });
        return false; // 阻止表单跳转
    });

    // 修改表单提交
    form.on('submit(editFormDemo)', function(data) {
        console.log(data.field); // 添加日志以检查提交的数据
        $.ajax({
            url: 'edit_data.php',
            type: 'POST',
            data: data.field,
            success: function(response) {
                response = JSON.parse(response);
                if (response.success) {
                    layer.msg('数据修改成功');
                    loadData();
                    layer.closeAll(); // 关闭所有弹出层
                } else {
                    layer.msg('数据修改失败: ' + response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error('修改数据失败:', error);
            }
        });
        return false; // 阻止表单跳转
    });

    // 删除表单提交
    form.on('submit(deleteFormDemo)', function(data) {
        $.ajax({
            url: 'delete_data.php',
            type: 'POST',
            data: data.field,
            success: function(response) {
                response = JSON.parse(response);
                if (response.success) {
                    layer.msg('数据删除成功');
                    loadData();
                    layer.closeAll(); // 关闭所有弹出层
                } else {
                    layer.msg('数据删除失败: ' + response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error('删除数据失败:', error);
            }
        });
        return false; // 阻止表单跳转
    });

    // 加载数据
    function loadData() {
        $.ajax({
            url: 'php/get_navigation.php',
            type: 'GET',
            success: function(response) {
                response = JSON.parse(response);
                var content = '';
                for (var category in response) {
                    content += '<h2>' + category + '</h2>';
                    content += '<div class="layui-row">';
                    for (var item in response[category]) {
                        content += '<div class="layui-col-md2"><a href="' + response[category][item] + '">' + item + '</a></div>'; // 修改为layui-col-md2
                    }
                    content += '</div>';
                }
                $('#content').html(content);
            }
        });
    }
});
</script>
</body>
</html>
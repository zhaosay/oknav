<!DOCTYPE html>
<html lang="zh-CN">

<head>
    <meta charset="UTF-8">
    <title></title>
    <link rel="stylesheet" href="../layui/css/layui.css">
    <link rel="stylesheet" href="../css/style.css"> <!-- 添加对style.css的引用 -->
</head>

<body>
    <div class="layui-layout layui-layout-admin">
        <!-- 头部 -->
        <div class="layui-header">
            <div class="layui-logo">导航后台管理系统</div>
        </div>

        <!-- 左侧导航 -->
        <div class="layui-side layui-bg-black">
            <div class="layui-side-scroll">
                <ul class="layui-nav layui-nav-tree" lay-filter="test">
                    <li class="layui-nav-item"><a href="javascript:;" id="addCategoryLink">新增分类</a></li>
                    <li class="layui-nav-item"><a href="javascript:;" id="deleteCategoryLink">删除分类</a></li> <!-- 新增删除分类按钮 -->
                    <li class="layui-nav-item"><a href="javascript:;" onclick="window.location.href='/nav/';">回到首页</a></li>
                </ul>
            </div>
        </div>

        <!-- 右侧内容 -->
        <div class="layui-body">
            <div class="layui-container">
                <form class="layui-form" id="addDataForm" lay-filter="formDemo">
                    <div class="layui-row">
                        <div class="layui-col-md3">
                            <div class="layui-form-item">
                                <label class="layui-form-label">分类</label>
                                <div class="layui-input-block">
                                    <select name="category" id="category" required>
                                        <option value="">请选择分类</option>
                                        <!-- 分类选项将通过JavaScript动态加载 -->
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="layui-col-md3">
                            <div class="layui-form-item">
                                <label class="layui-form-label">名称</label>
                                <div class="layui-input-block">
                                    <input type="text" name="name" required lay-verify="required" placeholder="请输入名称" autocomplete="off" class="layui-input">
                                </div>
                            </div>
                        </div>
                        <div class="layui-col-md3">
                            <div class="layui-form-item">
                                <label class="layui-form-label">链接</label>
                                <div class="layui-input-block">
                                    <input type="text" name="url" required lay-verify="required" placeholder="请输入链接" autocomplete="off" class="layui-input">
                                </div>
                            </div>
                        </div>
                        <div class="layui-col-md3">
                            <div class="layui-form-item">
                                <div class="layui-input-block">
                                    <button class="layui-btn" lay-submit lay-filter="formDemo">立即提交</button>
                                </div>
                            </div>
                        </div>
                        
                        </div>
                    </div>
                </form>
                <hr>
                <h2 style="padding: 10px;text-align: center;">所有数据</h2>
                <div class="layui-table-container">
                    <table id="dataTable" lay-filter="dataTable">
                        <tbody>
                            <!-- 数据行将通过JavaScript动态加载 -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- 修改表单 -->
    <div class="custom-modal" id="editDataForm" style="display:none;">
        <h3>修改链接</h3>
        <form class="layui-form" lay-filter="editFormDemo">
            <div class="layui-form-item">
                <label class="layui-form-label">分类</label>
                <div class="layui-input-block">
                    <select name="category" id="editCategory" required>
                        <option value="">请选择分类</option>
                        <!-- 分类选项将通过JavaScript动态加载 -->
                    </select>
                </div>
            </div>
             <div class="layui-form-item">
                <label class="layui-form-label">名称</label>
                <div class="layui-input-block">
                    <input type="text" name="name" id="editName" required lay-verify="required" placeholder="请输入名称" autocomplete="off" class="layui-input">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">链接</label>
                <div class="layui-input-block">
                    <input type="text" name="url" id="editUrl" required lay-verify="required" placeholder="请输入链接" autocomplete="off" class="layui-input">
                </div>
            </div>
            <div class="layui-form-item">
                <div class="layui-input-block">
                    <button class="layui-btn" lay-submit lay-filter="editFormDemo">立即提交</button>
                </div>
            </div>
        </form>
    </div> 


    <div class="custom-modal" id="deleteDataForm" style="display:none;">
        <h3>删除链接</h3>
        <form class="layui-form" lay-filter="deleteFormDemo">
            <div class="layui-form-item">
                <label class="layui-form-label">名称</label>
                <div class="layui-input-block">
                    <input type="text" name="name" id="deleteName" required lay-verify="required" placeholder="请输入名称" autocomplete="off" class="layui-input" readonly>
                </div>
            </div>
            <div class="layui-form-item">
                <div class="layui-input-block">
                    <button class="layui-btn" lay-submit lay-filter="deleteFormDemo">立即删除</button>
                </div>
            </div>
        </form>
    </div>

    <script src="../layui/layui.js"></script>
    <script src="../js/jquery-3.7.1.min.js"></script>
    <script>
        layui.use(['form', 'element', 'layer', 'table'], function() {
            var form = layui.form;
            var element = layui.element;
            var layer = layui.layer;
            var table = layui.table;
            var $ = layui.$;

            // 加载分类
            $.ajax({
                url: 'get_categories.php',
                type: 'GET',
                success: function(response) {
                    response = JSON.parse(response);
                    response.forEach(function(category) {
                        $('#category').append('<option value="' + category + '">' + category + '</option>');
                        $('#editCategory').append('<option value="' + category + '">' + category + '</option>');
                    });
                    form.render('select'); // 重新渲染select
                },
                error: function(xhr, status, error) {
                    console.error('加载分类失败:', error);
                }
            });

            // 加载所有数据
            function loadData() {
                $.ajax({
                    url: '../php/get_navigation.php',
                    type: 'GET',
                    data: {
                        category: '全部展示' // 确认category参数设置为'全部展示'
                    },
                    success: function(response) {
                        response = JSON.parse(response);
                        var data = [];
                        var index = 1; // 初始化序号
                        for (var category in response) {
                            for (var item in response[category]) {
                                data.push({
                                    index: index++,
                                    category: category,
                                    name: item,
                                    url: response[category][item].url,
                                    add_time: response[category][item].add_time
                                });
                            }
                        }
                        table.render({
                            elem: '#dataTable',
                            data: data,
                            cols: [[
                                {field: 'index', title: '序号', width: 80, sort: true},
                                {field: 'category', title: '分类', width: 250, sort: true},
                                {field: 'name', title: '名称', width: 150, sort: true},
                                {field: 'url', title: '链接', width: 350, sort: true},
                                {field: 'add_time', title: '添加时间', width: 180, sort: true},
                                {title: '操作', toolbar: '#toolbarDemo', width: 150}
                            ]],
                            page: true,
                            limit: 20 // 设置默认每页显示20条数据
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error('加载数据失败:', error);
                    }
                });
            }
            loadData();

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
                            loadData(); // 强制刷新表格
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

            // 新增分类
            $('#addCategoryLink').on('click', function() {
                layer.prompt({
                    formType: 0,
                    title: '请输入分类名称'
                }, function(value, index, elem) {
                    $.ajax({
                        url: 'add_category.php',
                        type: 'POST',
                        data: {
                            category: value
                        },
                        success: function(response) {
                            response = JSON.parse(response);
                            if (response.success) {
                                layer.msg('分类添加成功');
                                $('#category').append('<option value="' + value + '">' + value + '</option>');
                                $('#editCategory').append('<option value="' + value + '">' + value + '</option>');
                                form.render('select'); // 重新渲染select
                            } else {
                                layer.msg('分类添加失败: ' + response.message);
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('新增分类失败:', error);
                        }
                    });
                    layer.close(index);
                });
            });

            // 删除分类
            $('#deleteCategoryLink').on('click', function() {
                layer.prompt({
                    formType: 0,
                    title: '请输入要删除的分类名称'
                }, function(value, index, elem) {
                    $.ajax({
                        url: 'delete_category.php',
                        type: 'POST',
                        data: {
                            category: value
                        },
                        success: function(response) {
                            response = JSON.parse(response);
                            if (response.success) {
                                layer.msg('分类删除成功');
                                $('#category option[value="' + value + '"]').remove();
                                $('#editCategory option[value="' + value + '"]').remove();
                                form.render('select'); // 重新渲染select
                                loadData(); // 重新加载数据
                            } else {
                                layer.msg('分类删除失败: ' + response.message);
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('删除分类失败:', error);
                        }
                    });
                    layer.close(index);
                });
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
                            loadData(); // 强制刷新表格
                            layer.closeAll();
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
                            loadData(); // 强制刷新表格
                            layer.closeAll();
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

            // 表格工具栏事件
            table.on('tool(dataTable)', function(obj) {
                var data = obj.data;
                if (obj.event === 'edit') {
                    $('#editCategory').val(data.category);
                    $('#editName').val(data.name);
                    $('#editUrl').val(data.url); // 确保URL字段正确加载数据
                    form.render('select'); // 重新渲染select
                    layer.open({
                        type: 1,
                        title: false,
                        area: ['400px', '300px'],
                        shadeClose: true,
                        content: $('#editDataForm')
                    });
                } else if (obj.event === 'delete') {
                    $('#deleteName').val(data.name);
                    layer.open({
                        type: 1,
                        title: false,
                        area: ['400px', '200px'],
                        shadeClose: true,
                        content: $('#deleteDataForm')
                    });
                }
            });

            // 模糊查询功能
            $('#searchButton').on('click', function() {
                var keyword = $('#searchInput').val();
                $.ajax({
                    url: '../php/get_navigation.php',
                    type: 'GET',
                    data: {
                        keyword: keyword
                    },
                    success: function(response) {
                        response = JSON.parse(response);
                        var data = [];
                        var index = 1; // 初始化序号
                        for (var category in response) {
                            for (var item in response[category]) {
                                data.push({
                                    index: index++,
                                    category: category,
                                    name: item,
                                    url: response[category][item].url,
                                    add_time: response[category][item].add_time
                                });
                            }
                        }
                        table.render({
                            elem: '#dataTable',
                            data: data,
                            cols: [[
                                {field: 'index', title: '序号', width: 80, sort: true},
                                {field: 'category', title: '分类', width: 250, sort: true},
                                {field: 'name', title: '名称', width: 150, sort: true},
                                {field: 'url', title: '链接', width: 350, sort: true},
                                {field: 'add_time', title: '添加时间', width: 180, sort: true},
                                {title: '操作', toolbar: '#toolbarDemo', width: 150}
                            ]],
                            page: true,
                            limit: 20 // 设置默认每页显示20条数据
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error('模糊查询失败:', error);
                    }
                });
            });
        });
    </script>

    <script type="text/html" id="toolbarDemo">
        <a class="layui-btn layui-btn-xs" lay-event="edit">修改</a>
        <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="delete">删除</a>
    </script>
</body>

</html>
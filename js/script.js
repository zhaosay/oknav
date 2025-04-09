// 引入layui的element模块和jquery模块
layui.use(['element', 'jquery'], function(){
    var element = layui.element;
    var $ = layui.$;

    // 监听导航点击事件
    element.on('nav(nav)', function(elem){
        var category = $(elem).data('category'); // 获取点击的导航分类名称
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
});
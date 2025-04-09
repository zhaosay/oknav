document.addEventListener('DOMContentLoaded', function() {
    // 加载分类
    fetch('get_categories.php')
        .then(response => response.json())
        .then(data => {
            const categoryList = document.getElementById('category-list');
            data.forEach(category => {
                const item = document.createElement('li');
                item.textContent = htmlspecialchars(category.name);
                categoryList.appendChild(item);
            });
        })
        .catch(error => {
            console.error('加载分类失败:', error);
            alert('加载分类失败，请稍后再试。');
        });

    // 删除分类
    document.getElementById('delete-category-form').addEventListener('submit', function(event) {
        event.preventDefault();
        const categoryId = document.getElementById('category-id').value;
        fetch(`delete_category.php?id=${encodeURIComponent(categoryId)}`, {
            method: 'POST'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('分类删除成功。');
                location.reload();
            } else {
                alert('删除分类失败，请稍后再试。');
            }
        })
        .catch(error => {
            console.error('删除分类失败:', error);
            alert('删除分类失败，请稍后再试。');
        });
    });
});

function htmlspecialchars(string) {
    return String(string)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');
}
// script.js - THE ONE THAT WORKS 100%
document.addEventListener('DOMContentLoaded', () => {
    const checkboxes = document.querySelectorAll('.cat-filter');
    const container = document.getElementById('posts-container');

    function updatePosts() {
        const checked = Array.from(checkboxes)
            .filter(cb => cb.checked)
            .map(cb => cb.value);

        const url = checked.length > 0
            ? `home.php?cat[]=${checked.join('&cat[]=')}`   // ← THIS MATCHES YOUR PHP
            : 'home.php';

        fetch(url)
            .then(r => r.text())
            .then(html => {
                const temp = document.createElement('div');
                temp.innerHTML = html;
                const newPosts = temp.querySelector('#posts-container');
                if (newPosts) {
                    container.innerHTML = newPosts.innerHTML;
                }
            });
    }

    checkboxes.forEach(cb => cb.addEventListener('change', updatePosts));
});
document.querySelectorAll('.filtro-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        document.querySelector('.filtro-btn.active').classList.remove('active');
        btn.classList.add('active');

        const filtro = btn.dataset.filtro;

        document.querySelectorAll('.projeto-card').forEach(card => {
            const categoria = card.dataset.categoria;
            card.style.display = (filtro === 'todos' || categoria === filtro) ? 'flex' : 'none';
        });
    });
});
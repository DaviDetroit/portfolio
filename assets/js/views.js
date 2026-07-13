async function loadViews() {

    await fetch("/backend/view.php");

    const response = await fetch("/backend/get-views.php");

    const data = await response.json();

    document.getElementById("views-count").textContent =
        data.views.toLocaleString("pt-BR");

}

function initEye() {

    const svg = document.getElementById('view-eye');
    if (!svg) return;

    const iris = document.getElementById('iris');
    const pupil = document.getElementById('pupil');
    const shine = document.getElementById('shine');
    const blinkRect = document.getElementById('eye-blink');

    const maxOffsetX = 24;
    const maxOffsetY = 12;

    let targetX = 0, targetY = 0;
    let currentX = 0, currentY = 0;

    document.addEventListener('mousemove', (e) => {

        const rect = svg.getBoundingClientRect();
        const cx = rect.left + rect.width / 2;
        const cy = rect.top + rect.height / 2;

        const dx = e.clientX - cx;
        const dy = e.clientY - cy;

        const dist = Math.min(1, Math.hypot(dx, dy) / 400);
        const angle = Math.atan2(dy, dx);

        targetX = Math.cos(angle) * maxOffsetX * dist;
        targetY = Math.sin(angle) * maxOffsetY * dist;

    });

    function animateEye() {

        currentX += (targetX - currentX) * 0.15;
        currentY += (targetY - currentY) * 0.15;

        iris.setAttribute('cx', 100 + currentX);
        iris.setAttribute('cy', 70 + currentY);

        pupil.setAttribute('cx', 100 + currentX);
        pupil.setAttribute('cy', 70 + currentY);

        shine.setAttribute('cx', 92 + currentX);
        shine.setAttribute('cy', 60 + currentY);

        requestAnimationFrame(animateEye);

    }

    function blink() {

        svg.classList.add('blinking');

        setTimeout(() => svg.classList.remove('blinking'), 150);
        setTimeout(blink, 3000 + Math.random() * 3000);

    }

    animateEye();
    setTimeout(blink, 2000);

}

loadViews();
initEye();
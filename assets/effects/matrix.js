let matrixCanvas = null;
let matrixCtx = null;
let matrixInterval = null;

function startMatrix() {

    if (matrixCanvas) return;

    matrixCanvas = document.createElement("canvas");

    matrixCanvas.id = "matrix-canvas";

    document.body.appendChild(matrixCanvas);
    requestAnimationFrame(() => {
        matrixCanvas.style.opacity ="1";
    });

    matrixCtx = matrixCanvas.getContext("2d");

    resizeMatrix();

    window.addEventListener("resize", resizeMatrix);

    const letters = "アカサタナハマヤラワ0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";

    const fontSize = 16;

    const columns = Math.floor(matrixCanvas.width / fontSize);

    const drops = [];

    for (let i = 0; i < columns; i++) {

        drops[i] = 1;

    }

    matrixInterval = setInterval(() => {

        setTimeout(() => {

            matrixCanvas.style.opacity = "0";

            setTimeout(() => {

                clearEffects();

            }, 800);

        }, 2500);

        matrixCtx.fillStyle = "rgba(0,0,0,0.08)";
        matrixCtx.fillRect(0, 0, matrixCanvas.width, matrixCanvas.height);

        matrixCtx.fillStyle = "#00ff66";
        matrixCtx.font = `${fontSize}px Consolas`;

        for (let i = 0; i < drops.length; i++) {

            const text = letters[Math.floor(Math.random() * letters.length)];

            matrixCtx.fillText(text, i * fontSize, drops[i] * fontSize);

            if (
                drops[i] * fontSize > matrixCanvas.height &&
                Math.random() > 0.975
            ) {

                drops[i] = 0;

            }

            drops[i]++;

        }

    }, 33);

}

function resizeMatrix() {

    if (!matrixCanvas) return;

    matrixCanvas.width = window.innerWidth;

    matrixCanvas.height = window.innerHeight;

}
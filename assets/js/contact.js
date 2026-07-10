const form = document.getElementById("contact-form");
const successBox = document.getElementById("form-success");
const button = form.querySelector(".send-btn");

const originalHTML = button.innerHTML;

function buildParticles(qty) {
    let html = "";
    for (let i = 0; i < qty; i++) {
        const angle = (Math.PI * 2 * i) / qty;
        const distance = 28 + Math.random() * 10;
        const x = Math.cos(angle) * distance;
        const y = Math.sin(angle) * distance;
        html += `<span class="particle" style="--x:${x}px; --y:${y}px;"></span>`;
    }
    return html;
}

button.innerHTML = `
    <span class="btn-label">${originalHTML}</span>
    <div class="rocket-stage">
        <i class="fa-solid fa-rocket rocket"></i>
        ${buildParticles(10)}
    </div>
`;

function resetButton() {
    button.disabled = false;
    button.style.width = "";
    button.classList.remove("is-sending", "is-launch", "is-error");
}

form.addEventListener("submit", async (event) => {
    event.preventDefault();

    // trava a largura para o botão não "saltar" durante a animação
    button.style.width = button.offsetWidth + "px";
    button.disabled = true;
    button.classList.remove("is-error", "is-launch");
    button.classList.add("is-sending");

    try {
        const response = await fetch("backend/send-email.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({
                name: document.getElementById("contact-name").value,
                email: document.getElementById("contact-email").value,
                message: document.getElementById("contact-message").value
            })
        });

        // lê como texto primeiro: evita o falso "erro" quando o PHP
        // solta algum warning antes do JSON
        const raw = await response.text();
        let result;

        try {
            result = JSON.parse(raw);
        } catch (parseError) {
            console.error("Resposta do servidor não era JSON válido:", raw);
            throw new Error("invalid-json");
        }

        if (!response.ok || !result.success) {
            throw new Error(result.message || "send-failed");
        }

        // sucesso -> explode o foguete
        button.classList.remove("is-sending");
        button.classList.add("is-launch");

        setTimeout(() => {
            resetButton();
            showSuccess();
            form.reset();
        }, 550);

    } catch (error) {
        console.error(error);
        button.classList.remove("is-sending");
        button.classList.add("is-error");

        setTimeout(() => {
            button.classList.remove("is-error");
            resetButton();
        }, 450);

        alert("Não foi possível enviar sua mensagem. Tente novamente em alguns segundos.");
    }
});

function showSuccess() {
    form.classList.add("is-hidden");
    successBox.hidden = false;

    setTimeout(() => {
        successBox.hidden = true;
        form.classList.remove("is-hidden");
    }, 4000);
}
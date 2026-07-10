const form = document.getElementById("contact-form");
const successBox = document.getElementById("form-success");

form.addEventListener("submit", async (event) => {
    event.preventDefault();

    const button = form.querySelector(".send-btn");
    const originalText = button.innerHTML;

    button.disabled = true;
    button.innerHTML = "Enviando...";

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

        const result = await response.json();

        if (result.success) {
            showSuccess();
            form.reset();
        } else {
            alert(result.message || "Não foi possível enviar sua mensagem.");
        }

    } catch (error) {
        console.error(error);
        alert("Erro ao enviar o e-mail.");
    }

    button.disabled = false;
    button.innerHTML = originalText;
});

function showSuccess() {
    form.classList.add("is-hidden");
    successBox.hidden = false;

    // volta ao formulário depois de um tempo
    setTimeout(() => {
        successBox.hidden = true;
        form.classList.remove("is-hidden");
    }, 4000);
}
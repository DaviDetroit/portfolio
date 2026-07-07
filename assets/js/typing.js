const greeting = document.getElementById("greeting");
const name = document.getElementById("name");
const role = document.getElementById("role");

function typeText(element, text, delay = 70) {
    return new Promise(resolve => {
        element.textContent = "";

        const cursor = document.createElement("span");
        cursor.className = "typing-cursor";
        cursor.textContent = "▋";
        element.appendChild(cursor);

        let i = 0;
        const timer = setInterval(() => {
            if (i < text.length) {
                const char = text.charAt(i);
                element.insertBefore(document.createTextNode(char), cursor);
                i++;
            } else {
                clearInterval(timer);
                cursor.remove();
                resolve();
            }
        }, delay);
    });
}

async function startTyping() {
    await typeText(greeting, "Olá, eu sou");
    await typeText(name, "Davi Alves Fernandes");
    await typeText(role, "Desenvolvedor Backend\n& Banco de Dados");
}

document.addEventListener("DOMContentLoaded", startTyping);
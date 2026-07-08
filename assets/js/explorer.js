const projectTab = document.getElementById("project-tab-name");
const projectIcon = document.getElementById("project-icon");
const editorContent = document.querySelector(".editor-content");

const projects = {
    vipbot: {
        title: "VipBotDiscord.py",
        icon: "fa-brands fa-python",
        description: "Bot para gerenciamento de servidores Discord com sistema VIP, eventos automáticos e integração com MySQL.",
        technologies: ["Python", "MySQL", "Discord.py", "Railway", "API Football"],
        features: [
            "Sistema VIP",
            "Eventos automáticos",
            "Banco de Dados",
            "Logs"
        ],
        github: "https://github.com/DaviDetroit/VipBotDiscord"
    },

    football: {
        title: "WebScraping.py",
        icon: "fa-brands fa-python",
        description: "Um sistema completo e robusto para raspagem de dados de livros do site books.toscrape.com, armazenando informações e histórico de preços em banco de dados MySQL.",
        technologies: ["Python", "BeautifulSoup", "MySQL", "Requests"],
        features: [
            "Raspagem de livros",
            "Histórico de preços",
            "Armazenamento em banco",
            "Relatórios de preços"
        ],
        github: "https://github.com/DaviDetroit/WebScraping"
    },

    detroit: {
        title: "crudalunos.php",
        icon: "fa-brands fa-php",
        description: "Sistema de Consulta de Cadastros em PHP + MySQL. Consulta cadastros completos de pessoas físicas e jurídicas usando CPF ou CNPJ, com interface responsiva e proteção contra SQL Injection.",
        technologies: ["PHP", "MySQL", "PDO", "HTML"],
        features: [
            "Busca por CPF/CNPJ",
            "Validação automática de formato",
            "Exibição responsiva",
            "Prevenção contra SQL Injection",
            "Ordenação por registro recente"
        ],
        github: "https://github.com/DaviDetroit/php-crud-alunos"
    },

    procedures: {
        title: "estudos.sql",
        icon: "fa-solid fa-database",
        description: "Estudos de SQL com stored procedures e consultas para relatórios e automações.",
        technologies: ["SQL", "MySQL"],
        features: [
            "Stored Procedures",
            "Consultas",
            "Relatórios"
        ],
        github: "https://github.com/DaviDetroit/Estudos-sql"
    },

    portfolio: {
        title: "ChatBot.js",
        icon: "fa-brands fa-js",
        description: "Bot de atendimento via WhatsApp com painel web, usando Baileys, Node.js e MySQL. Registra interações e exibe status do bot em tempo real.",
        technologies: ["Node.js", "MySQL", "Baileys", "JavaScript"],
        features: [
            "Autenticação via QR Code",
            "Reconexão automática",
            "Painel web de status",
            "Persistência de interações em MySQL"
        ],
        github: "https://github.com/DaviDetroit/ChatBot"
    }
};


const files = document.querySelectorAll(".file");

const title = document.getElementById("project-title");
const description = document.getElementById("project-description");
const tech = document.getElementById("project-tech");
const features = document.getElementById("project-features");
const link = document.getElementById("project-link");

files.forEach(file => {

    file.addEventListener("click", () => {

        files.forEach(f => f.classList.remove("active"));

        file.classList.add("active");

        const project = projects[file.dataset.project];

        title.textContent = project.title;

        projectTab.textContent = project.title;

        projectIcon.className = project.icon;

        editorContent.style.animation = "none";

        void editorContent.offsetWidth;

        editorContent.style.animation = "fadeProject .25s ease";

        description.textContent = project.description;

        tech.innerHTML = "";

        project.technologies.forEach(item => {

            tech.innerHTML += `<span>${item}</span>`;

        });

        features.innerHTML = "";

        project.features.forEach(item => {

            features.innerHTML += `<li>${item}</li>`;

        });

        link.href = project.github;

    });

});

const folders = document.querySelectorAll(".folder");

folders.forEach(folder => {

    const header = folder.querySelector(".folder-header");

    const content = folder.querySelector(".folder-content");

    const arrow = folder.querySelector(".folder-arrow");

    header.addEventListener("click", () => {

        folder.classList.toggle("open");

        if(folder.classList.contains("open")){

            content.style.display = "block";

            arrow.classList.remove("fa-chevron-right");

            arrow.classList.add("fa-chevron-down");

        }else{

            content.style.display = "none";

            arrow.classList.remove("fa-chevron-down");

            arrow.classList.add("fa-chevron-right");

        }

    });

});


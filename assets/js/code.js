// ==============================
// SNIPPETS
// ==============================

const snippets = {

    party: {
        code: `function party() {

    confetti();

}`,
        run: party
    },

    snow: {
        code: `function snow() {

    startSnow();

}`,
        run: startSnow
    },

    fire: {
        code: `function fire() {

    ignite();

}`,
        run: ignite
    },

    matrix: {
        code: `function matrix() {

    startMatrix();

}`,
        run: startMatrix
    },

    reset: {
        code: `function reset() {

    clearEffects();

}`,
        run: reset
    }

};

// ==============================

let currentSnippet = "party";

const codeContent = document.getElementById("code-content");

const lineNumbers = document.getElementById("line-numbers");

const tabs = document.querySelectorAll(".tab");

const runButton = document.getElementById("run-code");

let typingInterval;



// ==============================
// ABAS
// ==============================

tabs.forEach(tab => {

    tab.addEventListener("click", () => {

        tabs.forEach(button => {

            button.classList.remove("active");

        });

        tab.classList.add("active");

        currentSnippet = tab.dataset.snippet;

        updateEditor(snippets[currentSnippet].code);

    });

});

function highlight(code){

    return code

        .replace(/function/g, '<span class="keyword">function</span>')

        .replace(/\b([a-zA-Z_]\w*)(?=\()/g, (match) => {

            if(match === "function") return match;

            return `<span class="method">${match}</span>`;

        })

        .replace(/party|snow|fire|matrix|reset/g, match => {

            return `<span class="function-name">${match}</span>`;

        })

        .replace(/[{}()]/g, match => {

            return `<span class="bracket">${match}</span>`;

        });

}

function updateEditor(code){

    clearInterval(typingInterval);

    codeContent.innerHTML = "";

    let index = 0;

    typingInterval = setInterval(() => {

        index++;

        const partialCode = code.substring(0, index);

        codeContent.innerHTML = highlight(partialCode) + '<span class="cursor"></span>';

        const totalLines = partialCode.split("\n").length;

        let numbers = "";

        for(let i = 1; i <= totalLines; i++){

            numbers += `${i}<br>`;

        }

        lineNumbers.innerHTML = numbers;

        if(index >= code.length){

            clearInterval(typingInterval);

            codeContent.innerHTML = highlight(code) + '<span class="cursor"></span>';

        }

    }, 50);

}

// ==============================
// EXECUTAR
// ==============================

runButton.addEventListener("click", () => {

    snippets[currentSnippet].run();

});

updateEditor(snippets.party.code);
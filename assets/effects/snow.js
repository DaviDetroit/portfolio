let snowInterval = null;

function startSnow() {

    if (snowInterval) return;

    snowInterval = setInterval(() => {

        const snow = document.createElement("div");

        snow.className = "snowflake";

        snow.innerHTML = "❄";

        snow.style.left = Math.random() * window.innerWidth + "px";

        snow.style.fontSize = (10 + Math.random() * 18) + "px";

        snow.style.animationDuration = (4 + Math.random() * 4) + "s";

        document.body.appendChild(snow);

        setTimeout(() => {

            snow.remove();

        }, 8000);

    }, 150);

}
let fireInterval = null;

function ignite() {

    if (fireInterval) return;

    fireInterval = setInterval(() => {

        const flame = document.createElement("div");

        flame.className = "flame";

        flame.style.left = Math.random() * window.innerWidth + "px";

        flame.style.width = (8 + Math.random() * 10) + "px";
        flame.style.height = (20 + Math.random() * 25) + "px";

        flame.style.animationDuration = (2 + Math.random()) + "s";

        document.body.appendChild(flame);

        setTimeout(() => {

            flame.remove();

        }, 3000);

    }, 80);

}
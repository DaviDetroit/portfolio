function party() {

    const duration = 7000;

    const animationEnd = Date.now() + duration;

    const defaults = {
        startVelocity: 30,
        spread: 360,
        ticks: 80,
        zIndex: 9999
    };

    function randomInRange(min, max) {
        return Math.random() * (max - min) + min;
    }

    const interval = setInterval(() => {

        const timeLeft = animationEnd - Date.now();

        if (timeLeft <= 0) {
            clearInterval(interval);
            return;
        }

        confetti({
            ...defaults,
            particleCount: 4,
            origin: {
                x: randomInRange(0.1, 0.9),
                y: randomInRange(0.1, 0.5)
            }
        });

    }, 180);

}
function clearEffects(){

    // Snow

    clearInterval(snowInterval);
    snowInterval = null;

    document.querySelectorAll(".snowflake").forEach(e => e.remove());

    // Fire

    clearInterval(fireInterval);
    fireInterval = null;

    document.querySelectorAll(".flame").forEach(e => e.remove());

    // Matrix

    clearInterval(matrixInterval);
    matrixInterval = null;

    if(matrixCanvas){

        matrixCanvas.remove();

        matrixCanvas = null;

        matrixCtx = null;

    }

}

function reset(){

    clearEffects();

}
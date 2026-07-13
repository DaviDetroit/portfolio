async function loadViews() {

    await fetch("/backend/view.php");

    const response = await fetch("/backend/get-views.php");

    const data = await response.json();

    document.getElementById("views-count").textContent =
        data.views.toLocaleString("pt-BR");
}

loadViews();
// assets/app.js
function handleFilterChange(select) {
    if (select.value) {
        window.location.href = select.value;
    }
}

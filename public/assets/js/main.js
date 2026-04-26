document.addEventListener("DOMContentLoaded", () => {
    const ticketForm = document.getElementById("ticketForm");
    const cancelBtn = document.getElementById("cancelBtn");

    if (cancelBtn && ticketForm) {
        cancelBtn.addEventListener("click", (event) => {
            event.preventDefault();
            ticketForm.reset();
        });
    }
});

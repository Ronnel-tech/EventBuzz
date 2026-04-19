
document.addEventListener("DOMContentLoaded", () => {
    const ticketForm = document.getElementById("ticketForm");
    const ticketList = document.getElementById("ticketList");
    const cancelBtn = document.getElementById("cancelBtn");

    ticketForm.addEventListener("submit", function (e) {
        e.preventDefault();

        const name = document.getElementById("name").value;
        const quantity = document.getElementById("quantity").value;
        const price = document.getElementById("price").value;
        const endDate = document.getElementById("endDate").value;
        const endTime = document.getElementById("endTime").value;

        const item = document.createElement("div");
        item.className = "grid grid-cols-4 gap-4 border-b border-gray-700 py-2";

        item.innerHTML = `
            <div>
                <p class="font-medium">${name}</p>
                <p class="text-sm text-secondary">Ends on ${endDate} at ${endTime}</p>
            </div>
            <div>${quantity}</div>
            <div>${price}</div>
            <div>
                <button type="button" class="btn btn-danger  px-4 py-1 rounded-full text-sm delete-btn">
                    Delete
                </button>
            </div>
        `;

        item.querySelector(".delete-btn").addEventListener("click", () => {
            item.remove();
        });

        ticketList.appendChild(item);
        ticketForm.reset();
    });

    cancelBtn.addEventListener("click", () => {
        ticketForm.reset();
    });
});

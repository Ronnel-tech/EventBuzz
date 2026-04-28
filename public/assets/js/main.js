document.addEventListener("DOMContentLoaded", () => {
    const ticketForm = document.getElementById("ticketForm");
    const cancelBtn = document.getElementById("cancelBtn");

    if (cancelBtn && ticketForm) {
        cancelBtn.addEventListener("click", (event) => {
            event.preventDefault();
            ticketForm.reset();
        });
    }

    const attendeeRows = Array.from(document.querySelectorAll("[data-payment-url]"));
    attendeeRows.forEach((row) => {
        row.addEventListener("click", () => {
            window.location.href = row.dataset.paymentUrl;
        });

        row.addEventListener("keydown", (event) => {
            if (event.key === "Enter" || event.key === " ") {
                event.preventDefault();
                window.location.href = row.dataset.paymentUrl;
            }
        });
    });

    const rowActions = Array.from(document.querySelectorAll("[data-row-action]"));
    rowActions.forEach((actionForm) => {
        actionForm.addEventListener("click", (event) => {
            event.stopPropagation();
        });

        actionForm.addEventListener("keydown", (event) => {
            event.stopPropagation();
        });
    });

    const scannerModal = document.getElementById("qrScannerModal");
    const openScannerButton = document.getElementById("openQrScanner");
    const closeScannerButton = document.getElementById("closeQrScanner");
    const scannerStatus = document.getElementById("qrScannerStatus");
    const fallbackInput = document.getElementById("qrFallbackInput");
    const openFallbackLinkButton = document.getElementById("openFallbackLink");
    const scannerReaderId = "qrScannerReader";
    let html5QrCode = null;

    function isAllowedScannerUrl(value) {
        try {
            const parsedUrl = new URL(value, window.location.origin);
            return parsedUrl.origin === window.location.origin && parsedUrl.pathname.indexOf("/organizer/qr_page") !== -1;
        } catch (error) {
            return false;
        }
    }

    function redirectToScannerUrl(value) {
        if (!scannerStatus) {
            return;
        }

        if (!isAllowedScannerUrl(value)) {
            scannerStatus.textContent = "The scanned QR code is not a valid attendee lookup link.";
            return;
        }

        window.location.href = new URL(value, window.location.origin).toString();
    }

    async function stopScanner() {
        if (!html5QrCode) {
            return;
        }

        try {
            await html5QrCode.stop();
        } catch (error) {
            // Ignore stop errors so the modal can still close.
        }

        try {
            await html5QrCode.clear();
        } catch (error) {
            // Ignore clear errors from partially started scanners.
        }

        html5QrCode = null;
    }

    async function closeScannerModal() {
        await stopScanner();
        scannerModal.classList.add("hidden");
        scannerModal.classList.remove("flex");
        if (scannerStatus) {
            scannerStatus.textContent = "Point the camera at an attendee QR code.";
        }
    }

    async function startScanner() {
        if (!scannerStatus) {
            return;
        }

        if (typeof window.Html5Qrcode === "undefined") {
            scannerStatus.textContent = "The QR scanner library could not be loaded. Paste the scanned link below instead.";
            return;
        }

        if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
            scannerStatus.textContent = "Camera access is not available in this browser. Paste the scanned link below instead.";
            return;
        }

        try {
            html5QrCode = new Html5Qrcode(scannerReaderId);
            scannerStatus.textContent = "Scanning for attendee QR code...";

            await html5QrCode.start(
                { facingMode: "environment" },
                {
                    fps: 10,
                    qrbox: { width: 260, height: 260 },
                    aspectRatio: 1.3333333
                },
                async (decodedText) => {
                    await stopScanner();
                    redirectToScannerUrl(decodedText);
                },
                () => {
                    // Keep scanning silently until a QR code is detected.
                }
            );
        } catch (error) {
            scannerStatus.textContent = "Camera access was denied or unavailable. Paste the scanned link below instead.";
        }
    }

    if (openScannerButton && scannerModal && closeScannerButton && openFallbackLinkButton && fallbackInput) {
        openScannerButton.addEventListener("click", () => {
            scannerModal.classList.remove("hidden");
            scannerModal.classList.add("flex");
            fallbackInput.value = "";
            startScanner();
        });

        closeScannerButton.addEventListener("click", () => {
            closeScannerModal();
        });

        scannerModal.addEventListener("click", (event) => {
            if (event.target === scannerModal) {
                closeScannerModal();
            }
        });

        openFallbackLinkButton.addEventListener("click", () => {
            redirectToScannerUrl(fallbackInput.value.trim());
        });
    }
});

document.addEventListener("DOMContentLoaded", function () {
    const gpuModelSelect = document.getElementById("gpu-model");
    const addonCheckboxes = document.querySelectorAll('input[name="addon"]');
    const quoteSummary = document.getElementById("quote-summary");
    const emailQuoteBtn = document.querySelector(".email-quote");

    function calculateTotal() {
        let total = 0;
        // Get GPU base price from the selected option
        const selectedOption = gpuModelSelect.options[gpuModelSelect.selectedIndex];
        if (selectedOption && selectedOption.getAttribute("data-price")) {
            total += parseFloat(selectedOption.getAttribute("data-price"));
        }
        // Add prices for each selected add-on
        addonCheckboxes.forEach(checkbox => {
            if (checkbox.checked) {
                total += parseFloat(checkbox.getAttribute("data-price"));
            }
        });
        // Update the total in the UI
        quoteSummary.textContent = "Your Estimated Total: $" + total.toFixed(2);
        return total;
    }

    // Attach event listeners for changes to update the quote
    gpuModelSelect.addEventListener("change", calculateTotal);
    addonCheckboxes.forEach(checkbox => {
        checkbox.addEventListener("change", calculateTotal);
    });

    // Email Quote functionality
    emailQuoteBtn.addEventListener("click", async function () {
        const gpuId = gpuModelSelect.value;
        if (!gpuId) {
            alert("Please select a GPU model.");
            return;
        }
        const selectedAddons = [];
        addonCheckboxes.forEach(checkbox => {
            if (checkbox.checked) {
                selectedAddons.push(checkbox.value);
            }
        });
        const total = calculateTotal();

        // Prepare data to send
        const formData = new FormData();
        formData.append("gpu_id", gpuId);
        formData.append("total", total);
        formData.append("addons", JSON.stringify(selectedAddons));

        try {
            const response = await fetch("email_quote.php", {
                method: "POST",
                body: formData
            });
            const result = await response.json();
            if (result.success) {
                alert("Your quote has been emailed to you!");
            } else {
                alert("Error sending quote: " + result.message);
            }
        } catch (error) {
            alert("Network error: " + error);
        }
    });
});

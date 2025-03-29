document.addEventListener("DOMContentLoaded", function () {
    const gpuSelect = document.getElementById("gpu-model");
    const downPaymentInput = document.getElementById("down-payment");
    const termSelect = document.getElementById("term");
    const quoteSummary = document.getElementById("quote-summary");
    const emailQuoteBtn = document.getElementById("email-quote-btn");

    function calculateMonthlyPayment() {
        const selectedOption = gpuSelect.options[gpuSelect.selectedIndex];
        const basePrice = parseFloat(selectedOption.getAttribute("data-price")) || 0;
        const downPaymentPercent = parseFloat(downPaymentInput.value) || 0;
        const term = parseInt(termSelect.value) || 0;

        const downPaymentAmount = (downPaymentPercent / 100) * basePrice;
        const financeAmount = basePrice - downPaymentAmount;
        let monthlyPayment = 0;
        if (term > 0) {
            monthlyPayment = financeAmount / term;
        }
        quoteSummary.textContent = "Your Estimated Monthly Payment: $" + monthlyPayment.toFixed(2);
        return {
            basePrice,
            downPaymentPercent,
            term,
            monthlyPayment: monthlyPayment.toFixed(2)
        };
    }

    // Attach change events for live calculation
    gpuSelect.addEventListener("change", calculateMonthlyPayment);
    downPaymentInput.addEventListener("input", calculateMonthlyPayment);
    termSelect.addEventListener("change", calculateMonthlyPayment);

    // When the Email Me My Quote button is clicked, send AJAX request
    emailQuoteBtn.addEventListener("click", async function () {
        const quoteData = calculateMonthlyPayment();
        if (!gpuSelect.value || !termSelect.value) {
            alert("Please select a GPU model and financing term.");
            return;
        }
        // Prepare form data
        const formData = new FormData();
        formData.append("gpu_id", gpuSelect.value);
        formData.append("down_payment", downPaymentInput.value);
        formData.append("term", termSelect.value);
        formData.append("monthly_payment", quoteData.monthlyPayment);
        formData.append("base_price", quoteData.basePrice);

        try {
            const response = await fetch("email_financing_quote.php", {
                method: "POST",
                body: formData
            });
            const result = await response.json();
            if (result.success) {
                alert("Your financing quote has been emailed to you!");
            } else {
                alert("Error sending quote: " + result.message);
            }
        } catch (error) {
            alert("Network error: " + error);
        }
    });
});

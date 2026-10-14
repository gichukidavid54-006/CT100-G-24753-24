
document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("registrationForm");
    const errorMessages = document.getElementById("errorMessages");

    const fullName = document.getElementById("fullName");
    const email = document.getElementById("email");
    const phone = document.getElementById("phone");
    const admissionNumber = document.getElementById("admissionNumber");
    const yearOfStudy = document.getElementById("yearOfStudy");
    const category = document.getElementById("category");
    const days = document.getElementById("days");
    const workshop = document.getElementById("workshop");
    const workshopSection = document.getElementById("workshopSection");
    const catering = document.getElementById("catering");
    const meal = document.getElementById("meal");
    const mealSection = document.getElementById("mealSection");
    const packageMessage = document.getElementById("packageMessage");

    // Conference charges
    const REGISTRATION_FEE = 1000;
    const DAILY_ATTENDANCE_FEE = 500;
    const WORKSHOP_FEE = 750;
    const DAILY_LUNCH_FEE = 300;

    function formatKSh(amount) {
        return "KSh " + amount.toLocaleString("en-KE");
    }

    // TASK 3: Show or hide workshop options.
    function showWorkshop() {
        const workshopSelected = category.value === "workshop";

        workshopSection.hidden = !workshopSelected;
        workshop.disabled = !workshopSelected;
        workshop.required = workshopSelected;

        if (!workshopSelected) {
            workshop.value = "";
        }

        updatePackageMessage();
        calculateFee();
    }

    // TASK 3: Show or hide meal options.
    function showMealOptions() {
        const lunchSelected = catering.value === "yes";

        mealSection.hidden = !lunchSelected;
        meal.disabled = !lunchSelected;
        meal.required = lunchSelected;

        if (!lunchSelected) {
            meal.value = "";
        }

        calculateFee();
    }

    // TASK 3: Display the selected package.
    function updatePackageMessage() {
        if (category.value === "workshop") {
            packageMessage.textContent =
                "Selected package: Conference + Workshop.";
        } else if (category.value === "conference") {
            packageMessage.textContent =
                "Selected package: Conference Only.";
        } else {
            packageMessage.textContent =
                "Select your participation category to see your package.";
        }
    }

    // TASK 4: Calculate and display all fees.
    function calculateFee() {
        const selectedDays = Number(days.value) || 0;

        const attendanceCost =
            selectedDays * DAILY_ATTENDANCE_FEE;

        const workshopCost =
            category.value === "workshop" ? WORKSHOP_FEE : 0;

        const mealCost =
            catering.value === "yes"
                ? selectedDays * DAILY_LUNCH_FEE
                : 0;

        const total =
            REGISTRATION_FEE +
            attendanceCost +
            workshopCost +
            mealCost;

        document.getElementById("registrationFee").textContent =
            formatKSh(REGISTRATION_FEE);

        document.getElementById("attendanceFee").textContent =
            formatKSh(attendanceCost);

        document.getElementById("workshopFee").textContent =
            formatKSh(workshopCost);

        document.getElementById("mealFee").textContent =
            formatKSh(mealCost);

        document.getElementById("totalFee").textContent =
            formatKSh(total);

        document.getElementById("totalFeeInput").value = total;
    }

    // TASK 2: Validate the registration form.
    function validateForm() {
        const errors = [];

        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        const phonePattern = /^\d{10}$/;
        const numberOfDays = Number(days.value);

        if (fullName.value.trim() === "") {
            errors.push("Full name cannot be empty.");
        }

        if (admissionNumber.value.trim() === "") {
            errors.push("Admission / registration number cannot be empty.");
        }

        if (!emailPattern.test(email.value.trim())) {
            errors.push("Enter a valid email address.");
        }

        if (!phonePattern.test(phone.value.trim())) {
            errors.push("Phone number must contain exactly 10 digits.");
        }

        if (yearOfStudy.value === "") {
            errors.push("Please select your year of study.");
        }

        if (category.value === "") {
            errors.push("Please select a participation category.");
        }

        if (
            !Number.isInteger(numberOfDays) ||
            numberOfDays < 1 ||
            numberOfDays > 3
        ) {
            errors.push("Conference days must be between 1 and 3.");
        }

        if (category.value === "workshop" && workshop.value === "") {
            errors.push("Please select a workshop.");
        }

        if (catering.value === "yes" && meal.value === "") {
            errors.push("Please select a meal preference.");
        }

        if (errors.length > 0) {
            errorMessages.innerHTML =
                "<strong>Please correct the following:</strong><ul>" +
                errors.map(function (message) {
                    return "<li>" + message + "</li>";
                }).join("") +
                "</ul>";

            errorMessages.hidden = false;
            errorMessages.scrollIntoView({
                behavior: "smooth",
                block: "center"
            });

            return false;
        }

        errorMessages.hidden = true;
        errorMessages.textContent = "";
        return true;
    }

    // TASK 5: Demonstrate JavaScript events.

    // onchange / change event
    category.addEventListener("change", showWorkshop);
    catering.addEventListener("change", showMealOptions);
    days.addEventListener("change", calculateFee);

    // oninput / input event
    [fullName, email, phone, admissionNumber].forEach(function (field) {
        field.addEventListener("input", function () {
            if (!errorMessages.hidden) {
                errorMessages.hidden = true;
                errorMessages.textContent = "";
            }
        });
    });

    // onsubmit / submit event
    form.addEventListener("submit", function (event) {
        calculateFee();

        if (!validateForm()) {
            event.preventDefault();
        }
    });

    // Set the initial form state.
    showWorkshop();
    showMealOptions();
    calculateFee();
});

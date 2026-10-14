
<?php
// Task 6: PHP Form Processing

// 1. Ensure the form was submitted using POST.
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    exit("Please submit your registration through the registration form.");
}

// 2. Receive submitted form values using $_POST.
$fullName = trim($_POST["fullName"] ?? "");
$admissionNumber = trim($_POST["admissionNumber"] ?? "");
$email = trim($_POST["email"] ?? "");
$phone = trim($_POST["phone"] ?? "");
$yearOfStudy = trim($_POST["yearOfStudy"] ?? "");
$category = trim($_POST["category"] ?? "");
$days = filter_var($_POST["days"] ?? null, FILTER_VALIDATE_INT);
$workshop = trim($_POST["workshop"] ?? "");
$catering = trim($_POST["catering"] ?? "no");
$meal = trim($_POST["meal"] ?? "");

// 3. Perform server-side validation.
$errors = [];

if ($fullName === "") {
    $errors[] = "Full name is required.";
}

if ($admissionNumber === "") {
    $errors[] = "Admission number is required.";
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "A valid email address is required.";
}

if (!preg_match("/^[0-9]{10}$/", $phone)) {
    $errors[] = "Phone number must contain exactly 10 digits.";
}

$validYears = ["Year 1", "Year 2", "Year 3", "Year 4", "Other"];
if (!in_array($yearOfStudy, $validYears, true)) {
    $errors[] = "Please select a valid year of study.";
}

if (!in_array($category, ["conference", "workshop"], true)) {
    $errors[] = "Please select a valid participation category.";
}

if ($days === false || $days === null || $days < 1 || $days > 3) {
    $errors[] = "Conference days must be between 1 and 3.";
}

if ($category === "workshop" && $workshop === "") {
    $errors[] = "Please select a workshop.";
}

$validWorkshops = [
    "Web Development",
    "Cybersecurity",
    "Cloud Computing",
    "Artificial Intelligence"
];

if ($category === "workshop" &&
    !in_array($workshop, $validWorkshops, true)) {
    $errors[] = "Please select a valid workshop.";
}

if (!in_array($catering, ["yes", "no"], true)) {
    $errors[] = "Please select a valid catering option.";
}

if ($catering === "yes" &&
    !in_array($meal, ["Regular", "Vegetarian"], true)) {
    $errors[] = "Please select a valid meal preference.";
}

// Display errors and stop if validation fails.
if (!empty($errors)) {
    http_response_code(400);
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Registration Error</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <main class="container">
            <h1>Registration Unsuccessful</h1>
            <p>Please correct the following errors:</p>
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?= htmlspecialchars($error, ENT_QUOTES, "UTF-8") ?></li>
                <?php endforeach; ?>
            </ul>
            <p><a href="register.html">Return to the registration form</a></p>
        </main>
    </body>
    </html>
    <?php
    exit;
}

// 4. Independently calculate the total fee in PHP.
$registrationFee = 1000;
$attendanceFee = $days * 500;
$workshopFee = ($category === "workshop") ? 750 : 0;
$mealFee = ($catering === "yes") ? $days * 300 : 0;

$totalAmount = $registrationFee
             + $attendanceFee
             + $workshopFee
             + $mealFee;

// 5. Prepare display values.
$categoryName = ($category === "workshop")
              ? "Conference + Workshop"
              : "Conference Only";

$workshopName = ($category === "workshop")
               ? $workshop
               : "None";

$mealsName = ($catering === "yes")
            ? "Yes (" . $meal . ")"
            : "No";

function safeOutput($value) {
    return htmlspecialchars($value, ENT_QUOTES, "UTF-8");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Successful</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <main class="container">
        <h1 style="color: #15803d;">REGISTRATION SUCCESSFUL</h1>

        <p>Dear <strong><?= safeOutput($fullName) ?></strong>,
        your registration has been received successfully.</p>

        <h2>Participant Details</h2>

        <p><strong>Participant:</strong>
            <?= safeOutput($fullName) ?></p>

        <p><strong>Admission Number:</strong>
            <?= safeOutput($admissionNumber) ?></p>

        <p><strong>Email:</strong>
            <?= safeOutput($email) ?></p>

        <p><strong>Phone:</strong>
            <?= safeOutput($phone) ?></p>

        <p><strong>Year of Study:</strong>
            <?= safeOutput($yearOfStudy) ?></p>

        <p><strong>Participation Category:</strong>
            <?= safeOutput($categoryName) ?></p>

        <p><strong>Conference Days:</strong>
            <?= $days ?></p>

        <p><strong>Workshop:</strong>
            <?= safeOutput($workshopName) ?></p>

        <p><strong>Meals:</strong>
            <?= safeOutput($mealsName) ?></p>

        <h2>Fee Breakdown</h2>

        <p>Registration Fee: KSh <?= number_format($registrationFee) ?></p>
        <p>Attendance Fee: KSh <?= number_format($attendanceFee) ?></p>
        <p>Workshop Fee: KSh <?= number_format($workshopFee) ?></p>
        <p>Meal Fee: KSh <?= number_format($mealFee) ?></p>

        <h2 style="color: #1d4ed8;">
            TOTAL AMOUNT PAYABLE: KSh <?= number_format($totalAmount) ?>
        </h2>

        <p>
            Thank you for registering for the
            <strong>Student Technology and Innovation Conference.</strong>
        </p>

        <p><a href="index.html">Return to Home</a></p>
    </main>
</body>
</html>
</>


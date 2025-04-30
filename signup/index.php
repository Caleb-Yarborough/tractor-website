<!-- Caleb Yabrborough -->
<?php 
$current_page = 'signup/index.php'; 
require_once '../includes/secure_conn.php';
session_start();
require('../includes/header.php');
require_once('../includes/pdo_connect.php');

$password_mismatch = false; 
?>

<?php
if (isset($_POST['submit']) && $_POST['submit'] == "Send") {
    $missing = [];

    // fullName
    $full_name = trim($_POST['fullName']);
    if (empty($full_name))
        $missing['fullName'] = "A name must be given: ";

    // email
    $valid_email = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);
    if (empty($_POST['email']))
        $missing['email'] = "A email must be given: ";
    elseif (!$valid_email)
        $missing['email'] = "Please enter a valid email address: ";
    else
        $email = $valid_email;

    // password
    $password = trim($_POST['password']);
    if (empty($password))
        $missing['password'] = "A password must be given: ";

    // confirmPassword
    $confirm_password = trim($_POST['confirmPassword']);
    if (empty($confirm_password))
        $missing['confirmPassword'] = "Password confirmation must be given: ";

    // make sure passwords match
    if (!empty($password) && !empty($confirm_password)) {
        if ($password !== $confirm_password) {
            $password_mismatch = true;
        }
    }

    // referral
    $referral = trim($_POST['referral']);
    if ($referral === 'select')
        $missing['referral'] = "How did you hear about us not selected";

    // If no errors, go to database
    if (empty($missing) && !$password_mismatch) {
        try {
            // Check if email already exists
            $sql = "SELECT * FROM TractorUsers WHERE email = :email";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            $numRows = $stmt->rowCount();
            if ($numRows >= 1) {
                $missing['email'] = "That email address is already registered.";
            } else {
                // Insert new user
                $sql2 = "INSERT INTO TractorUsers (full_name, email, password, referral) VALUES (?, ?, ?, ?)";
                $stmt2 = $pdo->prepare($sql2);
                $pw_hash = password_hash($password, PASSWORD_DEFAULT);
                $stmt2->bindParam(1, $full_name);
                $stmt2->bindParam(2, $email);
                $stmt2->bindParam(3, $pw_hash);
                $stmt2->bindParam(4, $referral);
                $stmt2->execute();
                $numRows = $stmt2->rowCount();
                
                // if no user created
                if ($numRows != 1) {
                    echo "<h2>We are unable to process your request at this time. Please try again later.</h2>";
                    include('../includes/footer.php');
                    exit;
                // if user created
                } else {
                    $_SESSION['full_name'] = $full_name;
                    echo "<h2>Sign-Up Confirmed!</h2>";
                    echo "<p>Thank you " . htmlspecialchars($full_name) . " for signing up!</p>";
                    echo "<p>You will receive weekly updates with the latest news.</p>";
                    include('../includes/footer.php'); 
                    exit;
                }
            }
        // if error
        } catch (PDOException $e) {
            echo $e->getMessage();
            include('../includes/footer.php');
            exit;
        }
    }
}
?>

<h2 class="invisible">Section heading</h2>

<!-- sign up form -->
<div class="form-box">
    <form class="form" name="signUpPage" id="signUpPage" method="post" action="index.php">
        <span class="title">Sign up</span>
        <span class="subtitle">Sign-Up with your email to receive a weekly Newsletter.</span>
        <div class="form-container">
            <!-- incorrect input error -->
            <?php if (isset($missing) || $password_mismatch) 
                echo '<h3 class="error">Please fix the item(s) indicated.</h3>';
            ?>
            <!-- name input -->
            <?php if (isset($missing['fullName'])) 
                echo '<span class="error">'.$missing['fullName'].'</span>'; 
            ?>
            <label for="fullName">Full Name</label>
            <input id="fullName" name="fullName" type="text" class="input" placeholder="Full Name" 
            <?php if (isset($full_name)) echo 'value="'.htmlspecialchars($full_name).'"'; ?> autofocus>
            <!-- email input -->
            <?php if (isset($missing['email'])) 
                echo '<span class="error">'.$missing['email'].'</span>'; 
            ?>
            <label for="email">Email</label>
            <input id="email" name="email" type="email" class="input" placeholder="Email" 
            <?php if (isset($email)) echo 'value="'.htmlspecialchars($email).'"'; ?>>
            <!-- passowrd input -->
            <?php if (isset($missing['password'])) 
                echo '<span class="error">'.$missing['password'].'</span>'; 
            ?>
            <label for="password">Password</label>
            <input id="password" name="password" type="password" class="input" placeholder="Password">
            <!-- match/confirm passowrd input -->
            <?php if (isset($missing['confirmPassword'])) 
                echo '<span class="error">'.$missing['confirmPassword'].'</span>'; 
            elseif ($password_mismatch)
                echo '<span class="error">Passwords do not match</span>';
            ?>
            <label for="confirmPassword">Confirm Password</label>
            <input id="confirmPassword" name="confirmPassword" type="password" class="input" placeholder="Confirm Password">
            <!-- How did you hear input -->
            <?php if (isset($missing['referral'])) 
                echo '<span class="error">'.$missing['referral'].'</span>'; 
            ?>
            <label for="referral">How did you hear about us?</label>
            <select id="referral" name="referral"> 
                <option value="select">Select one</option>
                <option value="social"
                <?php if(isset($referral) && $referral == "social") echo " selected"; ?>>Social Media</option>
                <option value="recommended"
                <?php if(isset($referral) && $referral == "recommended") echo " selected"; ?>>Recommended by a friend</option>
                <option value="search"
                <?php if(isset($referral) && $referral == "search") echo " selected"; ?>>Search engine</option>
            </select>
        </div>
        <!-- submit button -->
        <button type="submit" name="submit" value="Send">Sign up</button>
    </form>
</div>

<?php include('../includes/footer.php'); ?>
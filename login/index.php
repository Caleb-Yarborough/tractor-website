<!-- Caleb Yabrborough -->
<?php
require_once '../includes/secure_conn.php';
session_start();
$current_page = 'login/index.php';
require('../includes/header.php');
require_once('../includes/pdo_connect.php');

if (isset($_POST['send']) && $_POST['send'] == "Login") {
    $errors = [];

    // Email validation
    $valid_email = filter_var(trim($_POST['email'] ?? ''), FILTER_VALIDATE_EMAIL);
    if (empty($_POST['email']))
        $errors['email'] = 'Please enter an email address';
    elseif (!$valid_email)
        $errors['email'] = 'Please enter a valid email address';
    else
        $email = $valid_email;

    // Password validation
    $password = trim($_POST['password'] ?? '');
    if (empty($password))
        $errors['pw'] = "A password is required";

    // If no initial validation errors, check database
    if (empty($errors)) {
        try {
            // SQL Query for email in TractorUsers
            $sql = "SELECT * FROM TractorUsers WHERE email = :email";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            $num_rows = $stmt->rowCount();

            if ($num_rows == 0) {
                $errors['no_email'] = "That email doesn't exist";
            } else {
                // Email found, validate password
                $result = $stmt->fetch();
                $pw_hash = $result['password'];
                if (password_verify($password, $pw_hash)) {
                    // Successful login
                    $_SESSION['full_name'] = $result['full_name'];
                    $_SESSION['email'] = $email;
                    header('Location: ../loggedin/index.php');
                    exit;
                } else {
                    $errors['wrong_pw'] = "Incorrect password";
                }
            }
        } catch (PDOException $e) {
            echo $e->getMessage();
            include('../includes/footer.php');
            exit;
        }
    }
}
?>

<h2 class="invisible">Section heading</h2>

<!-- login form -->
<div class="login-form">
    <div class="form-box">
        <form class="form" name="loginPage" id="loginPage" method="post" action="index.php">
            <span class="title">Login</span>
            <span class="subtitle">Login with your email and password.</span>
            <div class="form-container">
                <!-- incorrect input error -->
                <?php if (!empty($errors)) 
                    echo '<h3 class="error">Please fix the item(s) indicated.</h3>';
                ?>
                <!-- email input -->
                <?php if (isset($errors['email'])) 
                    echo '<span class="error">'.$errors['email'].'</span>'; 
                if (isset($errors['no_email'])) 
                    echo '<span class="error">'.$errors['no_email'].'</span>'; 
                ?>
                <label for="email">Email</label>
                <input id="email" name="email" type="email" class="input" placeholder="Email" 
                <?php if (isset($email) && !isset($errors['no_email'])) 
                    echo 'value="'.htmlspecialchars($email).'"'; ?> autofocus>
                <!-- password input -->
                <?php if (isset($errors['pw'])) 
                    echo '<span class="error">'.$errors['pw'].'</span>'; 
                if (isset($errors['wrong_pw'])) 
                    echo '<span class="error">'.$errors['wrong_pw'].'</span>'; 
                ?>
                <label for="password">Password</label>
                <input id="password" name="password" type="password" class="input" placeholder="Password">

            </div>
            <!-- submit/login button -->
            <button type="submit" name="send" value="Login">Login</button>
        </form>
    </div>
</div>

<?php include('../includes/footer.php'); ?>
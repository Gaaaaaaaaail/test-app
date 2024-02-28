<?php
include 'functions.php';
$pdo = pdo_connect_mysql();
$msg = '';


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $id = isset($_POST['id']) && !empty($_POST['id']) && $_POST['id'] != 'auto' ? $_POST['id'] : NULL;
    $name = isset($_POST['name']) ? $_POST['name'] : '';
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $phone = isset($_POST['phone']) ? $_POST['phone'] : '';
    $address1 = isset($_POST['address1']) ? $_POST['address1'] : '';
    $address2 = isset($_POST['address2']) ? $_POST['address2'] : '';
    $city = isset($_POST['city']) ? $_POST['city'] : '';
    $state = isset($_POST['state']) ? $_POST['state'] : '';
    $zip = isset($_POST['zip']) ? $_POST['zip'] : '';
    $username = isset($_POST['username']) ? $_POST['username'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $confirm_password = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';

    $errors = [];

    
    $stmt = $pdo->prepare('SELECT * FROM contacts WHERE name = ?');
    $stmt->execute([$name]);
    $existingName = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($existingName) {
        $errors[] = 'Name already exists.';
    }

    $stmt = $pdo->prepare('SELECT * FROM contacts WHERE email = ?');
    $stmt->execute([$email]);
    $existingEmail = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($existingEmail) {
        $errors[] = 'Email already exists.';
    }

 
    $stmt = $pdo->prepare('SELECT * FROM contacts WHERE phone = ?');
    $stmt->execute([$phone]);
    $existingPhone = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($existingPhone) {
        $errors[] = 'Phone number already exists.';
    }

 
    $stmt = $pdo->prepare('SELECT * FROM contacts WHERE username = ?');
    $stmt->execute([$username]);
    $existingUsername = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($existingUsername) {
        $errors[] = 'Username already exists.';
    }

    if (empty($name)) {
        $errors[] = 'Name is required.';
    }
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Valid email address is required.';
    }
    if (empty($phone) || !preg_match('/^\d{11}$/', $phone)) {
        $errors[] = 'Phone number must be 11 digits.';
    }
    if (empty($address1)) {
        $errors[] = 'Address 1 is required.';
    }
    if (empty($city)) {
        $errors[] = 'City is required.';
    }
    if (empty($state)) {
        $errors[] = 'State/Province is required.';
    }
    if (empty($zip)) {
        $errors[] = 'Zip/Post Code is required.';
    }

    if (empty($username)) {
        $errors[] = 'Username is required.';
    }
  
    if (empty($password)) {
        $errors[] = 'Password is required.';
    }
    
    if (empty($confirm_password) || $password !== $confirm_password) {
        $errors[] = 'Passwords do not match.';
    }
     
    if (empty($errors)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        $stmt = $pdo->prepare('INSERT INTO contacts (id, name, email, phone, address1, address2, city, state, zip, username, password) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
        $stmt->execute([$id, $name, $email, $phone, $address1, $address2, $city, $state, $zip, $username, $hashed_password]);

        $msg = 'Thank you for registering, ' . $name . '!';
        

        $_POST = array();
    } else {
        $msg = implode('<br>', $errors);
    }
}
?>
<?=template_header('Create')?>

<div class="content update">
    <h2>Register Account</h2>
    <form action="create.php" method="post">
        <label for="name">Name</label>
        <input type="text" name="name" placeholder="Enter your name" id="name" value="<?= isset($_POST['name']) ? htmlspecialchars($_POST['name']) : '' ?>" required>
        <label for="email">Email</label>
        <input type="text" name="email" placeholder="Enter your email" id="email" value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>" required>
        <label for="phone">Phone</label>
        <input type="number" name="phone" maxlength="11" placeholder="Enter phone number" id="phone" value="<?= isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : '' ?>" required>
        <label for="address1">Address 1</label> 
        <input type="text" name="address1" placeholder="Enter Address Line 1" id="address1" value="<?= isset($_POST['address1']) ? htmlspecialchars($_POST['address2']) : '' ?>" required>
        <label for="address2">Address 2</label>
        <input type="text" name="address2" placeholder="Enter Address Line 2" id="address2" value="<?= isset($_POST['address2']) ? htmlspecialchars($_POST['address2']) : '' ?>" required>
        <label for="city">City</label>
        <input type="text" name="city" placeholder="Enter City" id="city" value="<?= isset($_POST['city']) ? htmlspecialchars($_POST['city']) : '' ?>" required>
        <label for="state">State/Province</label>
        <input type="text" name="state" placeholder="Enter State/Province" id="state" value="<?= isset($_POST['state']) ? htmlspecialchars($_POST['state']) : '' ?>" required>
        <label for="zip">Zip/Post Code</label>
        <input type="number" name="zip" maxlength="5" placeholder="Enter Zip/Post Code" id="zip" value="<?= isset($_POST['zip']) ? htmlspecialchars($_POST['zip']) : '' ?>" required>
        <label for="username">Username</label>
        <input type="text" name="username" placeholder="Enter your username" id="username" value="<?= isset($_POST['username']) ? htmlspecialchars($_POST['username']) : '' ?>" required>
        <label for="password">Password</label>
        <input type="password" name="password" placeholder="Enter your password" id="password" required>
        <label for="confirm_password">Confirm Password</label>
        <input type="password" name="confirm_password" placeholder="Confirm your password" id="confirm_password" required>
        <input type="submit" value="Register">
    </form>
    <?php if ($msg): ?>
    <p><?=$msg?></p>
    <?php endif; ?>
</div>

<?=template_footer()?>
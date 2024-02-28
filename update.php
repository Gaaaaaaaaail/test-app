<?php
include 'functions.php';
$pdo = pdo_connect_mysql();
$msg = '';

if (isset($_GET['id'])) {
    if (!empty($_POST)) {
        // This part is similar to the create.php, but instead we update a record and not insert
        $id = isset($_POST['id']) ? $_POST['id'] : NULL;
        $name = isset($_POST['name']) ? $_POST['name'] : '';
        $email = isset($_POST['email']) ? $_POST['email'] : '';
        $phone = isset($_POST['phone']) ? $_POST['phone'] : '';
        $address1 = isset($_POST['address1']) ? $_POST['address1'] : '';
        $address2 = isset($_POST['address2']) ? $_POST['address2'] : '';
        $city = isset($_POST['city']) ? $_POST['city'] : '';
        $state = isset($_POST['state']) ? $_POST['state'] : '';
        $zip = isset($_POST['zip']) ? $_POST['zip'] : '';

        
        // Update the record
        $stmt = $pdo->prepare('UPDATE contacts SET id = ?, name = ?, email = ?, phone = ?, address1 = ?, address2 = ?, city = ?, state = ?, zip = ?, WHERE id = ?');
        $stmt->execute([$id, $name, $email, $phone, $address1, $address2, $city, $state, $zip, $_GET['id']]);
        $msg = 'Updated Successfully!';
    }
    
    // Get the contact from the contacts table
    $stmt = $pdo->prepare('SELECT * FROM contacts WHERE id = ?');
    $stmt->execute([$_GET['id']]);
    $contact = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$contact) {
        exit('Contact doesn\'t exist with that ID!');
    }
} else {
    exit('No ID specified!');
}
?>

<?=template_header('Update')?>

<div class="content update">
    <h2>Update Contact #<?=$contact['id']?></h2>
    <form action="update.php?id=<?=$contact['id']?>" method="post">

        <label for="name">Name</label>
        <input type="text" name="name" placeholder="John Doe" value="<?=$contact['name']?>" id="name">
     
    
        <label for="email">Email</label>
        <input type="text" name="email" placeholder="johndoe@example.com" value="<?=$contact['email']?>" id="email">
        <label for="phone">Phone</label>
        <input type="text" name="phone" placeholder="2025550143" value="<?=$contact['phone']?>" id="phone">

        <label for="address1">Address 1</label>
        <input type="text" name="address1" placeholder="Enter Address 1" value="<?=$contact['address1']?>" id="address1">
        <label for="address2">Address 2</label>
        <input type="text" name="address2" placeholder="Enter Address 2" value="<?=$contact['address2']?>" id="address2">
        <label for="city">City</label>
        <input type="text" name="city" placeholder="Enter City" value="<?=$contact['city']?>" id="city">
        <label for="state">State/Province</label>
        <input type="text" name="state" placeholder="Enter State/Province" value="<?=$contact['state']?>" id="state">
        <label for="zip">Zip/Post Code</label>
        <input type="text" name="zip" placeholder="Enter Zip/Post Code" value="<?=$contact['zip']?>" id="zip">
        <input type="submit" value="Update">
    </form>
    <?php if ($msg): ?>
    <p><?=$msg?></p>
    <?php endif; ?>
</div>

<?=template_footer()?>
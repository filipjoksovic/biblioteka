<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Korisnici</title>
    <?php include './components/scripts.php'; ?>
</head>

<body>
    <?php include './components/header.php'; ?>
    <?php
    include '../controllers/UserController.php';
    $users = UserController::getStudents();
    for ($i = count($users) - 1; $i >= 0; $i--) {
        if ($users[$i]['role_id'] != 1) {
            unset($users[$i]);
        }
    }
    ?>

    <div class="container mt-3">
        <h3>Korisnici</h3>
        <table class="table table-striped">
            <thead>
                <th>ID</th>
                <th>Ime</th>
                <th>Prezime</th>
                <th>Email</th>
                <th>Odobri</th>
                <th>Dosije rezervacija</th>
            </thead>
            <tbody>
                <?php foreach ($users as $user) { ?>
                <tr>
                    <td><?php echo $user['id']; ?></td>
                    <td><?php echo $user['fname']; ?></td>
                    <td><?php echo $user['lname']; ?></td>
                    <td><?php echo $user['email']; ?></td>
                    <td>
                        <?php if($user["allowed"] == 0):?>
                        <a href="../controllers/UserController.php?allowUser=1&id=<?php echo $user['id']; ?>">Odobri</a>
                    </td>
                    <?php else:?>
                    Odobren
                    <?php endif;?>
                    <td><a href="./reservationList.php?id=<?php echo $user['id']; ?>">Dosije rezervacija</a></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>

</html>

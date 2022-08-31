<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bibliotekar</title>
    <?php include './components/scripts.php'; ?>
</head>

<body>

    <?php include './components/header.php'; ?>

    <?php if ($_SESSION['role_id'] != 2) {
        header('location:./home.php');
        return;
    }
    ?>
    <div class="container mt-3">
        <h1>Bibliotekar</h1>
        <div class="container-fluid d-flex w-100 justify-content-around mt-5">
            <a href="./books.php" class="action">
                <i class='bx bx-book-open action-icon'></i>
                <h4>Knjige</h4>
            </a>
            <a href="./reservations.php" class="action">
                <i class='bx bxs-bookmark action-icon'></i>
                <h4>Rezervacije</h4>
            </a>
            <a href="./users.php" class="action">
                <i class='bx bxs-user action-icon'></i>
                <h4>Korisnici</h4>
            </a>
        </div>
    </div>
</body>

</html>
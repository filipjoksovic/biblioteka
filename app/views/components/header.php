<?php require '../controllers/SessionController.php'; ?>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Biblioteka</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <?php if ($_SESSION["role_id"] == 1) : ?>
                    <a class="nav-link active" aria-current="page" href="/">Pocetna</a>


                    <?php elseif ($_SESSION["role_id"] == 2) : ?>
                    <a class=" nav-link" href="../views/librarian.php">Pocetna</a>
                    <?php elseif ($_SESSION["role_id"] == 3) : ?>
                    <a class="nav-link" href="../views/administrator.php">Pocetna</a>
                    <?php endif; ?>

                </li>
                <?php if ($_SESSION["role_id"] == 1) : ?>
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page"
                        href="./reservationList.php?id=<?php echo $_SESSION["user_id"]; ?>">Rezervacije</a>
                </li>
                <?php endif; ?>
                <?php if ($_SESSION['user_id'] == null) : ?>
                <li class="nav-item">
                    <a class="nav-link" href="./login.php">Login</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="./register.php">Registracija</a>
                </li>
                <?php else : ?>
                <li class="nav-item">
                    <a class="nav-link" href="./logout.php"><?php echo $_SESSION['username']; ?> - Odjava</a>
                </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<div class="container-fluid mt-5">
    <?php if (isset($_SESSION["success"])) : ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Uspeh!</strong><?php echo $_SESSION['success']; ?>.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php $_SESSION['success'] = null; ?>
    <?php endif; ?>

    <?php if (isset($_SESSION["error"])) : ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Greska!</strong><?php echo $_SESSION['error']; ?>.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php $_SESSION['error'] = null; ?>
    <?php endif; ?>
</div>
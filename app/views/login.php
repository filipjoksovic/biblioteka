<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <?php include 'components/scripts.php'; ?>
</head>

<body>
    <?php include 'components/header.php'; ?>
    <div class="container mt-3">
        <h3>Prijavljivanje studenta</h3>
        <form action="../controllers/UserController.php" method="POST">
            <input type="hidden" name="login" value=1>
            <div class="row mt-3">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="email@email.com">
                    </div>
                </div>

            </div>
            <div class="row mt-3">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="password">Lozinka</label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Loznika">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <button class="btn d-block w-50 mx-auto btn-success p-3 mt-5">Prijavi se</button>
                </div>
            </div>
        </form>
    </div>
</body>

</html>
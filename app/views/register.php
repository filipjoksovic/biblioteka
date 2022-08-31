<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registracija</title>
    <?php include 'components/scripts.php'; ?>
</head>

<body>
    <?php include 'components/header.php'; ?>
    <div class="container mt-3">
        <h3>Registracija studenta</h3>
        <form action="../controllers/UserController.php" method="POST">
            <input type="hidden" name="register" value=1>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email"
                            placeholder="email@email.com">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="password">Lozinka</label>
                        <input type="password" class="form-control" id="password" name="password"
                            placeholder="Loznika">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <label for="fname">Ime</label>
                    <input type="text" class="form-control" id="fname" name="fname" placeholder="Ime">
                </div>
                <div class="col-md-6">
                    <label for="lname">Prezime</label>
                    <input type="text" class="form-control" id="lname" name="lname" placeholder="Prezime">
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <button class="btn d-block w-50 mx-auto btn-success mt-3">Registruj se</button>
                </div>
            </div>
        </form>
    </div>
</body>

</html>

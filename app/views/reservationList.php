<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pregled rezervacija</title>
    <?php require './components/scripts.php'; ?>
</head>

<body>
    <?php require './components/header.php'; ?>
    <?php require '../controllers/ReservationController.php'; ?>
    <?php
    if ($_SESSION['role_id'] != 1 && $_SESSION['role_id'] != 2) {
        header('Location: ../index.php');
        return;
    }
    $uid = $_GET['id'];
    $reservations = ReservationController::getReservationsForUser($uid);
    $user = UserController::getUser($uid);
    ?>
    <div class="container mt-3">
        <div class="row">
            <div class="col-md-4">
                <h3>Podaci o korisniku</h3>
                <div class="row">
                    <div class="col-md-6">
                        <label>Ime</label>
                        <input disabled type="text" class="form-control" value="<?= $user['fname'] ?>">
                    </div>
                    <div class="col-md-6">
                        <label>Prezime</label>
                        <input disabled type="text" class="form-control" value="<?= $user['lname'] ?>">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <label>Email</label>
                        <input disabled type="text" class="form-control" value="<?= $user['email'] ?>">
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <table class="table">
                    <thead>
                        <th>ID</th>
                        <th>Knjiga</th>
                        <th>Datum rezervacije</th>
                        <th>(Predvidjen) Datum vracanja</th>
                        <th>Status</th>
                    </thead>
                    <tbody>
                        <?php foreach ($reservations as $reservation) : ?>
                        <?php
                        $reservation_date = new DateTime($reservation['reservation_date']);
                        $reservation_date_1 = new DateTime($reservation['reservation_date']);
                        
                        $reservation_return = new DateTime($reservation['reservation_return']);
                        if ($reservation_date == null) {
                            $reservation_start = new DateTime($reservation['reservation_date']);
                            $reservation_return = $reservation_start->modify('+30 day');
                        }
                        $currentDate = $reservation_date_1->modify('+30 day');
                        ?>
                        <tr <?php if($currentDate < $reservation_return):?> style="background-color:red !important;" <?php endif;?>>
                            <td><?= $reservation['rid'] ?></td>
                            <td><?= $reservation['name'] ?></td>
                            <td><?= $reservation_date->format('d.m.y') ?></td>
                            <?php if ($reservation["reservation_return"] == null) : ?>
                            <td style="color:red;"><?= $reservation_return->format('d.m.y') ?></td>
                            <?php else : ?>
                            <td><?= $reservation_return->format('d.m.y') ?></td>
                            <?php endif; ?>
                            <td>
                                <?php if ($reservation["allowed"] == 1) : ?>
                                Odobrena
                                <?php else : ?>
                                Na cekanju
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
</body>

</html>

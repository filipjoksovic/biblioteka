<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Rezervacije</title>
    <?php include './components/scripts.php'; ?>
</head>

<body>
    <?php
    include './components/header.php';
    require '../controllers/ReservationController.php';

    $reservations = ReservationController::getReservations();
    $allowedReservations = [];
    $pendingReservations = [];
    $returnedReservations = [];

    foreach ($reservations as $reservation) {
        if ($reservation['allowed'] == 1 && $reservation['reservation_return'] == '') {
            $allowedReservations[] = $reservation;
        } elseif ($reservation['allowed'] == 0 && $reservation['reservation_return'] == '') {
            $pendingReservations[] = $reservation;
        }
        if ($reservation['reservation_return'] != '') {
            $returnedReservations[] = $reservation;
        }
    }

    ?>

    <div class="container mt-5">
        <h3>Rezervacije na cekanju</h3>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Knjiga</th>
                    <th>Ime</th>
                    <th>Prezime</th>
                    <th>Email</th>
                    <th>Datum rezervacije</th>
                    <th>Predvidjen datum vracanja</th>
                    <th>Status</th>
                    <th>Pregled rezervacija</th>
                </tr>
            </thead>
            <thead>
                <?php foreach ($pendingReservations as $reservation) : ?>
                <?php
                    $reservation_date = new DateTime($reservation['reservation_date']);
                    $reservation_return;
                    if ($reservation['reservation_return'] == null) {
                        $reservation_return = '-';
                    } else {
                        $reservation_return = new DateTime($reservation['reservation_return']);
                    }
                    $current_date = new DateTime();
                    $reservation_end = new DateTime($reservation['reservation_date']);
                    $reservation_end->add(new DateInterval('P30D'));
                    ?>
                <tr <?php if ($reservation_end < $current_date) : ?> style="background-color:darkred;color:white;"
                    <?php endif; ?>>

                    <td><?= $reservation['id'] ?></td>
                    <td><?= $reservation['name'] ?></td>
                    <td><?= $reservation['fname'] ?></td>
                    <td><?= $reservation['lname'] ?></td>
                    <td><?= $reservation['email'] ?></td>
                    <td><?php echo $reservation_date->format('d.m.y'); ?></td>
                    <td>
                        <?php if ($reservation_return != '-') : ?>
                        <?php echo $reservation_return->format('d.m.y'); ?>
                        <?php else : ?>
                        <?php echo $reservation_end->format('d.m.Y'); ?>
                        <?php endif; ?>

                    </td>
                    <td>
                        <?php if ($reservation['allowed'] == 1) : ?>
                        <span disable class="btn btn-success">Dozvoljeno</span>
                        <?php else : ?>
                        <?php if ($reservation_end > $current_date) : ?>
                        <a href="../controllers/ReservationController.php?allowReservation=1&rid=<?php echo $reservation['rid']; ?>"
                            class="btn btn-warning">Dozvoli</a>
                        <?php else : ?>
                        <a href="../controllers/ReservationController.php?deleteReservation=1&rid=<?php echo $reservation['rid']; ?>"
                            disable class="btn btn-danger">Istekla</a>
                        <?php endif; ?>
                        <?php endif; ?>

                    </td>
                    <td>
                        <a href="./reservationList.php?id=<?php echo $reservation['uid']; ?>"
                            class="btn btn-primary">Pregled</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </thead>
        </table>
    </div>

    <div class="container mt-5">
        <h3>Dozvoljene rezervacije</h3>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Knjiga</th>
                    <th>Ime</th>
                    <th>Prezime</th>
                    <th>Email</th>
                    <th>Datum rezervacije</th>
                    <th>Predvidjen datum vracanja</th>
                    <th>Status</th>
                    <th>Vrati</th>
                    <th>Pregled rezervacija</th>
                </tr>
            </thead>
            <thead>
                <?php foreach ($allowedReservations as $reservation) : ?>
                <?php
                    $reservation_date = new DateTime($reservation['reservation_date']);
                    $reservation_return = new DateTime($reservation['reservation_date']);
                    $reservation_return = $reservation_return->modify('+30 days');
                    $current_date = new DateTime();
                    ?>

                <tr <?php if ($reservation_return < $current_date) : ?> style="background-color:darkred;color:white;"
                    <?php endif; ?>>
                    <td><?= $reservation['id'] ?></td>
                    <td><?= $reservation['name'] ?></td>
                    <td><?= $reservation['fname'] ?></td>
                    <td><?= $reservation['lname'] ?></td>
                    <td><?= $reservation['email'] ?></td>

                    <td><?php echo $reservation_date->format('d.m.y'); ?></td>
                    <td><?php echo $reservation_return->format('d.m.y'); ?></td>
                    <td>
                        <?php if ($reservation['allowed'] == 1) : ?>
                        <span disabled class="btn btn-success">Dozvoljeno</span>
                        <?php else : ?>
                        <a href="../controllers/ReservationController.php?allowReservation=1&rid=<?php echo $reservation['rid']; ?>"
                            class="btn btn-warning">Dozvoli</a>
                        <?php endif; ?>

                    </td>

                    <td>
                        <a href="../controllers/ReservationController.php?returnReservation=1&rid=<?php echo $reservation['rid']; ?>"
                            class="btn btn-danger">Vrati </a>
                    </td>
                    <td>
                        <a href="./reservationList.php?id=<?php echo $reservation['uid']; ?>"
                            class="btn btn-primary">Pregled</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </thead>
        </table>
    </div>

    <div class="container mt-5">
        <h3>Vracene rezervacije</h3>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Knjiga</th>
                    <th>Ime</th>
                    <th>Prezime</th>
                    <th>Email</th>
                    <th>Datum rezervacije</th>
                    <th>Datum vracanja</th>
                    <th>Pregled rezervacija</th>
                </tr>
            </thead>
            <thead>
                <?php foreach ($returnedReservations as $reservation) : ?>
                <?php
                    $reservation_date = new DateTime($reservation['reservation_date']);
                    $reservation_return = new DateTime($reservation['reservation_return']);
                    $supposed_return = new DateTime($reservation['reservation_date']);
                    $supposed_return = $supposed_return->modify('+30 days');
                    $current_date = new DateTime();
                    ?>

                <tr <?php if ($reservation_return > $supposed_return) : ?>style="background-color:darkred;color:white;"
                    <?php endif; ?>>
                    <td><?= $reservation['id'] ?></td>
                    <td><?= $reservation['name'] ?></td>
                    <td><?= $reservation['fname'] ?></td>
                    <td><?= $reservation['lname'] ?></td>
                    <td><?= $reservation['email'] ?></td>

                    <td><?php echo $reservation_date->format('d.m.y'); ?></td>
                    <td><?php echo $reservation_return->format('d.m.y'); ?></td>
                    <td>
                        <a href="./reservationList.php?id=<?php echo $reservation['uid']; ?>"
                            class="btn btn-primary">Pregled</a>
                    </td>

                </tr>
                <?php endforeach; ?>
            </thead>
        </table>
    </div>
</body>

</html>
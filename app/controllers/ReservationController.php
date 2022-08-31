<?php

class ReservationController
{
    public static function getReservations()
    {
        $connection = mysqli_connect('localhost', 'root', '', 'biblioteka');

        $query = "SELECT reservations.id as 'rid', books.id as 'bid', users.id as 'uid',users.*, books.name, reservations.reservation_date, reservations.reservation_return, reservations.allowed FROM reservations INNER JOIN users on reservations.user_id = users.id INNER JOIN books on books.id = reservations.book_id";
        $result = $connection->query($query);
        if ($result->num_rows != 0) {
            $reservations = $result->fetch_all(MYSQLI_ASSOC);
            return $reservations;
        } else {
            return false;
        }
    }
    public static function getReservationsForUser($uid)
    {
        $connection = mysqli_connect('localhost', 'root', '', 'biblioteka');

        $query = "SELECT reservations.id as 'rid', books.id as 'bid', users.id as 'uid',users.*, books.name, reservations.reservation_date, reservations.reservation_return, reservations.allowed FROM reservations INNER JOIN users on reservations.user_id = users.id INNER JOIN books on books.id = reservations.book_id WHERE users.id = $uid";
        $result = $connection->query($query);
        if ($result->num_rows != 0) {
            $reservations = $result->fetch_all(MYSQLI_ASSOC);
            return $reservations;
        } else {
            return false;
        }
    }
}
require "BookController.php";
require "UserController.php";
require "SessionController.php";
require "DatabaseController.php";
if (isset($_POST) && count($_POST) > 0) {
    if (isset($_POST["create_reservation"])) {
        $book_id = $_POST["bid"];
        $user_id = $_SESSION["user_id"];
        $reservation_date = $_POST["reservation_date"];


        if ($user_id == null) {
            $_SESSION["error"] = "Morate biti ulogovani kako biste mogli rezervisati knjigu.";
        } else {
            $query = "SELECT * FROM reservations WHERE user_id = $user_id AND book_id = $book_id AND reservation_return is NULL";
            $result = $connection->query($query);
            if ($result->num_rows != 0) {
                $_SESSION["error"] = "Vec ste rezervisali ovu knjigu";
                header("Location:../views/home.php");
                return;
            }

            $query = "SELECT stock FROM books WHERE id = $book_id";
            $result = $connection->query($query);
            $row = $result->fetch_assoc();
            $stock = $row["stock"];
            if ($stock >= 1) {
                $query = "UPDATE books SET stock = stock - 1 WHERE id = $book_id";
                $result = $connection->query($query);
                $query = "INSERT INTO reservations(user_id,book_id,reservation_date) VALUES($user_id,$book_id,'$reservation_date')";
                if ($connection->query($query) === TRUE) {
                    $_SESSION["success"] = "Uspesno kreirana rezervacija";
                } else {
                    echo $query;
                    return;
                    $_SESSION["error"] = "Doslo je do greske prilikom kreiranja rezervacije. Tekst greske: " . $connection->error;
                }
                header("Location: ../../index.php");
            } else {
                $_SESSION["error"] = "Nema vise knjiga na stanju.";
            }
        }
    }
} else if (isset($_GET) && count($_GET) > 0) {
    if (isset($_GET["allowReservation"])) {
        $reservation_id = $_GET["rid"];
        $query = "UPDATE reservations SET allowed = 1 WHERE id = $reservation_id";
        if ($connection->query($query) === TRUE) {
            $_SESSION["success"] = "Uspesno odobrena rezervacija";
        } else {
            $_SESSION["error"] = "Doslo je do greske prilikom odobravanja rezervacije. Tekst greske: " . $connection->error;
        }
        header("Location: $_SERVER[HTTP_REFERER]");
    } elseif (isset($_GET["returnReservation"])) {
        $reservation_id = $_GET["rid"];
        $return_date = date('Y-m-d');
        $query = "UPDATE reservations SET reservation_return = '{$return_date}' WHERE id = $reservation_id";
        if ($connection->query($query) === TRUE) {
            $query = "SELECT book_id FROM reservations WHERE id = $reservation_id";
            $result = $connection->query($query);
            $row = $result->fetch_assoc();
            $book_id = $row["book_id"];
            $query = "UPDATE books SET stock = stock + 1 WHERE id = $book_id";
            if ($connection->query($query) === TRUE) {
                $_SESSION["success"] = "Uspesno vracena rezervacija";
            } else {
                $_SESSION["error"] = "Doslo je do greske prilikom vracanja rezervacije. Tekst greske: " . $connection->error;
            }
        } else {
            $_SESSION["error"] = "Doslo je do greske prilikom vracanja rezervacije. Tekst greske: " . $connection->error;
        }
        header("Location: $_SERVER[HTTP_REFERER]");
    } elseif (isset($_GET["deleteReservation"])) {
        $reservation_id = $_GET["rid"];
        $query = "DELETE FROM reservations WHERE id = $reservation_id";
        if ($connection->query($query) === TRUE) {
            $_SESSION["success"] = "Uspesno obrisana rezervacija";
        } else {
            $_SESSION["error"] = "Doslo je do greske prilikom brisanja rezervacije. Tekst greske: " . $connection->error;
        }
        header("Location: $_SERVER[HTTP_REFERER]");
    }
}
<?php
require 'SessionController.php';
require 'DatabaseController.php';

class UserController
{
    public static function getLibrarians()
    {
        require 'DatabaseController.php';

        $query = "SELECT * FROM users WHERE role_id = 2";
        $result = $connection->query($query);
        $librarians = [];
        while ($row = $result->fetch_assoc()) {
            $librarians[] = $row;
        }
        return $librarians;
    }
    public static function getUser($uid)
    {
        $connection = mysqli_connect('localhost', 'root', '', 'biblioteka');
        $query = "SELECT * FROM users WHERE id = $uid";
        $result = $connection->query($query);
        if ($result->num_rows != 0) {
            $user = $result->fetch_assoc();
            return $user;
        }
        return false;
    }
    public static function getStudents()
    {
        require 'DatabaseController.php';
        $query = "SELECT * FROM users WHERE role_id = 1";
        $result = $connection->query($query);
        $students = [];
        while ($row = $result->fetch_assoc()) {
            $students[] = $row;
        }
        return $students;
    }
    public static function getUsers()
    {
        require 'DatabaseController.php';
        $query = "SELECT * FROM users";
        $result = $connection->query($query);
        $users = [];
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }
        return $users;
    }
}

if (isset($_POST) && count($_POST) > 0) {
    if (isset($_POST["register"])) {
        $fname = $_POST["fname"];
        $lname = $_POST["lname"];
        $email = $_POST["email"];
        $password = md5($_POST["password"]);

        if ($fname == "" || $lname == "" || $email == "" || $_POST["password"] == "") {
            $_SESSION["error"] = "Sva polja su obavezna";
            header("Location: ../views/register.php");
            return;
        }

        $query = "INSERT INTO users (fname,lname,email,password,role_id) VALUES('{$fname}','{$lname}','{$email}','{$password}',1)";
        if ($connection->query($query) === TRUE) {
            $_SESSION["success"] = "Uspesno registrovan nalog. Dobrodosli";
            $_SESSION["username"] = $fname . " " . $lname;
            $_SESSION["role_id"] = 1;
            $_SESSION["user_id"] = $connection->insert_id;
            header("Location:../views/home.php");
            return;
        }
    } else if (isset($_POST["login"])) {
        $email = $_POST['email'];
        $password = md5($_POST["password"]);

        if ($email == "" || $password == "") {
            $_SESSION["error"] = "Morate uneti sva polja";
            header("Location:../views/login.php");
            return;
        }

        $query = "SELECT * FROM users WHERE email = '{$email}' AND password = '{$password}' LIMIT 1";
        $result = $connection->query($query);

        if ($result->num_rows != 0) {
            $user = $result->fetch_assoc();
            $_SESSION["user_id"] = $user["id"];
            $_SESSION["role_id"] = $user["role_id"];
            $_SESSION["username"] = $user["fname"] . " " . $user["lname"];
            if ($user["role_id"] == 2) {
                header("Location:../views/librarian.php");
                return;
            } elseif ($user["role_id"] == 3) {
                header("Location:../views/administrator.php");
                return;
            } else {
                if ($user["allowed"] == 1) {
                    header("Location:../views/home.php");
                    return;
                } else {
                    $_SESSION["error"] = "Vas nalog jos nije odobren od strane administratora";
                    header("Location:../views/login.php");
                    return;
                }
            }
            $_SESSION["success"] = "Uspesno logovanje. Dobrodosli!";
        } else {
            $_SESSION["error"] = "Ne postoji korisnik sa unetim podacima. Proverite podatke i pokusajte ponovo.";
            header("Location:../views/login.php");
        }
    } else if (isset($_POST['add_librarian'])) {
        $fname = $_POST["fname"];
        $lname = $_POST["lname"];
        $email = $_POST["email"];
        $password = md5($_POST["password"]);
        $query = "INSERT INTO users (fname,lname,email,password,role_id) VALUES('{$fname}','{$lname}','{$email}','{$password}',2)";
        if ($connection->query($query) === TRUE) {
            $_SESSION["success"] = "Uspesno registrovan bibliotekar";
            header("Location:" . $_SERVER["HTTP_REFERER"]);
            return;
        } else {
            $_SESSION['error'] = "Doslo je do greske prilikom registrovanja bibliotekara.";
            header("Location:" . $_SERVER["HTTP_REFERER"]);
            return;
        }
    } else if (isset($_POST['add_librarian'])) {
        $email = $_POST['email'];
        $password = md5($_POST["password"]);

        $query = "SELECT * FROM users WHERE email = '{$email}' AND password = '{$password}' LIMIT 1";
        $result = $connection->query($query);
        if ($result) {
            $user = $result->fetch_assoc();
            $_SESSION["success"] = "Uspesno logovanje. Dobrodosli!";
            $_SESSION["user_id"] = $user["id"];
            $_SESSION["role_id"] = $user["role_id"];
            $_SESSION["username"] = $user["fname"] . " " . $user["lname"];
            header("Location:../views/home.php");
        } else {
            $_SESSION["error"] = "Ne postoji korisnik sa unetim podacima. Proverite podatke i pokusajte ponovo.";
            header("Location:../views/login.php");
        }
    } elseif (isset($_POST["delete_librarian"])) {
        $id = $_POST["librarian_id"];
        $query = "DELETE FROM users WHERE id = {$id}";
        if ($connection->query($query) === TRUE) {
            $_SESSION["success"] = "Uspesno obrisan bibliotekar";
            header("Location:" . $_SERVER["HTTP_REFERER"]);
            return;
        } else {
            $_SESSION["error"] = "Doslo je do greske prilikom brisanja bibliotekara. Greska: " . $connection->error;
            header("Location:" . $_SERVER["HTTP_REFERER"]);
            return;
        }
    } elseif (isset($_POST["editUser"])) {
        if ($_SESSION["role_id"] != 3) {
            $_SESSION["error"] = "Nemate pravo da menjate podatke o korisniku";
            header("Location:" . $_SERVER["HTTP_REFERER"]);
            return;
        }
        $id = $_POST["user_id"];
        $fname = $_POST["fname"];
        $lname = $_POST["lname"];
        $email = $_POST["email"];
        if ($_POST["password"] != "") {
            $password = md5($_POST["password"]);
            $query = "UPDATE users SET fname = '{$fname}', lname = '{$lname}', email = '{$email}', password = '{$password}' WHERE id = {$id}";
        } else {
            $query = "UPDATE users SET fname = '{$fname}', lname = '{$lname}', email = '{$email}' WHERE id = {$id}";
        }
        if ($connection->query($query) === TRUE) {
            $_SESSION["success"] = "Uspesno izmenjen korisnik";
            header("Location:" . $_SERVER["HTTP_REFERER"]);
            return;
        }
    } elseif (isset($_POST["deleteUser"])) {
        if ($_SESSION["role_id"] != 3) {
            $_SESSION["error"] = "Nemate pravo da brisete korisnike";
            header("Location:" . $_SERVER["HTTP_REFERER"]);
            return;
        }
        $id = $_POST["user_id"];
        $query = "DELETE FROM users WHERE id = {$id}";
        if ($connection->query($query) === TRUE) {
            $_SESSION["success"] = "Uspesno obrisan korisnik";
            header("Location:" . $_SERVER["HTTP_REFERER"]);
            return;
        } else {
            $_SESSION["error"] = "Doslo je do greske prilikom brisanja korisnika. Greska: " . $connection->error;
            header("Location:" . $_SERVER["HTTP_REFERER"]);
            return;
        }
    }
} elseif (isset($_GET) && count($_GET) > 0) {
    if (isset($_GET["delete_user"])) {
        $id = $_GET["delete_user"];
        $query = "DELETE FROM users WHERE id = {$id}";
        if ($connection->query($query) === TRUE) {
            $_SESSION["success"] = "Uspesno obrisan korisnik";
            header("Location:" . $_SERVER["HTTP_REFERER"]);
            return;
        } else {
            $_SESSION["error"] = "Doslo je do greske prilikom brisanja korisnika.";
            header("Location:" . $_SERVER["HTTP_REFERER"]);
            return;
        }
    } elseif (isset($_GET["allowUser"])) {
        $id = $_GET["id"];
        $query = "UPDATE users SET allowed = 1 WHERE id = {$id}";
        if ($connection->query($query) === TRUE) {
            $_SESSION["success"] = "Uspesno dozvoljen korisnik";
            header("Location:" . $_SERVER["HTTP_REFERER"]);
            return;
        }
    } elseif (isset($_GET["get_user_data"])) {
        $id = $_GET["get_user_data"];
        $query = "SELECT * FROM users WHERE id = {$id}";
        $result = $connection->query($query);
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            echo json_encode($user);
        }
    }
}
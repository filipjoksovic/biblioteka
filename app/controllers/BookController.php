<?php

class BookController
{
    public static function getBooks()
    {
        $connection = mysqli_connect('localhost', 'root', '', 'biblioteka');
        $query = "SELECT books.id as 'bid',book_types.name as 'tname',books.*, book_details.* FROM books INNER JOIN book_types on book_types.id = books.type_id INNER JOIN book_details ON books.id = book_details.book_id";
        $result = $connection->query($query);
        if ($result->num_rows != 0) {
            $books = array();
            while ($row = $result->fetch_assoc()) {
                $query = "SELECT * FROM book_authors WHERE book_id = {$row['bid']}";
                $authors = $connection->query($query);
                $row['authors'] = $authors->fetch_all(MYSQLI_ASSOC);
                $query = "SELECT * FROM book_mentors WHERE book_id = {$row['bid']}";
                $mentors = $connection->query($query);
                $row['mentors'] = $mentors->fetch_all(MYSQLI_ASSOC);
                $books[] = $row;
            }
            return $books;
        }
    }
    public static function getBook($id)
    {
        $connection = mysqli_connect('localhost', 'root', '', 'biblioteka');
        $query = "SELECT books.id as 'bid',book_types.name as 'tname',books.*, book_details.* FROM books INNER JOIN book_types on book_types.id = books.type_id INNER JOIN book_details ON books.id = book_details.book_id WHERE books.id = $id";
        $result = $connection->query($query);
        if ($result->num_rows != 0) {
            $book = $result->fetch_assoc();
            $query = "SELECT * FROM book_authors WHERE book_id = {$book['bid']}";
            $authors = $connection->query($query);
            $book['authors'] = $authors->fetch_all(MYSQLI_ASSOC);
            return $book;
        }
    }
}
require 'DatabaseController.php';
require 'SessionController.php';
if (isset($_POST) && count($_POST) > 0) {

    if (isset($_POST["add_book"])) {
        $name = $_POST["name"];
        $isbn = $_POST["isbn"];
        $publisher = $_POST["publisher"];
        $publishing_year = $_POST["publishing_year"];
        $description = $_POST["description"];
        $stock = $_POST["stock"];
        $book_type_id = $_POST["type_id"];
        $authors = $_POST["authors"];
        $mentors = $_POST["mentors"];
        $target_dir = "../uploads/";
        $target_file = $target_dir . basename($_FILES["preview"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if file already exists
        if (file_exists($target_file)) {
            echo "Sorry, file already exists.";
            return;
            $uploadOk = 0;
        }

        // Check file size
        if ($_FILES["preview"]["size"] > 5000000) {
            echo "Sorry, your file is too large.";
            return;
            $uploadOk = 0;
        }

        // Allow certain file formats
        if (
            $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
            && $imageFileType != "gif" && $imageFileType != "pdf"
        ) {
            echo "Mogu se postavljati samo slike ili pdf fajlovi kao kratki pregledi";
            $uploadOk = 0;
            return;
        }

        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            header("Location: {$_SERVER["HTTP_REFERER"]}");
            // if everything is ok, try to upload file
            return;
        } else {
            if (move_uploaded_file($_FILES["preview"]["tmp_name"], $target_file)) {
                $_SESSION["success"] =  "The file " . htmlspecialchars(basename($_FILES["preview"]["name"])) . " has been uploaded.";
            } else {
                echo  "Sorry, there was an error uploading your file.";
                return;
            }
        }
        $query = "INSERT INTO books (name, isbn, publisher, publishing_year, description, stock, type_id) VALUES ('$name', '$isbn', '$publisher', '$publishing_year', '$description', '$stock', '$book_type_id')";
        if ($connection->query($query) === TRUE) {
            $insert_id = $connection->insert_id;
            if ($book_type_id == 10) {
                $magazineChoice = $_POST["rent_type_id"];
                $query = "INSERT INTO book_details(book_id,book_preview_url,release_type) VALUES ($insert_id,'{$target_file}',$magazineChoice)";
            } else {
                $query = "INSERT INTO book_details(book_id,book_preview_url) VALUES ($insert_id,'{$target_file}')";
            }
            if ($connection->query($query) === TRUE) {
                $_SESSION["success"] = "Uspesno dodavanje detalja o knjizi";
                if ($book_type_id != 4) {
                    foreach ($authors as $author) {
                        $query = "INSERT INTO book_authors(book_id,author) VALUES ('$insert_id', '$author')";
                        if ($connection->query($query) === TRUE) {
                            $_SESSION["success"] = "Uspesno dodavanje autora knjige";
                        } else {
                            $_SESSION["error"] = "Greska pri dodavanju autora knjige";
                            header("Location: {$_SERVER["HTTP_REFERER"]}");
                            return;
                        }
                    }
                    if ($book_type_id != 1 && $book_type_id != 2 && $book_type_id != 3) {
                        foreach ($mentors as $mentor) {
                            $query = "INSERT INTO book_mentors(book_id,name) VALUES ('$insert_id', '$mentor')";
                            if ($connection->query($query) === TRUE) {
                                $_SESSION["success"] = "Uspesno dodavanje mentora knjige";
                            } else {
                                $_SESSION["error"] = "Greska pri dodavanju mentora knjige";
                                header("Location: {$_SERVER["HTTP_REFERER"]}");
                                return;
                            }
                        }
                    }
                } else {
                    $monography_authors = $_POST["monographies_authors"];
                    $monography_chapters = $_POST["monographies_chapters"];
                    for ($i = 0; $i < count($monography_authors); $i++) {
                        $query = "INSERT INTO book_authors(book_id,author,chapter_name) VALUES ('$insert_id', '$monography_authors[$i]', '$monography_chapters[$i]')";
                        if ($connection->query($query) === TRUE) {
                            $_SESSION["success"] = "Uspesno dodavanje autora knjige";
                        } else {
                            $_SESSION["error"] = "Greska pri dodavanju autora knjige. Greska: " . $connection->error;
                            header("Location: {$_SERVER["HTTP_REFERER"]}");
                            return;
                        }
                    }
                }
                header("Location: {$_SERVER["HTTP_REFERER"]}");
                return;
            } else {
                $_SESSION["error"] = "Greska pri dodavanju podataka o knjizi";
                header("Location: {$_SERVER["HTTP_REFERER"]}");
                return;
            }
        } else {
            echo 'Error inserting a book. Error: ' . $connection->error;
            return;
            $_SESSION["error"] = "Greska pri dodavanju knjige";
            header("Location: {$_SERVER["HTTP_REFERER"]}");
            return;
        }
    } elseif (isset($_POST["edit_book"])) {
        $id = $_POST["id"];
        $name = $_POST["name"];
        $isbn = $_POST["isbn"];
        $publisher = $_POST["publisher"];
        $publishing_year = $_POST["publishing_year"];
        $description = $_POST["description"];
        $stock = $_POST["stock"];
        $query = "UPDATE books SET name='$name', isbn='$isbn', publisher='$publisher', publishing_year='$publishing_year', description='$description', stock='$stock' WHERE id='$id'";
        if ($connection->query($query) === TRUE) {
            $_SESSION["success"] = "Uspesno izmenjen podatak o knjizi";
            header("Location: {$_SERVER["HTTP_REFERER"]}");
            return;
        } else {
            $_SESSION["error"] = "Greska pri izmeni podataka o knjizi";
            header("Location: {$_SERVER["HTTP_REFERER"]}");
            return;
        }
    } elseif (isset($_POST["rent_book"])) {
    }
} elseif (isset($_GET) && count($_GET) > 0) {
    if (isset($_GET["book"])) {
        $book_id = $_GET["book"];
        $book = BookController::getBook($book_id);
        echo json_encode($book);
        return;
    } elseif (isset($_GET["delete_book"])) {
        if ($_SESSION["role_id"] == 3) {

            $book_id = $_GET["delete_book"];
            $query = "DELETE FROM books WHERE id = $book_id";
            if ($connection->query($query) === TRUE) {
                $_SESSION["success"] = "Knjiga uspesno obrisana";
                header("Location: {$_SERVER['HTTP_REFERER']}");
                return;
            } else {
                $_SESSION["error"] = "Greska pri brisanju knjige";
                header("Location: {$_SERVER['HTTP_REFERER']}");
                return;
            }
        } else {
            $_SESSION["error"] = "Nemate pravo prisupa ovom delu aplikacije";
            header("Location: {$_SERVER['HTTP_REFERER']}");
            return;
        }
    }
}
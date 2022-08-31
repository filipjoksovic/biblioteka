<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Izmeni knjigu</title>
    <?php require './components/scripts.php'; ?>
</head>

<body>
    <?php require './components/header.php'; ?>
    <?php require '../controllers/BookController.php'; ?>
    <?php
    $id = $_GET['id'];
    $book = BookController::getBook($id);
    ?>
    <div class="container mt-3">
        <form action="../controllers/BookController.php" method="POST">
            <div class="row mt-3">
                <div class="col-md-6">
                    <label for="name">Naziv</label>
                    <input name="name" type="text" id="name" class="form-control"
                        value="<?php echo $book['name']; ?>">
                </div>
                <div class="col-md-6">
                    <label for="ISBN">ISBN broj</label>
                    <input id="ISBN" type="text" name="isbn" class="form-control"
                        value="<?php echo $book['isbn']; ?>">
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-md-6">
                    <label for="publisher">Izdavac</label>
                    <input id="publisher" type="text" name="publisher" class="form-control"
                        value="<?php echo $book['publisher']; ?>">
                </div>
                <div class="col-md-6">
                    <label for="publishing_year">Godina izdavanja</label>
                    <input id="publishing_year" type="number" name="publishing_year" class="form-control"
                        value="<?php echo $book['publishing_year']; ?>">
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-md-12">
                    <label for="description">Opis</label>
                    <textarea id="description" name="description" class="form-control"> <?php echo $book['description']; ?></textarea>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-md-12">
                    <label for="stock">Stanje</label>
                    <input id="stock" name="stock" class="form-control" type="number"
                        value="<?php echo $book['stock']; ?>">
                </div>

            </div>
            <input type="hidden" name="id" value="<?= $id ?>">
            <input type="hidden" name="edit_book" value="edit_book">
            <button type="submit" class="btn btn-primary  mt-5 d-block w-50 mx-auto">Izmeni</button>
        </form>
    </div>
</body>

</html>

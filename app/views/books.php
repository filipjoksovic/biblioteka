<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Knjige</title>
    <?php include './components/scripts.php'; ?>
</head>

<body>
    <?php include './components/header.php'; ?>
    <?php
    include '../controllers/BookController.php';
    $books = BookController::getBooks();
    ?>
    <?php
    $search = isset($_GET['search']) ? $_GET['search'] : '';

    if ($search != '') {
        for ($i = count($books) - 1; $i >= 0; $i--) {
            if (strpos($books[$i]['name'], $search) !== false) {
                continue;
            } elseif ($books[$i]['publishing_year'] == $search) {
                continue;
            } elseif (strpos($books[$i]['publisher'], $search) !== false) {
                continue;
            } elseif (strpos($books[$i]['isbn'], $search) !== false) {
                continue;
            }
            foreach ($books[$i]['authors'] as $author) {
                if (strpos($author['author'], $search) !== false) {
                    continue 2;
                }
            }
            foreach ($books[$i]['mentors'] as $mentor) {
                if (strpos($mentor['name'], $search) !== false) {
                    continue 2;
                }
            }
            unset($books[$i]);
        }
    }
    ?>
    <div class="container mt-3">
        <h2>Pretraga</h2>
        <form action="./books.php" method="GET">
            <input type="hidden" name="search" value=1>
            <div class="row">
                <div class="col-md-8">
                    <div class="form-group">
                        <label for="title">Naslov</label>
                        <input type="text" class="form-control" id="title" name="search" placeholder="Naslov"
                            value=<?php echo $search; ?>>
                    </div>
                </div>
                <div class="col-md-4">
                    <label class="invisible">tekst</label>
                    <button class="btn d-block w-75 mx-auto btn-success">Pretraga</button>
                </div>
            </div>
    </div>

    <div class="container mt-3">
        <h2>Prikaz knjiga</h2>
        <div class="row mt-3">
            <?php foreach ($books as $book) : ?>
            <div class="col-md-6 mt-3">
                <div class="card p-3 h-100  ">
                    <div class="card-title">
                        <h4><?php echo $book['name']; ?></h1>
                    </div>
                    <div class="card-body d-flex flex-column justify-content-around">
                        <div class="row">
                            <div class="col-md-6">
                                <p>ISBN: <?php echo $book['isbn']; ?></p>
                            </div>
                            <div class="col-md-6">
                                <p>Izdavac: <?php echo $book['publisher']; ?></p>
                            </div>
                        </div>
                        <div class="row">

                            <div class="col-md-6">
                                <p>Stanje: <?php echo $book['stock']; ?></p>
                            </div>
                            <div class="col-md-6">
                                <p>Tip: <?php echo $book['tname']; ?></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <span><?php if ($book['type_id'] == 4) : ?>Editori:<?php else : ?>
                                    Autori:<?php endif; ?>
                                    </p>
                                    <ul>
                                        <?php foreach ($book['authors'] as $author) : ?>
                                        <li> <?php echo $author['author']; ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                    </p>
                            </div>
                            <?php if (count($book["mentors"]) != 0) : ?>
                            <div class="col-md-6">
                                <p>Mentori:</p>
                                <ul>
                                    <?php foreach ($book['mentors'] as $mentor) : ?>
                                    <li> <?php echo $mentor['name']; ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                            <?php endif; ?>
                        </div>
                        <p class="card-text"><?php echo $book['description']; ?></p>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>

</html>
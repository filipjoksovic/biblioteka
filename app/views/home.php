<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pocetna - Biblioteka</title>
    <?php include './components/scripts.php'; ?>
</head>

<body>
    <?php include './components/header.php'; ?>
    <div class="container mt-3">
        <?php require '../controllers/BookController.php';
        $books = BookController::getBooks();
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

        <?php if ($_SESSION["role_id"] == 1) : ?>
        <form action="./home.php" method="get">
            <div class="row align-items-end">
                <div class="col-md-10">
                    <label for="search">Parametri za pretragu</label>
                    <input value="<?php echo $search; ?>" name="search" id="search" name="search" class="form-control"
                        placeholder="Autor, mentor, godina izdanja, izdavac, ISBN">
                </div>
                <div class="col-md-2 d-flex flex-grow-0 ">
                    <button class="btn btn-lg btn-success" type="submit">Pretrazi</button>
                </div>
            </div>
        </form>
        <?php endif; ?>

        <div class="row mt-3">
            <?php foreach ($books as $book) : ?>
            <div class="col-md-6 mt-3">
                <div class="card p-3 h-100 ">
                    <div class="card-title">
                        <h4><?php echo $book['name'] . ' - ' . $book['publishing_year']; ?></h1>
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
                                        <li> <?php echo $author['author']; ?> <?php if ($author["chapter_name"] != null) {
                                                                                            echo " - " .  $author["chapter_name"];
                                                                                        } ?></li>
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
                        <div class="d-flex w-100 justify-content-around align-items-centere">
                            <a href="<?php echo $book['book_preview_url'];
                                            ?>" target="_blank"
                                class="btn btn-primary mb-3 d-block w-50 h-100 mx-1 d-flex align-items-center justify-content-center">Pregled
                                prve
                                strane</a>
                            <?php if ($_SESSION["role_id"] == 1) : ?>

                            <?php if ($book["stock"] > 0) : ?>
                            <button data-bs-toggle="modal" data-bs-target="#reserveModal"
                                onclick="prepareReservation(<?php echo $book['bid']; ?>)" class=" btn
                            btn-warning d-block w-50 h-100 mx-1">Rezervisi</button>
                            <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!--Reserve Modal -->
    <div class="modal fade" id="reserveModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-lg modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Rezervisi knjigu</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="reserveForm" action="../controllers/ReservationController.php" method="POST">
                        <input type="hidden" name="create_reservation" value=1>
                        <div class="row mt-3">
                            <div class="col-md-12 mb-3">
                                <label>Naziv</label>
                                <input id="modalName" disabled class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label>ISBN</label>
                                <input id="modalISBN" disabled class="form-control"></input>
                            </div>
                            <div class="col-md-6">
                                <label>Godina izdavanja</label>
                                <input id="modalYear" disabled class="form-control"></input>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <label>Izdavac</label>
                                <input id="modalPublisher" disabled class="form-control"></input>
                            </div>
                            <div class="col-md-6">
                                <label>Dostupno stanje</label>
                                <input disabled id="modalStock" class="form-control"></input>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-12 mt-3">
                                <label>Tip knjige</label>
                                <input disabled id="modalType" class="form-control"></input>
                            </div>
                        </div>
                        <input disabled id="modalAuthors" class="form-control mt-3">
                        <textarea id="modalDescription" disabled class="form-control mt-3" class="card-text"></textarea>
                        <input type="hidden" id="modalBid" name="bid">
                        <div class="row">
                            <div class="col-md-12">
                                <label>Datum rezervacije</label>
                                <input type="date" id="reservation_date" name="reservation_date" class="form-control">
                                <span id="returnDate">Datum vracanja: </span>
                            </div>

                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-lg" data-bs-dismiss="modal">Otkazi</button>
                    <button type="button" class="btn btn-primary btn-lg" onclick="reserveBook()">Rezervisi
                        knjigu</button>
                </div>
            </div>
        </div>
    </div>


</body>
<script>
function prepareReservation(id) {
    $.get("../controllers/BookController.php?book=" + id, function(data) {
        let parsed = JSON.parse(data)
        console.log(parsed)
        $("#modalName").val(parsed.name)
        $("#modalISBN").val(parsed.isbn)
        $("#modalYear").val(parsed.publishing_year)
        $("#modalPublisher").val(parsed.publisher)
        $("#modalStock").val(parsed.stock)
        let authors = ""
        for (let i = 0; i < parsed.authors.length; i++) {
            if (i < parsed.authors.length - 2) {
                authors += parsed.authors[i].author + ", "
            } else {
                authors += parsed.authors[i].author
            }
        }
        $("#modalAuthors").val(authors)
        $("#modalType").val(parsed.tname)
        $("#modalDescription").text(parsed.description)
        $("#modalBid").val(parsed.bid)

    })
}

function reserveBook() {
    $("#reserveForm").submit();
}
$("#reservation_date").change(function() {
    let date = new Date($(this).val())
    let today = new Date()
    if (date < today) {
        alert("Datum rezervacije ne moze biti u proslosti")
        $(this).val("")
    } else {
        //add 30days to date
        date.setDate(date.getDate() + 30)
        $("#returnDate").text("Datum vracanja: " + date.toLocaleDateString())
        // $("#returnDate").text("Datum vracanja: " + dd + "." + mm + "." + yyyy)
    }
})
</script>

</html>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrator</title>
    <?php include './components/scripts.php'; ?>
</head>

<body>
    <?php include './components/header.php'; ?>

    <?php
    
    if ($_SESSION['role_id'] != 3) {
        header('location:./home.php');
        return;
    }
    
    require '../controllers/UserController.php';
    require '../controllers/BookController.php';
    require '../controllers/BookTypeController.php';
    $librarians = UserController::getLibrarians();
    $bookTypes = BookTypeController::getBookTypes();
    $books = BookController::getBooks();
    $users = UserController::getUsers();
    ?>

    <div class="container mt-3">
        <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active pill-nav" id="pills-home-tab" data-bs-toggle="pill"
                    data-bs-target="#pills-home" type="button" role="tab" aria-controls="pills-home"
                    aria-selected="true">Bibliotekari</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link pill-nav" id="pills-profile-tab" data-bs-toggle="pill"
                    data-bs-target="#pills-profile" type="button" role="tab" aria-controls="pills-profile"
                    aria-selected="false">Knjige</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link pill-nav" id="pills-profile-tab" data-bs-toggle="pill"
                    data-bs-target="#pills-users" type="button" role="tab" aria-controls="pills-profile"
                    aria-selected="false">Korisnici</button>
            </li>

        </ul>
        <div class="tab-content" id="pills-tabContent">
            <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                <div class="container-fluid">
                    <h4>Dodaj bibliotekara</h4>
                    <form action="../controllers/UserController.php" method="POST">
                        <input type="hidden" name="add_librarian" value=1>
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control" id="email" name="email"
                                        placeholder="">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="password">Lozinka</label>
                                    <input type="password" class="form-control" id="password" name="password"
                                        placeholder="">
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="fname">Ime</label>
                                    <input type="text" class="form-control" id="fname" name="fname"
                                        placeholder="">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="lname">Prezime</label>
                                    <input type="text" class="form-control" id="lname" name="lname"
                                        placeholder="">
                                </div>
                            </div>
                        </div>
                        <button class="btn btn-success mt-3 d-block w-50 mx-auto">Dodaj</button>
                    </form>
                    <h4>Prikaz bibliotekara</h4>
                    <table class="table table-bordered">
                        <thead>
                            <th>Ime</th>
                            <th>Prezime</th>
                            <th>Email</th>
                            <th>Ukloni</th>
                        </thead>
                        <?php if (count($librarians) > 0) : ?>
                        <?php foreach ($librarians as $librarian) : ?>
                        <tr>
                            <td><?php echo $librarian['fname']; ?></td>
                            <td><?php echo $librarian['lname']; ?></td>
                            <td><?php echo $librarian['email']; ?></td>
                            <td>
                                <form action="../controllers/UserController.php" method="POST">
                                    <input type="hidden" name="delete_librarian" value="1">
                                    <input type="hidden" name="librarian_id" value="<?php echo $librarian['id']; ?>">
                                    <button type="submit" class="btn btn-danger">Ukloni</button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </table>
                </div>

            </div>
            <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">

                <h3 class="text-center">Knjige</h4>
                    <h4>Dodaj knjigu</h4>
                    <form method="POST" action="../controllers/BookController.php" enctype="multipart/form-data">
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <label for="name">Naziv</label>
                                <input name="name" type="text" id="name" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label for="ISBN">ISBN broj</label>
                                <input id="ISBN" type="text" name="isbn" class="form-control">
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <label for="publisher">Izdavac</label>
                                <input id="publisher" type="text" name="publisher" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label for="publishing_year">Godina izdavanja</label>
                                <input id="publishing_year" type="number" name="publishing_year"
                                    class="form-control">
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-12">
                                <label for="description">Opis</label>
                                <textarea id="description" name="description" class="form-control"></textarea>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <label for="stock">Stanje</label>
                                <input id="stock" name="stock" class="form-control" type="number">
                            </div>
                            <div class="col-md-6">
                                <label for="type">Tip knjige</label>
                                <select name="type_id" class="form-control" id="type">
                                    <option selected value="" disabled>Odaberi</option>
                                    <?php foreach ($bookTypes as $bookType) : ?>
                                    <option value="<?php echo $bookType['id']; ?>">
                                        <?php echo $bookType['name']; ?>
                                    </option>
                                    <?php endforeach; ?>

                                </select>
                            </div>
                        </div>
                        <div class="row mt-3 d-none" id="authors">
                            <label id="author">Autori</label>
                            <div id="authors-1"
                                class="author-input d-flex align-center justify-content-between w-100">
                                <input id="mainAuthor" name="authors[]" type="text" class="form-control w-75">
                                <button id="addAuthorBtn" class="btn btn-success ml-3" onclick="addAuthor()"
                                    type="button">
                                    <i class='bx bx-plus'></i>
                                </button>
                            </div>
                        </div>
                        <div class="row mt-3 d-none" id="mentors">
                            <label>Mentori</label>
                            <div id="mentors-1"
                                class="mentor-input d-flex align-center justify-content-between w-100">
                                <input id="mainMentor" name="mentors[]" type="text" class="form-control w-75">
                                <button id="addMentorBtn" class="btn btn-success ml-3" onclick="addMentor()"
                                    type="button">
                                    <i class='bx bx-plus'></i>
                                </button>
                            </div>
                        </div>
                        <div class="monography d-none" id="monographies">
                            <div id="monography-1" class="monography-input">
                                <div class="row mt-3">
                                    <div id="chapter_author" class="col-md-5">
                                        <label>Autor poglavlja</label>
                                        <input id="mainMonographyAuthor" name="monographies_authors[]" type="text"
                                            class="form-control w-100">
                                    </div>
                                    <div id="chapter_name" class="col-md-5">
                                        <label>Naziv poglavlja</label>
                                        <input id="mainMonographyChapter" name="monographies_chapters[]"
                                            type="text" class="form-control w-100">
                                    </div>
                                    <div class="col-md-2 d-flex align-items-end ">
                                        <button id="addMonographyBtn" class="btn btn-success ml-3"
                                            onclick="addMonography()" type="button">
                                            <i class='bx bx-plus'></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-3 mb-3 d-none" id="magazineChoice">
                            <div class="row">
                                <div class="col-md-12">
                                    <label>Način izdavanja</label>
                                    <select name="rent_type_id" class="form-control" id="rent_type">
                                        <option selected value="" disabled>Odaberi</option>
                                        <option value="Nedeljno">Nedeljno</option>
                                        <option value="Mesečno">Mesečno</option>
                                        <option value="Tromečno">Tromečno</option>
                                        <option value="Šestomesečno">Šestomesečno</option>
                                        <option value="Godišnje">Godišnje</option>
                                    </select>
                                </div>
                            </div>
                        </div>


                        <div class="row mt-3">
                            <div class="col-md-12">
                                <div class="mt-3">
                                    <label for="formFile" class="form-label">Kratak pregled knjige</label>
                                    <input class="form-control" type="file" id="preview" name="preview"
                                        accept="application/pdf,image/*">
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="add_book" value="1">
                        <button class="btn btn-success mt-3 d-block w-50 mx-auto btn-lg">Kreiraj knjigu</button>
                    </form>

                    <h4>Pregled knjiga</h1>
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <th>Naziv</th>
                                    <th>ISBN broj</th>
                                    <th>Izdavac</th>
                                    <th>Godina izdavanja</th>
                                    <th>Opis</th>
                                    <th>Stanje</th>
                                    <th>Tip knjige</th>
                                    <th>Autori</th>
                                    <th>Kratak pregled</th>
                                    <th>Izmeni</th>
                                    <th>Ukloni</th>
                                </thead>
                                <tbody>
                                    <?php if (empty($books)) : ?>
                                    <tr>
                                        <td colspan="10" class="text-center">
                                            Nema knjiga u bazi
                                        </td>
                                    </tr>
                                    <?php else : ?>
                                    <?php foreach ($books as $book) : ?>
                                    <tr>
                                        <td><?php echo $book['name']; ?></td>
                                        <td><?php echo $book['isbn']; ?></td>
                                        <td><?php echo $book['publisher']; ?></td>
                                        <td><?php echo $book['publishing_year']; ?></td>
                                        <td><?php echo substr($book['description'], 0, 10) . '...'; ?></td>
                                        <td><?php echo $book['stock']; ?></td>
                                        <td><?php echo $book['tname']; ?></td>
                                        <td>
                                            <?php foreach ($book['authors'] as $author) : ?>
                                            <?php echo $author['author'] . ' '; ?>
                                            <?php endforeach; ?>
                                        </td>
                                        <td>
                                            <?php if (!empty($book['book_preview_url'])) : ?>
                                            <a href="<?php echo $book['book_preview_url']; ?>" target="_blank">
                                                Pregled
                                            </a>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <a href="./editbook.php?id=<?php echo $book['bid']; ?>">
                                                Izmeni
                                            </a>
                                        </td>
                                        <td>
                                            <a
                                                href="../controllers/BookController.php?delete_book=<?php echo $book['bid']; ?>">
                                                Ukloni
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
            </div>
            <div class="tab-pane fade" id="pills-users" role="tabpanel" aria-labelledby="pills-profile-tab">
                <div class="container-fluid">
                    <h4>Korisnici</h4>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <th>ID</th>
                                <th>Ime</th>
                                <th>Prezime</th>
                                <th>Email</th>
                                <th>Uloga</th>
                                <th>Izmeni</th>
                                <th>Ukloni</th>
                            </thead>
                            <tbody>
                                <?php if (empty($users)) : ?>
                                <tr>
                                    <td colspan="8" class="text-center">
                                        Nema korisnika u bazi
                                    </td>
                                </tr>
                                <?php else : ?>
                                <?php foreach ($users as $user) : ?>
                                <tr>
                                    <td><?php echo $user['id']; ?></td>
                                    <td><?php echo $user['fname']; ?></td>
                                    <td><?php echo $user['lname']; ?></td>
                                    <td><?php echo $user['email']; ?></td>
                                    <td><?php if ($user['role_id'] == 1) {
                                        echo 'Student';
                                    } elseif ($user['role_id'] == 2) {
                                        echo 'Bibliotekar';
                                    } else {
                                        echo 'Administrator';
                                    } ?></td>
                                    <td>
                                        <button class="btn btn-warning" type="button" onclick="getUserData()"
                                            data-bs-toggle="modal" data-bs-target="#userModal">Izmeni</button>
                                    </td>
                                    <td>
                                        <form action="../controllers/UserController.php" method="POST">
                                            <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                            <input type="hidden" name="delete_user" value="1">
                                            <button class="btn btn-danger" type="submit">Ukloni</button>
                                        </form>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table </div>
                    </div>
                </div>
            </div>

            <!-- User Modal -->
            <div class="modal fade" id="userModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
                <div class="modal-lg  modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Izmeni korisnika</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="../controllers/UserController.php" method="POST" id="editUserForm">
                                <div class="row mt-3">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="fname">Ime</label>
                                            <input type="text" class="form-control" id="modalFname"
                                                name="fname" placeholder="Ime" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="lname">Prezime</label>
                                            <input type="text" class="form-control" id="modalLname"
                                                name="lname" placeholder="Prezime" required>
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="email">Email</label>
                                                <input type="email" class="form-control" id="modalEmail"
                                                    name="email" placeholder="Email" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="password">Lozinka</label>
                                                <input type="password" class="form-control" id="modalPassword"
                                                    name="password" placeholder="Lozinka" required>
                                            </div>
                                        </div>
                                    </div>
                                    <input type="hidden" id="modalUID" name="user_id" value="">
                                    <input type="hidden" id="UID" name="editUser" value="">

                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Otkazi</button>
                            <button type="button" class="btn btn-primary" onclick="editSubmit()">Izmeni
                                korisnika</button>
                        </div>
                    </div>
                </div>
            </div>
            <script>
                function editSubmit() {
                    $("#editUserForm").submit();
                }

                function getUserData(id) {
                    $.get("../controllers/UserController.php?get_user_data=<?php echo $user['id']; ?>", function(data) {
                        var user = JSON.parse(data);
                        $("#modalFname").val(user.fname);
                        $("#modalLname").val(user.lname);
                        $("#modalEmail").val(user.email);
                        $("#modalUID").val(user.id);
                    });
                }

                function addMonography() {
                    let monographies = $("#monographies")
                    let monography_inputs = $(".monography-input").length
                    let count = monography_inputs + 1
                    $(monographies).append(
                        `
                <div id="monography-${count}" class="monography-input">
                    <div class="row mt-3">
                        <div id="chapter_author" class="col-md-5">
                            <label>Autor poglavlja</label>
                            <input name="monographies_authors[]" type="text" class="form-control w-100">
                        </div>
                        <div id="chapter_name" class="col-md-5">
                            <label>Naziv poglavlja</label>
                            <input id="mainMonography" name="monographies_chapters[]" type="text" class="form-control w-100">
                        </div>
                        <div class="col-md-2 d-flex align-items-end ">
                            <button id="addMonographyBtn" class="btn btn-danger ml-3" onclick="removeMonography(${count})" type="button"> <i class='bx bx-trash'></i></button>
                        </div>
                    </div>
                </div>
                `
                    )
                }

                function removeMonography(id) {
                    $("#monography-" + id).remove()
                }

                function addAuthor() {
                    let authors = $("#authors")
                    let authorInputs = $(".author-input").length
                    let count = authorInputs + 1
                    $(authors).append(
                        `
            <div id = "authors-${count}" class="author-input d-flex align-center justify-content-between w-100 mt-3">
                <input name="authors[]" type="text" class="form-control w-75">
                <button class = "btn btn-danger" onclick = "removeAuthor(${count})"><i class='bx bx-trash'></i></button>
            </div>`
                    )
                }

                function removeAuthor(id) {
                    $("#authors-" + id).remove()
                }

                function addMentor() {
                    let mentors = $("#mentors")
                    let mentorInputs = $(".mentor-input").length
                    let count = mentorInputs + 1
                    $(mentors).append(
                        `
            <div id = "mentors-${count}" class="author-input d-flex align-center justify-content-between w-100 mt-3">
                <input name="mentors[]" type="text" class="form-control w-75">
                <button type = "button" class = "btn btn-danger" onclick = "removeMentor(${count})"><i class='bx bx-trash'></i></button>
            </div>`
                    )
                }

                function removeMentor(id) {
                    $("#mentors-" + id).remove()
                }

                $("#type").change(function() {
                    console.log($(this).val())
                    if ($(this).val() == 1 || $(this).val() == 2 || $(this).val() == 3) {
                        //jedan ili vise autora
                        $("#authors").removeClass("d-none")
                        $("#mainAuthor").removeClass("w-100")
                        $("#mainAuthor").addClass('w-75')
                        $("#addAuthorBtn").removeClass('d-none')
                        $("#mentors").addClass("d-none")
                        $("#magazineChoice").addClass("d-none");


                    } else if ($(this).val() == 4) {
                        $("#authors").addClass("d-none")
                        $("#menotrs").addClass("d-none")
                        $("#monographies").removeClass("d-none")
                        $("#mentors").addClass("d-none")
                        $("#magazineChoice").addClass("d-none");

                    } else if ($(this).val() == 10) {
                        $("#authors").removeClass("d-none")
                        $("#mainAuthor").removeClass("w-100")
                        $("#mainAuthor").addClass('w-75')
                        $("#addAuthorBtn").removeClass('d-none')
                        $("#mentors").addClass("d-none")
                        $("#magazineChoice").addClass("d-none");
                        $("#magazineChoice").removeClass("d-none");
                        $("#monographies").addClass("d-none")

                    } else {
                        //vise autora bez mentora
                        $("#authors").removeClass("d-none")
                        $("#mainAuthor").addClass('w-100')
                        $("#mainAuthor").removeClass('w-75')
                        $("#addAuthorBtn").addClass('d-none')
                        $("#mentors").removeClass("d-none")
                        $("#monographies").addClass("d-none")
                        $("#magazineChoice").addClass("d-none");

                    }
                })
            </script>
</body>

</html>

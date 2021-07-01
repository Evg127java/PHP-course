<?php

use app\services\Flasher;

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title><?= $title ?? ''; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="/images/favicon.png">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>
<body>
<div class="container-fluid my-4">
    <nav class="navbar navbar-light bg-light justify-content-between">
        <div class="navbar-brand"><a href="/" class="navbar-brand"><strong>Ш++</strong></a> LIBRARY ADMIN PANEL</div>
        <form class="form-inline">
            <a href="http://logout:logout@<?= $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'] ?>">
                <button type="button" class="btn btn-outline-secondary my-2 my-sm-0">Sign out</button>
            </a>
        </form>
    </nav>
</div>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-7">
            <div class="row">
                <div class="col-md-12">
                    <table class="table table-bordered">
                        <thead class="thead-light">
                        <tr>
                            <th>Title</th>
                            <th>Authors</th>
                            <th>Year</th>
                            <th>Action</th>
                            <th>Clicks</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $books = $books ?? ''; ?>
                        <?php if (!empty($books)):
                            foreach ($books as $book): ?>
                                <tr >
                                    <td><a href="/book/<?= $book->id?>"><?= $book->title?></a></td>
                                    <td><?= $book->author?></td>
                                    <td><?= $book->year?></td>
                                    <td><a href="/admin/delete/<?=$book->id?>">Delete</a></td>
                                    <td><?= $book->clicks?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <nav aria-label="...">
                        <ul class="pagination pagination-sm justify-content-center">
                            <?php if (!empty($paginator)):
                                for ($i = 1; $i <= $paginator['pages']; $i++):
                                    $isActive = $paginator['currentPage'] == $i; ?>
                            <li class="page-item<?= $isActive ? ' disabled' : ''; ?>">
                                <a class="page-link" href="/admin/<?= $i; ?>"<?= $isActive ? ' tabindex="-1"' : ''; ?>><?= $i; ?></a>
                            </li>
                                <?php endfor; ?>
                            <?php endif; ?>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <div class="row">
                <div class="col-md-12">
                    <form class="table-bordered pt-4" action="/admin/add/<?= $paginator['currentPage'] ?? ''; ?>" method="post" enctype="multipart/form-data">
                        <legend class="col-md-12 pt-0">Add new book</legend>
                        <div class="row mx-0">
                            <div class="form-group col-md-7">
                                <input type="text" class="form-control" id="title" placeholder="Title" name="title">
                            </div>
                            <div class="form-group col-md-5">
                                <input type="text" class="form-control" id="author1" placeholder="Author1" name="author1">
                            </div>
                        </div>
                        <div class="row mx-0">
                            <div class="form-group col-md-7">
                                <input type="text" class="form-control" id="year" placeholder="Year" name="year">
                            </div>
                            <div class="form-group col-md-5">
                                <input type="text" class="form-control" id="author2" placeholder="Author2" name="author2">
                            </div>
                        </div>
                        <div class="row mx-0">
                            <div class="col-md-7">
                                <div class="custom-file mb-3">
                                    <input type="file" class="custom-file-input" id="file" name="file">
                                    <label class="custom-file-label" for="customFile">Choose image:</label>
                                </div>
                                <script src="/js/fileNameFiller.js"></script>
                                <script src="/js/imgPreview.js"></script>
                            </div>
                            <div class="form-group col-md-5">
                                <input type="text" class="form-control" id="author3" placeholder="Author3" name="author3">
                            </div>
                        </div>
                        <div class="row mx-0">
                            <div class="form-group col-md-7">
                                <span id="output"></span>
                            </div>
                            <div class="form-group col-md-5">
                                <textarea class="form-control" id="description" rows="5" placeholder="Description" name="description"></textarea>
                            </div>
                        </div>
                        <div class="row mx-0">
                            <div class="form-group col-md-7">
                                <button type="submit" class="btn btn-outline-secondary float-center">Add</button>
                            </div>
                            <div class="form-group col-md-5">
                                * Leave the fields empty if authors number is < 3.
                            </div>
                        </div>
                        <div class="row mx-3">
                            <?php $error = Flasher::get('error');
                            if ($error != ''): ?>
                            <div class="form-group col-md-12 alert alert-danger" role="alert">
                                <?= $error; ?>
                            </div>
                            <?php endif; ?>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
<!DOCTYPE html>

<html lang="ru">

<head>
    <script src="/js/googleAnalytics.js"></script>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title><?= $title ?? ''; ?></title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="library Sh++">
    <link rel="stylesheet" href="/css/libs.min.css">
    <link rel="stylesheet" href="/css/style.css">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css"
          crossorigin="anonymous"/>

    <link rel="shortcut icon" href="/images/favicon.png">
    <style>
        .details {
            display: none;
        }
    </style>
</head>

<?php require_once "header.php" ?>
<section id="main" class="main-wrapper">
        <div class="container">
            <div id="content" class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <?php if (!empty($searchString)): ?>
                    <div class="alert alert-info" role="alert">
                        Search result of <strong>"<?= $searchString; ?>":</strong>
                    </div>
                <?php endif ?>
                <?php $books = $books ?? '';
                if (empty($books)): ?>
                    <p>Any relevant item found</p>
                <? endif;
                foreach ($books as $book): ?>
                    <div data-book-id="<?= $book->id?>" class="book_item col-xs-6 col-sm-3 col-md-2 col-lg-2">
                        <div class="book">
                            <a href="http://library/book/<?= $book->id?>"><img src="/images/<?= $book->img?>" alt="<?= $book->title?>">
                                <div data-title="<?= $book->title?>" class="blockI" style="height: 46px;">
                                    <div data-book-title="<?= $book->title?>" class="title size_text"><?= $book->title?></div>
                                    <div data-book-author="<?= $book->author?>" class="author"><?= $book->author?></div>
                                </div>
                            </a>
                            <a href="http://library/book/<?= $book->id?>">
                                <button type="button" class="details btn btn-success">Читать</button>
                            </a>
                        </div>
                    </div>
                <?php endforeach;?>
            </div>
        </div>

    <center>

        <nav aria-label="...">
            <ul class="pagination">
                <?php if (!empty($paginator['hasPrevious']) && $paginator['hasPrevious']): ?>
                    <li class="page-item active">
                        <a class="page-link"
                           href="http://library/<?= $paginator['currentPage'] - 1; ?>" tabindex="-1">Previous</a>
                    </li>
                <?php endif ?>
                <?php if (!empty($paginator['currentPage'])): ?>
                    <li class="page-item"><a class="page-link" href="#">
                            <?= "Current page " . ($paginator['currentPage']); ?> </a>
                    </li>
                <?php endif ?>
                <?php if (!empty($paginator['hasNext']) && $paginator['hasNext']) : ?>
                    <li class="page-item active">
                        <a class="page-link"
                           href="http://library/<?= $paginator['currentPage'] + 1; ?>">Next</a>
                    </li>
                <?php endif ?>
            </ul>
        </nav>

    </center>
    </section>
<?php require_once "footer.php" ?>

<div class="sweet-overlay" tabindex="-1" style="opacity: -0.04; display: none;"></div>
<div class="sweet-alert hideSweetAlert" data-custom-class="" data-has-cancel-button="false"
     data-has-confirm-button="true" data-allow-outside-click="false" data-has-done-function="false" data-animation="pop"
     data-timer="null" style="display: none; margin-top: -169px; opacity: -0.04;">
    <div class="sa-icon sa-error" style="display: block;">
            <span class="sa-x-mark">
        <span class="sa-line sa-left"></span>
            <span class="sa-line sa-right"></span>
            </span>
    </div>
    <div class="sa-icon sa-warning" style="display: none;">
        <span class="sa-body"></span>
        <span class="sa-dot"></span>
    </div>
    <div class="sa-icon sa-info" style="display: none;"></div>
    <div class="sa-icon sa-success" style="display: none;">
        <span class="sa-line sa-tip"></span>
        <span class="sa-line sa-long"></span>

        <div class="sa-placeholder"></div>
        <div class="sa-fix"></div>
    </div>
    <div class="sa-icon sa-custom" style="display: none;"></div>
    <h2>Ооопс!</h2>
    <p style="display: block;">Ошибка error</p>
    <fieldset>
        <input type="text" tabindex="3" placeholder="">
        <div class="sa-input-error"></div>
    </fieldset>
    <div class="sa-button-container">
        <button class="cancel" tabindex="2" style="display: none; box-shadow: none;">Cancel</button>
        <div class="sa-confirm-button-container">
            <button class="confirm" tabindex="1"
                    style="display: inline-block; background-color: rgb(140, 212, 245); box-shadow: rgba(140, 212, 245, 0.8) 0px 0px 2px, rgba(0, 0, 0, 0.05) 0px 0px 0px 1px inset;">
                OK
            </button>
            <div class="la-ball-fall">
                <div></div>
                <div></div>
                <div></div>
            </div>
        </div>
    </div>
</div>
</body>

</html>
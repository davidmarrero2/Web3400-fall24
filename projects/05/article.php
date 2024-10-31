<?php 
// Step 1: Include config.php file
include 'config.php';

/* Step 2: Check if the $_GET['id'] exists; if it does, get the article record 
from the database and store it in the associative array named $article.*/
// SQL example: SELECT articles.*, users.full_name AS author FROM articles JOIN users ON articles.author_id = users.id WHERE is_published = 1 AND articles.id = ?
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = intval($_GET['id']);  // Convert ID to an integer for security

    // SQL query to fetch the article along with the author's full name
    $fetchStmt = $pdo->prepare("SELECT articles.*, users.full_name AS author FROM articles JOIN users ON articles.author_id = users.id 
        WHERE articles.is_published = 1 AND articles.id = ?");
    
    // Execute the query using the provided article ID
    $fetchStmt->execute([$_GET['id']]);

    // Fetch the article record into an associative array named $article
    $article = $fetchStmt->fetch(PDO::FETCH_ASSOC);
}
?>
<?php include 'templates/head.php'; ?>
<?php include 'templates/nav.php'; ?>

<!-- BEGIN YOUR CONTENT -->
<section class="section">
    <h1 class="title"><?= $article['title'] ?></h1>
    <div class="box">
        <article class="media">
            <figure class="media-left">
                <p class="image is-128x128">
                    <img src="https://picsum.photos/128">
                </p>
            </figure>
            <div class="media-content">
                <div class="content">
                    <p>
                        <?= $article['content'] ?>
                    </p>
                    <p>
                        <small><strong>Author: <?= $article['author'] ?></strong>
                            | Published: <?= time_ago($article['created_at']) ?>
                            <?php if ($article['modified_on'] !== NULL) : ?>
                                | Updated: <?= time_ago($article['modified_on']) ?>
                            <?php endif; ?>
                        </small>
                    </p>
                </div>
                <p class="buttons">
                    <a href="contact.php" class="button is-small is-info is-rounded">
                        <span class="icon">
                            <i class="fas fa-lg fa-hiking"></i>
                        </span>
                        <span><strong>Begin your journey now</strong></span>
                    </a>
                </p>
                <p class="buttons">
                    <a class="button is-small is-rounded">
                        <span class="icon is-small">
                            <i class="fas fa-thumbs-up"></i>
                        </span>
                        <span><?= $article['likes_count'] ?></span>
                    </a>
                    <a class="button is-small is-rounded">
                        <span class="icon is-small">
                            <i class="fas fa-star"></i>
                        </span>
                        <span><?= $article['favs_count'] ?></span>
                    </a>
                    <a class="button is-small is-rounded">
                        <span class="icon is-small">
                            <i class="fas fa-comment"></i>
                        </span>
                        <span><?= $article['comments_count'] ?></span>
                    </a>
                </p>
            </div>
        </article>
    </div>
</section>
<!-- END YOUR CONTENT -->


<?php include 'templates/footer.php'; ?>
<?php 
// Step 1: Include config.php file
include 'config.php';

// Step 2: Secure and only allow 'admin' users to access this page
if (!isset($_SESSION['loggedin']) || $_SESSION['user_role'] !== 'admin') {
    // Redirect user to login page or display an error message
    $_SESSION['messages'][] = "You must be an administrator to access that resource.";
    header('Location: login.php');
    exit;
}

// Step 3: Check if the update form was submitted. If so, update article details using an UPDATE SQL query.
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    try {
        // Retrieve form data
        $id = $_POST['id']; // Hidden input for article ID
        $title = $_POST['title'];
        $content = $_POST['content'];
        $is_published = isset($_POST['is_published']) ? 1 : 0;
        $is_featured = isset($_POST['is_featured']) ? 1 : 0;

        // Update article record in the database
        $stmt = $pdo->prepare("UPDATE `articles` SET `title` = ?, `content` = ?, `is_published` = ?, `is_featured` = ?, `modified_on` = NOW() 
            WHERE `id` = ?
        ");
        $stmt->execute([$title, $content, $is_published, $is_featured, $id]);

        // Redirect to the articles page after successful update
        header('Location: articles.php');
        exit;

    } catch (PDOException $e) {
        // Handle any database errors (optional)
        die("Database error occurred: " . $e->getMessage());
    }
}

/* Step 4: Else it's an initial page request, fetch the article's current data from the database by preparing 
and executing a SQL statement that uses the article id from the query string (ex. $_GET['id'])*/
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Prepare SQL to fetch the article by ID
    $stmt = $pdo->prepare("SELECT * FROM articles WHERE id = ?");
    $stmt->execute([$id]);

    // Fetch the article data
    $article = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$article) {
        // If no article is found, redirect with an error message
        $_SESSION['messages'][] = "An article with that ID does not exist.";
        header('Location: articles.php');
        exit;
    }
}
?>

<?php include 'templates/head.php'; ?>
<?php include 'templates/nav.php'; ?>

<!-- BEGIN YOUR CONTENT -->
<section class="section">
    <h1 class="title">Edit Article</h1>
    <form action="" method="post">
        <!-- ID -->
        <input type="hidden" name="id" value="<?= $article['id'] ?>">
        <!-- Title -->
        <div class="field">
            <label class="label">Title</label>
            <div class="control">
                <input class="input" type="text" name="title" value="<?= $article['title'] ?>" required>
            </div>
        </div>
        <!-- Content -->
        <div class="field">
            <label class="label">Content</label>
            <div class="control">
                <textarea class="textarea" id="content" name="content" required><?= $article['content'] ?></textarea>
            </div>
        </div>
        <!-- Submit -->
        <div class="field is-grouped">
            <div class="control">
                <button type="submit" class="button is-link">Update Article</button>
            </div>
            <div class="control">
                <a href="articles.php" class="button is-link is-light">Cancel</a>
            </div>
        </div>
    </form>
</section>
<!-- END YOUR CONTENT -->


<?php include 'templates/footer.php'; ?>
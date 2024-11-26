<?php
// Include config.php file
include 'config.php'; 

// Secure and only allow 'admin' users to access this page
if (!isset($_SESSION['loggedin']) || $_SESSION['user_role'] !== 'admin') {
    // Redirect user to login page or display an error message
    $_SESSION['messages'][] = "You must be an administrator to access that resource.";
    header('Location: login.php');
    exit;
}

// Check if the $_GET['id'] exists; if it does, get the ticket record from the database and store it in the associative array named $ticket.
if (isset($_GET['id'])) {

    // Prepare and execute the SELECT query to fetch the ticket data
    $stmt = $pdo->prepare("SELECT * FROM `tickets` WHERE `id` = ?");
    $stmt->execute([$_GET['id']]);
    $ticket = $stmt->fetch(PDO::FETCH_ASSOC);

    if($ticket) {
        // Fetch comments for the ticket
        $stmt = $pdo->prepare("SELECT * FROM `ticket_comments` WHERE `ticket_id` = ?");
        $stmt->execute([$_GET['id']]);
        $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Check if the user clicked to update the ticket status
        if (isset($_GET['status'])) {
            $status = $_GET['status'];

            $stmt = $pdo->prepare("UPDATE `tickets` SET `status` = ? WHERE `id` = ?");
            $stmt->execute([$status, $_GET['id']]);

            header("Location: ticket_detail.php?id=" . $_GET['id']);
            exit;
        }

        // Check if the comment form has been submitted. If true, then INSERT the ticket comment
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['msg'])) {
            $comment = $_POST['msg'];

            // Insert the new comment into the ticket_comments table
            $stmt = $pdo->prepare("INSERT INTO `ticket_comments` (`ticket_id`, `comment`, `user_id`) VALUES (?, ?, ?)");
            $stmt->execute([$_GET['id'], $comment, $_SESSION['user_id']]);

            header("Location: ticket_detail.php?id=" . $_GET['id']);
            exit;
        }

    } else {
        $_SESSION['messages'][] = "Ticket not found.";
        header('Location: tickets.php');
        exit;
    }

}

?>

<?php include 'templates/head.php'; ?>
<?php include 'templates/nav.php'; ?>

<!-- BEGIN YOUR CONTENT -->
<section class="section">
    <h1 class="title">Ticket Detail</h1>
    <p class="subtitle">
        <a href="tickets.php">View all tickets</a>
    </p>
    <div class="card">
        <header class="card-header">
            <p class="card-header-title">
                <?= htmlspecialchars($ticket['title'], ENT_QUOTES) ?>
                &nbsp;
                <?php if ($ticket['priority'] == 'Low') : ?>
                    <span class="tag"><?= $ticket['priority'] ?></span>
                <?php elseif ($ticket['priority'] == 'Medium') : ?>
                    <span class="tag is-warning"><?= $ticket['priority'] ?></span>
                <?php elseif ($ticket['priority'] == 'High') : ?>
                    <span class="tag is-danger"><?= $ticket['priority'] ?></span>
                <?php endif; ?>
            </p>
            <button class="card-header-icon">
                <a href="ticket_detail.php?id=<?= $ticket['id'] ?>">
                    <span class="icon">
                        <?php if ($ticket['status'] == 'Open') : ?>
                            <i class="far fa-clock fa-2x"></i>
                        <?php elseif ($ticket['status'] == 'In Progress') : ?>
                            <i class="fas fa-tasks fa-2x"></i>
                        <?php elseif ($ticket['status'] == 'Closed') : ?>
                            <i class="fas fa-times fa-2x"></i>
                        <?php endif; ?>
                    </span>
                </a>
            </button>
        </header>
        <div class="card-content">
            <div class="content">
                <time datetime="2016-1-1">Created: <?= date('F dS, G:ia', strtotime($ticket['created_at'])) ?></time>
                <br>
                <p><?= htmlspecialchars($ticket['description'], ENT_QUOTES) ?></p>
            </div>
        </div>
        <footer class="card-footer">
            <a href="ticket_detail.php?id=<?= $ticket['id'] ?>&status=Closed" class="card-footer-item">
                <span class="icon"><i class="fas fa-times fa-2x"></i></span>
                <span>&nbsp;Close</span>
            </a>
            <a href="ticket_detail.php?id=<?= $ticket['id'] ?>&status=In Progress" class="card-footer-item">
                <span><i class="fas fa-tasks fa_2x"></i></i></span>
                <span>&nbsp;In Progress</span>
            </a>
            <a href="ticket_detail.php?id=<?= $ticket['id'] ?>&status=Open" class="card-footer-item">
                <span><i class="far fa-clock fa-2x"></i></span>
                <span>&nbsp;Re-Open</span>
            </a>
        </footer>
    </div>
    <hr>
    <div class="block">
        <form action="" method="post">
            <div class="field">
                <label class="label"></label>
                <div class="control">
                    <textarea name="msg" class="textarea" placeholder="Enter your comment here..." required></textarea>
                </div>
            </div>
            <div class="field">
                <div class="control">
                    <button class="button is-link">Post Comment</button>
                </div>
            </div>
        </form>
        <hr>
        <div class="content">
            <h3 class="title is-4">Comments</h3>
            <?php foreach ($comments as $comment) : ?>
                <p class="box">
                    <span><i class="fas fa-comment"></i></span>
                    <?= date('F dS, G:ia', strtotime($comment['created_at'])) ?>
                    <br>
                    <?= nl2br(htmlspecialchars($comment['comment'], ENT_QUOTES)) ?>
                    <br>
                </p>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<!-- END YOUR CONTENT -->

<?php include 'templates/footer.php'; ?>
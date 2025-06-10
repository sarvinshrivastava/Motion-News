<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) {
    $articleId = $_POST['id'];

    // Load existing articles
    $articles = [];
    if (file_exists('./articles.json')) {
        $jsonContent = file_get_contents('./articles.json');
        $articles = json_decode($jsonContent, true) ?: [];
    }

    // Find and remove the article
    $articles = array_filter($articles, function ($article) use ($articleId) {
        return $article['id'] !== $articleId;
    });

    // Re-index the array (optional but recommended)
    $articles = array_values($articles);

    // Save back to JSON file
    file_put_contents('./articles.json', json_encode($articles, JSON_PRETTY_PRINT));

    // Redirect back to dashboard with success message
    echo "<script>
            alert('Article deleted successfully!');
            window.location.href = 'dashboard.php';
          </script>";
    exit;
} else {
    // If accessed directly without POST data
    header('Location: dashboard.php');
    exit;
}

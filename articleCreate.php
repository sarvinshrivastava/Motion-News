<?php
function validateInput($content)
{
    $content = htmlspecialchars(stripslashes(trim($content)));
    return $content;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = validateInput($_POST['title']);
    $content = validateInput($_POST['summary']);
    $genre = $_POST['genre'];

    if (empty($title) || empty($content) || $genre === "Select Genre" || $genre === "") {
        echo "<script>alert('All fields are required!');</script>";
        exit;
    } else {
        // Read existing articles properly
        $articles = [];
        if (file_exists('./articles.json')) {
            $jsonContent = file_get_contents('./articles.json');
            $articles = json_decode($jsonContent, true) ?: [];
        }

        // Generate a more robust unique ID
        $uniqueId = 'art_' . uniqid(date('Ymd'), true);

        // Create new article
        $article = [
            'id' => $uniqueId,
            'title' => $title,
            'content' => $content,
            'genre' => $genre,
            'date' => date('Y-m-d H:i:s')
        ];

        // Append to existing articles
        $articles[] = $article;

        // Save back to JSON file - FIXED PATH
        file_put_contents('./articles.json', json_encode($articles, JSON_PRETTY_PRINT));

        // Show success message then redirect
        echo "<script>
                alert('Article created successfully with ID: {$uniqueId}');
                window.location.href = 'dashboard.php';
              </script>";
        exit;
    }
}

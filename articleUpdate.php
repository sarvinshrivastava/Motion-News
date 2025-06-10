<?php
$article = null;
$isEdit = false;

// Load articles and find the one to edit
if (isset($_GET['id'])) {
    $articleId = $_GET['id'];
    $articles = [];

    if (file_exists('./articles.json')) {
        $jsonContent = file_get_contents('./articles.json');
        $articles = json_decode($jsonContent, true) ?: [];
    }

    // Find the article to edit
    foreach ($articles as $art) {
        if ($art['id'] === $articleId) {
            $article = $art;
            $isEdit = true;
            break;
        }
    }

    if (!$article) {
        echo "<script>alert('Article not found!'); window.location.href = 'dashboard.php';</script>";
        exit;
    }
}

// Handle form submission for editing
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) {
    $articleId = $_POST['id'];
    $title = htmlspecialchars(stripslashes(trim($_POST['title'])));
    $content = htmlspecialchars(stripslashes(trim($_POST['summary'])));
    $genre = $_POST['genre'];

    if (empty($title) || empty($content) || $genre === "") {
        echo "<script>alert('All fields are required!');</script>";
    } else {
        // Load existing articles
        $articles = [];
        if (file_exists('./articles.json')) {
            $jsonContent = file_get_contents('./articles.json');
            $articles = json_decode($jsonContent, true) ?: [];
        }

        // Find and update the article
        for ($i = 0; $i < count($articles); $i++) {
            if ($articles[$i]['id'] === $articleId) {
                $articles[$i]['title'] = $title;
                $articles[$i]['content'] = $content;
                $articles[$i]['genre'] = $genre;
                $articles[$i]['date'] = date('Y-m-d H:i:s'); // Update timestamp
                break;
            }
        }

        // Save back to JSON file
        file_put_contents('./articles.json', json_encode($articles, JSON_PRETTY_PRINT));

        echo "<script>
                alert('Article updated successfully!');
                window.location.href = 'dashboard.php';
              </script>";
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Edit Article</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen">
    <!-- Header -->
    <header class="bg-white shadow p-4 flex justify-between items-center">
        <h1 class="text-2xl font-bold text-blue-700">Edit Article</h1>
        <a href="dashboard.php" class="text-blue-600 hover:underline">Back to Dashboard</a>
    </header>

    <!-- Edit Article Form -->
    <section class="max-w-3xl mx-auto mt-8 bg-white rounded shadow p-6">
        <h2 class="text-xl font-semibold mb-4 text-gray-800">Edit Article</h2>
        <form action="articleUpdate.php" method="POST" class="space-y-4" onsubmit="return validateArticleForm()">
            <input type="hidden" name="id" value="<?= $article['id'] ?>">

            <div>
                <label for="title" class="block font-medium mb-1">Title</label>
                <input type="text" id="title" name="title" value="<?= htmlspecialchars($article['title']) ?>"
                    minlength="5" maxlength="100" required class="w-full border rounded px-3 py-2">
            </div>

            <div>
                <label for="summary" class="block font-medium mb-1">Content</label>
                <textarea id="summary" name="summary" minlength="10" maxlength="10000" required
                    class="w-full border rounded px-3 py-2 h-64"><?= htmlspecialchars($article['content']) ?></textarea>
            </div>

            <div>
                <label for="genre" class="block font-medium mb-1">Genre</label>
                <select id="genre" name="genre" required class="w-full border rounded px-3 py-2">
                    <option value="">Select Genre</option>
                    <?php
                    if (file_exists('genre.txt')) {
                        $genres = file('genre.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                        foreach ($genres as $genre) {
                            $value = htmlspecialchars(strtolower($genre));
                            $label = htmlspecialchars($genre);
                            $selected = ($article['genre'] === $value) ? 'selected' : '';
                            echo "<option value=\"$value\" $selected>$label</option>";
                        }
                    }
                    ?>
                </select>
            </div>

            <div class="flex gap-4">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    Update Article
                </button>
                <a href="dashboard.php" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                    Cancel
                </a>
            </div>
        </form>
    </section>

    <script>
        function validateArticleForm() {
            const title = document.getElementById("title").value.trim();
            const content = document.getElementById("summary").value.trim();
            const genre = document.getElementById("genre").value;

            if (title === "") {
                alert("Title is required!");
                return false;
            }
            if (content === "") {
                alert("Content is required!");
                return false;
            }
            if (genre === "") {
                alert("Please select a genre!");
                return false;
            }
            if (title.length < 5) {
                alert("Title must be at least 5 characters long!");
                return false;
            }
            if (content.length < 10) {
                alert("Content must be at least 10 characters long!");
                return false;
            }
            return true;
        }
    </script>
</body>

</html>
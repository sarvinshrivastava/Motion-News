<?php
session_start();

if (!isset($_SESSION['status']) || $_SESSION['status'] !== 'in') {
  header('Location: login.php');
  exit;
}

// Load articles from JSON file
$articles = [];
if (file_exists('./articles.json')) {
  $jsonContent = file_get_contents('./articles.json');
  $articles = json_decode($jsonContent, true) ?: [];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <title>Dashboard</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <!-- Tailwind CSS CDN -->
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen">
  <!-- Header -->
  <header class="bg-white shadow p-4 flex justify-between items-center">
    <h1 class="text-2xl font-bold text-blue-700">Dashboard</h1>
    <a href="index.php" class="text-blue-600 hover:underline">Back to News</a>
  </header>

  <!-- Create Article Form -->
  <section class="max-w-3xl mx-auto mt-8 bg-white rounded shadow p-6">
    <h2 class="text-xl font-semibold mb-4 text-gray-800">Create New Article</h2>
    <!-- Replace action and method as needed for PHP backend -->
    <form action="articleCreate.php" method="POST" class="space-y-4" onsubmit="return validateArticleForm()">
      <div>
        <label for="title" class="block font-medium mb-1">Title</label>
        <input type="text" id="title" name="title" minlength="5" maxlength="100" required class="w-full border rounded px-3 py-2" />
      </div>
      <div>
        <label for="summary" class="block font-medium mb-1">Summary</label>
        <textarea id="summary" name="summary" minlength="10" maxlength="10000" required class="w-full border rounded px-3 py-2"></textarea>
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
              echo "<option value=\"$value\">$label</option>";
            }
          }
          ?>
        </select>
      </div>
      <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Create Article</button>
    </form>
  </section>

  <!-- Published Articles List -->
  <main class="max-w-3xl mx-auto mt-10">
    <h2 class="text-xl font-semibold mb-4 text-gray-800">Published Articles</h2>
    <?php if (!empty($articles)): ?>
      <?php foreach ($articles as $article): ?>
        <div class="bg-white rounded shadow p-4 mb-4 flex flex-col md:flex-row md:justify-between">
          <div>
            <h3 class="text-lg font-semibold text-gray-800"><?= html_entity_decode($article['title']) ?></h3>
            <p class="text-gray-600"><?= html_entity_decode(substr($article['content'], 0, 150)) ?>...</p>
            <span class="inline-block mt-2 px-2 py-1 text-xs bg-blue-100 text-blue-700 rounded"><?= htmlspecialchars($article['genre']) ?></span>
          </div>
          <div class="flex flex-col items-end justify-between mt-2 md:mt-0">
            <span class="text-sm text-gray-500"><?= date('M j, Y', strtotime($article['date'])) ?></span>
            <div class="flex gap-2 mt-2">
              <a href="articleUpdate.php?id=<?= $article['id'] ?>" class="bg-yellow-400 text-white px-3 py-1 rounded hover:bg-yellow-500">Edit</a>
              <form action="articleDelete.php" method="POST" onsubmit="return confirm('Are you sure you want to delete this article?');" style="display: inline;">
                <input type="hidden" name="id" value="<?= $article['id'] ?>">
                <button type="submit" class="bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700">Delete</button>
              </form>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <div class="bg-white rounded shadow p-6 text-center text-gray-500">
        No articles published yet.
      </div>
    <?php endif; ?>
  </main>

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
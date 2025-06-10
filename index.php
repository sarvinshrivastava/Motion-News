<?php
session_start();
session_destroy();

// Load articles from JSON file
$articles = [];
if (file_exists('./articles.json')) {
  $jsonContent = file_get_contents('./articles.json');
  $articles = json_decode($jsonContent, true) ?: [];
}

// Sort articles by date (latest first)
usort($articles, function ($a, $b) {
  return strtotime($b['date']) - strtotime($a['date']);
});

function convertMarkdownBold($text)
{
  return preg_replace('/\*\*(.*?)\*\*/', '<strong>$1</strong>', $text);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <title>Motion News</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen">
  <!-- Header -->
  <header class="bg-white shadow p-4 flex justify-between items-center">
    <h1 class="text-2xl font-bold text-blue-700">Motion News</h1>
    <button class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700" onclick="window.location.href='login.php'">
      Login
    </button>
  </header>

  <!-- Filters and Sorting -->
  <section class="max-w-4xl mx-auto mt-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
    <div class="flex gap-2">
      <label for="genre" class="self-center font-medium">Genre:</label>
      <select id="genre" name="genre" class="border rounded px-3 py-2">
        <option value="">All</option>
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
    <div class="flex gap-2">
      <label for="sort" class="self-center font-medium">Sort by:</label>
      <select id="sort" name="sort" class="border rounded px-3 py-2">
        <option value="latest">Latest</option>
        <option value="oldest">Oldest</option>
      </select>
    </div>
  </section>

  <!-- News Headlines List -->
  <main class="max-w-4xl mx-auto mt-8">
    <?php if (!empty($articles)): ?>
      <?php foreach ($articles as $article): ?>
        <div class="bg-white rounded shadow p-4 mb-4 flex flex-col md:flex-row md:justify-between">
          <div>
            <h2 class="text-xl font-semibold text-gray-800"><?= html_entity_decode($article['title']) ?></h2>
            <p class="text-gray-600"><?= convertMarkdownBold(html_entity_decode(substr($article['content'], 0, 200))) ?>...</p>
            <span class="inline-block mt-2 px-2 py-1 text-xs bg-blue-100 text-blue-700 rounded"><?= htmlspecialchars($article['genre']) ?></span>
          </div>
          <div class="flex flex-col items-end justify-between mt-2 md:mt-0">
            <span class="text-sm text-gray-500"><?= date('M j, Y', strtotime($article['date'])) ?></span>
            <a href="news.php?id=<?= $article['id'] ?>" class="mt-2 text-blue-600 hover:underline">Read more</a>
          </div>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <div class="bg-white rounded shadow p-6 text-center text-gray-500">
        No news to display.
      </div>
    <?php endif; ?>
  </main>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const genreFilter = document.getElementById('genre');
      const sortFilter = document.getElementById('sort');
      const articlesContainer = document.querySelector('main');

      // Store original articles data
      const originalArticles = <?= json_encode($articles) ?>;

      function filterAndSort() {
        let filteredArticles = [...originalArticles];

        // Filter by genre
        const selectedGenre = genreFilter.value;
        if (selectedGenre) {
          filteredArticles = filteredArticles.filter(article =>
            article.genre.toLowerCase() === selectedGenre
          );
        }

        // Sort articles
        const sortBy = sortFilter.value;
        if (sortBy === 'latest') {
          filteredArticles.sort((a, b) => new Date(b.date) - new Date(a.date));
        } else if (sortBy === 'oldest') {
          filteredArticles.sort((a, b) => new Date(a.date) - new Date(b.date));
        }

        // Display filtered articles
        displayArticles(filteredArticles);
      }

      function displayArticles(articles) {
        if (articles.length === 0) {
          articlesContainer.innerHTML = `
            <div class="bg-white rounded shadow p-6 text-center text-gray-500">
              No articles found for the selected filters.
            </div>
          `;
          return;
        }

        let html = '';
        articles.forEach(article => {
          // Decode HTML entities and convert markdown
          const title = decodeHtmlEntities(article.title);
          const content = convertMarkdownBold(decodeHtmlEntities(article.content.substring(0, 200)));
          const date = new Date(article.date).toLocaleDateString('en-US', {
            year: 'numeric',
            month: 'short',
            day: 'numeric'
          });

          html += `
            <div class="bg-white rounded shadow p-4 mb-4 flex flex-col md:flex-row md:justify-between">
              <div>
                <h2 class="text-xl font-semibold text-gray-800">${title}</h2>
                <p class="text-gray-600">${content}...</p>
                <span class="inline-block mt-2 px-2 py-1 text-xs bg-blue-100 text-blue-700 rounded">${article.genre}</span>
              </div>
              <div class="flex flex-col items-end justify-between mt-2 md:mt-0">
                <span class="text-sm text-gray-500">${date}</span>
                <a href="news.php?id=${article.id}" class="mt-2 text-blue-600 hover:underline">Read more</a>
              </div>
            </div>
          `;
        });

        articlesContainer.innerHTML = html;
      }

      function decodeHtmlEntities(text) {
        const textarea = document.createElement('textarea');
        textarea.innerHTML = text;
        return textarea.value;
      }

      function convertMarkdownBold(text) {
        return text.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
      }

      // Add event listeners
      genreFilter.addEventListener('change', filterAndSort);
      sortFilter.addEventListener('change', filterAndSort);
    });
  </script>
</body>

</html>
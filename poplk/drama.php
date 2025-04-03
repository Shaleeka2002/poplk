<?php
// Database connection
$servername = "localhost";
$username = "root"; 
$password = ""; 
$dbname = "pop_lk";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get drama ID from URL
$drama_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($drama_id <= 0) {
    // No valid drama ID provided, redirect to home
    header("Location: index.php");
    exit;
}

// Get drama details using prepared statement
$sql = "SELECT d.*, c.category_name 
        FROM dramas d
        LEFT JOIN categories c ON d.category_id = c.category_id
        WHERE d.drama_id = ?";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    error_log("Prepare failed: " . $conn->error);
    header("Location: index.php");
    exit;
}

$stmt->bind_param("i", $drama_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    // Drama not found
    $stmt->close();
    header("Location: index.php");
    exit;
}

$drama = $result->fetch_assoc();
$stmt->close();

// Get trailer video
$trailer_sql = "SELECT * FROM videos WHERE drama_id = ? AND video_type = 'trailer' LIMIT 1";
$trailer_stmt = $conn->prepare($trailer_sql);
$trailer_stmt->bind_param("i", $drama_id);
$trailer_stmt->execute();
$trailer_result = $trailer_stmt->get_result();
$trailer = $trailer_result->fetch_assoc();
$trailer_stmt->close();

// Get episodes for this drama
$episodes_sql = "SELECT * FROM videos WHERE drama_id = ? AND video_type = 'episode' ORDER BY episode_number ASC";
$episodes_stmt = $conn->prepare($episodes_sql);
$episodes_stmt->bind_param("i", $drama_id);
$episodes_stmt->execute();
$episodes_result = $episodes_stmt->get_result();
$episodes = [];
while ($episode = $episodes_result->fetch_assoc()) {
    $episodes[] = $episode;
}
$episodes_stmt->close();

// If no episodes are found in the database, create dummy episodes based on the HTML template
if (empty($episodes) && isset($drama['title']) && $drama['title'] == 'Mr Plankton') {
    $episode_count = 16;
    $episode_descriptions = [
        'Start your journey into the drama world!',
        'Exciting twists await in this episode.',
        'The plot thickens in Episode 3.',
        'An unexpected twist changes everything!',
        'Episode 5 delivers more drama and suspense.',
        'The stakes are higher than ever!',
        'Episode 7 brings emotional moments.',
        'Shocking revelations in Episode 8!',
        'Drama intensifies in Episode 9.',
        'The mystery deepens in Episode 10.',
        'Exciting moments in Episode 11!',
        'The tension reaches its peak in Episode 12.',
        'Episode 13 delivers thrilling surprises!',
        'The story takes an unexpected turn.',
        'Prepare for an epic climax in Episode 15.',
        'The final episode wraps up everything!'
    ];
    
    for ($i = 1; $i <= $episode_count; $i++) {
        $episodes[] = [
            'video_id' => 1000 + $i,
            'drama_id' => $drama_id,
            'video_type' => 'episode',
            'title' => 'Episode ' . $i,
            'video_url' => '',
            'episode_number' => $i,
            'duration' => '1h ' . rand(5, 20) . 'm',
            'description' => $episode_descriptions[$i-1] ?? 'Watch this exciting episode!'
        ];
    }
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($drama['title']); ?> - POP LK</title>
    <link rel="stylesheet" href="style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
</head>
<body>
    <div class="background"></div>

    <header>
        <h1>POP LK</h1>
        <nav>
        <ul class="nav-menu">
            <li><a href="index.php">Home</a></li> <!-- Add the Home link -->
            
        </ul>

        </nav>
        <div class="search-bar-container">
            <input type="text" id="search-input" class="search-bar" placeholder="Search...">
            <button id="search-button" class="search-button">🔍</button>
        </div>
    </header>
    <main>
        <!-- Drama Information Section -->
        <section id="drama-container">
            <div class="video-container">
                <?php if ($trailer && !empty($trailer['video_url'])): ?>
                <iframe
                    width="853"
                    height="480"
                    src="https://www.youtube.com/embed/rc6BVr9Wq3w?autoplay=1&mute=1"
                    title="<?php echo htmlspecialchars($drama['title']); ?> | Official Teaser"
                    frameborder="0"
                    allow="autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" 
                    referrerpolicy="strict-origin-when-cross-origin"
                    allowfullscreen>
                </iframe>
                </iframe>
        <?php elseif (!empty($drama['poster_url'])): ?>
    <div class="episode-poster img">
        <img src="<?php echo htmlspecialchars($drama['poster_url']); ?>" alt="<?php echo htmlspecialchars($drama['title']); ?>" class="drama-poster">
    </div>
<?php endif; ?>
</div> 
            <article id="drama-info">
                <div class="h2">
                    <h2><?php echo htmlspecialchars($drama['title']); ?></h2>
                </div>

                
                <?php if (!empty($drama['title_sinhala'])): ?>
                <h3 class="sinhala-title"><?php echo htmlspecialchars($drama['title_sinhala']); ?></h3>
                <?php endif; ?>
                
                <?php if (!empty($drama['genre'])): ?>
                <p><strong>Genre:</strong> <?php echo htmlspecialchars($drama['genre']); ?></p>
                <?php endif; ?>
                
                <?php if (!empty($drama['description'])): ?>
                <p><?php echo htmlspecialchars($drama['description']); ?></p>
                <?php endif; ?>
                
                <?php if (!empty($drama['imdb_rating'])): ?>
                <p>
                    <strong>IMDb Rating:</strong>
                    <a href="https://www.imdb.com/title/tt26693803/" target="_blank"><?php echo htmlspecialchars($drama['imdb_rating']); ?>/10</a>
                </p>
                <?php endif; ?>
                
                <?php if (count($episodes) > 0): ?>
                <p><strong>Number of episodes:</strong> <?php echo count($episodes); ?></p>
                <?php endif; ?>
                
                <?php if (!empty($drama['release_year'])): ?>
                <p><strong>Release Year:</strong> <?php echo htmlspecialchars($drama['release_year']); ?></p>
                <?php endif; ?>
                
                <?php if (!empty($drama['category_name'])): ?>
                <p><strong>Category:</strong> <?php echo htmlspecialchars($drama['category_name']); ?></p>
                <?php endif; ?>
                
                <p><strong>Main Actors:</strong> Lee Junho, Im Yoon-ah, Ahn Se-ha, and others</p>
            </article>
        </section>

        <!-- Episodes Section -->
        <?php if (!empty($episodes)): ?>
        <section id="episode">
            <h3>Latest Episodes</h3>
            <div class="episode-grid">
                <?php foreach ($episodes as $episode): ?>
                <div class="episode-card">
                    <img src="<?php echo !empty($drama['poster_url']) ? htmlspecialchars($drama['poster_url']) : '10 New Korean Dramas Coming Out In November 2024 To Add To Your Watch List.jpeg'; ?>" alt="Episode <?php echo $episode['episode_number']; ?>">
                    <div class="episode-tag">Episode <?php echo $episode['episode_number']; ?></div>
                    <div class="episode-details">
                        <div class="price-tag">FREE</div>
                        
                        <?php if (!empty($episode['description'])): ?>
                        <div class="episode-description"><?php echo htmlspecialchars($episode['description']); ?></div>
                        <?php endif; ?>
                        
                        <?php if (!empty($episode['video_url'])): ?>
                        <a href="watch.php?id=<?php echo $episode['video_id']; ?>" target="_blank">
                            <button class="watch-button">Watch Online</button>
                        </a>
                        <?php else: ?>
                        <button class="watch-button">Watch Online</button>
                        <?php endif; ?>
                        
                        <button class="download-button">Download</button>
                        <div class="completion-tag">Not Completed</div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </section>
        <?php endif; ?>
    </main>

    <footer>
        <p>&copy; <?php echo date('Y'); ?> POP LK. All Rights Reserved.</p>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Search functionality
            const searchButton = document.getElementById('search-button');
            const searchInput = document.getElementById('search-input');
            
            searchButton.addEventListener('click', function() {
                const searchTerm = searchInput.value.trim();
                if (searchTerm !== '') {
                    window.location.href = 'search.php?q=' + encodeURIComponent(searchTerm);
                }
            });
            
            searchInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    searchButton.click();
                }
            });
        });
    </script>
</body>
</html>

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
  header("Location: index.php");
  exit;
}

// Get drama details using prepared statement
$sql = "SELECT d.*, c.category_name 
      FROM dramas d
      JOIN categories c ON d.category_id = c.category_id
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
  $stmt->close();
  header("Location: index.php");
  exit;
}

$drama = $result->fetch_assoc();
$stmt->close();

// Get drama videos using prepared statement
$sql = "SELECT * FROM videos WHERE drama_id = ? ORDER BY video_type, episode_number";
$stmt = $conn->prepare($sql);
if (!$stmt) {
  error_log("Prepare failed: " . $conn->error);
  header("Location: index.php");
  exit;
}

$stmt->bind_param("i", $drama_id);
$stmt->execute();
$videos_result = $stmt->get_result();
$stmt->close();

// Separate videos into trailer and episodes
$trailer = null;
$episodes = [];

while ($video = $videos_result->fetch_assoc()) {
  if (strtolower($video['video_type']) === 'trailer') {
    $trailer = $video;
  } else {
    $episodes[] = $video;
  }
}
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
  <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400..700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
  <header>
    <div class="logo">
      <a href="index.php">
        <h1>POP LK</h1>
      </a>
    </div>
    <nav>
      <ul class="nav-menu">
        <li><a href="index.php#kdrama">KDrama</a></li>
        <li><a href="index.php#cdrama">CDrama</a></li>
        <li><a href="index.php#jdrama">JDrama</a></li>
        <li><a href="index.php#others">Others</a></li>
      </ul>
    </nav>
    <div class="search-bar-container">
      <input type="text" id="search-input" class="search-bar" placeholder="Search...">
      <button id="search-button" class="search-button">🔍</button>
    </div>
  </header>

  <main>
    <div class="container">
      <a href="index.php" class="back-home-btn">← Back to Home</a>
      
      <div class="drama-details-container">
        <div class="drama-poster-container">
          <img src="<?php echo htmlspecialchars($drama['poster_url']); ?>" alt="<?php echo htmlspecialchars($drama['title']); ?>" class="drama-poster-img">
        </div>
        
        <div class="drama-info-container">
          <h1 class="drama-title"><?php echo htmlspecialchars($drama['title']); ?></h1>
          
          <?php if (!empty($drama['title_sinhala'])) { ?>
            <h2 class="drama-title-sinhala"><?php echo htmlspecialchars($drama['title_sinhala']); ?></h2>
          <?php } ?>
          
          <div class="drama-meta">
            <span class="drama-category"><?php echo htmlspecialchars($drama['category_name']); ?></span>
            <?php if (!empty($drama['genre'])) { ?>
              <span class="meta-separator">•</span>
              <span class="drama-genre"><?php echo htmlspecialchars($drama['genre']); ?></span>
            <?php } ?>
            <?php if (!empty($drama['release_year'])) { ?>
              <span class="meta-separator">•</span>
              <span class="drama-year"><?php echo htmlspecialchars($drama['release_year']); ?></span>
            <?php } ?>
          </div>
          
          <?php if (!empty($drama['imdb_rating'])) { ?>
            <div class="drama-rating">
              <i class="fas fa-star rating-star"></i>
              <span class="rating-value"><?php echo htmlspecialchars($drama['imdb_rating']); ?></span>
              <span class="rating-max">/10</span>
            </div>
          <?php } ?>
          
          <?php if (!empty($drama['description'])) { ?>
            <div class="drama-description">
              <p><?php echo htmlspecialchars($drama['description']); ?></p>
            </div>
          <?php } ?>
          
          <div class="drama-actions">
            <?php if ($trailer) { ?>
              <a href="#trailer" class="action-button watch-trailer-btn">
                <i class="fas fa-play"></i> Watch Trailer
              </a>
            <?php } ?>
            <?php if (!empty($episodes)) { ?>
              <a href="#episodes" class="action-button view-episodes-btn">
                <i class="fas fa-list"></i> View Episodes
              </a>
            <?php } ?>
            <button class="action-button favorite-btn">
              <i class="fas fa-heart"></i> Add to Favorites
            </button>
          </div>
        </div>
      </div>
      
      <!-- Trailer Section -->
      <?php if ($trailer) { ?>
        <div id="trailer" class="section-container">
          <h2 class="section-title">Trailer</h2>
          <div class="video-player">
            <video controls width="100%">
              <source src="<?php echo htmlspecialchars($trailer['video_url']); ?>" type="video/mp4">
              Your browser does not support the video tag.
            </video>
          </div>
        </div>
      <?php } ?>
      
      <!-- Episodes Section -->
      <?php if (!empty($episodes)) { ?>
        <div id="episodes" class="section-container">
          <h2 class="section-title">Episodes</h2>
          
          <div class="episodes-list">
            <?php foreach ($episodes as $episode) { ?>
              <div class="episode-item">
                <div class="episode-number">
                  <span>EP <?php echo $episode['episode_number']; ?></span>
                </div>
                
                <div class="episode-content">
                  <h3 class="episode-title">
                    <?php 
                    if (!empty($episode['title'])) {
                      echo htmlspecialchars($episode['title']);
                    } else {
                      echo 'Episode ' . $episode['episode_number'];
                    }
                    ?>
                  </h3>
                  
                  <?php if (!empty($episode['description'])) { ?>
                    <p class="episode-description"><?php echo htmlspecialchars($episode['description']); ?></p>
                  <?php } ?>
                  
                  <div class="episode-actions">
                    <a href="watch.php?id=<?php echo $episode['video_id']; ?>" class="watch-button">
                      <i class="fas fa-play"></i> Watch Now
                    </a>
                    <a href="download.php?id=<?php echo $episode['video_id']; ?>" class="download-button">
                      <i class="fas fa-download"></i> Download
                    </a>
                  </div>
                </div>
              </div>
            <?php } ?>
          </div>
        </div>
      <?php } ?>
    </div>
  </main>

  <footer>
    <p>&copy; <?php echo date('Y'); ?> POP LK. All Rights Reserved.</p>
  </footer>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Add active class to current nav item
      const currentPath = window.location.pathname;
      const navLinks = document.querySelectorAll('.nav-menu a');
      
      navLinks.forEach(link => {
        if (link.getAttribute('href').includes(currentPath)) {
          link.classList.add('active');
        }
      });
    });
  </script>
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>


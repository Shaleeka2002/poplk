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

// Function to get dramas by category
function getDramasByCategory($conn, $categoryId, $limit = 0) {
    $limitClause = $limit > 0 ? "LIMIT $limit" : "";
    $sql = "SELECT * FROM dramas WHERE category_id = $categoryId ORDER BY is_new DESC, drama_id DESC $limitClause";
    $result = $conn->query($sql);
    
    // Check if query was successful
    if (!$result) {
        echo "Error: " . $conn->error;
        return false;
    }
    
    return $result;
}

// Function to get slideshow items
function getSlideshow($conn) {
    $sql = "SELECT s.*, d.title, d.title_sinhala, v.video_url 
            FROM slideshow s 
            JOIN dramas d ON s.drama_id = d.drama_id 
            LEFT JOIN videos v ON v.drama_id = d.drama_id AND v.video_type = 'trailer'
            WHERE s.is_active = 1 
            ORDER BY s.display_order";
    $result = $conn->query($sql);
    
    // Check if query was successful
    if (!$result) {
        echo "Error: " . $conn->error;
        return false;
    }
    
    return $result;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POP LK</title>
    <link rel="stylesheet" href="style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
</head>
<body>
    <header>
        <h1>POP LK</h1>
        <nav>
            <ul class="nav-menu">
                <li><a href="#kdrama">KDrama</a></li>
                <li><a href="#cdrama">CDrama</a></li>
                <li><a href="#jdrama">JDrama</a></li>
                <li><a href="#others">Others</a></li>
            </ul>
        </nav>
        <div class="search-bar-container">
            <input type="text" id="search-input" class="search-bar" placeholder="Search...">
            <button id="search-button" class="search-button">🔍</button>
        </div>
    </header>

    <main>
        <div id="slideshow">
            <div class="slides">
                <?php
                $slideshowResult = getSlideshow($conn);
                $dotCount = 0;
                
                if ($slideshowResult && $slideshowResult->num_rows > 0) {
                    while($slide = $slideshowResult->fetch_assoc()) {
                        $dotCount++;
                        ?>
                        <div class="slide">
                            <?php if (!empty($slide['video_url'])) { ?>
                                <video autoplay muted loop playsinline>
                                    <source src="<?php echo htmlspecialchars($slide['video_url']); ?>" type="video/mp4">
                                    Your browser does not support the video tag.
                                </video>
                            <?php } else { ?>
                                <div class="fallback-image">No video available</div>
                            <?php } ?>
                            <div class="slide-content">
                                <h1><?php echo htmlspecialchars($slide['title']); ?></h1>
                                <p><?php echo htmlspecialchars($slide['description']); ?></p>
                                <a href="<?php echo htmlspecialchars($slide['button_link']); ?>" class="watch-now"><?php echo htmlspecialchars($slide['button_text']); ?></a>
                            </div>
                        </div>
                        <?php
                    }
                } else {
                    echo '<div class="slide"><div class="fallback-image">No slideshow items found</div></div>';
                    $dotCount = 1;
                }
                ?>
            </div>
            
            <!-- Dot Navigation -->
            <div class="dot-container">
                <?php for($i = 0; $i < $dotCount; $i++) { ?>
                    <div class="slideshow-dot <?php echo ($i === 0) ? 'active' : ''; ?>"></div>
                <?php } ?>
            </div>
        </div>

        <!-- KDrama Section -->
        <section id="kdrama" class="drama-section">
            <h2>KDrama</h2>
            <div class="drama-container">
                <div class="navigation-arrows">
                    <button class="arrow prev" data-section="kdrama" aria-label="Previous Dramas">&lt;</button>
                    <button class="arrow next" data-section="kdrama" aria-label="Next Dramas">&gt;</button>
                </div>

                <div class="drama-grid" id="kdrama-grid">
                    <?php
                    $kdramaResult = getDramasByCategory($conn, 1);
                    
                    if ($kdramaResult && $kdramaResult->num_rows > 0) {
                        while($drama = $kdramaResult->fetch_assoc()) {
                            ?>
                            <a href="drama.php?id=<?php echo $drama['drama_id']; ?>" class="drama-card-link">
                                <div class="drama-card">
                                    <?php if ($drama["is_new"]) { ?>
                                        <div class="tag">NEW</div>
                                    <?php } ?>
                                    <div class="poster-container">
                                        <img src="<?php echo htmlspecialchars($drama["poster_url"]); ?>" alt="<?php echo htmlspecialchars($drama["title"]); ?>" onerror="this.src='images/placeholder.jpg';">
                                    </div>
                                    <div class="drama-info">
                                        <h3><?php echo htmlspecialchars($drama["title"]); ?></h3>
                                        <?php if (!empty($drama["title_sinhala"])) { ?>
                                            <h4><?php echo htmlspecialchars($drama["title_sinhala"]); ?></h4>
                                        <?php } ?>
                                        <p>Genre: <?php echo htmlspecialchars($drama["genre"]); ?></p>
                                        <div class="rating-container">
                                            <div class="imdb-logo">IMDb</div>
                                            <div class="rating-value"><b><?php echo htmlspecialchars($drama["imdb_rating"]); ?>/10</b></div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                            <?php
                        }
                    } else {
                        echo "<p class='no-content'>No KDramas found</p>";
                    }
                    ?>
                </div>
                <div class="see-all-btn-container">
                    <a href="kdrama-see-all.php" class="see-all-btn">See All</a>
                </div>
            </div>
        </section>

        <!-- CDrama Section -->
        <section id="cdrama" class="drama-section">
            <h2>CDrama</h2>
            <div class="drama-container">
                <div class="navigation-arrows">
                    <button class="arrow prev" data-section="cdrama" aria-label="Previous Dramas">&lt;</button>
                    <button class="arrow next" data-section="cdrama" aria-label="Next Dramas">&gt;</button>
                </div>

                <div class="drama-grid" id="cdrama-grid">
                    <?php
                    $cdramaResult = getDramasByCategory($conn, 2);
                    
                    if ($cdramaResult && $cdramaResult->num_rows > 0) {
                        while($drama = $cdramaResult->fetch_assoc()) {
                            ?>
                            <a href="drama.php?id=<?php echo $drama['drama_id']; ?>" class="drama-card-link">
                                <div class="drama-card">
                                    <?php if ($drama["is_new"]) { ?>
                                        <div class="tag">NEW</div>
                                    <?php } ?>
                                    <div class="poster-container">
                                        <img src="<?php echo htmlspecialchars($drama["poster_url"]); ?>" alt="<?php echo htmlspecialchars($drama["title"]); ?>" onerror="this.src='images/placeholder.jpg';">
                                    </div>
                                    <div class="drama-info">
                                        <h3><?php echo htmlspecialchars($drama["title"]); ?></h3>
                                        <?php if (!empty($drama["title_sinhala"])) { ?>
                                            <h4><?php echo htmlspecialchars($drama["title_sinhala"]); ?></h4>
                                        <?php } ?>
                                        <p>Genre: <?php echo htmlspecialchars($drama["genre"]); ?></p>
                                        <div class="rating-container">
                                            <div class="imdb-logo">IMDb</div>
                                            <div class="rating-value"><b><?php echo htmlspecialchars($drama["imdb_rating"]); ?>/10</b></div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                            <?php
                        }
                    } else {
                        echo "<p class='no-content'>No CDramas found</p>";
                    }
                    ?>
                </div>
                <div class="see-all-btn-container">
                    <a href="cdrama-see-all.php" class="see-all-btn">See All</a>
                </div>
            </div>
        </section>

        <!-- JDrama Section -->
        <section id="jdrama" class="drama-section">
            <h2>JDrama</h2>
            <div class="drama-container">
                <div class="navigation-arrows">
                    <button class="arrow prev" data-section="jdrama" aria-label="Previous Dramas">&lt;</button>
                    <button class="arrow next" data-section="jdrama" aria-label="Next Dramas">&gt;</button>
                </div>
                
                <div class="drama-grid" id="jdrama-grid">
                    <?php
                    $jdramaResult = getDramasByCategory($conn, 3);
                    
                    if ($jdramaResult && $jdramaResult->num_rows > 0) {
                        while($drama = $jdramaResult->fetch_assoc()) {
                            ?>
                            <a href="drama.php?id=<?php echo $drama['drama_id']; ?>" class="drama-card-link">
                                <div class="drama-card">
                                    <?php if ($drama["is_new"]) { ?>
                                        <div class="tag">NEW</div>
                                    <?php } ?>
                                    <div class="poster-container">
                                        <img src="<?php echo htmlspecialchars($drama["poster_url"]); ?>" alt="<?php echo htmlspecialchars($drama["title"]); ?>" onerror="this.src='images/placeholder.jpg';">
                                    </div>
                                    <div class="drama-info">
                                        <h3><?php echo htmlspecialchars($drama["title"]); ?></h3>
                                        <?php if (!empty($drama["title_sinhala"])) { ?>
                                            <h4><?php echo htmlspecialchars($drama["title_sinhala"]); ?></h4>
                                        <?php } ?>
                                        <p>Genre: <?php echo htmlspecialchars($drama["genre"]); ?></p>
                                        <div class="rating-container">
                                            <div class="imdb-logo">IMDb</div>
                                            <div class="rating-value"><b><?php echo htmlspecialchars($drama["imdb_rating"]); ?>/10</b></div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                            <?php
                        }
                    } else {
                        echo "<p class='no-content'>No JDramas found</p>";
                    }
                    ?>
                </div>
                <div class="see-all-btn-container">
                    <a href="jdrama-see-all.php" class="see-all-btn">See All</a>
                </div>
            </div>
        </section>

        <!-- Others Section -->
        <section id="others" class="drama-section">
            <h2>Others</h2>
            <div class="drama-container">
                <div class="navigation-arrows">
                    <button class="arrow prev" data-section="others" aria-label="Previous Dramas">&lt;</button>
                    <button class="arrow next" data-section="others" aria-label="Next Dramas">&gt;</button>
                </div>
                
                <div class="drama-grid" id="others-grid">
                    <?php
                    $othersResult = getDramasByCategory($conn, 4);
                    
                    if ($othersResult && $othersResult->num_rows > 0) {
                        while($drama = $othersResult->fetch_assoc()) {
                            ?>
                            <a href="drama.php?id=<?php echo $drama['drama_id']; ?>" class="drama-card-link">
                                <div class="drama-card">
                                    <?php if ($drama["is_new"]) { ?>
                                        <div class="tag">NEW</div>
                                    <?php } ?>
                                    <div class="poster-container">
                                        <img src="<?php echo htmlspecialchars($drama["poster_url"]); ?>" alt="<?php echo htmlspecialchars($drama["title"]); ?>" onerror="this.src='images/placeholder.jpg';">
                                    </div>
                                    <div class="drama-info">
                                        <h3><?php echo htmlspecialchars($drama["title"]); ?></h3>
                                        <?php if (!empty($drama["title_sinhala"])) { ?>
                                            <h4><?php echo htmlspecialchars($drama["title_sinhala"]); ?></h4>
                                        <?php } ?>
                                        <p>Genre: <?php echo htmlspecialchars($drama["genre"]); ?></p>
                                        <div class="rating-container">
                                            <div class="imdb-logo">IMDb</div>
                                            <div class="rating-value"><b><?php echo htmlspecialchars($drama["imdb_rating"]); ?>/10</b></div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                            <?php
                        }
                    } else {
                        echo "<p class='no-content'>No other dramas found</p>";
                    }
                    ?>
                </div>
                <div class="see-all-btn-container">
                    <a href="others-see-all.php" class="see-all-btn">See All</a>
                </div>
            </div>
        </section>
    </main>

    <footer>
    <p>&copy; <?php echo date('Y'); ?></p>
</p>
</p>
    POP LK. All Rights Reserved.</p>
    </footer>

    <script src="script.js"></script>
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
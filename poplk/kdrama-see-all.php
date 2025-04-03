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

// Get category ID (1 for KDrama)
$categoryId = 1;

// Get category name
$sql = "SELECT category_name FROM categories WHERE category_id = $categoryId";
$result = $conn->query($sql);
$category = $result->fetch_assoc();
$categoryName = $category['category_name'];

// Get all dramas in this category
$sql = "SELECT * FROM dramas WHERE category_id = $categoryId ORDER BY is_new DESC, drama_id DESC";
$dramas = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All <?php echo $categoryName; ?> - POP LK</title>
    <link rel="stylesheet" href="style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <style>
        .all-dramas-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 30px;
            padding: 30px;
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .page-title {
            text-align: center;
            margin: 30px 0;
            font-size: 2.5rem;
        }
        
        .back-button {
            display: inline-block;
            background-color: #032541;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            margin: 20px 0 0 30px;
        }
        
        .back-button:hover {
            background-color: #01b4e4;
        }
    </style>
</head>
<body>
    <header>
        <h1>POP LK</h1>
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
        <a href="index.php" class="back-button">← Back to Home</a>
        <h1 class="page-title">All <?php echo $categoryName; ?></h1>
        
        <div class="all-dramas-grid">
            <?php
            if ($dramas->num_rows > 0) {
                while($drama = $dramas->fetch_assoc()) {
                    ?>
                    <a href="drama.php?id=<?php echo $drama['drama_id']; ?>" class="drama-card">
                        <div class="drama-card">
                            <?php if ($drama["is_new"]) { ?>
                                <div class="tag">NEW</div>
                            <?php } ?>
                            <img src="<?php echo $drama["poster_url"]; ?>" alt="<?php echo $drama["title"]; ?>">
                            <h3><?php echo $drama["title"]; ?></h3>
                            <?php if (!empty($drama["title_sinhala"])) { ?>
                                <h4><?php echo $drama["title_sinhala"]; ?></h4>
                            <?php } ?>
                            <p>Genre: <?php echo $drama["genre"]; ?></p>
                            <div class="rating-container">
                                <div class="imdb-logo">IMDb</div>
                                <div class="rating-value"><b><?php echo $drama["imdb_rating"]; ?>/10</b></div>
                            </div>
                        </div>
                    </a>
                    <?php
                }
            } else {
                echo "<p>No dramas found in this category</p>";
            }
            ?>
        </div>
    </main>

    <footer>
    <p>&copy; <?php echo date('Y'); ?></p>
    ?> POP LK. All Rights Reserved.</p>
    </footer>

    <script src="script.js"></script>
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
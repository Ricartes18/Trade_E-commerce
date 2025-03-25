<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PoCaSwap</title>
    <link rel="stylesheet" href="..\css\trade_upload.css">
    <link rel="stylesheet" href="..\css\navbar.css">
    <link rel="stylesheet" href="..\css\footer.css">
    <link href="https://fonts.googleapis.com/css2?family=Changa+One&family=Climate+Crisis&family=Dela+Gothic+One&display=swap" rel="stylesheet">
</head>

    <!-- Navigation Bar -->
        <nav class="navbar">
            <ul class="nav-links">
                <li><a href="#">Home</a></li>
                <li><a href="#">Shop</a></li>
                <li><a href="#">Tracker</a></li>
                <li><a href="#">Trades</a></li>
            </ul>
            <div class="logo">
                <img src="..\img\PoCaSwap Logo.png" alt="Logo">
            </div>
            <div class="profile">
                <a href="#"><img src="..\img\shopping_bag.png" alt="shopping bag"> @username</a>
            </div>
        </nav>

    <body>
    <!-- Main Section -->
    <div class="container">
    <div class="card-upload">
        <div class="upload-box">
            <p>Upload your card here</p>
            <input type="file" id="cardUpload">
        </div>

        <label for="cardName">Card Name:</label>
        <input type="text" id="cardName">

        <label for="cardDescription">Description:</label>
        <input type="text" id="cardDescription">
    </div>



        <h1 class="arrow">to</h1>

        <div class="card-preview">
            <img src="yunjin.png" alt="Photocard">
            <h3>Huh Yunjin - UNFORGIVEN Day</h3>
            <p>GROUP: LE SSERAFIM</p>
            <p>ALBUM: UNFORGIVEN</p>
            <p>VERSION: Day</p>
            <p>MEMBER: Huh Yunjin</p>
            <p>PHOTOCARD CONDITION: 10/10 Mint Condition</p>
        </div>
    </div>

    <button class="trade-btn">TRADE!</button>
    </body>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-wrapper">
            <div class="footer-center">
                <h2>PoCaSwap</h2>
                <p>Shop. Swap. Collect</p>
            </div>

            <div class="footer-bottom">
                <div class="footer-left">
                    <ul>
                        <li><a href="#">Home</a></li>
                        <li><a href="#">Shop</a></li>
                        <li><a href="#">Tracker</a></li>
                        <li><a href="#">Trades</a></li>
                        <li><a href="#">Shopping Bag</a></li>
                    </ul>
                </div>

                <div class="footer-right">
                    <a href="#"><img src="../img/instagram.png" alt="Instagram"></a>
                    <a href="#"><img src="../img/facebook.png" alt="Facebook"></a>
                    <a href="#"><img src="../img/twitter.png" alt="Twitter"></a>
                </div>
            </div>
        </div>
    </footer>
</html>

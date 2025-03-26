<?php

class Photocard {
    public $title;
    public $image;

    public function __construct($title, $image) {
        $this->title = $title;
        $this->image = $image;
    }

    public function displayCard() {
        return "
        <div class='card'>
            <img src='{$this->image}' alt='{$this->title}'>
        </div>";
    }
}

$chooseCard = new Photocard("Choose a Card", "images/choose_card.png");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/redirection.css">
    <link rel="stylesheet" href="css/footer.css">
    <link rel="stylesheet" href="css/header.css">
    <link rel="shortcut icon" href="images/PoCaSwap Logo.ico"/>
    <title>Choose a Card!</title>
</head>
<body>
    <header>
        <nav class="navbar">
            <ul class="nav-links">
                <li><a href="#">Home</a></li>
                <li><a href="#">Shop</a></li>
                <li><a href="#">Tracker</a></li>
                <li><a href="#">Trades</a></li>
            </ul>
            <div class="logo">
                <a href="index.php"><img src="images/PoCaSwap Logo.png" alt="Logo"></a>
            </div>
            <div class="profile">
                <a href="cart.php"><img src="images/shopping_bag.png" alt="shopping bag"> 
                    <?php 
                        if(isset($_SESSION['role'])){
                            if($_SESSION['role'] === "admin" ){
                                echo "<a href='admin/dashboard.php'>Dashboard</a>";
                            } if(($_SESSION['role']) === "user"){
                            echo "<p>".$_SESSION['username']."</p>";
                            }
                            echo "<div class='logout'>".
                                "<form action='logout.php' method='post'>". 
                                    "<button class='logout-btn' type='submit' name='logout'>".
                                        "<img src='images/logout_button.png' alt='Log out' class='logout-img'>".
                                    "</button>
                                </form>
                            </div>";
                        } else {
                            echo "<a href='login.php'>Login</a> <a>|</a> <a href='sign_up.php'>Sign Up</a>";
                        }
                    ?>
                </a>
            </div>
        </nav>
    </header>
    <div class="trade-container">
        <div class="container-left">
            <h2>Choose a card to trade!</h2>
            <a href="#" class="trade-button">Check Here!</a>
        </div>
        <div class="container-right">
            <?php echo $chooseCard->displayCard(); ?>
        </div>
    </div>
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
                    <a href="#"><img src="images/instagram.png" alt="Instagram"></a>
                    <a href="#"><img src="images/facebook.png" alt="Facebook"></a>
                    <a href="#"><img src="images/twitter.png" alt="Twitter"></a>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>

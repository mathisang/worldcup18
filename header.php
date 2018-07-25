<header>
    <div class="topnav">
        <div class="maxwidth">
            <?php
            if(empty($_SESSION['auth'])) {
                ?>
           <div></div>
            <?php
            }
            if(isset($_SESSION['auth'])) {
                ?>
                <p style="text-transform: uppercase; margin-left: .5rem">Bonjour, <span style="color: #0f4582"><?php echo $_SESSION['auth']->username ?></span> !</p>
                <a href="./deconnexion.php">DECONNEXION</a>
            <?php
            }
            else {
                ?>
                <div>
                <a href="./inscription.php">INSCRIPTION</a>
                <a href="./connexion.php">CONNEXION</a>
                </div>
                <?php
            }
            ?>
        </div>
    </div>
    <nav>
        <div class="maxwidth">
            <div class="leftnav">
                <a href="index.php"><img src="./lib/images/logo.png" alt="logo" /></a>
            </div>
            <div class="width">
                <div class="midnav">
                    <h1>PRONOSTICS COUPE DU MONDE 2018</h1>
                    <span>14 JUIN - 15 JUILLET</span>
                </div>
                <div class="rightnav">
                    <div class="hr"></div>
                    <div>
                        <a href="./index.php" <?php if($courante === "index") { echo 'class="active"'; } ?>>ACCUEIL</a>
                        <a href="./resultat.php" <?php if($courante === "resultat") { echo 'class="active"'; } ?>>RESULTAT</a>
                        <a href="./pronostics.php" <?php if($courante === "pronostique") { echo 'class="active"'; } ?>>PRONOSTIC</a>
                        <?php
                        if(isset($_SESSION['auth'])) {
                            if($_SESSION['auth']->admin === "1") {
                                ?>
                                <a href="./adminwc2018/">ADMINISTRATION</a>
                                <?php
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </nav>
</header>
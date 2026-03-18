<?php include("includes/header.php"); ?>
<?php 
require("includes/db.php"); 
if ($_SERVER["REQUEST_METHOD"]=="POST"){
    $username=$_POST["username"];
    $password=$_POST["password"];

    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$username]);

    $user = $stmt->fetch();

    if ($user) {
    
        if (password_verify($password, $user["password"])) {
            echo "Connexion réussie !";
        } else {
            echo "Mot de passe incorrect";
        }
    } else {
        echo "Utilisateur introuvable";
    }

}
?>
<form method=POST>
    Nom d'utilisateur<input type="text" name="username">
    Mot de passe<input type="password" name="password">
    <button type="submit">Se connecter</button>
</form>
<?php include("includes/footer.php"); ?>
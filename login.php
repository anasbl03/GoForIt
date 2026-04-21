<?php 
require("includes/db.php"); 
include("includes/header.php"); 

if ($_SERVER["REQUEST_METHOD"]=="POST") {

    $email = $_POST["email"];
    $password = $_POST["password"];

    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$email]);

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        if (password_verify($password, $user["password"])) {

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_role'] = $user['role']; 

            if (isset($_GET['redirect'])) {
                header("Location: " . $_GET['redirect']);
            } else {
                header("Location: index.php");
            }
            exit;

        } else {
            echo "Mot de passe incorrect";
        }
    } else {
        echo "Utilisateur introuvable";
    }
}
?>
<div class="container-fluid vh-100 d-flex align-items-center justify-content-center">

  <div class="card shadow-lg p-4" style="width: 420px; border-radius: 15px;">
    
    <div class="text-center mb-4">
      <h3 class="fw-bold">Connexion</h3>
      <p class="text-muted">Bienvenue, connecte-toi</p>
    </div>

    <form method="POST">

      <div class="form-floating mb-3">
        <input 
          type="email" 
          class="form-control" 
          name="email" 
          placeholder="Email"
          value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>"
          required
        >
        <label>Email</label>
      </div>

      <div class="form-floating mb-3">
        <input 
          type="password" 
          class="form-control" 
          name="password" 
          placeholder="Mot de passe"
          required
        >
        <label>Mot de passe</label>
      </div>

      <button class="btn btn-primary w-100 py-2 mb-3" type="submit">
        Se connecter
      </button>

      <div class="text-center">
        <a href="register.php" class="text-decoration-none">
          Pas encore de compte ? S'inscrire
        </a>
      </div>

    </form>

  </div>

</div>

<?php include("includes/footer.php"); ?>
<?php
include_once "Utilisateur.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $mdp = $_POST['mdp'];
    $confirmmdp = $_POST['confirmmdp'];

    $utilisateur = new Utilisateur($bd);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "L'adresse email est invalide.";
    } elseif ($mdp !== $confirmmdp) {
        $message = "Les mots de passe ne correspondent pas.";
    } elseif (!preg_match('/^(?=.*[A-Z])(?=.*[\W]).{8,12}$/', $mdp)) {
        $message = "Le mot de passe ne respecte pas les critères.";
    } else {
        $mdpHash = password_hash($mdp, PASSWORD_DEFAULT);
        $resultat = $utilisateur->ajouterUtilisateur($email, $mdpHash);
        if ($resultat) {
            $message = "Création réussie.";
        } else {
            $message = "Erreur lors de la création.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulaire de création de compte</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" />
    <link rel="stylesheet" href="formulaire.css" />
</head>
<body>
    <div class="formulaire-section">
        <h2>Créer un compte</h2>
        <form method="post" id="formulaire">
            <input type="email" id="email" name="email" placeholder="E-mail" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" title="Veuillez entrer une adresse e-mail valide." required>
            <span id="email-error-message" style="color:blue; display:none;">L'adresse e-mail est invalide.</span>

            <input type="password" id="mdp" name="mdp" placeholder="Mot de passe" pattern="^(?=.*[A-Z])(?=.*[\W]).{8,12}$" title="Le mot de passe doit contenir entre 8 et 12 caractères, avec au moins une majuscule et un caractère spécial." required>
            <span id="mdp-error-message" style="color:blue; display:none;">Le mot de passe doit contenir entre 8 et 12 caractères, avec au moins une majuscule et un caractère spécial.</span>

            <input type="password" id="confirmmdp" name="confirmmdp" placeholder="Confirmation de mot de passe" pattern="^(?=.*[A-Z])(?=.*[\W]).{8,12}$" title="Le mot de passe doit contenir entre 8 et 12 caractères, avec au moins une majuscule et un caractère spécial." required>
            <span id="error-message" style="color:blue; display:none;">Les mots de passe ne correspondent pas.</span>

            <button type="submit" name="submit">Créer le compte</button>
        </form>

        <div id="message-container" class="notification">
            <?php if (isset($message)) { ?>
                <?php echo $message; ?>
            <?php } ?>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/vanilla-tilt/1.8.1/vanilla-tilt.min.js" crossorigin="anonymous"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const form = document.getElementById('formulaire');
            const email = document.getElementById('email');
            const mdp = document.getElementById('mdp');
            const confirmMdp = document.getElementById('confirmmdp');
            const errorMessage = document.getElementById('error-message');
            const emailErrorMessage = document.getElementById('email-error-message');
            const mdpErrorMessage = document.getElementById('mdp-error-message');

            // Vérification de l'email en temps réel
            email.addEventListener('input', function() {
                const emailPattern = /^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$/;
                if (!emailPattern.test(email.value)) {
                    emailErrorMessage.style.display = 'block';
                } else {
                    emailErrorMessage.style.display = 'none';
                }
            });

            mdp.addEventListener('input', function() {
                const mdpPattern = /^(?=.*[A-Z])(?=.*[\W]).{8,12}$/;
                if (!mdpPattern.test(mdp.value)) {
                    mdpErrorMessage.style.display = 'block';
                } else {
                    mdpErrorMessage.style.display = 'none';
                }
            });

            confirmMdp.addEventListener('input', function() {
                if (mdp.value !== confirmMdp.value) {
                    errorMessage.style.display = 'block';
                } else {
                    errorMessage.style.display = 'none';
                }
            });

            <?php if (isset($message)) { ?>
                var notification = document.getElementById('message-container');
                notification.style.display = 'block';
                setTimeout(function(){
                    notification.style.opacity = '0';
                    setTimeout(function(){
                        notification.style.display = 'none';
                    }, 1000);
                }, 2000);
            <?php } ?>

            document.oncontextmenu = function() {
                alert('Vous ne pouvez pas copier/coller');
                return false; // Empêche le menu contextuel de s'afficher
            };

            document.onkeydown = function(event) {
                // Vérifie si les touches Ctrl+C ou Ctrl+V sont enfoncées
                if ((event.ctrlKey || event.metaKey) && (event.key === 'c' || event.key === 'v')) {
                    alert('Vous ne pouvez pas copier/coller');
                    return false; // Empêche l'action de copier/coller
                }
            };

                    });

            VanillaTilt.init(document.querySelector(".card.front"), {
                    max: 25,
                    speed: 300,
                    glare: true,
                    "max-glare": 0.4,
            });
    </script>
</body>
</html>

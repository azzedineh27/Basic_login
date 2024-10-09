<?php 
class Utilisateur {
    private $bd;

    public function __construct($bd) {
        $this->bd = $bd;
    }
    public function getUserById($userId) {
        $stmt = $this->bd->prepare("SELECT * FROM utilisateurs WHERE id = :id");
        $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result : null;
    }
    public function ajouterUtilisateur() {
    $req = $this->bd->prepare("INSERT INTO utilisateurs (email, mot_de_passe,) VALUES (:email, :mot_de_passe,)");
    $req->bindParam(':email', $nomUtilisateur);
    $req->bindParam(':mot_de_passe', $motDePasse);
    return $req->execute();
}

}
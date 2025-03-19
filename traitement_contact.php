<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$servername = "agencrk633.mysql.db"; // Remplace par l'hôte exact d'OVH
$username = "agencrk633"; // Remplace par ton utilisateur OVH
$password = "cUdkut2caxkymehwuz"; // Remplace par ton mot de passe OVH
$dbname = "agencrk633"; // Nom de la base OVH

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

$nature_demande = $_POST['nature_demande'];
$nom_prenom = $_POST['nom_prenom'];
$email = $_POST['email'];
$telephone = $_POST['telephone'];
$message = $_POST['message'];
$date = date('Y-m-d H:i:s');

$sql = "INSERT INTO contacts (nature_demande, nom_prenom, email, telephone, message, date) 
        VALUES (:nature_demande, :nom_prenom, :email, :telephone, :message, :date)";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':nature_demande', $nature_demande);
$stmt->bindParam(':nom_prenom', $nom_prenom);
$stmt->bindParam(':email', $email);
$stmt->bindParam(':telephone', $telephone);
$stmt->bindParam(':message', $message);
$stmt->bindParam(':date', $date);

try {
    $stmt->execute();
    $to = "maxime.renoudgrappin@gmail.com"; // Remplace par l’email de l’entreprise
    $subject = "Nouvelle soumission de formulaire de contact";
    $body = "Nature de la demande : $nature_demande\n" .
            "Nom et prénom : $nom_prenom\n" .
            "Email : $email\n" .
            "Téléphone : $telephone\n" .
            "Message : $message\n" .
            "Date : $date";
    $headers = "From: noreply@agencelesecrins.com\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
    mail($to, $subject, $body, $headers);
    echo "Message envoyé avec succès !";
} catch(PDOException $e) {
    echo "Erreur lors de l'insertion : " . $e->getMessage();
}

$conn = null;
?>

<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Définir le type de contenu de la réponse comme JSON
header('Content-Type: application/json');

// Fonction pour envoyer une réponse JSON et arrêter le script
function sendResponse($success, $message) {
    echo json_encode(['success' => $success, 'message' => $message]);
    exit;
}

// Validation des données du formulaire
if (!isset($_POST['nature_demande']) || empty($_POST['nature_demande']) ||
    !isset($_POST['nom_prenom']) || empty($_POST['nom_prenom']) ||
    !isset($_POST['email']) || empty($_POST['email']) ||
    !isset($_POST['telephone']) || empty($_POST['telephone']) ||
    !isset($_POST['message']) || empty($_POST['message'])) {
    sendResponse(false, "Tous les champs du formulaire sont requis.");
}

// Nettoyage des données pour éviter les injections
$nature_demande = filter_var($_POST['nature_demande'], FILTER_SANITIZE_STRING);
$nom_prenom = filter_var($_POST['nom_prenom'], FILTER_SANITIZE_STRING);
$email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
$telephone = filter_var($_POST['telephone'], FILTER_SANITIZE_STRING);
$message = filter_var($_POST['message'], FILTER_SANITIZE_STRING);
$date = date('Y-m-d H:i:s');

// Validation de l'email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    sendResponse(false, "L'adresse email n'est pas valide.");
}

// Connexion à la base de données
$servername = "agencrk633.mysql.db";
$username = "agencrk633";
$password = "cUdkut2caxkymehwuz";
$dbname = "agencrk633";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    sendResponse(false, "Erreur de connexion à la base de données : " . $e->getMessage());
}

// Insertion des données dans la base de données
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

    // Envoi de l'email
    $to = "maxime.renoudgrappin@gmail.com";
    $subject = "Nouvelle soumission de formulaire de contact";
    $body = "Nature de la demande : $nature_demande\n" .
            "Nom et prénom : $nom_prenom\n" .
            "Email : $email\n" .
            "Téléphone : $telephone\n" .
            "Message : $message\n" .
            "Date : $date";
    $headers = "From: noreply@agencelesecrins.com\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

    // Envoyer l'email et vérifier si cela réussit
    if (mail($to, $subject, $body, $headers)) {
        sendResponse(true, "Message envoyé avec succès !");
    } else {
        // Si l'email échoue, on renvoie une erreur mais les données sont déjà dans la base
        sendResponse(false, "Erreur lors de l'envoi de l'email, mais les données ont été enregistrées.");
    }
} catch(PDOException $e) {
    sendResponse(false, "Erreur lors de l'insertion dans la base de données : " . $e->getMessage());
}

// Fermer la connexion à la base de données
$conn = null;
?>

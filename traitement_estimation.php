<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// On définit le type de réponse en JSON
header('Content-Type: application/json');

// Fonction pour renvoyer une réponse JSON et arrêter le script
function sendResponse($success, $message) {
    echo json_encode(['success' => $success, 'message' => $message]);
    exit;
}

// Vérification des champs obligatoires
$required_fields = ['nom_prenom', 'telephone', 'email', 'adresse_bien', 'code_postal', 'ville', 'type_bien', 'remarques'];
foreach ($required_fields as $field) {
    if (!isset($_POST[$field]) || empty($_POST[$field])) {
        sendResponse(false, "Tous les champs du formulaire sont requis.");
    }
}

// Nettoyage des données
$nom_prenom   = filter_var($_POST['nom_prenom'], FILTER_SANITIZE_STRING);
$telephone    = filter_var($_POST['telephone'], FILTER_SANITIZE_STRING);
$email        = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
$adresse_bien = filter_var($_POST['adresse_bien'], FILTER_SANITIZE_STRING);
$code_postal  = filter_var($_POST['code_postal'], FILTER_SANITIZE_STRING);
$ville        = filter_var($_POST['ville'], FILTER_SANITIZE_STRING);
$type_bien    = filter_var($_POST['type_bien'], FILTER_SANITIZE_STRING);
$remarques    = filter_var($_POST['remarques'], FILTER_SANITIZE_STRING);
$date         = date('Y-m-d H:i:s');

// Validation de l'email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    sendResponse(false, "L'adresse email n'est pas valide.");
}

// Connexion à la base de données
$servername = "agencrk633.mysql.db";
$username   = "agencrk633";
$password   = "cUdkut2caxkymehwuz";
$dbname     = "agencrk633";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    sendResponse(false, "Erreur de connexion à la base de données : " . $e->getMessage());
}

// Récupération des champs optionnels et conversion si nécessaire
$annee_construction = isset($_POST['annee_construction']) ? (int)$_POST['annee_construction'] : null;
$nombre_pieces      = isset($_POST['nombre_pieces']) ? (int)$_POST['nombre_pieces'] : null;
$surface_habitable  = isset($_POST['surface_habitable']) ? (float)$_POST['surface_habitable'] : null;
$surface_exterieure = isset($_POST['surface_exterieure']) ? (float)$_POST['surface_exterieure'] : null;
$annexes            = $_POST['annexes'] ?? null;
if (is_array($annexes)) {
    $annexes = implode(', ', $annexes);
}
$etat_general   = $_POST['etat_general'] ?? null;
$surface_carrez = isset($_POST['surface_carrez']) ? (float)$_POST['surface_carrez'] : null;
$etage          = isset($_POST['etage']) ? (int)$_POST['etage'] : null;
$ascenseur      = $_POST['ascenseur'] ?? null;
$exposition     = $_POST['exposition'] ?? null;
$balcon         = $_POST['balcon'] ?? null;
$terrasse       = $_POST['terrasse'] ?? null;
$lots_habitation= isset($_POST['lots_habitation']) ? (int)$_POST['lots_habitation'] : null;
$lots_commerciaux= isset($_POST['lots_commerciaux']) ? (int)$_POST['lots_commerciaux'] : null;
$nombre_etages  = isset($_POST['nombre_etages']) ? (int)$_POST['nombre_etages'] : null;
$surface        = isset($_POST['surface']) ? (float)$_POST['surface'] : null;
$superficie     = isset($_POST['superficie']) ? (float)$_POST['superficie'] : null;
$constructible  = $_POST['constructible'] ?? null;
$viabilise      = $_POST['viabilise'] ?? null;
$servitude      = $_POST['servitude'] ?? null;
$permis_construire = $_POST['permis_construire'] ?? null;
$description    = $_POST['description'] ?? null;
$type_estimation= $_POST['type_estimation'] ?? null;

// Préparation et exécution de l'insertion dans la table "estimations"
$sql = "INSERT INTO estimations (
    nom_prenom, telephone, email, adresse_bien, code_postal, ville, type_bien, remarques, date,
    annee_construction, nombre_pieces, surface_habitable, surface_exterieure, annexes, etat_general,
    surface_carrez, etage, ascenseur, exposition, balcon, terrasse, lots_habitation, lots_commerciaux,
    nombre_etages, surface, superficie, constructible, viabilise, servitude, permis_construire, description, type_estimation
) VALUES (
    :nom_prenom, :telephone, :email, :adresse_bien, :code_postal, :ville, :type_bien, :remarques, :date,
    :annee_construction, :nombre_pieces, :surface_habitable, :surface_exterieure, :annexes, :etat_general,
    :surface_carrez, :etage, :ascenseur, :exposition, :balcon, :terrasse, :lots_habitation, :lots_commerciaux,
    :nombre_etages, :surface, :superficie, :constructible, :viabilise, :servitude, :permis_construire, :description, :type_estimation
)";

$stmt = $conn->prepare($sql);
$stmt->bindParam(':nom_prenom', $nom_prenom);
$stmt->bindParam(':telephone', $telephone);
$stmt->bindParam(':email', $email);
$stmt->bindParam(':adresse_bien', $adresse_bien);
$stmt->bindParam(':code_postal', $code_postal);
$stmt->bindParam(':ville', $ville);
$stmt->bindParam(':type_bien', $type_bien);
$stmt->bindParam(':remarques', $remarques);
$stmt->bindParam(':date', $date);
$stmt->bindParam(':annee_construction', $annee_construction, PDO::PARAM_INT);
$stmt->bindParam(':nombre_pieces', $nombre_pieces, PDO::PARAM_INT);
$stmt->bindParam(':surface_habitable', $surface_habitable);
$stmt->bindParam(':surface_exterieure', $surface_exterieure);
$stmt->bindParam(':annexes', $annexes);
$stmt->bindParam(':etat_general', $etat_general);
$stmt->bindParam(':surface_carrez', $surface_carrez);
$stmt->bindParam(':etage', $etage, PDO::PARAM_INT);
$stmt->bindParam(':ascenseur', $ascenseur);
$stmt->bindParam(':exposition', $exposition);
$stmt->bindParam(':balcon', $balcon);
$stmt->bindParam(':terrasse', $terrasse);
$stmt->bindParam(':lots_habitation', $lots_habitation, PDO::PARAM_INT);
$stmt->bindParam(':lots_commerciaux', $lots_commerciaux, PDO::PARAM_INT);
$stmt->bindParam(':nombre_etages', $nombre_etages, PDO::PARAM_INT);
$stmt->bindParam(':surface', $surface);
$stmt->bindParam(':superficie', $superficie);
$stmt->bindParam(':constructible', $constructible);
$stmt->bindParam(':viabilise', $viabilise);
$stmt->bindParam(':servitude', $servitude);
$stmt->bindParam(':permis_construire', $permis_construire);
$stmt->bindParam(':description', $description);
$stmt->bindParam(':type_estimation', $type_estimation);

try {
    $stmt->execute();

    // Envoi de l'email de notification
    $to = "maxime.renoudgrappin@gmail.com";
    $subject = "Nouvelle demande d'estimation";
    $body = "Nom et prénom : $nom_prenom\n" .
            "Téléphone : $telephone\n" .
            "Email : $email\n" .
            "Adresse du bien : $adresse_bien, $code_postal $ville\n" .
            "Type de bien : $type_bien\n" .
            "Remarques : $remarques\n" .
            "Date : $date\n" .
            (isset($annee_construction) ? "Année de construction : $annee_construction\n" : "") .
            (isset($nombre_pieces) ? "Nombre de pièces : $nombre_pieces\n" : "") .
            (isset($surface_habitable) ? "Surface habitable : $surface_habitable m²\n" : "") .
            (isset($surface_exterieure) ? "Surface extérieure : $surface_exterieure m²\n" : "") .
            (isset($annexes) ? "Annexes : $annexes\n" : "") .
            (isset($etat_general) ? "État général : $etat_general\n" : "") .
            (isset($surface_carrez) ? "Surface Carrez : $surface_carrez m²\n" : "") .
            (isset($etage) ? "Étage : $etage\n" : "") .
            (isset($ascenseur) ? "Ascenseur : $ascenseur\n" : "") .
            (isset($exposition) ? "Exposition : $exposition\n" : "") .
            (isset($balcon) ? "Balcon : $balcon\n" : "") .
            (isset($terrasse) ? "Terrasse : $terrasse\n" : "") .
            (isset($lots_habitation) ? "Lots d'habitation : $lots_habitation\n" : "") .
            (isset($lots_commerciaux) ? "Lots commerciaux : $lots_commerciaux\n" : "") .
            (isset($nombre_etages) ? "Nombre d'étages : $nombre_etages\n" : "") .
            (isset($surface) ? "Surface : $surface m²\n" : "") .
            (isset($superficie) ? "Superficie : $superficie m²\n" : "") .
            (isset($constructible) ? "Constructible : $constructible\n" : "") .
            (isset($viabilise) ? "Viabilisé : $viabilise\n" : "") .
            (isset($servitude) ? "Servitude : $servitude\n" : "") .
            (isset($permis_construire) ? "Permis de construire : $permis_construire\n" : "") .
            (isset($description) ? "Description : $description\n" : "") .
            (isset($type_estimation) ? "Type d'estimation : $type_estimation\n" : "");
    $headers = "From: noreply@agencelesecrins.com\r\n" .
               "Content-Type: text/plain; charset=UTF-8\r\n";
    mail($to, $subject, $body, $headers);

    // On renvoie la réponse JSON
    sendResponse(true, "Demande d'estimation envoyée avec succès !");
} catch(PDOException $e) {
    sendResponse(false, "Erreur lors de l'insertion : " . $e->getMessage());
}

$conn = null;
?>

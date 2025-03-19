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

// Champs principaux
$nom_prenom = $_POST['nom_prenom'] ?? '';
$telephone = $_POST['telephone'] ?? '';
$email = $_POST['email'] ?? '';
$adresse_bien = $_POST['adresse_bien'] ?? '';
$code_postal = $_POST['code_postal'] ?? '';
$ville = $_POST['ville'] ?? '';
$type_bien = $_POST['type_bien'] ?? '';
$remarques = $_POST['remarques'] ?? '';
$date = date('Y-m-d H:i:s');

// Champs dynamiques (optionnels)
$annee_construction = isset($_POST['annee_construction']) ? (int)$_POST['annee_construction'] : null;
$nombre_pieces = isset($_POST['nombre_pieces']) ? (int)$_POST['nombre_pieces'] : null;
$surface_habitable = isset($_POST['surface_habitable']) ? (float)$_POST['surface_habitable'] : null;
$surface_exterieure = isset($_POST['surface_exterieure']) ? (float)$_POST['surface_exterieure'] : null;
$annexes = $_POST['annexes'] ?? null;
$etat_general = $_POST['etat_general'] ?? null;
$surface_carrez = isset($_POST['surface_carrez']) ? (float)$_POST['surface_carrez'] : null;
$etage = isset($_POST['etage']) ? (int)$_POST['etage'] : null;
$ascenseur = $_POST['ascenseur'] ?? null;
$exposition = $_POST['exposition'] ?? null;
$balcon = $_POST['balcon'] ?? null;
$terrasse = $_POST['terrasse'] ?? null;
$lots_habitation = isset($_POST['lots_habitation']) ? (int)$_POST['lots_habitation'] : null;
$lots_commerciaux = isset($_POST['lots_commerciaux']) ? (int)$_POST['lots_commerciaux'] : null;
$nombre_etages = isset($_POST['nombre_etages']) ? (int)$_POST['nombre_etages'] : null;
$surface = isset($_POST['surface']) ? (float)$_POST['surface'] : null;
$superficie = isset($_POST['superficie']) ? (float)$_POST['superficie'] : null;
$constructible = $_POST['constructible'] ?? null;
$viabilise = $_POST['viabilise'] ?? null;
$servitude = $_POST['servitude'] ?? null;
$permis_construire = $_POST['permis_construire'] ?? null;
$description = $_POST['description'] ?? null;
$type_estimation = $_POST['type_estimation'] ?? null;

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
    $to = "maxime.renoudgrappin@gmail.com"; // Remplace par l’email de l’entreprise
    $subject = "Nouvelle demande d'estimation";
    $body = "Nom et prénom : $nom_prenom\n" .
            "Téléphone : $telephone\n" .
            "Email : $email\n" .
            "Adresse du bien : $adresse_bien, $code_postal $ville\n" .
            "Type de bien : $type_bien\n" .
            "Remarques : $remarques\n" .
            "Date : $date\n" .
            // Ajoute les champs dynamiques s’ils existent
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
    $headers = "From: noreply@agencelesecrins.com\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
    mail($to, $subject, $body, $headers);
    echo "Demande d'estimation envoyée avec succès !";
} catch(PDOException $e) {
    echo "Erreur lors de l'insertion : " . $e->getMessage();
}

$conn = null;
?>

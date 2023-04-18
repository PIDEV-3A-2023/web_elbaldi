<?php
// Se connecter à la base de données
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "eratech";

$conn = mysqli_connect($servername, $username, $password, $dbname);

// Vérifier la connexion
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}

// Récupérer les données POST
$ref_produit = $_POST['ref_produit'];
$nouveauPrix = $_POST['prix'];

// Mettre à jour le prix du produit dans la base de données
$sql = "UPDATE produit SET prixVente = '$nouveauPrix' WHERE ref_produit = '$ref_produit'";

if (mysqli_query($conn, $sql)) {
  // Retourner une réponse JSON avec succès
  $response = array('success' => true);
  echo json_encode($response);
} else {
  // Retourner une réponse JSON avec une erreur
  $response = array('success' => false, 'error' => mysqli_error($conn));
  echo json_encode($response);
}

// Fermer la connexion à la base de données
mysqli_close($conn);
?>

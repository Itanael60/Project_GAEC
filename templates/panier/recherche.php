<?php 

include("lib/connexion.php");

$recherche = $_POST["recherche"] ?? "";
if(!empty($texte)){
    $stmt = $pdo->prepare("INSERT INTO citations (texte, date_insertion) VALUES (?,?)");
    $result = $stmt->execute([$texte, (new DateTime("now"))->format("Y-m-d")]);
    $id=$pdo->lastInsertId();
    if($result)/* true par défaut */ {
       // echo "OK";
       $resultat = [ "id" => $id,
       "status" => "OK"];
       echo json_encode($resultat);
    } else {
        //echo "NOK";
        $resultat = [ "id" => 0,
       "status" => "NOK"];
       echo json_encode($resultat);
    }
} else {
    //echo "NOK";
    $resultat = [ "id" => 0,
       "status" => "NOK"];
       echo json_encode($resultat);
};
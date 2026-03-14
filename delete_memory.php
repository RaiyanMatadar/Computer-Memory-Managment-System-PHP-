<?php 

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    
    require_once "db_connect.php";

    // get id from url and connected to variable 
    $id = $_GET["id"];

    $query = "DELETE FROM memory_details WHERE id = :id";
    $stmt = $pdo->prepare($query);

    $stmt->bindParam(":id",$id);
    
    if ($stmt->execute()) {
        echo "data deleted!";
        header("Location: view_memory.php");
    } else {
        echo "could'nt delete data";
    }
}

?>


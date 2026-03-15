<?php 

try {
    
    require_once "db_connect.php";


    // this will execute if the user search 
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
        // stored input value to variable 
        $search_computer = $_POST["search_computer"];

        $stmt = $pdo->prepare("SELECT * FROM memory_details WHERE computer_name = :search_computer");
        $stmt->bindParam(":search_computer",$search_computer);
        $stmt->execute();

        // storing the resulted data into this array 
        $computers = $stmt->fetchAll(PDO::FETCH_ASSOC);    

        if (empty($_POST["search_computer"])) {  
            header("Location: view_memory.php");
            exit();
        }
        

    } else {
        
        $stmt = $pdo->query("SELECT * FROM memory_details");
        $computers = $stmt->fetchAll(PDO::FETCH_ASSOC);

    }

    $pdo = null;
    $stmt = null;


} catch (PDOException $e) {
    echo "Database Error: " . $e->getMessage();
}



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>


    <form action="view_memory.php" method="post">
        <label>Search computer</label>
        <input type="search" id="site-search" name="search_computer" />
        <button type="submit" name="search_submit">Search</button>
    </form>

<table cellpadding="8" border="1">
    <thead>
        <tr>
            <th>ID</th>
            <th>Computer Name</th>
            <th>RAM</th>
            <th>ROM</th>
            <th>Cache Memory</th>
            <th>Total Memory</th>
            <th>Type</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($computers as $computer): ?>
        <tr>
            <td><?= htmlspecialchars($computer['id'])?></td>
            <td><?= htmlspecialchars($computer['computer_name'])?></td>
            <td><?= htmlspecialchars($computer['ram'])?></td>
            <td><?= htmlspecialchars($computer['rom'])?></td>
            <td><?= htmlspecialchars($computer['cache_memory'])?></td>
            <td><?= htmlspecialchars($computer['total_memory'])?></td>
            <td><?= htmlspecialchars($computer['memory_type'])?></td>

            <td>
                <button><a href="update_memory.php?action=update&id=<?= $computer['id']?>">Update</a></button>
                <button><a href="delete_memory.php?action=update&id=<?= $computer['id']?>" onclick="return confirm('Are you sure you want to delete this item?');">Delete</a></button>               
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>

    <button><a href="add_memory.php">back to Home</a></button>
</table>
</body>
</html>
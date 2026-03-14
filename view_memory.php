<?php 

try {
    
    require_once "db_connect.php";
    
    // query for selecting all the data 
    $stmt = $pdo->query("SELECT * FROM memory_details");
    $computers = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // once the fetch complete data connection should close 
    echo '<pre>';
    print_r($computers);
    echo '</pre>';

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        
        echo $_POST["search-computer"];
        echo "true";

    } else {
        echo "else";
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
        <input type="search" id="site-search" name="search-computer" />
        <button type="submit" name="search-submit">Search</button>
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
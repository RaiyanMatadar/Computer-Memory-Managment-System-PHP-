<?php 

try {
    
    require_once "db_connect.php";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // this condition is for search feature 
    if (isset($_POST["searching"]) && $_POST["searching"] == "submit") {
            
            // stored input value to variable 
            $search_computer = $_POST["search_computer"];

            // it will check if the input match to any computer name then it will return the results 
            $stmt = $pdo->prepare("SELECT * FROM memory_details WHERE computer_name LIKE :search_computer");
            $stmt->bindValue(":search_computer",$search_computer . "%");
            $stmt->execute();

            // storing the resulted data into this array 
            $computers = $stmt->fetchAll(PDO::FETCH_ASSOC);     

            // echo '<pre>';
            // print_r($computers);
            // echo '</pre>';

            // if empty send the user to the view_memory page 
            if (empty($_POST["search_computer"])) {  
                header("Location: view_memory.php");
                exit();
            }
        }

        // this will exicute only if the user select any option for filtering 
        if (isset($_POST["filtering"]) && $_POST["filtering"] == "submit") {            
            // storing memory_type into variable it user selet one 
            $memory_type = $_POST["memory_type"];
        
            if (!empty($memory_type)) {

                $stmt = $pdo->prepare("SELECT * FROM memory_details WHERE memory_type = :memory_type");
                $stmt->bindParam(":memory_type", $memory_type);
                $stmt->execute();
                
                $computers = $stmt->fetchAll(PDO::FETCH_ASSOC);
            } 
            else {    
                $stmt = $pdo->query("SELECT * FROM memory_details");
                $computers = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
        }
        

    } else {
        
        $stmt = $pdo->query("SELECT * FROM memory_details");
        $computers = $stmt->fetchAll(PDO::FETCH_ASSOC);

        
        // echo '<pre>';
        // print_r($computers);
        // echo '</pre>';
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

    <!-- for searching  -->
    <form action="view_memory.php" method="post">
        <label>Search computer</label>
        <input type="hidden" name="searching" value="submit">
        <input type="search" id="site-search" name="search_computer" />
        <button type="submit" name="search_submit">Search</button>
    </form>

    <!-- for filtering  -->
    <form action="view_memory.php" method="post">
        <input type="hidden" name="filtering" value="submit">
        <select name="memory_type" onchange="this.form.submit()">
            <option value="">-- Select Type --</option>
            <option value="Low Memory"    <?= (isset($_POST['memory_type']) && $_POST['memory_type'] === 'Low Memory')    ? 'selected' : '' ?>>Low Memory</option>
            <option value="Medium Memory" <?= (isset($_POST['memory_type']) && $_POST['memory_type'] === 'Medium Memory') ? 'selected' : '' ?>>Medium Memory</option>
            <option value="High Memory"   <?= (isset($_POST['memory_type']) && $_POST['memory_type'] === 'High Memory')   ? 'selected' : '' ?>>High Memory</option>
        </select>
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
            <th>Action</th>
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
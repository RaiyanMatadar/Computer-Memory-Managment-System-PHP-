<?php 

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $computer_name = $_POST["computer_name"];
    $ram = $_POST["ram"];
    $rom = $_POST["rom"];
    $cache_memory = $_POST["cache_memory"];
    
    // this condition will check if the user click submit btn 
    // without entering data then we wont accept that and 
    // we will stop the forward code to be execute 

    require_once "db_connect.php";

    if (empty($computer_name) || empty($ram) || empty($rom) || empty($cache_memory)) {
        header("Location: add_memory.php");
        exit();
    }

    // calculation for inserting data to database 
    $total_memory = $ram + $rom + $cache_memory;

    $memory_type = "";
    
    if ($total_memory < 4000) {
        $memory_type = "Low Memory";
    } 
    else if ($total_memory >= 4000 && $total_memory <= 8000) {
        $memory_type = "Medium Memory";
    }
    else if($total_memory > 8000 ) {
        $memory_type = "High Memory";
    }
    else {
        $memory_type = "Not defined";
    }

    // query for inserting data 
    $query="INSERT INTO memory_details (computer_name,ram,rom,cache_memory,total_memory,memory_type) 
                VALUES (:computer_name,:ram,:rom,:cache_memory,:total_memory,:memory_type);";


    $stmt = $pdo->prepare($query);

    $stmt->bindParam("computer_name",$computer_name);
    $stmt->bindParam("ram",$ram);
    $stmt->bindParam("rom",$rom);
    $stmt->bindParam("cache_memory",$cache_memory);
    $stmt->bindParam("total_memory",$total_memory);
    $stmt->bindParam("memory_type",$memory_type);
    
    $stmt->execute();
    // this set to null for closing the databse connection after 
    //data get into database 
    $pdo = null;
    $stmt = null;

 
    // keeping ghe user to the existing page once user submit the data 
    header("Location: add_memory.php");
    die();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>computer memory managment System</title>
</head>
<body>
<form action="add_memory.php" method="post">
        <input type="text" name="computer_name" placeholder="enter Computer Name">
        <input type="text" name="ram" placeholder="enter your RAM">
        <input type="number" name="rom" placeholder="enter your ROM">
        <input type="text" name="cache_memory" placeholder="enter Cache Memory">
        <button type="submit">submit</button>
    </form>
    <button><a href="view_memory.php">View Data</a></button>
</body>
</html>
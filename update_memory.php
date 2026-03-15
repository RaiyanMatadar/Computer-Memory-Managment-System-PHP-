<?php 

// this if is for adding the data to the input by default 
if ($_SERVER["REQUEST_METHOD"] == "GET") {

   try {
    // for connecting to databse 
    require_once "db_connect.php";

    // get id from url and connected to variable 
    $id = $_GET["id"];

    // query for selecting specific memory_details 
    $query = "SELECT * FROM memory_details WHERE id = $id";

    // made query with prepare statement to make it secure
    $stmt = $pdo->prepare($query);

    // execute the query
    $stmt->execute();

    // for fetching data to computer variable from database
    $computer = $stmt->fetch(PDO::FETCH_ASSOC);

   } catch (PDOException $e){
        echo "error in catch";
   }
}  

// this below code is for updating the data and showing to webpage 
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    try {
        
        // for connecting to databse 
        require_once "db_connect.php";

        // selecting student_if from url using GET 
        $id = $_GET["id"];
        
        // selecting other using POST 
        $computer_name = $_POST["computer_name"];
        $ram = $_POST["ram"];
        $rom = $_POST["rom"];
        $cache_memory = $_POST["cache_memory"];
        
        //  calculation the updated info and inserting data to database 
        $total_memory = number_format(($ram + $rom + $cache_memory) / 1024, 2);

        $memory_type = "";
        
        if ($total_memory < (4000 / 1024)) {
            $memory_type = "Low Memory";
        } 
        else if ($total_memory >= (4000 / 1024) && $total_memory <= (8000 / 1024)) {
            $memory_type = "Medium Memory";
        }
        else if($total_memory > (8000 / 1024) ) {
            $memory_type = "High Memory";
        }

        // query for updating the data 
        $query = "UPDATE memory_details 
                  SET computer_name = :computer_name, 
                      ram = :ram, 
                      rom = :rom, 
                      cache_memory = :cache_memory,
                      total_memory = :total_memory,
                      memory_type = :memory_type
                  WHERE id = :id";

        // this is for security perpuse 
        $stmt = $pdo->prepare($query);

        // connecting them using bind on the qury placeholder 
        $stmt->bindParam(":id", $id);
        $stmt->bindParam(":computer_name",$computer_name);
        $stmt->bindParam(":ram",$ram);
        $stmt->bindParam(":rom",$rom);
        $stmt->bindParam(":cache_memory",$cache_memory);
        $stmt->bindParam(":total_memory",$total_memory);
        $stmt->bindParam(":memory_type",$memory_type);
              
        // executing query 
        $stmt->execute();

        // sending the computer to the view page 
        header("Location: view_memory.php");

    } catch (Throwable $e) {
        echo "error came we are in catch mode right now";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Managment System</title>
</head>
<body>

    <form action="" method="post">
        <input type="text" value="<?= $computer['computer_name']; ?>" name="computer_name" placeholder="enter name">
        <input type="text" value="<?= $computer['ram']; ?>" name="ram" placeholder="enter ram">
        <input type="number" value="<?= $computer['rom']; ?>" name="rom" placeholder="enter rom number">
        <input type="text" value="<?= $computer['cache_memory']; ?>" name="cache_memory" placeholder="enter cache_memory name">
        <button type="submit" name="update">Update data</button>
    </form>
    

</body>
</html>
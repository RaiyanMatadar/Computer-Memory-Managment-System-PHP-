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
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;1,9..40,300&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        html {
            font-size: 15px;
        }

        body {
            font-family: 'DM Sans', sans-serif;
            font-weight: 300;
            background-color: #0f0f0f;
            color: #e8e8e8;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 12px;
            padding: 40px 20px;
            letter-spacing: 0.01em;
        }

        form {
            width: 100%;
            max-width: 420px;
            background-color: #161616;
            border: 1px solid #242424;
            border-radius: 14px;
            padding: 36px 32px;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        form::before {
            content: "Memory Entry";
            display: block;
            font-size: 0.72rem;
            font-weight: 500;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: #505050;
            margin-bottom: 8px;
        }

        input {
            width: 100%;
            background-color: #1e1e1e;
            border: 1px solid #2a2a2a;
            border-radius: 8px;
            color: #e8e8e8;
            font-family: 'DM Sans', sans-serif;
            font-size: 0.93rem;
            font-weight: 300;
            padding: 12px 16px;
            outline: none;
            transition: border-color 0.18s ease, background-color 0.18s ease;
            -webkit-appearance: none;
        }

        input::placeholder {
            color: #3d3d3d;
            font-weight: 300;
        }

        input:hover {
            border-color: #363636;
            background-color: #202020;
        }

        input:focus {
            border-color: #525252;
            background-color: #212121;
        }

        /* Remove number input spinners */
        input[type="number"]::-webkit-inner-spin-button,
        input[type="number"]::-webkit-outer-spin-button {
            -webkit-appearance: none;
        }
        input[type="number"] {
            -moz-appearance: textfield;
        }

        /* Submit button — inside the form */
        form button {
            margin-top: 8px;
            width: 100%;
            padding: 13px 20px;
            background-color: #e8e8e8;
            color: #0f0f0f;
            border: none;
            border-radius: 8px;
            font-family: 'DM Sans', sans-serif;
            font-size: 0.88rem;
            font-weight: 500;
            letter-spacing: 0.04em;
            cursor: pointer;
            transition: background-color 0.18s ease, transform 0.1s ease;
        }

        form button:hover {
            background-color: #d0d0d0;
        }

        form button:active {
            transform: scale(0.985);
            background-color: #c4c4c4;
        }

        /* View Data link — styled as ghost button */
        body > a {
            display: block;
            width: 100%;
            max-width: 420px;
            padding: 13px 20px;
            background-color: transparent;
            border: 1px solid #242424;
            border-radius: 8px;
            font-family: 'DM Sans', sans-serif;
            font-size: 0.88rem;
            font-weight: 400;
            color: #909090;
            text-decoration: none;
            text-align: center;
            transition: border-color 0.18s ease, background-color 0.18s ease, color 0.18s ease;
        }

        body > a:hover {
            border-color: #383838;
            background-color: #161616;
            color: #c0c0c0;
        }

        body > a:active {
            background-color: #1e1e1e;
        }
    </style>
</head>
<body>
<form action="add_memory.php" method="post">
        <input type="text" name="computer_name" placeholder="enter Computer Name">
        <input type="text" name="ram" placeholder="enter your RAM">
        <input type="number" name="rom" placeholder="enter your ROM">
        <input type="text" name="cache_memory" placeholder="enter Cache Memory">
        <button type="submit">submit</button>
    </form>
    <a href="view_memory.php">View Data</a>
</body>
</html>
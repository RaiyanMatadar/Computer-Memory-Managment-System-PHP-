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
    }


    // pagination 
    
    $recordsPerPage = 2;

    // if view_memory.php?page=NOT NULL then the if will execute 
    //http://localhost/Computer-Memory-Managment-System-PHP-/view_memory.php?page=4
    if(isset($_GET['page'])){
        $page= $_GET['page'];
    } else{
        $page=1;
    }
    // if the page=4 then the offset value would be 6 
    $offset = ($page - 1) * $recordsPerPage; 

    // for getting the total row from the database  
    $total = $pdo->query("SELECT COUNT(*) FROM memory_details")->fetchColumn();
    $totalPages = ceil($total / $recordsPerPage); //- VALUE 5

    $paginationQue = "SELECT * FROM memory_details LIMIT :limit OFFSET :offset";
    $stmt2 = $pdo->prepare($paginationQue);
    $stmt2->bindValue(':limit',  $recordsPerPage, PDO::PARAM_INT);
    $stmt2->bindValue(':offset', $offset,         PDO::PARAM_INT);
    $stmt2->execute();

    $rows = $stmt2->fetchAll(PDO::FETCH_ASSOC);

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
            flex-direction: row;
            flex-wrap: wrap;
            justify-content: center;
            align-items: flex-start;
            align-content: flex-start;
            padding: 48px 24px;
            gap: 12px;
            letter-spacing: 0.01em;
        }

        /* ── Both forms share one row ── */
        form {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        /* Search form: grows to fill available space */
        form:first-of-type {
            flex: 1 1 400px;
            max-width: 620px;
        }

        /* Filter form: fixed, doesn't grow */
        form:last-of-type {
            flex: 0 0 auto;
        }

        /* Table and back button span full row */
        table,
        body > button {
            flex: 0 0 100%;
            max-width: 860px;
        }

        label {
            font-size: 0.72rem;
            font-weight: 500;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: #505050;
            white-space: nowrap;
        }

        input[type="search"],
        input[type="text"] {
            flex: 1;
            background-color: #1e1e1e;
            border: 1px solid #2a2a2a;
            border-radius: 8px;
            color: #e8e8e8;
            font-family: 'DM Sans', sans-serif;
            font-size: 0.9rem;
            font-weight: 300;
            padding: 11px 16px;
            outline: none;
            transition: border-color 0.18s ease, background-color 0.18s ease;
            -webkit-appearance: none;
        }

        input[type="search"]::placeholder {
            color: #3d3d3d;
        }

        input[type="search"]:hover,
        input[type="text"]:hover {
            border-color: #363636;
            background-color: #202020;
        }

        input[type="search"]:focus,
        input[type="text"]:focus {
            border-color: #525252;
            background-color: #212121;
        }

        select {
            background-color: #1e1e1e;
            border: 1px solid #2a2a2a;
            border-radius: 8px;
            color: #909090;
            font-family: 'DM Sans', sans-serif;
            font-size: 0.9rem;
            font-weight: 300;
            padding: 11px 16px;
            outline: none;
            cursor: pointer;
            transition: border-color 0.18s ease, background-color 0.18s ease;
            -webkit-appearance: none;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%23505050' d='M6 8L1 3h10z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 14px center;
            padding-right: 36px;
        }

        select:hover {
            border-color: #363636;
            background-color: #202020;
        }

        select:focus {
            border-color: #525252;
        }

        select option {
            background-color: #1e1e1e;
            color: #e8e8e8;
        }

        /* Search submit button */
        form button {
            padding: 11px 20px;
            background-color: #e8e8e8;
            color: #0f0f0f;
            border: none;
            border-radius: 8px;
            font-family: 'DM Sans', sans-serif;
            font-size: 0.85rem;
            font-weight: 500;
            letter-spacing: 0.04em;
            cursor: pointer;
            white-space: nowrap;
            transition: background-color 0.18s ease, transform 0.1s ease;
        }

        form button:hover {
            background-color: #d0d0d0;
        }

        form button:active {
            transform: scale(0.985);
            background-color: #c4c4c4;
        }

        /* ── Table ── */
        table {
            width: 100%;
            max-width: 100vw;
            border-collapse: collapse;
            border: 1px solid #1e1e1e;
            border-radius: 12px;
            overflow: hidden;
            margin-top: 8px;
            font-size: 0.88rem;
        }

        thead {
            background-color: #161616;
        }

        thead tr {
            border-bottom: 1px solid #242424;
        }

        th {
            padding: 13px 16px;
            text-align: left;
            font-size: 0.7rem;
            font-weight: 500;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: #505050;
            white-space: nowrap;
        }

        tbody tr {
            border-bottom: 1px solid #1a1a1a;
            transition: background-color 0.15s ease;
        }

        tbody tr:last-of-type {
            border-bottom: none;
        }

        tbody tr:hover {
            background-color: #161616;
        }

        td {
            padding: 13px 16px;
            color: #c0c0c0;
            font-weight: 300;
            vertical-align: middle;
        }

        /* Action links inside td — styled as ghost buttons */
        td a {
            display: inline-block;
            padding: 6px 14px;
            background-color: transparent;
            border: 1px solid #2a2a2a;
            border-radius: 6px;
            font-family: 'DM Sans', sans-serif;
            font-size: 0.8rem;
            font-weight: 400;
            color: #909090;
            text-decoration: none;
            margin-right: 6px;
            transition: border-color 0.15s ease, background-color 0.15s ease, color 0.15s ease;
        }

        td a:last-child {
            margin-right: 0;
        }

        td a:hover {
            border-color: #3a3a3a;
            background-color: #1e1e1e;
            color: #c8c8c8;
        }

        /* Keep action td from wrapping */
        td:last-child {
            white-space: nowrap;
        }

        /* Delete link — reddish tint */
        td a:last-of-type {
            border-color: #2a1a1a;
            color: #7a4040;
        }

        td a:last-of-type:hover {
            border-color: #4a2020;
            background-color: #1e1010;
            color: #c06060;
        }

        /* ── Back to Home link — sits after the table in body ── */
        body > a {
            display: block;
            flex: 0 0 100%;
            max-width: 860px;
            padding: 13px 20px;
            background-color: transparent;
            border: 1px solid #242424;
            border-radius: 8px;
            font-family: 'DM Sans', sans-serif;
            font-size: 0.85rem;
            font-weight: 400;
            color: #909090;
            text-decoration: none;
            text-align: center;
            transition: border-color 0.15s ease, background-color 0.15s ease, color 0.15s ease;
        }

        body > a:hover {
            border-color: #383838;
            background-color: #161616;
            color: #c0c0c0;
        }
    </style>
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
        <?php foreach ($rows as $row): ?>
        <tr>
            <td><?= htmlspecialchars($row['id'])?></td>
            <td><?= htmlspecialchars($row['computer_name'])?></td>
            <td><?= htmlspecialchars($row['ram'])?></td>
            <td><?= htmlspecialchars($row['rom'])?></td>
            <td><?= htmlspecialchars($row['cache_memory'])?></td>
            <td><?= htmlspecialchars($row['total_memory'])?></td>
            <td><?= htmlspecialchars($row['memory_type'])?></td>

            <td>
                <a href="update_memory.php?action=update&id=<?= $row['id']?>">Update</a>
                <a href="delete_memory.php?action=update&id=<?= $row['id']?>" onclick="return confirm('Are you sure you want to delete this item?');">Delete</a>               
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<div style="width: 100vw; display: flex; justify-content: center; flex-direction: row;">
    <?php for ($i=1; $i <= $totalPages ; $i++) { ?>
        <a style="color: white; margin: 10px; list-style: none;" href="view_memory.php?page=<?= $i ?>"><?= $i ?></a>
    <?php } ?>
</div>

<a href="add_memory.php">back to Home</a>

    
</body>
</html>



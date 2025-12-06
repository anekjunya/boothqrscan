<?php
$servername = "localhost";
$username = "aaneoffice";
$password = "aaneo@248";
$database = "aaneotechOffice";

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = "";
$icon = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $telephone = $_POST['telephone'];
    $sql = "SELECT * FROM participants WHERE telephone = '$telephone'";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $first_name = $row['first_name'];
        $last_name = $row['last_name'];
        $section = $row['section'];
        $p_id = $row['p_id']; // Get the p_id
        
        if ($row['checked'] == 1) {
            $message = "คุณ: $first_name $last_name ได้มีการเช็คอินแล้ว ID:$p_id , Section: $section";
            $icon = "warning";
        } else {
            $update_sql = "UPDATE participants SET checked = 1 WHERE telephone = '$telephone'";
            if ($conn->query($update_sql) === TRUE) {
                $message = "คุณ: $first_name $last_name เช็คอินสำเร็จ ID:$p_id , Section: $section ";
                $icon = "success";
            } else {
                $message = "Error updating record: " . $conn->error;
                $icon = "error";
            }
        }
    } else {
        $message = "ไม่พบข้อมูลลงทะเบียน!";
        $icon = "error";
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Check-in</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f4f4f4;
            margin: 0;
            flex-direction: column;
        }
        .container {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 100%;
            max-width: 400px;
        }
        input[type="text"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            text-align: center;
            font-weight: bold;
        }
        button {
            background: #007BFF;
            color: white;
            border: none;
            padding: 10px 15px;
            width: 100%;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background: #0056b3;
        }
        .robot-image {
            width: 400px;
            margin-bottom: 20px;
         }
    </style>
</head>
<body>
    <img src="images/solutionday25.png" alt="Robot Arm" class="robot-image">
    <div class="container">
        <h2>Solution Day 2025</h2>
        <form method="POST" action="">
            <label for="telephone">กรอกเบอร์โทรศัพท์:</label>
            <input type="text" id="telephone" name="telephone" placeholder="0891234567" required>
            <button type="submit">Check-in</button>
        </form>
    </div>
    
    <?php if (!empty($message)): ?>
        <script>
            Swal.fire({
                icon: "<?php echo $icon; ?>",
                title: "แจ้งเตือน",
                text: "<?php echo $message; ?>",
                confirmButtonText: "ตกลง"
            }).then(() => {
                <?php if ($icon === "success") echo "window.location.href='qrscan.php';"; ?>
            });
        </script>
    <?php endif; ?>
</body>
</html>
<?php
require_once "auth_check.php";

$host = "localhost";
$dbUser = "root";
$dbPass = "";
$dbName = "lab_5b"; 

$conn = new mysqli($host, $dbUser, $dbPass, $dbName);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $matric = trim($_POST["matric"] ?? "");
    $name = trim($_POST["name"] ?? "");
    $accessLevel = trim($_POST["accessLevel"] ?? "");

    if ($matric !== "" && $name !== "" && $accessLevel !== "") {
        $stmt = $conn->prepare("UPDATE users SET name = ?, role = ? WHERE matric = ?");
        $stmt->bind_param("sss", $name, $accessLevel, $matric);

        if ($stmt->execute()) {
            $stmt->close();
            $conn->close();
            header("Location: display.php");
            exit;
        } else {
            $message = "Failed to update record: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $message = "Please fill in all fields.";
    }
}

$matricParam = trim($_GET["matric"] ?? $_POST["matric"] ?? "");
$user = null;

if ($matricParam !== "") {
    $stmt = $conn->prepare("SELECT matric, name, role AS accessLevel FROM users WHERE matric = ? LIMIT 1");
    $stmt->bind_param("s", $matricParam);
    $stmt->execute();
    $res = $stmt->get_result();
    $user = $res->fetch_assoc();
    $stmt->close();
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Lab 5b Q7</title>
        <style>
            .row {
                margin-bottom: 8px;
            }
            label {
                display: inline-block;
                width: 100px;
            }
        </style>
    </head>

    <body>
        <div class="box">
            <h2>Update User</h2>

            <?php if ($message !== ""): ?>
                <p><strong><?php echo htmlspecialchars($message); ?></strong></p>
            <?php endif; ?>

            <?php if ($user): ?>
                <form method="POST" action="">
                    <div class="row">
                        <label for="matric">Matric</label>
                        <input type="text" id="matric" name="matric" value="<?php echo htmlspecialchars($user["matric"]); ?>" readonly>
                    </div>
                    <div class="row">
                        <label for="name">Name</label>
                        <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user["name"]); ?>" required>
                    </div>
                    <div class="row">
                        <label for="accessLevel">Access Level</label>
                        <input type="text" id="accessLevel" name="accessLevel" value="<?php echo htmlspecialchars($user["accessLevel"]); ?>" required>
                    </div>
                    <button type="submit">Update</button>
                    <a href="display.php">Cancel</a>
                </form>
            <?php else: ?>
                <p>User not found. <a href="display.php">Cancel</a></p>
            <?php endif; ?>
        </div>
    </body>
</html>
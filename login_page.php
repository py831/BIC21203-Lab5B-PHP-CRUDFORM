<?php
session_start();

$loginFailed = false;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $host = "localhost";
    $dbUser = "root";
    $dbPass = "";
    $dbName = "lab_5b";

    $conn = new mysqli($host, $dbUser, $dbPass, $dbName);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $matric = trim($_POST["matric"] ?? "");
    $password = $_POST["password"] ?? "";

    if ($matric !== "" && $password !== "") {
        $stmt = $conn->prepare("SELECT matric, name, password, role AS accessLevel FROM users WHERE matric = ? LIMIT 1");
        $stmt->bind_param("s", $matric);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            $passwordMatches = password_verify($password, $row["password"]) || hash_equals($row["password"], $password);

            if ($passwordMatches) {
                session_regenerate_id(true);
                $_SESSION["matric"] = $row["matric"];
                $_SESSION["name"] = $row["name"];
                $_SESSION["accessLevel"] = $row["accessLevel"];

                $stmt->close();
                $conn->close();

                header("Location: display.php");
                exit;
            }
        }

        $stmt->close();
    }

    $conn->close();
    $loginFailed = true;
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Lab 5b Q6</title>
        <style>
            .row {
                margin-bottom: 6px;
            }
            .register-link {
                margin-top: 14px;
            }
        </style>
    </head>

    <body>
        <?php if ($loginFailed): ?>
            <div class="box">
                Invalid username or password, try <a href="">login</a> again.
            </div>
        <?php else: ?>
            <div class="box">
                <form method="POST" action="">
                    <div class="row">
                        <label for="matric">Matric:</label>
                        <input type="text" id="matric" name="matric" required>
                    </div>
                    <div class="row">
                        <label for="password">Password:</label>
                        <input type="password" id="password" name="password" required>
                    </div>
                    <button type="submit">Login</button>
                </form>

                <div class="register-link">
                    <a href="register.php">Register</a> here if you have not.
                </div>
            </div>
        <?php endif; ?>
    </body>
</html>
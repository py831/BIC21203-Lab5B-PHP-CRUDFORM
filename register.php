<!DOCTYPE html>
<html lang="en">

    <head>
        <title>Lab 5b Q3</title>
    </head>

    <body>
        <?php
            $message="";

            if($_SERVER["REQUEST_METHOD"]==="POST"){
                $host="localhost";
                $dbUser="root";
                $dbPass="";
                $dbName="lab_5b";

                $conn=new mysqli($host, $dbUser, $dbPass, $dbName);

                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                $matric = trim($_POST["matric"] ?? "");
                $name = trim($_POST["name"] ?? "");
                $password = $_POST["password"] ?? "";
                $role = $_POST["role"] ?? "";

                if ($matric === "" || $name === "" || $password === "" || $role === "") {
                    $message = "Please fill in all fields and select a role.";
                } else {
                    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                    $stmt = $conn->prepare("INSERT INTO users (matric, name, password, role) VALUES (?, ?, ?, ?)");
                    $stmt->bind_param("ssss", $matric, $name, $hashedPassword, $role);

                    if ($stmt->execute()) {
                        $message = "Registration successful!";
                    } else {
                        $message = "Error inserting record: " . $stmt->error;
                    }

                    $stmt->close();
                }

                $conn->close();
            }
            ?>

            <form method="POST" action="">
                <div class="row">
                    <label for="matric">Matric:</label>
                    <input type="text" id="matric" name="matric">
                </div>
                <br>
                <div class="row">
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="name">
                </div>
                <br>
                <div class="row">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password">
                </div>
                <br>
                <div class="row">
                    <label for="role">Role:</label>
                    <select id="role" name="role" required>
                        <option value="">Please select</option>
                        <option value="student">Student</option>
                        <option value="lecturer">Lecturer</option>
                        <option value="lecturer">Staff</option>
                    </select>
                </div>
                <br>
                <button type="submit">Submit</button>
            </form>

            <?php if ($message !== ""): ?>
                <div class="message"><strong><?php echo htmlspecialchars($message); ?></strong></div>
            <?php endif; ?>
    </body>
</html>
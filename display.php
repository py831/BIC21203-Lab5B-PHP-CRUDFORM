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

$sql = "SELECT matric, name, role AS accessLevel FROM users";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Lab 5b Q7</title>
        <style>
            table {
                border-collapse: collapse;
                font-family: "Times New Roman", Times, serif;
            }
            th, td {
                border: 1px solid black;
                padding: 4px 8px;
                text-align: left;
            }
            th {
                text-align: center;
            }
        </style>
    </head>

    <body>
        <table>
            <thead>
                <tr>
                    <th>Matric</th>
                    <th>Name</th>
                    <th>Level</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result && $result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <?php $encodedMatric = htmlspecialchars(urlencode($row["matric"]), ENT_QUOTES, "UTF-8"); ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row["matric"]); ?></td>
                            <td><?php echo htmlspecialchars($row["name"]); ?></td>
                            <td><?php echo htmlspecialchars($row["accessLevel"]); ?></td>
                            <td>
                                <a href="update.php?matric=<?php echo $encodedMatric; ?>">Update</a>
                                <a href="delete.php?matric=<?php echo $encodedMatric; ?>" onclick="return confirm('Are you sure you want to delete this record?');">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4">No records found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <?php
        if ($result) {
            $result->free();
        }
        $conn->close();
        ?>
    </body>
</html>
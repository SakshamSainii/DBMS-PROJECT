<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "university";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to execute SQL queries
function executeQuery($sql) {
    global $conn;
    $result = $conn->query($sql);
    if (!$result) {
        echo "Error executing query: " . $conn->error;
        return false;
    }
    return $result;
}

// Function to display data in a table
function displayTable($result) {
    if ($result->num_rows > 0) {
        echo "<table>";
        $row = $result->fetch_assoc();
        echo "<tr>";
        foreach ($row as $key => $value) {
            echo "<th>$key</th>";
        }
        echo "</tr>";
        $result->data_seek(0);
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            foreach ($row as $value) {
                echo "<td>$value</td>";
            }
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "No data found";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>University Management System</title>
    <style>
        /* CSS styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        header {
            background-color: #333;
            color: #fff;
            padding: 20px;
            text-align: center;
        }
        nav {
            background-color: #f2f2f2;
            padding: 10px;
            text-align: center;
        }
        nav a {
            color: #333;
            text-decoration: none;
            padding: 10px;
        }
        nav a:hover {
            background-color: #ddd;
        }
        main {
            padding: 20px;
        }
        table {
            border-collapse: collapse;
            width: 100%;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        form {
            margin-bottom: 20px;
        }
        input[type=text], input[type=date], select {
            padding: 6px 10px;
            margin: 8px 0;
            display: inline-block;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        input[type=submit] {
            background-color: #4CAF50;
            color: white;
            padding: 10px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        input[type=submit]:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <header>
        <h1>University Management System</h1>
    </header>
    <nav>
        <a href="?action=home">Home</a>
        <a href="?action=viewStudents">Students</a>
        <a href="?action=viewStaff">Staff</a>
        <a href="?action=viewCourses">Courses</a>
        <a href="?action=viewRegistration">Registration</a>
        <a href="?action=viewReports">Reports</a>
        <a href="?action=viewTransactions">Transactions</a>
        <a href="?action=viewAttendance">Attendance</a>
        <a href="?action=EditRecords">Edit Records</a>
    </nav>
    <main>
        <?php
        // Handle form submissions and display data
        if (isset($_GET["action"])) {
            $action = $_GET["action"];
            switch ($action) {
                case "home":
                    echo "<h2>Welcome to the University Management System</h2>";
                    echo "<p>This system allows you to manage various aspects of the university, including students, staff, courses, registration, reports, transactions, and attendance.</p>";
                    break;
                case "viewStudents":
                    $sql = "SELECT * FROM Student";
                    $result = executeQuery($sql);
                    displayTable($result);
                    break;
               case "viewStaff":
                   $sql = "SELECT * FROM Staff";
                   $result = executeQuery($sql);
                   displayTable($result);
                   break;
               case "viewCourses":
                   $sql = "SELECT * FROM Courses";
                   $result = executeQuery($sql);
                   displayTable($result);
                   break;
               case "viewRegistration":
                   $sql= "SELECT * FROM Registration";
                   $result = executeQuery($sql);
                   displayTable($result);
                   break;
               case "viewReports":
                   $sql = "SELECT * FROM Reports";
                   $result = executeQuery($sql);
                   displayTable($result);
                   break;
               case "viewTransactions":
                   $sql = "SELECT * FROM Transactions";
                   $result = executeQuery($sql);
                   displayTable($result);
                    break;
                case "viewAttendance":
                    $sql = "SELECT * FROM Attendance";
                    $result = executeQuery($sql);
                    displayTable($result);
                    break;
                    case "EditRecords":
                        echo '<a href="file2.php">Edit Records</a>';
                        break;
            }
        }
        ?>
    </main>
</body>
</html>
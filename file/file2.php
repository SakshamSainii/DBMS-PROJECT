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

// Handle form submissions
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $table = $_POST["table"];
    $action = $_POST["action"];
    $data = array();
    foreach ($_POST as $key => $value) {
        if ($key != "table" && $key != "action") {
            $data[$key] = mysqli_real_escape_string($conn, $value); // Escape user input to prevent SQL injection
        }
    }

    switch ($action) {
        case "add":
            $columns = implode(", ", array_keys($data));
            $values = "'" . implode("', '", array_values($data)) . "'";
            $sql = "INSERT INTO $table ($columns) VALUES ($values)";
            if (executeQuery($sql)) {
                echo "New record added to $table table successfully";
            } else {
                echo "Error adding record to $table table";
            }
            break;
        case "update":
            $set = array();
            foreach ($data as $key => $value) {
                $set[] = "$key='$value'";
            }
            $setClause = implode(", ", $set);
            $primaryKey = array_keys($data)[0];
            $primaryValue = $data[$primaryKey];
            $sql = "UPDATE $table SET $setClause WHERE $primaryKey='$primaryValue'";
            if (executeQuery($sql)) {
                echo "Record updated in $table table successfully";
            } else {
                echo "Error updating record in $table table";
            }
            break;
        case "delete":
            $primaryKey = array_keys($data)[0];
            $primaryValue = $data[$primaryKey];
            $sql = "DELETE FROM $table WHERE $primaryKey='$primaryValue'";
            if (executeQuery($sql)) {
                echo "Record deleted from $table table successfully";
            } else {
                echo "Error deleting record from $table table";
            }
            break;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>University Management System</title>
    <style>
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
        .error {
            color: red;
            font-weight: bold;
        }
    </style>
    <script>
        function showForm(formId) {
            var forms = document.getElementsByClassName('form');
            for (var i = 0; i < forms.length; i++) {
                forms[i].style.display = 'none';
            }
            document.getElementById(formId).style.display = 'block';
        }
    </script>
</head>
<body>
    <header>
        <h1>University Management System</h1>
    </header>
    <nav>
    <a href="file1.php" onclick="window.open('file1.php', '_blank'); return false;">Home</a>
        <a href="#" onclick="showForm('studentForm')">Students</a>
        <a href="#" onclick="showForm('staffForm')">Staff</a>
        <a href="#" onclick="showForm('courseForm')">Courses</a>
        <a href="#" onclick="showForm('registrationForm')">Registration</a>
        <a href="#" onclick="showForm('reportForm')">Reports</a>
        <a href="#" onclick="showForm('transactionForm')">Transactions</a>
        <a href="#" onclick="showForm('attendanceForm')">Attendance</a>
    </nav>
    <main>
        <div id="studentForm" class="form" style="display:none;">
            <h2>Students</h2>
            <form method="post">
                <input type="hidden" name="table" value="Student">
                Student ID: <input type="text" name="stud_ID" required><br>
                First Name: <input type="text" name="stfname"><br>
                Last Name: <input type="text" name="stlname"><br>
                Contact: <input type="text" name="stcontact"><br>
                Birth Date: <input type="date" name="stbirthdate"><br>
                Gender: <input type="text" name="stgender"><br>
                <input type="submit" name="action" value="add">
                <input type="submit" name="action" value="update">
                <input type="submit" name="action" value="delete">
            </form>
        </div>

        <div id="staffForm" class="form" style="display:none;">
            <h2>Staff</h2>
            <form method="post">
                <input type="hidden" name="table" value="Staff">
                Staff ID: <input type="text" name="staff_ID" required><br>
                First Name: <input type="text" name="fname"><br>
                Last Name: <input type="text" name="lname"><br>
                Address: <input type="text" name="address"><br>
                Contact: <input type="text" name="contact"><br>
                Gender: <input type="text" name="gender"><br>
                <input type="submit" name="action" value="add">
                <input type="submit" name="action" value="update">
                <input type="submit" name="action" value="delete">
            </form>
        </div>

        <div id="courseForm" class="form" style="display:none;">
            <h2>Courses</h2>
            <form method="post">
                <input type="hidden" name="table" value="Courses">
                Course ID: <input type="text" name="course_ID" required><br>
                Course Name: <input type="text" name="coursename"><br>
                Department: <input type="text" name="department"><br>
                <input type="submit" name="action" value="add">
                <input type="submit" name="action" value="update">
                <input type="submit" name="action" value="delete">
    </form>
    </div>

        <div id="registrationForm" class="form" style="display:none;">
            <h2>Registration</h2>
            <form method="post">
                <input type="hidden" name="table" value="Registration">
                Student ID: <input type="text" name="stud_ID" required><br>
                Course ID: <input type="text" name="course_ID" required><br>
                Registration Date: <input type="date" name="registration_date"><br>
                <input type="submit" name="action" value="add">
                <input type="submit" name="action" value="update">
                <input type="submit" name="action" value="delete">
            </form>
        </div>

        <div id="reportForm" class="form" style="display:none;">
            <h2>Reports</h2>
            <form method="post">
                <input type="hidden" name="table" value="Reports">
                Report ID: <input type="text" name="report_ID" required><br>
                Name: <input type="text" name="name"><br>
                Staff ID: <input type="text" name="staff_ID"><br>
                Project: <input type="text" name="project"><br>
                Date: <input type="date" name="date"><br>
                <input type="submit" name="action" value="add">
                <input type="submit" name="action" value="update">
                <input type="submit" name="action" value="delete">
            </form>
        </div>

        <div id="transactionForm" class="form" style="display:none;">
            <h2>Transactions</h2>
            <form method="post">
                <input type="hidden" name="table" value="Transactions">
                Transaction ID: <input type="text" name="trans_ID" required><br>
                Student ID: <input type="text" name="stud_ID"><br>
                Staff ID: <input type="text" name="staff_ID"><br>
                Transaction Date: <input type="date" name="transaction_date"><br>
                Course Line: <input type="text" name="course_line"><br>
                <input type="submit" name="action" value="add">
                <input type="submit" name="action" value="update">
                <input type="submit" name="action" value="delete">
            </form>
        </div>

        <div id="attendanceForm" class="form" style="display:none;">
            <h2>Attendance</h2>
            <form method="post">
                <input type="hidden" name="table" value="Attendance">
                Attendance ID: <input type="text" name="attendance_ID" required><br>
                Student ID: <input type="text" name="stud_ID"><br>
                Course ID: <input type="text" name="course_ID"><br>
                Attendance Date: <input type="date" name="attend_date"><br>
                Status: <input type="text" name="status"><br>
                <input type="submit" name="action" value="add">
                <input type="submit" name="action" value="update">
                <input type="submit" name="action" value="delete">
            </form>
        </div>
    </main>
</body>
</html>
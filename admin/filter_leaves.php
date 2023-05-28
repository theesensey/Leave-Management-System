<?php
//Creates a session or Resumes the current one based on a session identifier 
session_start();

require_once '../config.php';

$sql = "SELECT * FROM leave_type ORDER BY l_name ASC";
$leaveTypeResult = mysqli_query($con, $sql);

//Checking if user is logged in
if (!$_SESSION['email']) {
    //Redirects to appropriate page / method
    header('Location: ../login.php ');
}

$name = $_SESSION['name'];
$user_id = $_SESSION['id'];

// Construct the SQL query based on the selected filters
$dateFilter = $_GET['date'] ?? '';
$statusFilter = $_GET['status'] ?? '';
$leaveTypeFilter = $_GET['leave_type_id'] ?? '';
$whereClause = '';
if ($dateFilter !== 'All') {
    switch ($dateFilter) {
        case '1':
            $whereClause .= " AND leave.leave_from >= DATE_SUB(NOW(), INTERVAL 1 DAY)";
            break;
        case '2':
            $whereClause .= " AND leave.leave_from >= DATE_SUB(NOW(), INTERVAL 7 DAY)";
            break;
        case '3':
            $whereClause .= " AND leave.leave_from >= DATE_SUB(NOW(), INTERVAL 1 MONTH)";
            break;
        case '4':
            $whereClause .= " AND leave.leave_from >= DATE_SUB(NOW(), INTERVAL 6 MONTH)";
            break;
        case '5':
            $whereClause .= " AND leave.leave_from >= DATE_SUB(NOW(), INTERVAL 1 YEAR)";
            break;
        case '6':
            $whereClause .= " AND leave.leave_from >= DATE_SUB(NOW(), INTERVAL 5 YEAR)";
            break;
        default:
            break;
    }
}
if ($statusFilter !== 'All') {
    $whereClause .= " AND leave.status = '$statusFilter'";
}
if ($leaveTypeFilter !== 'ALL') {
    $whereClause .= " AND leave.leave_type_id = '$leaveTypeFilter'";
}
$query = "SELECT leave.id, leave.employee_id, leave.leave_type_id, leave.leave_from, leave.leave_to, leave.description, leave.status, employee.name ,leave_type.l_name 
FROM `leave` 
INNER JOIN employee ON leave.employee_id = employee.id
INNER JOIN leave_type ON leave.leave_type_id = leave_type.id
WHERE 1=1 $whereClause

;";

$result = mysqli_query($con, $query);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/employee.css">
    <title>Pending requests</title>
</head>

<body>
    <div class="topnav">
        <a href="index.php">Home</a>
        <a href="profile.php">Profile</a>
        <a href="manage_leaves.php">Manage Leaves</a>
        <a href="other.php">Manage System</a>
        <a href="../logout.php">Logout</a>
    </div>
    <!-- The main content area -->
    <br>
    <form method="GET">
        <div class="filter">
            <label id="date"><b>Date</b></label>
            <select id="date" name="date">
                <option value="All">All*</option>
                <option value="1">Last 24 Hrs</option>
                <option value="2">The last 7 days</option>
                <option value="3">Last Month</option>
                <option value="4">Last 6 Months</option>
                <option value="5">Last Year</option>
                <option value="6">Last 5 Years</option>
            </select>
            <label id="status"><b>Leave Status</b></label>
            <select id="status" name="status">
                <option value="All">All*</option>
                <option value="approved">Approved</option>
                <option value="pending">Pending</option>
                <option value="rejected">Rejected</option>
            </select>
            </select>
            <label for="leave_type_id"><b>Leave Type</b></label>
            <select id="leave_type_id" name="leave_type_id">
                <option value="ALL">All Leave Types</option>
                <?php while ($row = mysqli_fetch_assoc($leaveTypeResult)) { ?>
                    <option value="<?php echo $row['id']; ?>"><?php echo $row['l_name']; ?></option>
                <?php } ?>
            </select>

            <div class="search">
                <button type="submit" class="button">Search</button>
            </div>
        </div>
    </form>
    <br>
    <div class="table">
        <table class="table">
            <tr>
                <th>Id</th>
                <th>Name</th>
                <th>Leave From</th>
                <th>Leave To</th>
                <th>Leave Type</th>
                <th>Reasons</th>
                <th>Leave Status</th>
            </tr>
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['name']; ?></td>
                    <td><?php echo date('d-m-Y', strtotime($row['leave_from'])); ?></td>
                    <td><?php echo date('d-m-Y', strtotime($row['leave_to'])); ?></td>
                    <td><?php echo $row['l_name']; ?></td>
                    <td><?php echo $row['description']; ?></td>
                    <td><?php echo $row['status']; ?></td>
                </tr>
            <?php } ?>
        </table>
    </div>
    <br>
    <div class="panel-footer">Go back <a href="manage_leaves.php">Manage Leaves</a></div>
</body>

</html>
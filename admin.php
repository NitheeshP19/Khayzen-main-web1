<?php
require_once 'bat/db_config.php';

session_start();

// Simple logout logic
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: admin.php");
    exit;
}

// Simple login logic
$error = '';
if (isset($_POST['login'])) {
    if ($_POST['username'] === ADMIN_USER && $_POST['password'] === ADMIN_PASS) {
        $_SESSION['admin_logged_in'] = true;
    } else {
        $error = 'Invalid credentials';
    }
}

$is_logged_in = isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'];

// Database retrieval logic
$submissions = [];
if ($is_logged_in) {
    try {
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        
        $stmt = $pdo->query("SELECT * FROM submissions ORDER BY created_at DESC");
        $submissions = $stmt->fetchAll();
    } catch (PDOException $e) {
        $error = "Database connection failed. Please ensure your credentials in bat/db_config.php are correct and the table exists.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Khayzen Systems</title>
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/fonts.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
        body {
            background: #f4f7f6;
            font-family: 'Poppins', sans-serif;
        }
        .admin-header {
            background: linear-gradient(135deg, #007bff, #0056b3);
            color: white;
            padding: 20px 0;
            margin-bottom: 40px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        .card {
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            border: none;
        }
        .login-container {
            max-width: 400px;
            margin: 100px auto;
        }
        .table-container {
            background: white;
            padding: 30px;
            border-radius: 15px;
        }
        .status-badge {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
        .type-contact { background: #e3f2fd; color: #1976d2; }
        .type-subscribe { background: #f1f8e9; color: #388e3c; }
    </style>
</head>
<body>

<?php if (!$is_logged_in): ?>
    <div class="container">
        <div class="login-container">
            <div class="card p-4">
                <div class="text-center mb-4">
                    <img src="images/favicon.ico" alt="Logo" width="60">
                    <h4 class="mt-3 font-weight-bold">Admin Login</h4>
                </div>
                <?php if ($error): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>
                <form method="POST">
                    <div class="form-group">
                        <label>Username</label>
                        <input type="text" name="username" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <button type="submit" name="login" class="btn btn-primary btn-block btn-lg mt-4">Login</button>
                    <p class="text-center mt-3 small text-muted">Use credentials from <code>bat/db_config.php</code></p>
                </form>
            </div>
        </div>
    </div>
<?php else: ?>
    <div class="admin-header">
        <div class="container d-flex justify-content-between align-items-center">
            <h2 class="m-0 font-weight-bold">Khayzen Admin Panel</h2>
            <a href="?logout=1" class="btn btn-outline-light btn-sm px-4" style="border-radius: 20px;">Logout</a>
        </div>
    </div>

    <div class="container-fluid px-5">
        <?php if ($error): ?>
            <div class="alert alert-warning"><?php echo $error; ?></div>
        <?php endif; ?>

        <div class="table-container">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="m-0 font-weight-bold">Recent Submissions</h4>
                <div class="text-muted">Total: <?php echo count($submissions); ?> entries</div>
            </div>
            
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="thead-light">
                        <tr>
                            <th>ID</th>
                            <th>Date</th>
                            <th>Type</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Message</th>
                            <th>IP Address</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($submissions as $row): ?>
                            <tr>
                                <td><?php echo $row['id']; ?></td>
                                <td class="small"><?php echo date('M d, Y H:i', strtotime($row['created_at'])); ?></td>
                                <td>
                                    <span class="status-badge type-<?php echo strtolower($row['form_type']); ?>">
                                        <?php echo ucfirst($row['form_type']); ?>
                                    </span>
                                </td>
                                <td class="font-weight-bold"><?php echo htmlspecialchars($row['name']); ?></td>
                                <td><a href="mailto:<?php echo $row['email']; ?>"><?php echo htmlspecialchars($row['email']); ?></a></td>
                                <td><?php echo htmlspecialchars($row['phone']); ?></td>
                                <td class="small" style="max-width: 300px;"><?php echo htmlspecialchars($row['message']); ?></td>
                                <td class="text-muted small"><?php echo $row['ip_address']; ?></td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if (empty($submissions)): ?>
                            <tr>
                                <td colspan="8" class="text-center py-5">No submissions found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php endif; ?>

</body>
</html>

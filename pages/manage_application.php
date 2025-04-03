<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    echo "Access denied.";
    exit;
}

$requestsFile = __DIR__ . '/../admin_requests.txt';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'], $_POST['user_id'])) {
    $action = $_POST['action'];
    $targetId = $_POST['user_id'];
    $lines = file($requestsFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $updatedLines = [];

    foreach ($lines as $line) {
        $parts = explode('|', $line);
        if (count($parts) === 5 && trim($parts[0]) === $targetId && trim($parts[4]) === 'pending') {
            $parts[4] = $action === 'approve' ? 'approved' : 'rejected';
            $line = implode('|', $parts);
        }
        $updatedLines[] = $line;
    }

    file_put_contents($requestsFile, implode(PHP_EOL, $updatedLines) . PHP_EOL);
}

$requests = [];
if (file_exists($requestsFile)) {
    $lines = file($requestsFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $parts = explode('|', $line);
        if (count($parts) === 5 && trim($parts[4]) === 'pending') {
            $requests[] = [
                'user_id' => trim($parts[0]),
                'username' => trim($parts[1]),
                'email' => trim($parts[2]),
                'reason' => trim($parts[3]),
                'status' => trim($parts[4])
            ];
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Admin Applications</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Outfit', sans-serif;
            background-color: #0a0a40;
            color: white;
            padding: 100px 20px 40px;
        }
        .container {
            max-width: 1000px;
            margin: auto;
        }
        html, body {
            margin: 0;
            padding: 0;
        }
        h2 {
            text-align: center;
            margin-bottom: 30px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #1a1a60;
            border-radius: 10px;
            overflow: hidden;
        }
        th, td {
            padding: 12px 16px;
            border-bottom: 1px solid #333;
        }
        th {
            background-color: #12124f;
        }
        tr:hover {
            background-color: #2a2a7a;
        }
        .btn {
            padding: 6px 12px;
            border-radius: 6px;
            font-weight: bold;
            border: none;
            cursor: pointer;
            margin-right: 6px;
        }
        .approve {
            background-color: #00c853;
            color: white;
        }
        .reject {
            background-color: #ff1744;
            color: white;
        }
    </style>
</head>
<body>

<?php include "../components/_nav.php"; ?>

<div class="container">
    <h2>Manage Admin Applications (Text File)</h2>
    <table>
        <thead>
            <tr>
                <th>User ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Reason</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($requests) > 0): ?>
                <?php foreach ($requests as $request): ?>
                    <tr>
                        <td><?= htmlspecialchars($request['user_id']) ?></td>
                        <td><?= htmlspecialchars($request['username']) ?></td>
                        <td><?= htmlspecialchars($request['email']) ?></td>
                        <td><?= htmlspecialchars($request['reason']) ?></td>
                        <td><?= htmlspecialchars($request['status']) ?></td>
                        <td>
                            <form method="post" style="display:inline">
                                <input type="hidden" name="user_id" value="<?= htmlspecialchars($request['user_id']) ?>">
                                <button class="btn approve" name="action" value="approve">Approve</button>
                            </form>
                            <form method="post" style="display:inline">
                                <input type="hidden" name="user_id" value="<?= htmlspecialchars($request['user_id']) ?>">
                                <button class="btn reject" name="action" value="reject">Reject</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="6" style="text-align:center;">No pending requests.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

</body>
</html>

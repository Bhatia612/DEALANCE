<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    echo "Access denied.";
    exit;
}

include "../database/db.php";

if (isset($_POST['delete_user'])) {
    $userId = intval($_POST['user_id']);
    if ($userId !== $_SESSION['user_id']) {
        $stmt = $conn->prepare("DELETE FROM users WHERE user_id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
    }
}

if (isset($_POST['update_role'])) {
    $userId = intval($_POST['user_id']);
    $newRole = $_POST['new_role'];
    $stmt = $conn->prepare("UPDATE users SET role = ? WHERE user_id = ?");
    $stmt->bind_param("si", $newRole, $userId);
    $stmt->execute();
}

$result = $conn->query("SELECT user_id, username, email, role, created_at FROM users");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Users</title>
    <style>
        body {
            font-family: 'Outfit', sans-serif;
            background-color: #0a0a40;
            color: white;
            padding: 40px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: #1a1a60;
            border-radius: 8px;
            overflow: hidden;
        }

        th, td {
            padding: 12px 20px;
            text-align: left;
            border-bottom: 1px solid #333;
        }

        th {
            background-color: #12124f;
        }

        tr:hover {
            background-color: #2a2a7a;
        }

        form {
            display: inline;
        }

        .btn {
            padding: 6px 12px;
            border-radius: 4px;
            border: none;
            cursor: pointer;
        }

        .btn-delete {
            background-color: #ff4d4d !important;
            color: white;
        }

        .btn-delete:hover {
            background-color: #e60000 !important;
        }

        .btn-update {
            background-color: #4CAF50 !important;
            color: white;
        }

        .btn-update:hover {
            background-color: #388E3C !important;
        }

        select {
            background-color: #fff;
            color: #000;
            border-radius: 4px;
            padding: 4px;
        }
    </style>
</head>
<body>

<?php include "../components/_nav.php"; ?>

<h2>Manage Users</h2>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Email</th>
            <th>Role</th>
            <th>Created At</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $row['user_id'] ?></td>
            <td><?= htmlspecialchars($row['username']) ?></td>
            <td><?= htmlspecialchars($row['email']) ?></td>
            <td>
                <form method="post" style="display: flex; gap: 5px;">
                    <input type="hidden" name="user_id" value="<?= $row['user_id'] ?>">
                    <select name="new_role">
                        <option value="admin" <?= $row['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
                        <option value="employer" <?= $row['role'] === 'employer' ? 'selected' : '' ?>>Employer</option>
                        <option value="freelancer" <?= $row['role'] === 'freelancer' ? 'selected' : '' ?>>Freelancer</option>
                    </select>
                    <button type="submit" name="update_role" class="btn btn-update">Update</button>
                </form>
            </td>
            <td><?= $row['created_at'] ?></td>
            <td>
                <?php if ($row['user_id'] != $_SESSION['user_id']): ?>
                <form method="post" onsubmit="return confirm('Are you sure to delete this user?');">
                <input type="hidden" name="user_id" value="<?= $row['user_id'] ?>">
                <button type="submit" name="delete_user" class="btn btn-delete">Delete</button>
                </form>
                <?php else: ?>
                <span style="color: #aaa;">(You)</span>
                <?php endif; ?>
            </td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>

</body>
</html>

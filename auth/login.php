<?php
session_start();
include '../config/db.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    $stmt = $conn->prepare("SELECT UserID, Name, Password FROM users WHERE Email = ? AND Status = 'Active'");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $stmt->bind_result($user_id, $name, $stored_password);
        $stmt->fetch();

        // Compare plain text password directly
        if ($password === $stored_password) {
            $stmt->close();

            $roleQuery = $conn->prepare("
                SELECT r.RoleName 
                FROM userroles ur 
                JOIN roles r ON ur.RoleID = r.RoleID 
                WHERE ur.UserID = ?
            ");
            $roleQuery->bind_param("i", $user_id);
            $roleQuery->execute();
            $roleResult = $roleQuery->get_result()->fetch_assoc();

            $_SESSION["user_id"] = $user_id;
            $_SESSION["name"] = $name;
            $_SESSION["role"] = $roleResult["RoleName"];

            // Check the user's role and redirect accordingly
            switch ($_SESSION["role"]) {
                case "Vet":
                    header("Location: ../vet/vet-dashboard.php");
                    break;
                case "Volunteer":
                    header("Location: ../volunteer/volunteer-dashboard.php"); // Adjust the URL accordingly
                    break;
                case "Admin":
                    header("Location: ../admin/admin-dashboard.php"); // Adjust the URL accordingly
                    break;
                case "Supplier":
                    header("Location: ../marketplace/product-supply-dashboard.php"); // Adjust the URL accordingly
                    break;
                case "Service Provider":
                    header("Location: ../vendor/vendor-dashboard.php"); // Adjust the URL accordingly
                    break;
                case "User":
                default:
                    header("Location: ../user/user-home.php");
                    break;
            }
            exit;
        } else {
            $message = "❌ Invalid password.";
        }
    } else {
        $message = "❌ No active account found with that email.";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Login</title>
    <style>
        body {
            font-family: "Segoe UI", sans-serif;
            background: #eef2f5;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .form-container {
            background: #fff;
            padding: 40px;
            width: 100%;
            max-width: 400px;
            border-radius: 12px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }

        .form-container h2 {
            text-align: center;
            margin-bottom: 25px;
            color: #333;
        }

        input {
            width: 100%;
            padding: 12px;
            margin: 8px 0 16px;
            border: 1px solid #ccc;
            border-radius: 8px;
            box-sizing: border-box;
        }

        button {
            width: 100%;
            background-color: #28a745;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
        }

        button:hover {
            background-color: #218838;
        }

        .message {
            margin-bottom: 15px;
            text-align: center;
            font-weight: bold;
            color: red;
        }

        .back-link {
            display: block;
            text-align: center;
            margin-top: 15px;
            color: #555;
            text-decoration: none;
        }

        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="form-container">
        <h2>Login</h2>

        <?php if (!empty($message)): ?>
            <div class="message"><?php echo $message; ?></div>
        <?php endif; ?>

        <form method="POST">
            <input type="email" name="email" placeholder="Email Address" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>

        <a class="back-link" href="register.php">Don't have an account? Register here</a>
    </div>
</body>

</html>
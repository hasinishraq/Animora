<?php
session_start();
include '../config/db.php';

$roles = [];
$roleQuery = $conn->query("SELECT RoleID, RoleName FROM roles");

if ($roleQuery && $roleQuery->num_rows > 0) {
    while ($row = $roleQuery->fetch_assoc()) {
        $roles[] = $row;
    }
}

$message = "";

// Fetch Divisions for the dropdown
$divisionQuery = $conn->query("SELECT id, division_name FROM divisions");
$divisions = [];
if ($divisionQuery && $divisionQuery->num_rows > 0) {
    while ($row = $divisionQuery->fetch_assoc()) {
        $divisions[] = $row;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name     = $_POST["name"];
    $email    = $_POST["email"];
    $password = $_POST["password"];
    $phone    = $_POST["phone"];
    $status   = 'Active';
    $role_id  = $_POST["role"];
    $division_id = $_POST["division"];
    $district_id = $_POST["district"];
    $area_id    = $_POST["area"];

    // === Validate and handle image upload ===
    $targetDir = "../uploads/profile/";  // Store in profile folder inside uploads
    $fileTmp   = $_FILES["profile_image"]["tmp_name"] ?? null;
    $fileName  = basename($_FILES["profile_image"]["name"] ?? '');
    $fileExt   = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    $allowed   = ['jpg', 'jpeg', 'png', 'gif'];

    if (!$fileTmp) {
        $message = "❌ Please upload a profile photo.";
    } elseif (!in_array($fileExt, $allowed)) {
        $message = "❌ Only JPG, JPEG, PNG & GIF files are allowed.";
    } else {
        $newFileName = uniqid("profile_", true) . "." . $fileExt;
        $targetPath = $targetDir . $newFileName;

        // Check if directory exists, if not create it
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);  // Create directory with full permissions
        }

        if (move_uploaded_file($fileTmp, $targetPath)) {
            // Save relative path to DB (remove "../")
            $relativePath = "uploads/profile/" . $newFileName;

            // === Check for duplicate email ===
            $check = $conn->prepare("SELECT Email FROM users WHERE Email = ?");
            $check->bind_param("s", $email);
            $check->execute();
            $check->store_result();

            if ($check->num_rows > 0) {
                $message = "❌ Email already registered.";
            } else {
                // Insert into Users table
                $stmt = $conn->prepare("INSERT INTO users (Name, Email, Password, Phone,  ProfilePhoto, Status, CreatedAt, division_id, district_id, area_id) VALUES (?, ?, ?, ?, ?, ?, NOW(), ?, ?, ?)");
                $stmt->bind_param("ssssssiii", $name, $email, $password, $phone, $relativePath, $status, $division_id, $district_id, $area_id);

                if ($stmt->execute()) {
                    $user_id = $conn->insert_id;

                    // Insert into userRoles table
                    $roleStmt = $conn->prepare("INSERT INTO userRoles (UserID, RoleID) VALUES (?, ?)");
                    $roleStmt->bind_param("ii", $user_id, $role_id);
                    $roleStmt->execute();

                    $message = "✅ Registration successful!";
                } else {
                    $message = "❌ Error: " . $stmt->error;
                }
            }
        } else {
            $message = "❌ Failed to upload profile photo.";
        }
    }
}

// Fetch districts based on division selection (AJAX)
if (isset($_GET['division_id'])) {
    $division_id = $_GET['division_id'];
    $districtQuery = $conn->prepare("SELECT id, district_name FROM districts WHERE division_id = ?");
    $districtQuery->bind_param("i", $division_id);
    $districtQuery->execute();
    $districtResult = $districtQuery->get_result();
    $districts = [];

    while ($row = $districtResult->fetch_assoc()) {
        $districts[] = $row;
    }

    echo json_encode($districts);
    exit();
}

// Fetch areas based on district selection (AJAX)
if (isset($_GET['district_id'])) {
    $district_id = $_GET['district_id'];
    $areaQuery = $conn->prepare("SELECT id, area_name FROM areas WHERE district_id = ?");
    $areaQuery->bind_param("i", $district_id);
    $areaQuery->execute();
    $areaResult = $areaQuery->get_result();
    $areas = [];

    while ($row = $areaResult->fetch_assoc()) {
        $areas[] = $row;
    }

    echo json_encode($areas);
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <style>
        body {
            font-family: "Segoe UI", sans-serif;
            background: #f0f4f8;
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

        input, select {
            width: 100%;
            padding: 12px;
            margin: 8px 0 16px;
            border: 1px solid #ccc;
            border-radius: 8px;
            box-sizing: border-box;
        }

        button {
            width: 100%;
            background-color: #007BFF;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }

        .message {
            margin-bottom: 15px;
            text-align: center;
            font-weight: bold;
            color: green;
        }

        .message.error {
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
    <script>
        // Fetch districts based on selected division
        function fetchDistricts(divisionId) {
            var xhr = new XMLHttpRequest();
            xhr.open('GET', 'register.php?division_id=' + divisionId, true);
            xhr.onload = function() {
                if (xhr.status == 200) {
                    var districts = JSON.parse(xhr.responseText);
                    var districtSelect = document.getElementById('district');
                    districtSelect.innerHTML = '<option value="">-- Select District --</option>';

                    districts.forEach(function(district) {
                        var option = document.createElement('option');
                        option.value = district.id;
                        option.text = district.district_name;
                        districtSelect.appendChild(option);
                    });
                }
            };
            xhr.send();
        }

        // Fetch areas based on selected district
        function fetchAreas(districtId) {
            var xhr = new XMLHttpRequest();
            xhr.open('GET', 'register.php?district_id=' + districtId, true);
            xhr.onload = function() {
                if (xhr.status == 200) {
                    var areas = JSON.parse(xhr.responseText);
                    var areaSelect = document.getElementById('area');
                    areaSelect.innerHTML = '<option value="">-- Select Area --</option>';

                    areas.forEach(function(area) {
                        var option = document.createElement('option');
                        option.value = area.id;
                        option.text = area.area_name;
                        areaSelect.appendChild(option);
                    });
                }
            };
            xhr.send();
        }
    </script>
</head>
<body>
    <div class="form-container">
        <h2>Register</h2>

        <?php if (!empty($message)): ?>
            <div class="message <?php echo strpos($message, '❌') !== false ? 'error' : ''; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">
            <input type="text" name="name" placeholder="Full Name" required>
            <input type="email" name="email" placeholder="Email Address" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="text" name="phone" placeholder="Phone Number">
            <input type="file" name="profile_image" accept="image/*" required>

            <select name="division" id="division" onchange="fetchDistricts(this.value)" required>
                <option value="">-- Select Division --</option>
                <?php foreach ($divisions as $division): ?>
                    <option value="<?php echo $division['id']; ?>"><?php echo htmlspecialchars($division['division_name']); ?></option>
                <?php endforeach; ?>
            </select>

            <select name="district" id="district" onchange="fetchAreas(this.value)" required>
                <option value="">-- Select District --</option>
            </select>

            <select name="area" id="area" required>
                <option value="">-- Select Area --</option>
            </select>

            <select name="role" required>
                <option value="">-- Select Role --</option>
                <?php foreach ($roles as $role): ?>
                    <option value="<?php echo $role['RoleID']; ?>"><?php echo htmlspecialchars($role['RoleName']); ?></option>
                <?php endforeach; ?>
            </select>

            <button type="submit">Register</button>
        </form>

        <a class="back-link" href="/animora/index.php">← Back to Home</a>
    </div>
</body>
</html>

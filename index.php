<?php
// List of relevant employee IDs
$employeeIds = [1, 17, 19];

// API URL
$apiUrl = "https://ethan-raulin.alwaysdata.net/api.php";

// Function to fetch employee data from the API
function getEmployeeData($id, $apiUrl) {
    $url = $apiUrl . "?action=employee&id=" . $id;
    $response = file_get_contents($url);
    return json_decode($response, true);
}

// Retrieve data for each employee
$employees = [];
foreach ($employeeIds as $id) {
    $employees[$id] = getEmployeeData($id, $apiUrl);
}

if (isset($_COOKIE['auth_token']) && isset($_COOKIE['user_email'])) {
    // If both cookies are set, check the role and redirect accordingly
    $userEmail = $_COOKIE['user_email'];
    foreach ($employees as $employee) {
        if ($employee['employee_email'] === $userEmail) {
            $role = $employee['employee_role'];
            switch ($role) {
                case 'employee':
                    echo "<script>window.location.href = 'Employes/HomeEmp.php';</script>";
                    break;
                case 'chief':
                    echo "<script>window.location.href = 'Chiefs/HomeChief.php';</script>";
                    break;
                case 'it':
                    echo "<script>window.location.href = 'IT/HomeIT.php';</script>";
                    break;
                default:
                    echo "<script>window.location.href = 'HomeClient.php';</script>";
                    break;
            }
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f4f4f4;
        }

        .login-container {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 300px;
        }

        h1 {
            color: #2C3E50;
        }

        label {
            display: block;
            margin-top: 10px;
            font-weight: bold;
            color: #333;
        }

        input {
            width: 80%;
            padding: 8px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        button {
            margin-top: 15px;
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background-color: #388E3C;
        }

        .link {
            display: block;
            margin-top: 15px;
            color: #4CAF50;
            text-decoration: none;
        }

        .link:hover {
            text-decoration: underline;
        }

        .post-it {
            width: 200px;
            height: 200px;
            background-color: #ffeb3b;
            border: 2px solid #fbc02d;
            box-shadow: 5px 5px 15px rgba(0, 0, 0, 0.2);
            padding: 10px;
            font-family: 'Arial', sans-serif;
            font-size: 13px;
            color: #333;
            transform: rotate(-6deg);
            /* rotation effect for "post-it" look */
        }

        .post-it p {
            margin: 0;
            padding: 0;
        }
    </style>
</head>

<body>
    <div class="login-container">
        <h1>Login Page</h1>
        <form action="api.php" method="POST" id="login-form">
            <input type="hidden" name="action" value="connex">
            <input type="hidden" name="api_key" value="e8f1997c763">
            <label>Email :</label>
            <input type="email" name="email" id="email" required>
            <label>Password :</label>
            <input type="password" name="password" id="password" required>
            <button type="submit">Login</button>
        </form>
        <a class="link" href="Client/HomeClient.php">Access the site without logging in.</a>
    </div>
    <br>
    <div class="post-it">
        <p>Login credentials!<br><br></p>
        <?php foreach ($employees as $id => $employee): ?>
            <p><?php echo htmlspecialchars($employee['employee_role']); ?> :</p>
            <p><?php echo htmlspecialchars($employee['employee_email']); ?><br>
            <?php echo htmlspecialchars($employee['employee_password']); ?><br><br></p>
        <?php endforeach; ?>
    </div>

    <script>
        // Login form with cookie handling
        const form = document.getElementById('login-form');
        form.addEventListener('submit', function(event) {
            event.preventDefault();

            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;

            // Sending request to API for login
            fetch('api.php', {
                method: 'POST',
                body: new URLSearchParams({
                    'action': 'connex',
                    'email': email,
                    'password': password,
                }),
                credentials: 'same-origin'  // Ensure cookies are sent with the request
            })
            .then(response => response.text())  // Read response as text for debugging
            .then(data => {
                try {
                    const jsonResponse = JSON.parse(data);  // Try to parse the response as JSON
                    if (jsonResponse.success) {
                        const authToken = jsonResponse.auth_token; // Assuming the token is returned by the API
                        document.cookie = "auth_token=" + authToken + ";path=/;max-age=" + (60 * 60);  // 1 hour
                        document.cookie = "user_email=" + email + ";path=/;max-age=" + (60 * 60);  // 1 hour

                        // Redirect based on the role
                        window.location.href = jsonResponse.redirect_url;  // This redirect is returned by the API
                    } else {
                        alert(jsonResponse.error || 'Incorrect credentials');
                    }
                } catch (error) {
                    console.error('Error parsing JSON:', error);
                    console.log('Raw server response:', data);
                    alert('Login error: the server response is not in the expected format.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Login error');
            });
        });
    </script>
</body>

</html>
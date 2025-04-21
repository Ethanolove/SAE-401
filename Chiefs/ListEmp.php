<?php
// Start output buffering to avoid any output before headers
ob_start();
session_start();

// Check for the existence of the 'auth_token' cookie (ensures the user is authenticated)
if (!isset($_COOKIE['auth_token']) || empty($_COOKIE['auth_token'])) {
    // If the cookie is missing or empty, redirect to the login page
    header("Location: ../index.php");
    exit();
}

if (isset($_SESSION['user_id'])) {
    $employeeId = $_SESSION['user_id'];
} else {
    die("User not logged in.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Management Interface</title>
  <link rel="stylesheet" href="../cssgen.css" />
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

  <style>
    table {
      border-collapse: collapse;
      margin-top: 1em;
      width: 75%;
      margin: auto;
    }
    th, td {
      padding: 8px;
      border: 1px solid #ccc;
      text-align: left;
    }
    td[contenteditable] {
      background-color: #fefefe;
    }
    #toggleForm {
      border: 2px solid #4D4D4D;
      padding-left: 5px;
      padding-right: 5px;
      cursor: pointer;
      margin-left: 5px;
    }
  </style>

<script type="text/javascript">
  const employeeId = <?php echo $employeeId; ?>;
  const API_URL = "https://ethan-raulin.alwaysdata.net/api.php";

  // Global variable to store the store ID
  let employeeStoreId = null;

  // Function to load employee personal info
function loadPersonalInfo() {
  $.get(API_URL, { action: "employee", id: employeeId }, function (data) {
    // Check if the response contains a store_id
    if (data && data.store_id) {
      employeeStoreId = data.store_id.store_id;  // Ensure this is the correct path

      // Once the store_id is retrieved, we can load the employees
      loadEmployeesForStore();
    } else {
      console.error("Error: Store ID not found in the response.");
    }
  }, "json");
}

// Function to retrieve employees from the store
function loadEmployeesForStore() {
  // Check if the store ID was properly retrieved
  if (!employeeStoreId) {
    console.error("Store ID missing!");
    return;
  }

  // Perform the AJAX call to retrieve employees associated with the store_id
  $.get(API_URL, { action: "employees_by_store", store_id: employeeStoreId }, function (data) {
    if (data.error) {
      console.error("Error:", data.error);
      return;
    }

    // Empty the table before filling it with new data
    const tableBody = $("#informations");
    tableBody.empty();  // Empty the previous rows

    // Loop through the employee data and display it in the table
    data.forEach(employee => {
      const row = `
        <tr>
          <td>${employee.employee_id}</td>
          <td>${employee.name}</td>
          <td>${employee.email}</td>
          <td>${employee.role}</td>
        </tr>
      `;
      tableBody.append(row);  // Add each row to the table
    });
  }, "json");
}

// Call the function to load employee personal info
loadPersonalInfo();

$(document).ready(function () {
    // Form validation and submission

    $("#AddEmployeeForm").submit(function (e) {
        e.preventDefault(); // Prevent default submission

        // Check the form fields
        const employeeName = $("#newEmployeeName").val().trim();
        const employeeEmail = $("#newEmployeeEmail").val().trim();
        const employeePassword = $("#newEmployeePassword").val().trim();
        const employeeRole = $("#newEmployeeRole").val();

        // Check if all fields are filled
        if (!employeeName || !employeeEmail || !employeePassword || !employeeRole) {
            alert("All fields are required.");
            return;
        }

        // Build the formData object with employee information
        const formData = {
            store_id: employeeStoreId, 
            name: employeeName,
            email: employeeEmail,
            password: employeePassword,
            role: employeeRole
        };

        // Perform the AJAX call to add an employee
        $.ajax({
            url: API_URL + '?action=add_employee',  // Action to add an employee
            method: 'POST',
            data: {
                store_id: employeeStoreId, 
                name: employeeName,
                email: employeeEmail,
                password: employeePassword,
                role: employeeRole,
                api_key: "e8f1997c763"  // Add the API key in the sent data
            },
            success: function (data) {
                console.log("API response:", data);  // Show the API response
                if (data.success) {
                    alert("Employee added successfully!");
                    loadEmployeesForStore(); // Reload the list of employees
                    $("#AddEmployeeForm")[0].reset(); // Reset the form
                } else {
                    alert("Error: " + data.message);
                }
            },
            error: function (xhr, status, error) {
                console.error("AJAX error: ", xhr.responseText);
                alert("Failed to add the employee.");
            }
        });
    });
});
</script>
</head>

<body>
  <div id="content">
    <?php $page = "Chief"; require_once("../www/header.inc.php"); ?>
    <h1>Employee List</h1>

    <section>
      <!-- Personal info of employees -->
      <table>
        <thead>
          <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Role</th>
          </tr>
        </thead>
        <tbody id="informations">
        </tbody>
      </table>
    </section>
    <section>
      <h2>Add a New Employee<span id="toggleForm">+</span></h2>
      <form action="" id="AddEmployeeForm" style="display: none;">
        <label for="newEmployeeName">Name</label>
        <input type="text" id="newEmployeeName" required>

        <label for="newEmployeeEmail">Email</label>
        <input type="email" id="newEmployeeEmail" required>

        <label for="newEmployeePassword">Password</label>
        <input type="password" id="newEmployeePassword" required>

        <label for="newEmployeeRole">Role</label>
        <input type="text" id="newEmployeeRole" value="employee" readonly required>
        <button type="submit">Add Employee</button>
      </form>

    </section>
    <script>
        const toggleFormButton = document.getElementById('toggleForm');
        const form = document.getElementById('AddEmployeeForm');
        toggleFormButton.addEventListener('click', function() {
          if (form.style.display === 'none') {
            form.style.display = 'block';
            toggleFormButton.textContent = '-';
          } else {
            form.style.display = 'none';
            toggleFormButton.textContent = '+';
          }
        });
      </script>
  </div>
  <?php require_once("../www/footer.inc.php"); ?>
</body>
</html>
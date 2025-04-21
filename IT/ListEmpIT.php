<?php
// Start output buffering to prevent any output before headers
ob_start();
session_start();

// Check if the 'auth_token' cookie exists (ensures the user is authenticated)
if (!isset($_COOKIE['auth_token']) || empty($_COOKIE['auth_token'])) {
    // If the cookie is absent or empty, redirect to the login page
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
    }
  </style>

<script type="text/javascript">
  const employeeId = <?php echo $employeeId; ?>;
  const API_URL = "https://ethan-raulin.alwaysdata.net/api.php";

  $(document).ready(function () {
    // Load the list of stores when the page loads
    $.get(API_URL, { action: "stores" }, function (data) {
      if (data.error) {
        console.error("Error retrieving stores:", data.error);
        return;
      }

      const storeSelect = $("#chooseStore");
      storeSelect.empty();  // Clear the old content
      storeSelect.append('<option value="">Select a store</option>');  // Default option

      // Add each store to the dropdown list
      data.forEach(store => {
        storeSelect.append(`<option value="${store.store_id}">${store.store_name}</option>`);
      });
    }, "json");

    // When the user selects a store, load employees
    $("#chooseStore").change(function () {
      const choice = $(this).val();
      if (choice) {
        employeeStoreId = choice;  // Update the selected store ID
        loadEmployeesForStore();  // Load employees for the selected store
      } else {
        $("#InfosEmp").hide();  // Hide the employee info if no store is selected
      }
    });

    // Function to load employees for the selected store
    function loadEmployeesForStore() {
      // Check that the store ID is properly retrieved
      if (!employeeStoreId) {
        console.error("Store ID missing!");
        return;
      }

      // Make the AJAX call to retrieve employees associated with the store_id
      $.get(API_URL, { action: "employees_by_store", store_id: employeeStoreId }, function (data) {
        if (data.error) {
          console.error("Error:", data.error);
          return;
        }

        // Clear the table before filling it with new data
        const tableBody = $("#informations");
        tableBody.empty();  // Clear old rows in the table
        $("#InfosEmp").show();

        // Loop through employee data and display it in the table
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

    // Submit the form to add an employee
    $("#AddEmployeeForm").submit(function (e) {
      e.preventDefault();  // Prevent the default form submission

      const employeeName = $("#newEmployeeName").val().trim();
      const employeeEmail = $("#newEmployeeEmail").val().trim();
      const employeePassword = $("#newEmployeePassword").val().trim();
      const employeeRole = $("#newEmployeeRole").val();

      if (!employeeName || !employeeEmail || !employeePassword || !employeeRole || !employeeStoreId) {
        alert("All fields are required.");
        return;
      }

      const formData = {
        store_id: employeeStoreId,  // Include the selected store ID
        name: employeeName,
        email: employeeEmail,
        password: employeePassword,
        role: employeeRole
      };

      $.ajax({
        url: API_URL + '?action=add_employee',
        method: 'POST',
        data: formData,
        success: function (data) {
          if (data.success) {
            alert("Employee added successfully!");
            loadEmployeesForStore();  // Reload the list of employees
            $("#AddEmployeeForm")[0].reset();  // Reset the form
          } else {
            alert("Error: " + data.message);
          }
        },
        error: function (xhr, status, error) {
          alert("Failed to add the employee.");
        }
      });
    });

  });
</script>
</head>

<body>
  <div id="content">
    <?php $page = "IT"; require_once("../www/header.inc.php"); ?>

    <h1>Select a store:</h1>
    <select name="store4emp" id="chooseStore">
      <!-- Stores will be added here dynamically -->
    </select>

    <div id="InfosEmp" style="display:none; margin-top: 1em;">
      <h2>Employee List</h2>
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

      <section>
        <h2>Add a new employee <span id="toggleForm">+</span></h2>

        <form action="" id="AddEmployeeForm" style="display: none;">
          <label for="newEmployeeName">Name</label>
          <input type="text" id="newEmployeeName" required>

          <label for="newEmployeeEmail">Email</label>
          <input type="email" id="newEmployeeEmail" required>

          <label for="newEmployeePassword">Password</label>
          <input type="password" id="newEmployeePassword" required>

          <label for="newEmployeeRole">Role</label>
          <select name="newEmployeeRole" id="newEmployeeRole" required>
            <option value="employee">Employee</option>
            <option value="chief">Chief</option>
          </select>

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
  </div>
  
  <?php require_once("../www/footer.inc.php"); ?>
</body>
</html>
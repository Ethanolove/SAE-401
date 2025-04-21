<?php
ob_start();
session_start();

if (!isset($_COOKIE['auth_token']) || empty($_COOKIE['auth_token'])) {
    header("Location: ../index.php");
    exit();
}

if (isset($_SESSION['user_id'])) {
    $employeeId = $_SESSION['user_id'];
} else {
    die("Utilisateur non connecté.");
}
?>

<!DOCTYPE html>
<html lang="fr">
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
      width: 100%;
    }
    th, td {
      padding: 8px;
      border: 1px solid #ccc;
      text-align: left;
    }
    td[contenteditable] {
      background-color: #fefefe;
    }
  </style>

<script type="text/javascript">
  const employeeId = <?php echo $employeeId; ?>;
  const API_URL = "https://ethan-raulin.alwaysdata.net/api.php";

  $(document).ready(function () {
  $("#Select").change(function () {
    const choice = $(this).val();
    $("#GestionMarques, #GestionCategories, #GestionStocks, #GestionMagasin, #InfosPerso, #productSelectSection, #productEditForm").hide();

    switch (choice) {
      case "brands":
        $("#GestionMarques").show();
        loadAllBrands();
        break;
      case "categories":
        $("#GestionCategories").show();
        loadAllCategories();
        break;
      case "products":
        $("#productSelectSection").show();
        loadAllProducts();
        break;
      case "stocks":
        $("#GestionStocks").show();
        loadAllProductsForStock();
        break;
      case "stores":
        $("#GestionMagasin").show();
        loadStoreInfo();
        break;
      case "personal":
        $("#InfosPerso").show();
        loadPersonalInfo();
        break;
    }
  });

    // ---------- PRODUCTS ---------- 
    function loadAllProducts() {
      $.ajax({
        url: API_URL,
        data: { action: "products" },
        dataType: "json",
        success: function (data) {
          let options = "<option value=''>-- Choose a product --</option>";
          data.forEach(prod => {
            options += `<option value="${prod.product_id}">${prod.product_name}</option>`;
          });
          $("#ProductSelect").html(options);
        }
      });
    }

    $("#ProductSelect").change(function () {
      const id = $(this).val();
      if (!id) return $("#productEditForm").hide();

      $.ajax({
        url: API_URL,
        data: { action: "product", id: id },
        dataType: "json",
        success: function (product) {
          $("#productName").val(product.product_name);
          $("#productYear").val(product.model_year);
          $("#productPrice").val(product.list_price);
          $("#productEditForm").show().data("productId", product.product_id);
        }
      });
    });

    $("#productEditForm").submit(function (e) {
      e.preventDefault();

      const formData = {
        product_name: $("#productName").val(),
        model_year: $("#productYear").val(),
        list_price: $("#productPrice").val(),
        brand: $("#BrandSelect").val(),
        category: $("#CategorySelect").val()
      };

      $.ajax({
        url: API_URL + '?action=updateProductInfo&id=' + employeeId,
        method: 'PUT',
        data: formData,
        success: function (data) {
          console.log(data);
          alert("Update successful!");
        },
        error: function () {
          alert("Update failed.");
        }
      });
    });

    // ---------- BRANDS ----------
    function loadAllBrands() {
      $.ajax({
        url: API_URL,
        data: { action: "brands" },
        dataType: "json",
        success: function (data) {
          let options = "<option value=''>-- Choose a brand --</option>";
          data.forEach(brand => {
            options += "<option value='" + brand.brand_id + "'>" + brand.brand_name + "</option>";
          });
          $("#marqueSelect").html(options);
          $("#marqueSelect2").html(options);
        }
      });
    }

    let selectedBrandId = null;

    $(document).ready(function () {
      loadAllBrands();

      $("#marqueSelect").change(function () {
        selectedBrandId = $(this).val();
        if (!selectedBrandId) return $("#marqueEditForm").hide();

        $.ajax({
          url: API_URL,
          data: { action: "brand", id: selectedBrandId },
          dataType: "json",
          success: function (brand) {
            $("#brandName").val(brand.brand_name);
            $("#marqueEditForm").show();
          }
        });
      });

      $("#marqueEditForm").submit(function (e) {
        e.preventDefault();

        if (!selectedBrandId) {
          alert("No brand selected.");
          return;
        }

        const formData = {
          brand_name: $("#brandName").val()
        };

        $.ajax({
          url: API_URL + '?action=updateBrand&id=' + selectedBrandId,
          method: 'PUT',
          data: formData,
          success: function (data) {
            console.log(data);
            alert("Update successful!");
            loadAllBrands();
          },
          error: function (xhr, status, error) {
            console.error("Error updating brand: ", error);
            alert("Update failed.");
          }
        });
      });

      $("#AddMarqueForm").submit(function (e) {
          e.preventDefault();

          const newMarqueName = $("#newMarqueName").val().trim();

          if (newMarqueName === "") {
              alert("Brand name cannot be empty.");
              return;
          }

          const formData = { brand_name: newMarqueName };

          $.ajax({
              url: API_URL + '?action=add_brand',
              method: 'POST',                    
              data: formData,                   
              success: function (data) {
                  console.log(data);
                  alert("Brand added successfully!");
                  loadAllBrands();
              },
              error: function (xhr, status, error) {
                  console.error("Error adding brand: ", xhr.responseText);
                  alert("Failed to add brand.");
              }
          });
      });

      $("#DeleteMarqueForm").submit(function (e) {
        e.preventDefault();

        const brandId = $("#marqueSelect2").val();
        if (!brandId) {
          alert("Please select a brand to delete.");
          return;
        }

        if (!confirm("Are you sure you want to delete this brand?")) return;

        $.ajax({
          url: API_URL + '?action=delete_brand&id=' + brandId,
          method: 'DELETE',
          success: function (data) {
            console.log(data);
            alert("Deletion successful!");
            loadAllBrands();
          },
          error: function (xhr, status, error) {
            console.error("Error deleting brand: ", error);
            alert("Deletion failed.");
          }
        });
      });
    });

    // ---------- CATEGORIES ----------
    function loadAllCategories() {
    $.ajax({
        url: API_URL,
        data: { action: "categories" },
        dataType: "json",
        success: function (data) {
            let options = "<option value=''>-- Choose a category --</option>";
            data.forEach(category => {
                options += `<option value="${category.category_id}">${category.category_name}</option>`;
            });
            $("#CategorySelect").html(options);
            $("#CategorySelect2").html(options);
        }
    });
}

let selectedCategoryId = null;

$(document).ready(function () {
    loadAllCategories();

    $("#CategorySelect").change(function () {
        selectedCategoryId = $(this).val();
        if (!selectedCategoryId) return $("#CategoryEditForm").hide();

        $.ajax({
            url: API_URL,
            data: { action: "category", id: selectedCategoryId },
            dataType: "json",
            success: function (category) {
                $("#categoryName").val(category.category_name);
                $("#CategoryEditForm").show();
            }
        });
    });

    $("#CategoryEditForm").submit(function (e) {
        e.preventDefault();

        if (!selectedCategoryId) {
            alert("No category selected.");
            return;
        }

        const formData = {
            category_name: $("#categoryName").val()
        };

        $.ajax({
            url: API_URL + '?action=updateCategory&id=' + selectedCategoryId,
            method: 'PUT',
            data: formData,
            success: function (data) {
                console.log(data);
                alert("Update successful!");
                loadAllCategories();
            },
            error: function (xhr, status, error) {
                console.error("Error updating category: ", error);
                alert("Update failed.");
            }
        });
    });

    $("#AddCategoryForm").submit(function (e) {
        e.preventDefault();

        const newCategoryName = $("#newCategoryName").val().trim();

        if (newCategoryName === "") {
            alert("Category name cannot be empty.");
            return;
        }

        const formData = { category_name: newCategoryName };

        $.ajax({
            url: API_URL + '?action=addCategory',
            method: 'POST',
            data: formData,
            success: function (data) {
                console.log(data);
                alert("Category added successfully!");
                loadAllCategories();
            },
            error: function (xhr, status, error) {
                console.error("Error adding category: ", xhr.responseText);
                alert("Failed to add category.");
            }
        });
    });

    $("#DeleteCategoryForm").submit(function (e) {
        e.preventDefault();

        const categoryId = $("#CategorySelect2").val();
        if (!categoryId) {
            alert("Please select a category to delete.");
            return;
        }

        if (!confirm("Are you sure you want to delete this category?")) return;

        $.ajax({
            url: API_URL + '?action=deleteCategory&id=' + categoryId,
            method: 'DELETE',
            success: function (data) {
                console.log(data);
                alert("Deletion successful!");
                loadAllCategories();
            },
            error: function (xhr, status, error) {
                console.error("Error deleting category: ", error);
                alert("Deletion failed.");
            }
        });
    });
});

    // ---------- STOCK ----------
let storeId = null;

function loadAllProductsForStock() {
  $.ajax({
    url: API_URL,
    data: { action: "products" },
    dataType: "json",
    success: function (data) {
      let options = "<option value=''>-- Choose a product --</option>";
      data.forEach(prod => {
        options += `<option value="${prod.product_id}">${prod.product_name}</option>`;
      });
      $("#ProductSelect2").html(options);
    }
  });
}

$("#ProductSelect2").change(function () {
  const productId = $(this).val();

  if (!storeId) {
    $.get(API_URL, { action: "employee", id: employeeId }, function (data) {
      storeId = data.store_id.store_id;
      console.log("storeId extracted: ", storeId);

      if (!productId || !storeId) return $("#StockManagementForm").hide();

      $.ajax({
        url: API_URL,
        data: { 
          action: "get_stock", 
          product_id: productId,
          store_id: storeId
        },
        dataType: "json",
        success: function (stocks) {
          console.log("API response: ", stocks);

          if (stocks.quantity !== undefined) {
            $("#quantity").val(stocks.quantity);
            $("#StockManagementForm").show();
          } else {
            alert("Stock not available for this product in this store.");
          }
        },
        error: function () {
          alert("Error fetching stock.");
        }
      });
    }, "json");
  } else {
    if (!productId || !storeId) return $("#StockManagementForm").hide();

    $.ajax({
      url: API_URL,
      data: { 
        action: "get_stock", 
        product_id: productId,
        store_id: storeId
      },
      dataType: "json",
      success: function (stocks) {
        console.log("API response: ", stocks);

        if (stocks.quantity !== undefined) {
          $("#quantity").val(stocks.quantity);
          $("#StockManagementForm").show();
        } else {
          alert("Stock not available for this product in this store.");
        }
      },
      error: function () {
        alert("Error fetching stock.");
      }
    });
  }
});

$("#UpdateStockButton").click(function (e) {
  e.preventDefault();

  const productId = $("#ProductSelect2").val();
  const updatedQuantity = $("#quantity").val();

  if (!productId) {
    alert("Please select a product.");
    return;
  }
  if (updatedQuantity === "") {
    alert("Please enter a quantity.");
    return;
  }
  if (!storeId) {
    alert("Store ID missing.");
    return;
  }

  $.ajax({
  url: API_URL + '?action=update_stock',
  method: 'PUT',
  contentType: 'application/json',
  data: JSON.stringify({
    product_id: productId,
    quantity: updatedQuantity,
    store_id: storeId
  }),
  success: function (data) {
    console.log("Data sent to API: ", {
      product_id: productId,
      quantity: updatedQuantity,
      store_id: storeId
    });
    alert("Stock updated successfully!");
    loadAllProductsForStock();
    $("#StockManagementForm").hide();
  },
  error: function (xhr, status, error) {
    console.log(xhr.responseText);
    alert("Error updating stock.");
  }
});

loadAllProductsForStock();
  });
    // ---------- STORE INFO ----------
    function loadStoreInfo() {
      $.get(API_URL, { action: "employee", id: employeeId }, function (data) {
        $("#storeId").val(data.store_id.store_id);
        $("#storeName").val(data.store_id.store_name);
        $("#street").val(data.store_id.street);
        $("#city").val(data.store_id.city);
        $("#state").val(data.store_id.state);
        $("#zip_code").val(data.store_id.zip_code);
        $("#storePhone").val(data.store_id.phone);
        $("#storeEmail").val(data.store_id.email);
      }, "json");
    }

    $("#magasinForm").submit(function (e) {
      e.preventDefault();

      const formData = {
        store_id: $("#storeId").val(),
        store_name: $("#storeName").val(),
        street: $("#street").val(),
        city: $("#city").val(),
        state: $("#state").val(),
        zip_code: $("#zip_code").val(),
        phone: $("#storePhone").val(),
        email: $("#storeEmail").val()
      };

      $.ajax({
        url: API_URL + '?action=updateStoreInfo&id=' + employeeId,
        method: 'PUT',
        data: formData,
        success: function (data) {
          console.log(data);
          alert("Update successful!");
        },
        error: function () {
          alert("Update failed.");
        }
      });
    });

    // ---------- PERSONAL INFO ----------
    function loadPersonalInfo() {
      $.get(API_URL, { action: "employee", id: employeeId }, function (data) {
        $("#employeeName").val(data.employee_name);
        $("#employeeEmail").val(data.employee_email);
        $("#employeePassword").val(data.employee_password);
      }, "json");
    }

    $("#persoForm").submit(function (e) {
      e.preventDefault();

      const formData = {
        employee_name: $("#employeeName").val(),
        employee_email: $("#employeeEmail").val(),
        employee_password: $("#employeePassword").val()
      };

      $.ajax({
        url: API_URL + '?action=updateEmployeeInfo&id=' + employeeId,
        method: 'PUT',
        data: formData,
        success: function (data) {
          console.log(data);
          alert("Update successful!");
        },
        error: function () {
          alert("Update failed.");
        }
      });
    });
  });
</script>
</head>

<body>
  <div id="content">
      <?php $page = "IT"; require_once("../www/header.inc.php"); ?>
      <h1>Management Interface</h1>

      <section>
        <h2>What would you like to manage?</h2>
        <form>
          <select id="Select">
            <option value="">Select</option>
            <option value="brands">Brands</option>
            <option value="categories">Categories</option>
            <option value="products">Products</option>
            <option value="stocks">Stocks</option>
            <option value="stores">Store Info</option>
            <option value="personal">Personal Info</option>
          </select>
        </form>

        <div id="productSelectSection" style="display:none; margin-top: 1em;">
          <label for="ProductSelect">Product:</label>
          <select id="ProductSelect"></select>
        </div>

        <form id="productEditForm" style="display:none; margin-top: 1em;">
          <h3>Edit Product</h3>
          <label>Name: <input type="text" id="productName" required /></label><br/>
          <label>Year: <input type="number" id="productYear" required /></label><br/>
          <label>Price ($): <input type="number" id="productPrice" step="0.01" required /></label><br/>
          <button type="submit">Save Changes</button>
        </form>

        <div id="GestionMarques" style="display:none; width: 40%;">
          <div>
            <h2>Edit a Brand</h2>
            <select name="marque" id="marqueSelect"></select>

            <form id="marqueEditForm" style="display:none; margin-top: 1em;">
              <input type="text" id="brandName" required /><br><br>
              <button type="submit">Save Changes</button>
            </form>
          </div>

          <div>
            <h2>Delete a Brand</h2>
            <form action="" id="DeleteMarqueForm">
              <select name="marque" id="marqueSelect2"></select>
              <button type="submit">Delete</button>
            </form>
          </div>

          <div>
            <h2>Add a Brand</h2>
            <form action="" id="AddMarqueForm">
            <input type="text" id="newMarqueName" placeholder="Brand Name" required>
            <button type="submit">Add Brand</button>
            </form>
          </div>
        </div>

        <div id="GestionCategories" style="display:none;">
        <div>
            <h2>Edit a Category</h2>
            <select name="categorie" id="CategorySelect"></select>

            <form id="CategoryEditForm" style="display:none; margin-top: 1em;">
              <input type="text" id="categoryName" required /><br><br>
              <button type="submit">Save Changes</button>
            </form>
          </div>

          <div>
            <h2>Delete a Category</h2>
            <form action="" id="DeleteCategoryForm">
              <select name="categorie" id="CategorySelect2"></select>
              <button type="submit">Delete</button>
            </form>
          </div>

          <div>
            <h2>Add a Category</h2>
            <form action="" id="AddCategoryForm">
            <input type="text" id="newCategoryName" placeholder="Category Name" required>
            <button type="submit">Add Category</button>
            </form>
          </div>
        </div>

        <div id="GestionStocks" style="display:none;">
          <div>
              <h2>Manage Stock</h2>
              <select name="produit" id="ProductSelect2">
              </select>
              <br><br>

              <div id="StockManagementForm" style="display:none;">
                  <label for="quantity">Quantity</label>
                  <input type="number" id="quantity" min="0" required />
                  <button type="submit" id="UpdateStockButton">Update Stock</button>
              </div>
          </div>
        </div>

        <div id="GestionMagasin" style="display:none; margin-top: 1em;">
          <h3>Store Information</h3>
          <form id="magasinForm">
            <label>Store ID: <input type="text" id="storeId" disabled /></label><br/>
            <label>Store Name: <input type="text" id="storeName" required /></label><br/>
            <label>Street: <input type="text" id="street" /></label><br/>
            <label>City: <input type="text" id="city" /></label><br/>
            <label>State: <input type="text" id="state" /></label><br/>
            <label>Zip Code: <input type="text" id="zip_code" /></label><br/>
            <label>Phone: <input type="text" id="storePhone" /></label><br/>
            <label>Email: <input type="email" id="storeEmail" /></label><br/>
            <button type="submit">Save</button>
          </form>
        </div>

        <div id="InfosPerso" style="display:none; margin-top: 1em;">
          <h3>Personal Information</h3>
          <form id="persoForm">
            <label>Full Name: <input type="text" id="employeeName" required /></label><br/>
            <label>Email: <input type="email" id="employeeEmail" required /></label><br/>
            <label>Password: <input type="text" id="employeePassword" required /></label><br/>
            <button type="submit">Save</button>
          </form>
        </div>
      </section>
  </div>
  <?php require_once("../www/footer.inc.php"); ?>
</body>
</html>
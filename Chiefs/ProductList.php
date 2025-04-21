<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product List</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.13.2/themes/base/jquery-ui.css" rel="stylesheet" type="text/css"/>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.13.2/jquery-ui.min.js"></script>
    <link rel="stylesheet" href="../cssgen.css" />
    <script type="text/javascript">
        $(document).ready(function(){
            loadBrands(); // Load brands as soon as the page is ready
            loadCategories(); // Load categories as soon as the page is ready
            loadYears(); // Load years as soon as the page is ready
            loadPrices(); // Load price ranges as soon as the page is ready
            
            // Show an initial message
            $("#ListeProd").html("<p class='loadingMessage'>Choose your filters to see the products</p>");
            
            // Update products when filters are selected
            $("#BrandSelect, #CategorySelect, #YearSelect, #PriceSelect").change(function() {
                $("#ListeProd").html("<p class='loadingMessage'>Loading products...</p>");
                loadProducts(20); // Load products when a filter is applied

                setTimeout(function() {
                    if ($("#ListeProd").children().length > 5) {
                        $("#ListeProd").css("overflow-y", "scroll");
                        $("#ListeProd").css("max-height", "60vh"); // Prevent the list from exceeding the page
                    } else {
                        $("#ListeProd").css("overflow-y", "visible"); // Reset if less than 5 items
                    }
                }, 400); // Time for products to load
            });
        });

        // Function to retrieve and load brands in the select
        function loadBrands() {
            $.ajax({
                url: "https://ethan-raulin.alwaysdata.net/api.php",
                data: { action: "brands" }, // Action to get the brands
                dataType: "json",
                success: function(data){
                    let html = "<option value=''>Brands / All</option>";
                    for (let i = 0; i < data.length; i++) {
                        html += "<option value='" + data[i].brand_id + "'>" + data[i].brand_name + "</option>";
                    }
                    $("#BrandSelect").html(html); // Fill the select with brands
                },
                error: function(xhr, status, error) {
                    console.error("Error loading brands:", error);
                }
            });
        }
        
        // Function to retrieve and load categories in the select
        function loadCategories() {
            $.ajax({
                url: "https://ethan-raulin.alwaysdata.net/api.php",
                data: { action: "categories" }, // Action to get the categories
                dataType: "json",
                success: function(data){
                    let html = "<option value=''>Categories / All</option>";
                    for (let i = 0; i < data.length; i++) {
                        html += "<option value='" + data[i].category_id + "'>" + data[i].category_name + "</option>";
                    }
                    $("#CategorySelect").html(html); // Fill the select with categories
                },
                error: function(xhr, status, error) {
                    console.error("Error loading categories:", error);
                }
            });
        }
        
        // Function to retrieve and load years in the select
        function loadYears() {
            $.ajax({
                url: "https://ethan-raulin.alwaysdata.net/api.php",
                data: { action: "years" }, // Action to get the years
                dataType: "json",
                success: function(data){
                    let html = "<option value=''>Years / All</option>";
                    for (let i = 0; i < data.length; i++) {
                        html += "<option value='" + data[i] + "'>" + data[i]+ "</option>";
                    }
                    $("#YearSelect").html(html); // Fill the select with years
                },
                error: function(xhr, status, error) {
                    console.error("Error loading years:", error);
                }
            });
        }

        // Function to load price ranges in the select
        function loadPrices() {
            let priceRanges = [
                { min: 0, max: 500, label: "0 - 500 $" },
                { min: 500, max: 1000, label: "500 - 1000 $" },
                { min: 1000, max: 2000, label: "1000 - 2000 $" },
                { min: 2000, max: 5000, label: "2000 - 5000 $" },
                { min: 5000, max: 12000, label: "5000+ $" }
            ];
            
            let html = "<option value=''>Prices / All</option>";
            for (let i = 0; i < priceRanges.length; i++) {
                html += "<option value='" + priceRanges[i].min + "-" + priceRanges[i].max + "'>" + priceRanges[i].label + "</option>";
            }
            $("#PriceSelect").html(html);
        }

        // Function to load products based on filters
        function loadProducts(limit) {
            let brand = $("#BrandSelect").val();
            let category = $("#CategorySelect").val();
            let year = $("#YearSelect").val();
            let price = $("#PriceSelect").val();
            console.log(price);
            
            console.log("Applied filters:", {
                brand: brand,
                category: category,
                year: year,
                price: price,
                limit: limit
            });
            
            $.ajax({
                url: `https://ethan-raulin.alwaysdata.net/api.php?brand=${brand}&category=${category}&year=${year}&price=${price}`,
              
                dataType: "json",
                success: function(data){
                    console.log("Results received:", data);
                    
                    if (data && data.length > 0) {
                        let html = "";
                        for(let i = 0; i < data.length; i++){
                            html += `<div>
                                <h3>${data[i].product_name}</h3>
                                <p>Brand: ${data[i].brand ? data[i].brand.brand_name : 'Not specified'}</p>
                                <p>Category: ${data[i].category ? data[i].category.category_name : 'Not specified'}</p>
                                <p>Year: ${data[i].model_year}</p>
                                <p class="price">Price: ${data[i].list_price} $</p>
                            </div>`;
                        }
                        $("#ListeProd").html(html);
                    } else {
                        $("#ListeProd").html("<p class='loadingMessage'>No products match your criteria</p>");
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Error loading products:", error);
                    console.log("Raw response:", xhr.responseText);
                    $("#ListeProd").html("<p class='loadingMessage'>An error occurred while loading products</p>");
                }
            });
        }
    </script>
</head>
<body>
    <div id="content">
    <?php $page = "Chief"; require_once("../www/header.inc.php"); ?>
    
    <h1>Product List</h1>
    
    <section>
        <h2>Filter your search</h2>
        <form>
            <select id="BrandSelect">
                <option value="">Brands / All</option>
            </select>
            
            <select id="CategorySelect">
                <option value="">Categories / All</option>
            </select>
            
            <select id="YearSelect">
                <option value="">Years / All</option>
            </select>
            
            <select id="PriceSelect">
                <option value="">Prices / All</option>
            </select>
            <button type="button" onclick="$('#BrandSelect, #CategorySelect, #YearSelect, #PriceSelect').val(''); $('#ListeProd').html('<p class=\'loadingMessage\'>Choose your filters to see the products</p>'); $('#ListeProd').css('overflow-y', 'visible');">Reset filters</button>
        </form>
        <div id="ListeProd"></div>
    </section>
    </div>
    <?php require_once("../www/footer.inc.php"); ?>
</body>
</html>
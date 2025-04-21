<style>
    .header {
        display: flex;
        background-color: #5DA35F;
        justify-content: space-around;
        padding: 10px;
        align-items: center;
    }
    .prod {
        text-decoration: none;
        color: #4A5F3A;
        display: flex;
        padding: 10px;
        border: 2px #4A5F3A solid;
    }
</style>

<?php
if ($page == "Client") {
    echo "<nav class='header'>
            <a href='HomeClient.php'><img src='../icones/velo.png' alt='home' style='width: 50px; height: 50px;'></a>
            <a href='ProductList.php' class='prod'>Our Products</a>
            <a href='../index.php' class='logout-btn'><img src='../icones/exit.png' alt='exitlogo' style='width: 50px; height: 50px;'></a>
        </nav>";
} else if ($page == "Employee") {
    echo "<nav class='header'>
            <a href='HomeEmp.php'><img src='../icones/velo.png' alt='home' style='width: 50px; height: 50px;'></a>
            <a href='ProductList.php' class='prod'>Our Products</a>
            <a href='Gestion.php' class='prod'>Management Interface</a>
            <a href='../index.php' class='logout-btn'><img src='../icones/exit.png' alt='exitlogo' style='width: 50px; height: 50px;'></a>
        </nav>";
} else if ($page == "Chief") {
    echo "<nav class='header'>
            <a href='HomeChief.php'><img src='../icones/velo.png' alt='home' style='width: 50px; height: 50px;'></a>
            <a href='ProductList.php' class='prod'>Our Products</a>
            <a href='Gestion.php' class='prod'>Management Interface</a>
            <a href='ListEmp.php' class='prod'>Employee List</a>
            <a href='../index.php' class='logout-btn'><img src='../icones/exit.png' alt='exitlogo' style='width: 50px; height: 50px;'></a>
        </nav>";
} else if ($page == "IT") {
    echo "<nav class='header'>
            <a href='HomeIT.php'><img src='../icones/velo.png' alt='home' style='width: 50px; height: 50px;'></a>
            <a href='ProductList.php' class='prod'>Our Products</a>
            <a href='Gestion.php' class='prod'>Management Interface</a>
            <a href='ListEmpIT.php' class='prod'>Employee List</a>
            <a href='../index.php' class='logout-btn'><img src='../icones/exit.png' alt='exitlogo' style='width: 50px; height: 50px;'></a>
        </nav>";
} else {
    echo "<nav class='header'>
            <a href='../index.php'><img src='../icones/velo.png' alt='home' style='width: 50px; height: 50px;'></a>
            <a href='../index.php'><img src='../icones/exit.png' alt='exitlogo' style='width: 50px; height: 50px;'></a>
        </nav>";
}
?>

<script>
    function deleteCookies() {
        document.cookie = "auth_token=; path=/; max-age=-1";
        document.cookie = "user_email=; path=/; max-age=-1";
    }

    document.querySelectorAll('.logout-btn').forEach(function(button) {
        button.addEventListener('click', function(event) {
            deleteCookies();
            window.location.href = "../index.php";
        });
    });
</script>
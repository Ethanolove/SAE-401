<?php
if ($page == "Client") {
    echo '<footer id="footer">
        <a href="HomeClient.php">Home</a>
        <a href="legalMentions.php">Legal mentions</a>
        <a href="../index.php" class="logout-btn" style="margin-right: 50px">Logout</a>
        @Copyright 2025 - Ethan Raulin
    </footer>';
} else if ($page == "Employee") {
    echo '<footer id="footer">
        <a href="HomeEmp.php">Home</a>
        <a href="legalMentions.php">Legal mentions</a>
        <a href="../index.php" class="logout-btn" style="margin-right: 50px">Logout</a>
        @Copyright 2025 - Ethan Raulin
    </footer>';
} else if ($page == "Chief") {
    echo '<footer id="footer">
        <a href="HomeChief.php">Home</a>
        <a href="legalMentions.php">Legal mentions</a>
        <a href="../index.php" class="logout-btn" style="margin-right: 50px">Logout</a>
        @Copyright 2025 - Ethan Raulin
    </footer>';
} else if ($page == "IT") {
    echo '<footer id="footer">
        <a href="HomeIT.php">Home</a>
        <a href="legalMentions.php">Legal mentions</a>
        <a href="../index.php" class="logout-btn" style="margin-right: 50px">Logout</a>
        @Copyright 2025 - Ethan Raulin
    </footer>';
} else {
    echo '<footer id="footer">
        <a href="../Client/HomeClient.php">Home</a>
        <a href="../Client/legalMentions.php">Legal mentions</a>
        <a href="../index.php" class="logout-btn" style="margin-right: 50px">Logout</a>
        @Copyright 2025 - Ethan Raulin
    </footer>';
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
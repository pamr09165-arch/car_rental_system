<?php

require_once("../includes/session.php");

include("../includes/header.php");

include("../includes/navbar.php");

?>

<div class="d-flex">

<?php
include("../includes/sidebar.php");
?>

<div class="container-fluid p-4">

<h2>Dashboard</h2>

<hr>

<div class="alert alert-success">

ยินดีต้อนรับ

<b>

<?= $_SESSION["name"] ?>

</b>

</div>

</div>

</div>

<?php

include("../includes/footer.php");

?>
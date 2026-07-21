<?php
require_once("../includes/session.php");
require_once("../config/conn.php");

include("../includes/header.php");
include("../includes/navbar.php");
?>

<div class="d-flex">

    <?php include("../includes/sidebar.php"); ?>

    <div class="container-fluid p-4">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2>
                <i class="bi bi-people-fill"></i>
                Customer Management
            </h2>

            <a href="create.php" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i>
                เพิ่มลูกค้า
            </a>
        </div>

        <!-- เพิ่มสำเร็จ -->
        <?php if (isset($_GET["success"])) { ?>
            <div class="alert alert-success alert-dismissible fade show">
                <i class="bi bi-check-circle-fill"></i>
                เพิ่มลูกค้าเรียบร้อยแล้ว
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php } ?>

        <!-- แก้ไขสำเร็จ -->
        <?php if (isset($_GET["update"])) { ?>
            <div class="alert alert-primary alert-dismissible fade show">
                <i class="bi bi-check-circle-fill"></i>
                แก้ไขข้อมูลลูกค้าเรียบร้อยแล้ว
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php } ?>

        <!-- ลบสำเร็จ -->
        <?php if (isset($_GET["delete"])) { ?>
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="bi bi-check-circle-fill"></i>
                ลบข้อมูลลูกค้าเรียบร้อยแล้ว
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php } ?>

        <hr>

        <?php

        $sql = "SELECT * FROM customers ORDER BY created_at DESC";
        $result = mysqli_query($conn, $sql);

        ?>

        <table id="customerTable" class="table table-bordered table-hover align-middle">

            <thead class="table-dark">

                <tr>

                    <th width="100">บัตรประชาชน</th>
                    <th>ชื่อ-นามสกุล</th>
                    <th>เบอร์โทร</th>
                    <th>Email</th>
                    <th>เลขบัตรประชาชน</th>
                    <th width="150" class="text-center">Action</th>

                </tr>

            </thead>

            <tbody>

                <?php if (mysqli_num_rows($result) > 0) { ?>

                    <?php while ($row = mysqli_fetch_assoc($result)) { ?>

                        <tr>

                            <td class="text-center">

                                <?php
                                $image = "../assets/images/no-image.png";

                                if (
                                    !empty($row["id_card_image"]) &&
                                    file_exists("../uploads/customers/" . $row["id_card_image"])
                                ) {
                                    $image = "../uploads/customers/" . $row["id_card_image"];
                                }
                                ?>

                                <img
                                    src="<?= $image ?>"
                                    class="rounded border"
                                    style="width:90px;height:60px;object-fit:cover;">

                            </td>

                            <td><?= htmlspecialchars($row["full_name"]) ?></td>

                            <td><?= htmlspecialchars($row["phone"]) ?></td>

                            <td><?= htmlspecialchars($row["email"]) ?></td>

                            <td><?= htmlspecialchars($row["id_card_no"]) ?></td>

                            <td class="text-center">

                                <a href="edit.php?id=<?= $row["id"] ?>"
                                    class="btn btn-warning btn-sm">

                                    <i class="bi bi-pencil-square"></i>

                                </a>

                                <a href="#"
                                    class="btn btn-danger btn-sm deleteBtn"
                                    data-id="<?= $row["id"] ?>">

                                    <i class="bi bi-trash"></i>

                                </a>

                            </td>

                        </tr>

                    <?php } ?>

                <?php } else { ?>

                    <tr>

                        <td colspan="6" class="text-center text-muted">

                            <i class="bi bi-people"></i>

                            ยังไม่มีข้อมูลลูกค้า

                        </td>

                    </tr>

                <?php } ?>

            </tbody>

        </table>

    </div>

</div>

<?php include("../includes/footer.php"); ?>

<script>
$(document).ready(function() {

    $('#customerTable').DataTable({

        pageLength: 10,

        language: {

            search: "🔍 ค้นหา :",

            lengthMenu: "แสดง _MENU_ รายการ",

            info: "แสดง _START_ ถึง _END_ จาก _TOTAL_ รายการ",

            infoEmpty: "ไม่มีข้อมูล",

            zeroRecords: "ไม่พบข้อมูล",

            paginate: {

                previous: "ก่อนหน้า",

                next: "ถัดไป"

            }

        }

    });

});

$('.deleteBtn').click(function(e){

    e.preventDefault();

    let id = $(this).data('id');

    Swal.fire({

        title:'ลบข้อมูลลูกค้า',

        text:'คุณต้องการลบลูกค้ารายนี้ใช่หรือไม่',

        icon:'warning',

        showCancelButton:true,

        confirmButtonColor:'#dc3545',

        confirmButtonText:'ลบ',

        cancelButtonText:'ยกเลิก'

    }).then((result)=>{

        if(result.isConfirmed){

            window.location='delete.php?id='+id;

        }

    });

});
</script>
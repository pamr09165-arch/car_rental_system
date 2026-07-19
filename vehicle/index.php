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
                <i class="bi bi-car-front-fill"></i>
                Vehicle Management
            </h2>

            <a href="create.php" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i>
                เพิ่มรถ
            </a>
        </div>

        <!-- เพิ่มข้อมูลสำเร็จ -->
        <?php if (isset($_GET["success"])) { ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill"></i>
                เพิ่มข้อมูลรถเรียบร้อยแล้ว
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php } ?>

        <!-- แก้ไขข้อมูลสำเร็จ -->
        <?php if (isset($_GET["update"])) { ?>
            <div class="alert alert-primary alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill"></i>
                แก้ไขข้อมูลรถเรียบร้อยแล้ว
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php } ?>

        <!-- ลบข้อมูลสำเร็จ -->
        <?php if (isset($_GET["delete"])) { ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill"></i>
                ลบข้อมูลรถเรียบร้อยแล้ว
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php } ?>

        <hr>

        <?php

        $sql = "SELECT * FROM vehicles ORDER BY created_at DESC";
        $result = mysqli_query($conn, $sql);

        ?>

        <table id="vehicleTable" class="table table-bordered table-hover align-middle">

            <thead class="table-dark">

                <tr>

                    <th>Brand</th>
                    <th>Model</th>
                    <th>Plate</th>
                    <th class="text-end">Price / Day</th>
                    <th>Status</th>
                    <th width="150" class="text-center">Action</th>

                </tr>

            </thead>

            <tbody>

                <?php if (mysqli_num_rows($result) > 0) { ?>

                    <?php while ($row = mysqli_fetch_assoc($result)) { ?>

                        <tr>

                            <td><?= htmlspecialchars($row['brand']) ?></td>

                            <td><?= htmlspecialchars($row['model']) ?></td>

                            <td><?= htmlspecialchars($row['plate_no']) ?></td>

                            <td class="text-end">
                                <?= number_format($row['price_per_day'], 2) ?>
                            </td>

                            <td>

                                <?php

                                switch ($row['status']) {

                                    case "available":
                                        echo '<span class="badge bg-success">Available</span>';
                                        break;

                                    case "rented":
                                        echo '<span class="badge bg-danger">Rented</span>';
                                        break;

                                    case "maintenance":
                                        echo '<span class="badge bg-warning text-dark">Maintenance</span>';
                                        break;

                                    default:
                                        echo '<span class="badge bg-secondary">' . htmlspecialchars($row['status']) . '</span>';
                                        break;
                                }

                                ?>

                            </td>

                            <td class="text-center">

                                <a href="edit.php?id=<?= $row['id'] ?>"
                                   class="btn btn-warning btn-sm"
                                   title="แก้ไข">

                                    <i class="bi bi-pencil-square"></i>

                                </a>

                                <a href="#"
                                   class="btn btn-danger btn-sm deleteBtn"
                                   data-id="<?= $row['id'] ?>"
                                   title="ลบ">

                                    <i class="bi bi-trash"></i>

                                </a>

                            </td>

                        </tr>

                    <?php } ?>

                <?php } else { ?>

                    <tr>

                        <td colspan="6" class="text-center text-muted">

                            <i class="bi bi-car-front"></i>

                            ยังไม่มีข้อมูลรถ

                        </td>

                    </tr>

                <?php } ?>

            </tbody>

        </table>

    </div>

</div>

<?php include("../includes/footer.php"); ?>

<script>

$(document).ready(function(){

    $('#vehicleTable').DataTable({

        pageLength:10,

        order:[[0,"asc"]],

        language:{

            search:"🔍 ค้นหา :",

            lengthMenu:"แสดง _MENU_ รายการ",

            info:"แสดง _START_ ถึง _END_ จาก _TOTAL_ รายการ",

            infoEmpty:"ไม่มีข้อมูล",

            zeroRecords:"ไม่พบข้อมูล",

            paginate:{
                previous:"ก่อนหน้า",
                next:"ถัดไป"
            }

        }

    });

});

// SweetAlert Delete

$('.deleteBtn').click(function(e){

    e.preventDefault();

    let id = $(this).data('id');

    Swal.fire({

        title:'ลบข้อมูลรถ',

        text:'คุณต้องการลบรถคันนี้ใช่หรือไม่',

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
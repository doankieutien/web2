<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>San pham</title>
    <link href="/php-sql/assets/vendor/DataTables/datatables.css" type="text/css" rel="stylesheet"/>
    <link href="/php-sql/assets/vendor/DataTables/Buttons-1.6.3/css/buttons.dataTables.min.css" type="text/css" rel="stylesheet"/>
    <link href="/php-sql/assets/vendor/DataTables/Buttons-1.6.3/css/buttons.bootstrap4.min.css" type="text/css" rel="stylesheet"/>
</head>
<body>
<?php
     include_once(__DIR__ .'/../../layouts/styles.php');
    
    ?>
    <?php
     include_once(__DIR__ .'/../../layouts/partials/header.php');
    
    ?>
    <div class="container">
    <div class="row">
        <div class="col-md-2">
        <?php
    include_once(__DIR__ .'/../../layouts/partials/sidebar.php');
    ?>
        </div>
        <div class="col-md-8">
        
        <h1>Bang san pham</h1>
        <a class="btn btn-primary" href="create.php" role="button">Them moi san pham</a>
        <?php
        include_once(__DIR__ . '/../../../dbconnect.php');
            $sql= <<<EOT
            SELECT sp.*,
		    lsp.lsp_ten, 
		    nsx.nsx_ten,
		    km.km_ten, km.km_noidung, km.km_tungay, km.km_denngay
            FROM `sanpham` sp
            JOIN `loaisanpham` lsp ON sp.lsp_ma = lsp.lsp_ma
            JOIN `nhasanxuat` nsx ON sp.nsx_ma = nsx.nsx_ma
            LEFT JOIN `khuyenmai` km ON sp.km_ma = km.km_ma
            ORDER BY sp.sp_ma DESC
EOT;
            $result = mysqli_query($conn, $sql);
            $data = [];
        while ( $row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
            $km_ten = $row['km_ten'];
            $km_noidung = $row['km_noidung'];
            $km_tungay = date('d/m/y', strtotime($row['km_tungay']));
            $km_denngay = date('d/m/y', strtotime($row['km_denngay']));
            $km_tomtat = '';
            if(!empty($km_ten)){
                $km_tomtat = sprintf("Khuyen mai %s, noi dung %s, tu ngay %s, den ngay %s",
                $km_ten,
                $km_noidung,
                $km_tungay,
                $km_denngay
            );
            }
            
            
            $data []= array(
            'sp_ma' => $row['sp_ma'],
            'sp_ten' => $row['sp_ten'],
            'sp_gia' => $row['sp_gia'],
            'lsp_ten' => $row['lsp_ten'],
            'nsx_ten' => $row['nsx_ten'],
            'km_tomtat' => $km_tomtat


        );
    }
    ?>
        <table id= "tbdanhsach" class="table table-bordered table-hover mt-2">
                    <thead class="thead-dark">
                        <tr>
                            <th>Ma</th>
                            <th>Tên Sản phẩm</th>
                            <th>Giá</th>
                            <th>Loại sản phẩm</th>
                            <th>Nhà sản xuất</th>
                            <th>Khuyến mãi</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($data as $sp): ?>
                        <tr>
                            <td><?= $sp['sp_ma'] ?></td>
                            <td><?= $sp['sp_ten'] ?></td>
                            <td><?= $sp['sp_gia'] ?></td>
                            <td><?= $sp['lsp_ten'] ?></td>
                            <td><?= $sp['nsx_ten'] ?></td>
                            <td><?= $sp['km_tomtat'] ?></td>
                            <td>
                                <!-- Nút sửa, bấm vào sẽ hiển thị form hiệu chỉnh thông tin dựa vào khóa chính `sp_ma` -->
                                <a href="edit.php?sp_ma=<?= $sp['sp_ma'] ?>" class="btn btn-warning">
                                    Sửa
                                </a>
                                <!-- Nút xóa, bấm vào sẽ xóa thông tin dựa vào khóa chính `sp_ma` -->
                                <button class="btn btn-danger btnDelete" sp_ma="<?= $sp['sp_ma'] ?>"> Xóa</button>
                                
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <!-- End block content -->
        </div>
    </div>
    
    </div>
    
   
    <?php
    include_once(__DIR__ .'/../../layouts/partials/footer.php');
    ?>
    <?php
    include_once(__DIR__ .'/../../layouts/scripts.php');
    ?>
    <script src="/php-sql/assets/vendor/DataTables/datatables.min.js"></script>
    <script src="/php-sql/assets/vendor/DataTables/Buttons-1.6.3/js/buttons.bootstrap4.min.js"></script>
    <script src="/php-sql/assets/vendor/DataTables/pdfmake-0.1.36/pdfmake.min.js"></script>
    <script src="/php-sql/assets/vendor/DataTables/pdfmake-0.1.36/vfs_fonts.js"></script>
    <script src="/php-sql/assets/vendor/sweetalert/sweetalert.min.js"></script>
    
    <script>
        $(document).ready( function () {
        $('#tbdanhsach').DataTable({
            button: [
                'copy', 'excel', 'pdf'
            ]
        });

        $('.btnDelete').click(function({
            swal({
            title: "Ban co muon xoa?",
            text: "Neu xoa du lieu se bi mat vinh vien",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })
            .then((willDelete) => {
             if (willDelete) {
                
             }
            });
        } else {
            swal("Can than hon nhe!");
    }
        });   
    } );
    </script>
</body>
</html>
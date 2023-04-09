<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<style>
    .rekap{
        width: 100%;
    }
    .rekap, .rekap th, .rekap td {
        border: 1px solid black;
        border-collapse: collapse;
    }
   .rekap th, .rekap td {
        padding: 5px;
    }
    table#t01 tr:nth-child(even) {
        background-color: #eee;
    }
    table#t01 tr:nth-child(odd) {
       background-color:#fff;
    }
    table#t01 th {
        background-color: black;
        color: white;
    }
</style>
<body>
    <table style="margin-bottom:0.5cm;">
        <tr>
         
            <td><b>Kelas</b><td>
            <td>:</td>
            <td><?php echo e($siswa[0]['jenjang']); ?> - <?php echo e($siswa[0]['section']); ?></td>
        </tr>

    </table>
    <table class="rekap" cellpadding="8">
     
        <thead>
            <tr>
                <th>No</th>
                <th>NISN</th>
                <th>Nama Lengkap</th>
                <th>Tanggal Lahir</th>
                <th>Jenis Kelamin</th>
                <th>Nomor Telepon</th>
            </tr>
        </thead>
        
        <tbody>
            <?php
                $no = 1;
            ?>
           <?php $__currentLoopData = $siswa; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $_siswa): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
               <tr>
                    <td><?php echo e($no++); ?></td>
                    <td><?php echo e($_siswa['nisn']); ?></td>
                    <td><?php echo e($_siswa['name']); ?></td>
                    <td><?php echo e($_siswa['tanggal_lahir']); ?></td>
                    <td style="width: 5px"><?php if($_siswa['jenis_kelamin'] == 'laki-laki'): ?>
                        L
                       <?php else: ?>
                         P
                       <?php endif; ?></td>
                    <td><?php echo e($_siswa['no_telp']); ?></td>
               </tr>
           <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
</body>
</html><?php /**PATH /var/www/dev/api/current/resources/views/rekap/rekap-siswa.blade.php ENDPATH**/ ?>
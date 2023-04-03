<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<style>
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

    <table style="margin-bottom:0.5cm">
        <tr>
            <td><b>Kelas</b><td>
            <td>:</td>
            <td><?php echo e($rekap[1][0]["kelas"]["jenjang"]); ?> - <?php echo e($rekap[1][0]["kelas"]["section"]); ?></td>
        </tr>

        <tr>
            <td><b>Tanggal</b><td>
            <td>:</td>
            <td><?php echo e($date['monthName']); ?> <?php echo e($date['year']); ?></td>
        </tr>
    </table>
    <table class="rekap" cellpadding="8">
     
        <thead>
            <tr>
                <th rowspan="2">No</th>
                <th rowspan="2">Nama</th>
                <th colspan="<?php echo e(count($rekap)); ?>">
                    <?php echo e($date['monthName']); ?>

                </th>
            </tr>

            <tr>
                <?php for($i = 1; $i <= $numOfTheDay; $i++): ?>
                    <th><?php echo e($i); ?></th>
                <?php endfor; ?>
               
                
               
            </tr>
        </thead>
        
        <tbody>
            <?php
               $no=1;
            ?>
            <?php $__currentLoopData = $rekap[1]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rekap_item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <tr>
                <td align=center><?php echo e($no++); ?></td>
                <td><?php echo e($rekap_item['user']['name']); ?></td>
                <?php for($i=1; $i <= $numOfTheDay; $i++): ?>
                <?php for($i = 1; $i <= count($rekap); $i++): ?>
                <?php echo e($i); ?>

                <?php $__currentLoopData = $rekap[$i]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                  <?php if($item['user']['id'] == $rekap_item['user']['id']): ?>
                    <td align="center">
                        <?php
                       if(count($item['kehadiran']) !=0){
                        $kehadiran = $item['kehadiran'][0]['kehadiran'];
                        switch ($kehadiran) {
                            case 'hadir':
                                echo 'H';
                                break;
                            case 'izin':
                                echo 'I';
                                break;
                            case 'sakit':
                                echo 'S';
                                break;
                            case 'absen':
                                echo 'A';
                                break;
                            case 'telat':
                                echo 'T';
                                break;
                            default:
                                break;
                        }
                       }
                         
                        ?>
                    </td>
                  <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                  <?php endfor; ?>
                <?php endfor; ?>
              </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            
      
          
        </tbody>
    </table>
<p style="margin-top:0.5cm; font-weight:bold;">Keterangan</p>
    <table>
        <tr>
            <td>H<td>
            <td>:</td>
            <td>Hadir</td>
        </tr>

        <tr>
            <td>A<td>
            <td>:</td>
            <td>Alfa</td>
        </tr>


        <tr>
            <td>T<td>
            <td>:</td>
            <td>Telat</td>
        </tr>


        <tr>
            <td>I<td>
            <td>:</td>
            <td>Izin</td>
        </tr>

        <tr>
            <td>S<td>
            <td>:</td>
            <td>Sakit</td>
        </tr>
    </table>
</body>
</html><?php /**PATH /var/www/dev/api/current/resources/views/rekap/rekap-kehadiran-kelas.blade.php ENDPATH**/ ?>
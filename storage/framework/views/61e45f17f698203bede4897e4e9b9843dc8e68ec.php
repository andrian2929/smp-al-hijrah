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
            <td><?php echo e($rekap[1][0]["user"]["name"]); ?></td>
        </tr>

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
                <th rowspan="2">Tanggal</th>
                <th colspan="4">
                   Kehadiran
                </th>
            </tr>

            <tr>
                <th>Hadir</th>
                <th>Izin</th>
                <th>Sakit</th>
                <th>Alfa</th>
            </tr>
        </thead>

        <tbody>
            <?php for($i = 1; $i <= $numOfTheDay; $i++): ?>
               <tr>
                <td><?php echo e($i); ?></td>
                <td><?php echo e(\Carbon\Carbon::create($date['year'], $date['month'], $i)->format('Y-m-d')); ?></td>
                <td align="center">
                    <?php
                    $kehadiran = $rekap[$i][0]['kehadiran'];
                        if(count($kehadiran) !=0){
                           $kehadiran = $kehadiran[0]['kehadiran'];
                           if($kehadiran == 'hadir'){
                               echo 'H';
                           }
                        }
                        
                    ?>
                </td>

                <td align="center">
                    <?php
                    $kehadiran = $rekap[$i][0]['kehadiran'];
                        if(count($kehadiran) !=0){
                           $kehadiran = $kehadiran[0]['kehadiran'];
                           if($kehadiran == 'izin'){
                               echo 'I';
                           }
                        }
                        
                    ?>
                </td>

                
                <td align="center">
                    <?php
                    $kehadiran = $rekap[$i][0]['kehadiran'];
                        if(count($kehadiran) !=0){
                           $kehadiran = $kehadiran[0]['kehadiran'];
                           if($kehadiran == 'sakit'){
                               echo 'S';
                           }
                        }
                        
                    ?>
                </td>

                   
                <td align="center">
                    <?php
                    $kehadiran = $rekap[$i][0]['kehadiran'];
                        if(count($kehadiran) !=0){
                           $kehadiran = $kehadiran[0]['kehadiran'];
                           if($kehadiran == 'absen'){
                               echo 'A';
                           }
                        }
                        
                    ?>
                </td>
                
                
               </tr>
            <?php endfor; ?>

            <tr>
                <th colspan="2">Total</th>
                <td>
                    <?php
                    $hadir = 0;
                    ?>
                    <?php for($i = 1; $i <= $numOfTheDay; $i++): ?>
                        <?php
                        $kehadiran = $rekap[$i][0]['kehadiran'];
                            if(count($kehadiran) !=0){
                            $kehadiran = $kehadiran[0]['kehadiran'];
                            if($kehadiran == 'hadir'){
                                $hadir++;
                            }
                            }
                        ?>
                    <?php endfor; ?>
                    <?php echo e($hadir); ?>


                </td>
                <td>
                    <?php
                    $izin = 0;
                    ?>
                    <?php for($i = 1; $i <= $numOfTheDay; $i++): ?>
                        <?php
                        $kehadiran = $rekap[$i][0]['kehadiran'];
                            if(count($kehadiran) !=0){
                            $kehadiran = $kehadiran[0]['kehadiran'];
                            if($kehadiran == 'izin'){
                                $izin++;
                            }
                            }
                        ?>
                    <?php endfor; ?>
                    <?php echo e($izin); ?>

                </td>
                <td>
                    <?php
                    $sakit = 0;
                    ?>
                    <?php for($i = 1; $i <= $numOfTheDay; $i++): ?>
                        <?php
                        $kehadiran = $rekap[$i][0]['kehadiran'];
                            if(count($kehadiran) !=0){
                            $kehadiran = $kehadiran[0]['kehadiran'];
                            if($kehadiran == 'sakit'){
                                $sakit++;
                            }
                            }
                        ?>
                    <?php endfor; ?>
                    <?php echo e($sakit); ?>

                </td>
                <td>
                    <?php
                    $alfa = 0;
                    ?>
                    <?php for($i = 1; $i <= $numOfTheDay; $i++): ?>
                        <?php
                        $kehadiran = $rekap[$i][0]['kehadiran'];
                            if(count($kehadiran) !=0){
                            $kehadiran = $kehadiran[0]['kehadiran'];
                            if($kehadiran == 'absen'){
                                $alfa++;
                            }
                            }
                        ?>
                    <?php endfor; ?>
                    <?php echo e($alfa); ?>

                </td>
            </tr>
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
</html><?php /**PATH /var/www/dev/api/current/resources/views/rekap/rekap-kehadiran-siswa.blade.php ENDPATH**/ ?>
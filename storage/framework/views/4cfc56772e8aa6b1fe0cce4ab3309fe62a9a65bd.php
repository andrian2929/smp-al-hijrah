<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <title><?php echo e(config('app.name', 'SMP Al Hijrah')); ?></title>

    <!-- Fonts -->

    <!-- Styles -->
    <link href="<?php echo e(asset('css/app.css')); ?>" rel="stylesheet">

</head>

<body>
    <div id="app">
        <router-view></router-view>
    </div>
    <!-- Scripts -->
    <script src="<?php echo e(url(mix('js/app.js'))); ?>" defer></script>
</body>

</html>
<?php /**PATH /var/www/dev/api/current/resources/views/layout/antd.blade.php ENDPATH**/ ?>
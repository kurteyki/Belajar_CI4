<!DOCTYPE html>
<html class="h-100">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= (!empty($title) ? $title : 'No Title') ?></title>

    <?= $this->include('_layout/css') ?>
</head>
<body class="d-flex flex-column h-100">
    
    <?= $this->include('_layout/nav') ?>

    <?= $this->renderSection('content') ?>

    <?= $this->include('_layout/footer') ?>
    <?= $this->include('_layout/js') ?>
</body>
</html>
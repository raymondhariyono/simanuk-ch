<!DOCTYPE html>
<html lang="id">

<head>
   <meta charset="UTF-8">
   <title><?= esc($title ?? 'Fakultas Teknik') ?></title>
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100 flex items-center justify-center min-h-screen">

   <?= $this->renderSection('content') ?>

</body>

</html>
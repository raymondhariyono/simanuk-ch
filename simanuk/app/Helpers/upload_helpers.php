<?php

if (!function_exists('upload_file')) {
   /**
    * Helper untuk upload file dengan penamaan otomatis & random
    * * @param \CodeIgniter\HTTP\Files\UploadedFile $file Objek file
    * @param string $targetFolder Path folder tujuan (relatif terhadap FCPATH)
    * @return string|null Path file yang disimpan atau null jika gagal
    */
   function upload_file($file, $targetFolder)
   {
      if (!$file->isValid() || $file->hasMoved()) {
         return null;
      }

      // Pastikan folder tujuan ada
      $fullPath = FCPATH . $targetFolder;
      if (!is_dir($fullPath)) {
         mkdir($fullPath, 0775, true);
      }

      // Generate nama random
      $newName = $file->getRandomName();

      // Pindahkan
      $file->move($fullPath, $newName);

      // Kembalikan path relatif untuk database
      return $targetFolder . '/' . $newName;
   }
}

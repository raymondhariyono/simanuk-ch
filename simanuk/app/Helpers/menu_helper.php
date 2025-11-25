<?php

if (!function_exists('getLinkClasses')) {
   /**
    * Menentukan class CSS untuk link sidebar (Active vs Inactive).
    * Mengambil URI saat ini secara otomatis.
    */
   function getLinkClasses(string $targetPath)
   {
      // Ambil service URI dari CodeIgniter
      $uri = service('uri');

      // Ambil path saat ini (misal: 'admin/dashboard')
      $currentPath = trim($uri->getPath(), '/');
      $targetPath  = trim($targetPath, '/');

      // Logika Cek: Apakah URL saat ini diawali dengan target path?
      // Ini menangani sub-menu juga (misal: 'admin/peminjaman/detail' akan tetap mengaktifkan 'admin/peminjaman')
      $isActive = ($currentPath === $targetPath) || ($targetPath !== '' && str_starts_with($currentPath, $targetPath . '/'));

      // Base classes (selalu dipakai)
      $baseClasses = 'flex items-center space-x-3 p-3 rounded-lg font-medium transition-colors duration-200';

      // State AKTIF
      if ($isActive) {
         return $baseClasses . ' bg-blue-100 text-blue-700';
      }

      // State TIDAK AKTIF
      return $baseClasses . ' text-gray-600 hover:bg-blue-50 hover:text-blue-700';
   }
}

if (!function_exists('getIconClasses')) {
   /**
    * Menentukan warna Icon (Active vs Inactive).
    */
   function getIconClasses(string $targetPath)
   {
      $uri = service('uri');
      $currentPath = trim($uri->getPath(), '/');
      $targetPath  = trim($targetPath, '/');

      $isActive = ($currentPath === $targetPath) || ($targetPath !== '' && str_starts_with($currentPath, $targetPath . '/'));

      // Return warna icon
      return $isActive ? 'text-blue-700' : 'text-gray-500';
   }
}

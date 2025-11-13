<?php

/**
 * Helper function kecil untuk menghasilkan kelas-kelas CSS
 * berdasarkan apakah link tersebut aktif atau tidak.
 */
function getLinkClasses($path)
{
   global $currentPath;
   $targetPath = rtrim(preg_replace('#^' . preg_quote(site_url(), '#') . '#', '', site_url($path)), '/');

   // Cek jika path saat ini sama persis atau merupakan "anak" dari path target
   // Pengecualian untuk '/' agar tidak selalu aktif
   $isActive = ($targetPath === $currentPath) || ($targetPath !== '' && str_starts_with($currentPath, $targetPath));

   $baseClasses = 'flex items-center space-x-3 p-3 rounded-lg font-medium';

   if ($isActive) {
      return $baseClasses . ' bg-blue-100 text-blue-700';
   }

   return $baseClasses . ' text-gray-600 hover:bg-blue-50 hover:text-blue-700';
}

/**
 * Helper function untuk kelas ikon
 */
function getIconClasses($path)
{
   global $currentPath;
   $targetPath = rtrim(preg_replace('#^' . preg_quote(site_url(), '#') . '#', '', site_url($path)), '/');
   $isActive = ($targetPath === $currentPath) || ($targetPath !== '' && str_starts_with($currentPath, $targetPath));

   return $isActive ? 'text-blue-700' : 'text-gray-500';
}

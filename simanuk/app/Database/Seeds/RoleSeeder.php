<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class RoleSeeder extends Seeder
{
   public function run()
   {
      $db = \Config\Database::connect();

      $roles = [
         ['nama_role' => 'admin'],
         ['nama_role' => 'tu'],
         ['nama_role' => 'peminjam'],
         ['nama_role' => 'pemimpin'],
      ];

      foreach ($roles as $role) {
         $exists = $db->table('roles')
            ->where('nama_role', $role['nama_role'])
            ->get()
            ->getRow();

         if (! $exists) {
            $db->table('roles')->insert([
               'nama_role'        => $role['nama_role'],
               'created_at'  => date('Y-m-d H:i:s'),
               'updated_at'  => date('Y-m-d H:i:s'),
            ]);
            echo "Role '{$role['nama_role']}' berhasil dibuat\n";
         } else {
            echo "Role '{$role['nama_role']}' sudah ada\n";
         }
      }
   }
}

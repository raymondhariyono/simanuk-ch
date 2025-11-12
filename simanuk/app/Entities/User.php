<?php

namespace App\Entities;

use CodeIgniter\Shield\Entities\User as ShieldUser;
use App\Models\RoleModel; // Model untuk tabel roles kustom

class User extends ShieldUser
{
   protected ?string $roleName = null;

   protected $casts = [
      'id'             => 'int',
      'id_role'        => 'int', // Kolom kustom 
      'active'         => 'boolean',
      'created_at'     => 'datetime',
      'updated_at'     => 'datetime',
      'deleted_at'     => 'datetime',
      'nama_lengkap'   => 'string', // Kolom kustom 
      'organisasi'     => 'string', // Kolom kustom 
      'kontak'         => 'string', // Kolom kustom 
   ];

   protected $attributes = [
      'username'     => null,
      'nama_lengkap' => null,
      'email'        => null,
      'no_hp'        => null,
      'id_role'      => null,
      'status'       => null,
      'active'       => true,
   ];

   protected $dates = ['created_at', 'updated_at', 'deleted_at'];

   public function hasRole(string $role): bool
   {
      // return $this->getRoleName() === $role;
      return strtolower($this->getRoleName()) === strtolower($role);
   }

   public function getRoleName(): string
   {
      // 1. Jika sudah ada di cache, langsung kembalikan
      if ($this->roleNameCache !== null) {
         return $this->roleNameCache;
      }

      // 2. Jika user ini (dari tabel 'users') tidak punya id_role
      if (empty($this->attributes['id_role'])) {
         $this->roleNameCache = ''; // Set cache ke kosong
         return '';
      }

      // 3. Ambil dari database (HANYA SEKALI)
      $roleModel = model(RoleModel::class);
      $role = $roleModel->find($this->attributes['id_role']);

      if ($role) {
         // 4. Simpan ke cache dan kembalikan
         $this->roleNameCache = $role['nama_role'];
         return $this->roleNameCache;
      }

      // Jika id_role ada tapi tidak ditemukan di tabel 'roles'
      $this->roleNameCache = '';
      return '';
   }
}

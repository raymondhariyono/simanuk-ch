<?php
   // app/Models/ExtendedUserModel.php

   namespace App\Models;

   use CodeIgniter\Shield\Models\UserModel;
   use App\Entities\User;

   class ExtendedUserModel extends UserModel
   {
      protected $primaryKey = 'id';
      protected $returnType = User::class; // entity kustom

      protected $allowedFields = [
         'username',
         'status',
         'status_message',
         'active',
         'last_active',
         'deleted_at',
         // field kustom ERD
         'id_role',
         'nama_lengkap',
         'organisasi',
         'kontak',
      ];

      protected $useTimestamps = true;
      protected $useSoftDeletes = true;

      public function save($data): bool
      {
         // cek apakah ini operasi UPDATE (data memiliki ID)
         $id = null;

         if (is_object($data) && isset($data->id)) {
            $id = $data->id;
         } elseif (is_array($data) && isset($data[0]->id)) {
            $id = $data[0]->id;
         }

         // jika operasi UPDATE; gunakan logika dari shield
         // shield akan secara otomatis mendeteksi perubahan password
         if ($id) {
            return parent::save($data);
         }

         // jika CREATE, jalankan logikanya
         // Jika $data adalah array atau Entity
         $attributes = is_array($data) ? $data : $data->toRawArray();

         // Extract data untuk Shield's identities
         $email = $attributes['email'] ?? null;
         $password = $attributes['password'] ?? null;
         $username = $attributes['username'] ?? null;

         // Pastikan username ada
         if (empty($username) && !empty($email)) {
            $username = explode('@', $email)[0];
            $attributes['username'] = $username;
         }

         // Hapus email dan password dari attributes users table
         unset($attributes['email'], $attributes['password']);

         // Set active default
         if (!isset($attributes['active'])) {
            $attributes['active'] = 1;
         }

         // 1. Insert ke users table (parent Shield)
         $userId = $this->insert($attributes, true); // true = returnID

         if (!$userId) {
            return false;
         }

         // 2. GUNAKAN SHIELD's addIdentity() untuk simpan email & password
         $user = $this->find($userId);

         if ($email && $password) {
            // Cara Shield yang benar
            $user->email = $email; // Shield akan handle ini

            // Buat identity email_password
            $emailIdentity = $user->getEmailIdentity();
            if ($emailIdentity === null) {
               $user->createEmailIdentity([
                  'email'    => $email,
                  'password' => $password,
               ]);
            }
         }

         // 3. Tambahkan ke group Shield berdasarkan id_role
         if (!empty($attributes['id_role'])) {
            $roleModel = model(\App\Models\RoleModel::class);
            $role = $roleModel->find($attributes['id_role']);

            if ($role) {
               $groupName = strtolower($role['nama_role']);
               $user->addGroup($groupName); // Gunakan method Shield
            }
         }

         return true;
      }

      public function getAllUserWithRole()
      {
         return $this->select('users.*, roles.nama_role')
            ->join('roles', 'roles.id_role = users.id_role', 'left');
      }

      public function getUserWithRole($userId)
      {
         return $this->select('users.*, roles.nama_role')
            ->join('roles', 'roles.id_role = users.id_role', 'left')
            ->where('users.id', $userId)
            ->first();
      }

      public function updatePassword($userId, $password)
      {
         $user = $this->find($userId);
      }
   }

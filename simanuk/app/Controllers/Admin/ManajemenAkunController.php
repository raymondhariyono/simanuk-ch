<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ExtendedUserModel;
use App\Models\RoleModel;

class ManajemenAkunController extends BaseController
{
   protected $userModel;
   protected $roleModel;

   public function __construct()
   {
      $this->userModel = model(ExtendedUserModel::class);
      $this->roleModel = new RoleModel();
   }

   /**
    * R: Menampilkan Daftar Akun (Index)
    */
   public function index()
   {
      $users = $this->userModel
         ->select('users.*, roles.nama_role')
         ->join('roles', 'roles.id_role = users.id_role')
         ->findAll();

      $data = [
         'title' => 'Manajemen Akun Pengguna',
         'users' => $users,
         'showSidebar' => true,
      ];

      return view('admin/manajemen_akun_view', $data);
   }

   /**
    * C: Menampilkan Form Tambah Akun
    */
   public function new()
   {
      $data = [
         'title' => 'Tambah Akun Baru',
         'roles' => $this->roleModel->findAll(), // Untuk dropdown role
         'breadcrumbs' => [
            ['name' => 'Manajemen Akun', 'url' => site_url('admin/manajemen-akun')],
            ['name' => 'Tambah Baru']
         ]
      ];
      return view('admin/akun/create_view', $data);
   }

   /**
    * C: Memproses Penyimpanan Akun Baru (CREATE)
    */
   public function create()
   {
      // 1. Validasi Input
      if (!$this->validate([
         'username' => 'required|alpha_dash|min_length[3]|is_unique[users.username]',
         'email' => 'required|valid_email|is_unique[auth_identities.secret]', // Cek unique identity
         'password' => 'required|min_length[8]',
         'nama_lengkap' => 'required',
         'kontak' => 'required',
         'id_role' => 'required',
      ])) {
         return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
      }

      $data = $this->request->getPost();

      // 2. Insert ke User Model (Logika Role & Identity ada di ExtendedUserModel::save())
      // Karena tidak ada ID, ExtendedUserModel::save() akan menjalankan CREATE
      $this->userModel->save($data);

      return redirect()->to(site_url('admin/manajemen-akun'))->with('message', 'Akun berhasil ditambahkan.');
   }

   /**
    * U: Menampilkan Form Edit Akun
    */
   public function edit($id)
   {
      $user = $this->userModel->find($id);
      if (!$user) return redirect()->back()->with('error', 'User tidak ditemukan.');

      $data = [
         'title' => 'Edit Akun',
         'user' => $user,
         'roles' => $this->roleModel->findAll(),
         'breadcrumbs' => [['name' => 'Manajemen Akun', 'url' => site_url('admin/manajemen-akun')],
         ['name' => 'Edit Akun']]
      ];

      return view('admin/akun/edit_view', $data);
   }

   /**
    * U: Memproses Update Akun
    */
   public function update($id)
   {
      $user = $this->userModel->find($id);
      if (!$user) return redirect()->back()->with('error', 'User tidak ditemukan.');

      // 1. Validasi Input (Abaikan unique check untuk user saat ini)
      if (!$this->validate([
         'username' => "required|alpha_dash|min_length[3]|is_unique[users.username,id,{$id}]",
         'email' => "required|valid_email|is_unique[auth_identities.secret,user_id,{$id}]",
         'password' => 'permit_empty|min_length[8]',
         'kontak' => "required|is_unique[users.kontak,id,{$id}]",
         'id_role' => 'required',
      ])) {
         return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
      }

      $data = $this->request->getPost();

      // 2. Update Data Diri (tabel users)
      $user->fill($data); // Mengisi data dari POST ke Entity User
      $this->userModel->save($user); // Menyimpan data users

      // 3. Update Role (Jika berubah)
      $oldRoleName = strtolower($this->roleModel->find($user->id_role)['nama_role']);
      $newRoleName = strtolower($this->roleModel->find($data['id_role'])['nama_role']);

      if ($oldRoleName !== $newRoleName) {
         $user->removeGroup($oldRoleName); // Hapus role lama
         $user->addGroup($newRoleName);    // Tambah role baru
      }

      // 4. Update Password (Shield otomatis menangani hashing)
      if (!empty($data['password'])) {
         $user->password = $data['password'];
         $this->userModel->save($user);
      }

      // Update Email: Shield otomatis handle email jika kita pakai ExtendedUserModel yang benar

      return redirect()->to(site_url('admin/manajemen-akun'))->with('message', 'Akun berhasil diperbarui.');
   }

   /**
    * D: Menghapus Akun
    */
   public function delete($id)
   {
      // Best Practice: Self-protection
      if ($id == auth()->user()->id) {
         return redirect()->back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
      }

      // Shield otomatis menghapus data di tabel auth_identities dan auth_groups (CASCADE)
      $this->userModel->delete($id);

      return redirect()->to(site_url('admin/manajemen-akun'))->with('message', 'Akun berhasil dihapus.');
   }
}

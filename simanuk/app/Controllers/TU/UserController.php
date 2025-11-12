<?php

namespace App\Controllers\TU;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\RoleModel; // Model untuk tabel roles kustom
use App\Entities\User; // Entitas kustom Anda

class UserController extends BaseController
{
   /** @var \App\Models\ExtendedUserModel */
   protected $userModel;
   protected $roleModel;

   public function __construct()
   {
      // Mendapatkan provider auth (ExtendedUserModel)
      $this->userModel = auth()->getProvider();
      $this->roleModel = new RoleModel();
   }

   // [Bagian READ] Menampilkan daftar semua pengguna
   public function index()
   {
      $users = $this->userModel->select('users.*, roles.nama_role')
         ->join('roles', 'roles.id_role = users.id_role')
         ->orderBy('users.id_role', 'ASC')
         ->findAll();

      $data = [
         'title' => 'Kelola Akun Pengguna (TU)',
         'users' => $users,
      ];

      return view('tu/user_management/index', $data);
   }

   // [Bagian CREATE] Menampilkan form tambah pengguna
   public function create()
   {
      $data = [
         'title' => 'Tambah Akun Baru',
         'roles' => $this->roleModel->findAll(),
      ];
      return view('tu/user_management/create', $data);
   }

   // [Bagian CREATE] Memproses penyimpanan pengguna baru
   public function save(): RedirectResponse
   {
      $rules = [
         'email'          => 'required|valid_email|is_unique[users.email]',
         'username'       => 'required|alpha_dash|min_length[3]|is_unique[users.username]',
         'nama_lengkap'   => 'required|min_length[3]',
         'id_role'        => 'required|is_natural_no_zero|is_not_unique[roles.id_role]',
         'password'       => 'required|min_length[8]',
         'pass_confirm'   => 'required|matches[password]',
      ];

      if (!$this->validate($rules)) {
         return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
      }

      // 1. Buat Entitas User Kustom
      $user = new User($this->request->getPost());

      // 2. Set status aktif dan isikan kontak/organisasi
      $user->active = 1; // Langsung aktif karena dibuat TU
      $user->organisasi = $this->request->getPost('organisasi');
      $user->kontak = $this->request->getPost('kontak');

      // 3. Simpan data user ke database (tabel users dan auth_identities)
      $this->userModel->save($user);
      // 3. Dapatkan nama grup dari id_role
      $role = $this->roleModel->find($user->id_role);
      if (!$role) {
         return redirect()->back()->withInput()->with('error', 'Role yang dipilih tidak valid.');
      }
      $groupName = $role->nama_role;

      // 4. Simpan user dan langsung tambahkan ke grup Shield
      $this->userModel->withGroup($groupName)->save($user);

      return redirect()->to(site_url('tu/kelola/akun'))->with('message', 'Akun pengguna berhasil dibuat.');
   }

   // [Bagian DELETE] Menonaktifkan (Soft Delete) Akun
   public function delete(int $id): RedirectResponse
   {
      if ($id === auth()->user()->id) {
         return redirect()->back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
      }

      // Shield secara default mendukung soft delete, yang kita gunakan di ExtendedUserModel
      $this->userModel->delete($id);

      return redirect()->to(site_url('tu/kelola/akun'))->with('message', 'Akun pengguna berhasil dinonaktifkan.');
   }

   // TODO: Implementasikan fungsi edit() dan update() untuk melengkapi CRUD
   // Fungsi ini akan mirip dengan save(), tetapi menggunakan $this->userModel->update($id, $data)
}

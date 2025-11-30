<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ExtendedUserModel;

class ProfileController extends BaseController
{
   protected $userModel;

   public function __construct()
   {
      // Inisialisasi model secara langsung untuk mendapatkan instance yang lengkap
      $this->userModel = auth()->getProvider();
   }

   public function index()
   {
      $user = auth()->user();

      $data = [
         'title' => 'Manajemen Akun Pengguna',
         'showSidebar' => true, // Flag untuk menyembunyikan sidebar
         'user' => $user,
         'breadcrumbs' => [
            [
               'name' => 'Beranda',
               'url' => site_url('admin/dashboard')
            ],
            [
               'name' => 'Profil Pengguna',
            ]
         ]
      ];

      return view('profile/profile_view', $data);
   }

   public function update()
   {
      $user = auth()->user();
      $input = $this->request->getPost();

      // Validasi Input
      $rules = [
         'username' => [
            'rules' => "required|alpha_dash|min_length[3]|is_unique[users.username,id,{$user->id}]",
            'errors' => [
               'required'   => 'Username wajib diisi.',
               'alpha_dash' => 'Username hanya boleh berisi karakter alfanumerik, underscore, dan tanda hubung.',
               'min_length' => 'Username minimal 3 karakter.',
               'is_unique'  => 'Username ini sudah digunakan oleh pengguna lain.',
            ]
         ],
         'nama_lengkap' => [
            'rules' => "required|min_length[3]|is_unique[users.nama_lengkap,id,{$user->id}]",
            'errors' => [
               'required'   => 'Nama lengkap wajib diisi.',
               'min_length' => 'Nama lengkap minimal 3 karakter.',
               'is_unique'  => 'Nama lengkap ini sudah terdaftar.',
            ]
         ],
         'organisasi' => [
            'rules' => "required",
            'errors' => [
               'required' => 'Organisasi wajib diisi.',
            ]
         ],
         'kontak' => [
            'rules' => "required|numeric|min_length[10]",
            'errors' => [
               'required'   => 'Nomor kontak wajib diisi.',
               'numeric'    => 'Nomor kontak harus berupa angka.',
               'min_length' => 'Nomor kontak tidak valid.',
            ]
         ],
      ];

      if (!$this->validate($rules)) {
         return redirect()->back()->withInput()->with('error', $this->validator->getErrors());
      }

      // Hapus data yang tidak perlu di-update secara langsung dari input
      unset($input['email']);

      if ($this->userModel->update($user->id, $input)) {
         return redirect()->to('profile')->with('msg', 'Profil berhasil diperbarui.');
      }

      return redirect()->back()->withInput()->with('error', 'Gagal memperbarui profil.');
   }

   public function changePassword()
   {
      $user = auth()->user();

      // Validasi Input Password
      $rules = [
         'password_lama'       => 'required',
         'password_baru'       => 'required|min_length[8]',
         'konfirmasi_password' => 'required|matches[password_baru]',
      ];

      if (!$this->validate($rules)) {
         return redirect()->back()->withInput()->with('error', $this->validator->getErrors());
      }

      $oldPassword = $this->request->getPost('password_lama');
      $newPassword = $this->request->getPost('password_baru');

      // 1. Verifikasi Password Lama
      // Ambil identity 'email_password' user untuk mendapatkan hash password saat ini
      $identity = $user->getEmailIdentity();

      if (!$identity) {
         return redirect()->back()->with('error', 'Akun ini tidak menggunakan login password.');
      }

      // Verifikasi hash password lama
      $authenticator = service('passwords');
      if (! $authenticator->verify($oldPassword, $identity->secret2)) {
         return redirect()->back()->withInput()->with('error', 'Kata sandi lama yang Anda masukkan salah.');
      }

      // 3. Simpan Password Baru
      // Cukup set atribut pada entity, Shield yang mengurus sisanya via Model
      $user->password = $newPassword;

      // Panggil save(). Karena kita sudah memperbaiki Model di Langkah 1, 
      // ini akan lari ke parent::save() yang menghandle update dengan benar.
      if ($this->userModel->save($user)) {
         return redirect()->to('profile')->with('msg', 'Kata sandi berhasil diubah.');
      }
   }
}

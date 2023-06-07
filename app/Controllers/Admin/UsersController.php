<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\DiagnosaModel;
use App\Models\PenggunaModel;

class UsersController extends BaseController
{
   protected $PenggunaModel;
   protected $DiagnosaModel;

   public function __construct()
   {
      $this->PenggunaModel = new PenggunaModel();
      $this->DiagnosaModel = new DiagnosaModel();
      $this->db = \Config\Database::connect();
   }

   public function index()
   {
      $query = $this->db->table('users')
               ->select('users.*, kecamatan.kecamatan')
               ->join('kecamatan', 'users.kecamatan_id = kecamatan.id')
               ->where('users.kecamatan_id !=', '0')
               ->orderBy('users.id', 'desc')
               ->get()->getResultArray();
      $data = [
         'datapengguna' => $query,
         'notif' => $this->DiagnosaModel->notification()
      ];
      return view('/server-side/data-pengguna', $data);
   }
}

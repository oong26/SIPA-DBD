<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\DiagnosaModel;
use App\Models\GejalaModel;
use App\Models\PengetahuanModel;
use App\Models\PenggunaModel;
use App\Models\PenyakitModel;
use App\Models\SolusiModel;

class DashboardController extends BaseController
{
   protected $PenggunaModel;
   protected $PengetahuanModel;
   protected $GejalaModel;
   protected $PenyakitModel;
   protected $SolusiModel;
   protected $DiagnosaModel;

   public function __construct()
   {
      $this->PenggunaModel = new PenggunaModel();
      $this->PengetahuanModel = new PengetahuanModel();
      $this->GejalaModel = new GejalaModel();
      $this->PenyakitModel = new PenyakitModel();
      $this->SolusiModel = new SolusiModel();
      $this->DiagnosaModel = new DiagnosaModel();
      $this->db = \Config\Database::connect();
      $this->kecamatan = $this->db->table('kecamatan');
   }

   public function index()
   {
      $kecamatan = $this->db->table('kecamatan')->orderBy('id', 'desc')->get()->getResult();
      foreach($kecamatan as $value) {
         $query = $this->db->table('diagnosa')
                           ->select('diagnosa.*, users.kecamatan_id, kecamatan.*')
                           ->join('users', 'diagnosa.id_user = users.id')
                           ->join('kecamatan', 'users.kecamatan_id = kecamatan.id')
                           ->where('kecamatan.id', $value->id)
                           ->get()->getResult();
         if(count($query) != 0) {
            $nama_kecamatan[] = $value->kecamatan;
            $jumlah[] = count($query);
         }
      }
      // var_dump($var);die;
      $data = [
         'kecamatan' => $nama_kecamatan,
         'jumlah' => $jumlah,
         'totalpengguna' => $this->PenggunaModel->countAllResults(),
         'totalpengetahuan' => $this->PengetahuanModel->countAllResults(),
         'totalgejala' => $this->GejalaModel->countAllResults(),
         'totalpenyakit' => $this->PenyakitModel->countAllResults(),
         'totalsolusi' => $this->SolusiModel->countAllResults(),
         'totaldiagnosa' => $this->DiagnosaModel->countAllResults(),
         'notif' => $this->DiagnosaModel->notification()
      ];
      return view('/server-side/dashboard', $data);
   }

   public function rules() {
      $data = [ 'notif' => $this->DiagnosaModel->notification() ];
      return view('/server-side/rules-base', $data);
   }

   public function kecamatan_index()
   {
      $kecamatan = $this->db->table('kecamatan')->orderBy('id', 'desc')->get()->getResult();
      $data = [
         'kecamatan' => $kecamatan,
         'notif' => $this->DiagnosaModel->notification()
      ];
      return view('server-side/kecamatan', $data);
   }

   public function kecamatan_insert()
   {
      $this->kecamatan->insert([
         'kecamatan' => $this->request->getPost('kecamatan')
      ]);

      return redirect()->to('/data-kecamatan');
   }

   public function kecamatan_update($id)
   {
      $kecamatan = $this->request->getPost('kecamatan');
      $this->kecamatan->where('id', $id);
      $this->kecamatan->update([
         'kecamatan' => $kecamatan
      ]);
      return redirect()->to('/data-kecamatan');
   }

   public function kecamatan_delete($id)
   {
      $this->kecamatan->where('id', $id);
      $this->kecamatan->delete();
      return redirect()->to('/data-kecamatan');
   }
}

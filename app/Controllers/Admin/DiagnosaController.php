<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\DiagnosaModel;
use App\Models\PenyakitModel;
use App\Models\SolusiModel;

class DiagnosaController extends BaseController
{
   protected $DiagnosaModel;
   protected $PenyakitModel;
   protected $SolusiModel;
   protected $helpers = ["form"];

   public function __construct()
   {
      $this->DiagnosaModel = new DiagnosaModel();
      $this->PenyakitModel = new PenyakitModel();
      $this->SolusiModel = new SolusiModel();
   }

   public function index()
   {
      $data = [
         "datadiagnosa" => $this->DiagnosaModel->dataDiagnosa(),
         "notif" => $this->DiagnosaModel->notification(),
      ];
      return view("/server-side/diagnosa/index", $data);
   }

   public function diagnosa()
   {
      try {
         $numbtable = 1;
         $getKode = $this->request->getVar("kode_gejala");
         $getGejala = $this->request->getVar("nama_gejala");
         $getKeyakinan = $this->request->getVar("nilai_keyakinan");
         // return json_encode($getKode);
         // return json_encode($getGejala);
         // return json_encode($getKeyakinan);
         $nilaiUser = [];

         $allPenyakit = $this->DiagnosaModel->getPenyakit();
         // return json_encode($getKode);
         $kodePenyakitArr = [];
         $finalResultArr = [];
         foreach ($allPenyakit as $key => $value) {
            $selectedNilaiCF = [];
            $selectedKode = [];
            $selectedKeyakinan = [];
            for ($i=0; $i < count($getKode); $i++) { 
               $data = $this->DiagnosaModel->getBasisPengetahuanByGejala($getKode[$i]);
               if ($data != null) {
                  // return json_encode($data);
                  // return 'ada';
                  foreach ($data as $key => $item) {
                     if ($value->kode_penyakit == $item->kode_penyakit) {
                        array_push($selectedKode, $item->kode_gejala);
                        array_push($selectedNilaiCF, $item->cf_pakar);
                        array_push($selectedKeyakinan, $getKeyakinan[$i]);
                     }
                  }
               }
               else {
                  // return 'tidak ada';
               }
            }
            $value->selected_kode_gejala = $selectedKode;
            $value->selected_cf_pakar = $selectedNilaiCF;
            $value->selected_nilai_keyakinan = $selectedKeyakinan;
            $arrResultPerGejala = [];
            for ($i=0; $i < count($selectedKeyakinan); $i++) { 
               $resultPerGejala = $selectedKeyakinan[$i] * $selectedNilaiCF[$i];
               array_push($arrResultPerGejala, $resultPerGejala);
            }
            $value->result_per_gejala = $arrResultPerGejala;
            $result = round(array_sum($arrResultPerGejala), 2);
            $value->result_per_penyakit = $result;
            array_push($kodePenyakitArr, $value->kode_penyakit);

            // perhitungan CF
            $hasil = 0;
            if (count($arrResultPerGejala) > 0) {
               $hasil = $arrResultPerGejala[0];
               for ($i=0; $i < count($arrResultPerGejala); $i++) { 
                  if ($i > 0) {
                     $hasil = $hasil + $arrResultPerGejala[$i] * (1 - $hasil);
                  }
               }
               $hasil = round($hasil * 100, 0);
            }
            array_push($finalResultArr, $hasil);
            $value->final_result = $hasil;
         }
         $maxKey = current(array_keys($finalResultArr, max($finalResultArr)));
         $selectedKodePenyakit = $allPenyakit[$maxKey]->kode_penyakit;
         $selectedResultPenyakitPercent = $allPenyakit[$maxKey]->final_result;
         $penyakit = $this->PenyakitModel->getPenyakitByKode($selectedKodePenyakit);
         $solusi = $this->SolusiModel->getSolusiByKode($selectedKodePenyakit);
         $imgPath = base_url().'assets/images/penyakit-seeder/'.$penyakit['gambar'];
         $resultView = "
               <div class='col-md-9' style='text-align: justify;'>
               <p class='lead'>Berdasarkan gejala dan nilai keyakinan yang telah Anda sebutkan. Hasil diagnosa menyatakan gejala tersebut memiliki kemungkinan persentase <strong style='font-size: larger;'>$selectedResultPenyakitPercent%</strong>, bahwa penyakit yang sedang diderita adalah <strong style='font-size: larger;'>$penyakit[nama_penyakit]</strong>.</p>

               <h3 class='mt-5'>Solusi Pengobatan</h3><strong class='lead'>$solusi[detail_solusi]</strong>
               </div>
               <div class='col-md-3'>
               <img src='$imgPath' alt='Gambar Hasil Diagnosa' width='100%'>
               </div>
               <div class='col-md-12' hidden>
               <input type='text' value='$selectedKodePenyakit' name='kode_penyakit'>
               <input type='text' value='$selectedResultPenyakitPercent' name='cf_hasil'>
               </div>
            ";

         $data = [
            "autocode" => $this->DiagnosaModel->autoCodeDiagnosa(),
            "datapenyakit" => $this->PenyakitModel->datapenyakit(),
            "numbtable" => $numbtable,
            "getKode" => $getKode,
            "getGejala" => $getGejala,
            "getKeyakinan" => $getKeyakinan,
            "nilaiUser" => $nilaiUser,
            "hasil" => $selectedResultPenyakitPercent,
            "result_view" => $resultView,
         ];

         return view("/client-side/diagnosa2", $data);
      } catch (\Exception $e) {
         // return 'error';
         return $e->getMessage();
      }
   }

   function cf(array $nilaiPakar, array $nilaiUser, array $gejala)
   {
      $hasilDiagnosa = "";
      $persentaseHasil = "";
      $nilaiUserD01 = [];
      $nilaiUserD02 = [];
      $nilaiUserD03 = [];

      $gejalaD01 = [];
      $gejalaD02 = [];
      $gejalaD03 = [];

      $basis = [];

      $nilaiPakarD01 = [];
      $nilaiPakarD02 = [];
      $nilaiPakarD03 = [];

      $nilaiCFD01 = [];
      $nilaiCFD02 = [];
      $nilaiCFD03 = [];

      $hasilD01 = 0;
      $hasilD02 = 0;
      $hasilD03 = 0;
      $panjangGejala = count($gejala);
      return json_encode($nilaiPakar);
      for ($i = 0; $i < $gejala; $i++) {
         if (
            $gejala[$i] == "G01" ||
            $gejala[$i] == "G02" ||
            $gejala[$i] == "G03" ||
            $gejala[$i] == "G04" ||
            $gejala[$i] == "G05" ||
            $gejala[$i] == "G06" ||
            $gejala[$i] == "G07"
         ) {
            $gejalaD01[] = $gejala[$i];
            $nilaiPakarD01[] = $nilaiPakar[$i];
            $nilaiUserD01[] = $nilaiUser[$i];
         }
         if (
            $gejala[$i] == "G08" ||
            $gejala[$i] == "G09" ||
            $gejala[$i] == "G10" ||
            $gejala[$i] == "G11" ||
            $gejala[$i] == "G05" ||
            $gejala[$i] == "G06" ||
            $gejala[$i] == "G12" ||
            $gejala[$i] == "G13" ||
            $gejala[$i] == "G14" ||
            $gejala[$i] == "G15" ||
            $gejala[$i] == "G04"
         ) {
            $gejalaD02[] = $gejala[$i];
            $nilaiPakarD02[] = $nilaiPakar[$i];
            $nilaiUserD02[] = $nilaiUser[$i];
         }
         if (
            $gejala[$i] == "G16" ||
            $gejala[$i] == "G17" ||
            $gejala[$i] == "G18" ||
            $gejala[$i] == "G19" ||
            $gejala[$i] == "G20" ||
            $gejala[$i] == "G21" ||
            $gejala[$i] == "G22" ||
            $gejala[$i] == "G23"
         ) {
            $gejalaD03[] = $gejala[$i];
            $nilaiPakarD03[] = $nilaiPakar[$i];
            $nilaiUserD03[] = $nilaiUser[$i];
         }
      }

      // $panjangGejalaD01 = count($gejalaD01);
      // for ($a = 0; $a < $panjangGejalaD01; $a++) {
      //    $nilaiCFD01[] = $nilaiPakarD01[$a] * $nilaiUserD01[$a];
      // }

      // $panjangGejalaD02 = count($gejalaD02);
      // for ($b = 0; $b < $panjangGejalaD02; $b++) {
      //    $nilaiCFD02[] = $nilaiPakarD02[$b] * $nilaiUserD02[$b];
      // }
      // $panjangGejalaD03 = count($gejalaD03);
      // for ($c = 0; $c < $panjangGejalaD03; $c++) {
      //    $nilaiCFD03[] = $nilaiPakarD03[$c] * $nilaiUserD03[$c];
      //       // echo $nilaiCFD03[$b]. " ";
      // }

      // $panjangGejalaD01 = count($gejalaD01);
      // if ($panjangGejalaD01 > 0) {
      //       $hasilD01 = $nilaiCFD01[0];
      //       for ($a = 1; $a < $panjangGejalaD01; $a++) {
      //          if ($panjangGejalaD01 > 1) {
      //               $hasilD01 = $hasilD01 + $nilaiCFD01[$a] * (1 - $hasilD01);
      //          }
      //       }
      // }
      // $panjangGejalaD02 = count($gejalaD02);
      // if ($panjangGejalaD02 > 0) {
      //       $hasilD02 = $nilaiCFD02[0];
      //       for ($a = 1; $a < $panjangGejalaD02; $a++) {
      //          if ($panjangGejalaD02 > 1) {
      //               $hasilD02 = $hasilD02 + $nilaiCFD02[$a] * (1 - $hasilD02);
      //          }
      //       }
      // }
      // $panjangGejalaD03 = count($gejalaD03);
      // if ($panjangGejalaD03 > 0) {
      //       $hasilD03 = $nilaiCFD03[0];
      //       for ($a = 1; $a < $panjangGejalaD03; $a++) {
      //          if ($panjangGejalaD03 > 1) {
      //               $hasilD03 = $hasilD03 + $nilaiCFD03[$a] * (1 - $hasilD03);
      //          }
      //       }
      // }
      // if ($hasilD01 > $hasilD02) {
      //       if ($hasilD01 > $hasilD03) {
      //          $hasilDiagnosa = "D01";
      //          $persentaseHasil = $hasilD01;
      //       } else {
      //          $hasilDiagnosa = "D03";
      //          $persentaseHasil = $hasilD03;
      //       }
      // } elseif ($hasilD02 > $hasilD03) {
      //       $hasilDiagnosa = "D02";
      //       $persentaseHasil = $hasilD02;
      // } else {
      //       $hasilDiagnosa = "D03";
      //       $persentaseHasil = $hasilD03;
      // }
      // $akurasi = $persentaseHasil * 100;
      // $output = number_format($akurasi, 2, ".", "");
      // $conn = mysqli_connect("localhost", "root", "", "sistem_pakar");
      // $penyakit = mysqli_query(
      //       $conn,
      //       "SELECT nama_penyakit, gambar FROM penyakit WHERE kode_penyakit = '$hasilDiagnosa'"
      // );
      // $dataPenyakit = mysqli_fetch_array($penyakit);

      // $solusi = mysqli_query(
      //       $conn,
      //       "SELECT detail_solusi FROM solusi WHERE kode_penyakit = '$hasilDiagnosa'"
      // );
      // $diagnosa = mysqli_fetch_array($solusi);

      // echo "<div class='col-md-9' style='text-align: justify;'>
      //                   <p class='lead'>Berdasarkan gejala dan nilai keyakinan yang telah Anda sebutkan. Hasil diagnosa menyatakan gejala tersebut memiliki kemungkinan persentase <strong style='font-size: larger;'>$output%</strong>, bahwa penyakit yang sedang diderita adalah <strong style='font-size: larger;'>$dataPenyakit[nama_penyakit]</strong>.</p>

      //                   <h3 class='mt-5'>Solusi Pengobatan</h3><strong class='lead'>$diagnosa[detail_solusi]</strong>
      //                </div>
      //                <div class='col-md-3'>
      //                   <img src='assets/images/penyakit-seeder/$dataPenyakit[gambar]' alt='Gambar Hasil Diagnosa' width='100%'>
      //                </div>
      //                <div class='col-md-12' hidden>
      //                   <input type='text' value='$hasilDiagnosa' name='kode_penyakit'>
      //                   <input type='text' value='$output' name='cf_hasil'>
      //                </div>
      //             ";
   }

   public function insert()
   {
      $this->DiagnosaModel->save([
            "kode_diagnosa" => $this->request->getVar("kode_diagnosa"),
            "id_user" => $this->request->getVar("id_user"),
            "tanggal_diagnosa" => $this->request->getVar("tanggal_diagnosa"),
            "gejala" => $this->request->getVar("gejala"),
            "kode_penyakit" => $this->request->getVar("kode_penyakit"),
            "cf_hasil" => $this->request->getVar("cf_hasil"),
      ]);

      return redirect()->to("/data-diagnosa-user");
   }
}

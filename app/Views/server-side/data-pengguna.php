<?= $this->extend('/layouts/main-layout') ?>

<?= $this->section('content') ?>
<div class="page-content">
   <div class="container-fluid">

      <!-- start page title -->
      <div class="row">
         <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
               <h4 class="mb-0">Data Pengguna</h4>

               <div class="page-title-right">
                  <ol class="breadcrumb m-0">
                     <li class="breadcrumb-item active">
                        <span class="uil-home-alt me-2"></span>Dashboard &nbsp; / &nbsp; Data Pengguna
                     </li>
                  </ol>
               </div>

            </div>
         </div>
      </div>
      <!-- end page title -->

      <div class="row">
         <div class="col-12">
            <div class="card">
               <div class="card-body">
                  <table id="datatable" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                     <thead>
                        <tr>
                           <th>#No</th>
                           <th>Email</th>
                           <th>Username</th>
                           <th>Alamat</th>
                        </tr>
                     </thead>
                     <tbody>
                        <?php $number = 1; foreach($datapengguna as $calldata) : ?>
                        <tr>
                           <td><?= $number++; ?></td>
                           <td><?= $calldata['email']; ?></td>
                           <td><?= $calldata['username']; ?></td>
                           <td><?= $calldata['kecamatan']; ?></td>
                        </tr>
                        <?php endforeach ?>
                     </tbody>
                  </table>
               </div>
            </div>
         </div> <!-- end col -->
      </div> <!-- end row -->

   </div>
</div>
<?= $this->endSection() ?>
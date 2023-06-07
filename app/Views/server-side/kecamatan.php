<?= $this->extend('/layouts/main-layout') ?>

<?= $this->section('content') ?>
<div class="page-content">
   <div class="container-fluid">

      <!-- start page title -->
      <div class="row">
         <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
               <h4 class="mb-0">Data Kecamatan</h4>

               <div class="page-title-right">
                  <ol class="breadcrumb m-0">
                     <li class="breadcrumb-item active">
                        <span class="uil-home-alt me-2"></span>Dashboard &nbsp; / &nbsp; Kecamatan &nbsp; / &nbsp; Data Kecamatan
                     </li>
                  </ol>
               </div>

            </div>
         </div>
      </div>
      <!-- end page title -->
      <a href="#tambah" data-bs-toggle="modal" class="btn btn-primary waves-effect waves-light me-1 mb-3">
         Tambah kecamatan
      </a>
      <div class="row">
         <div class="col-12">
            <div class="card">
               <div class="card-body">
                  <table id="datatable" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                     <thead>
                        <tr>
                           <th>#No</th>
                           <th>Kecamatan</th>
                           <th>action</th>
                        </tr>
                     </thead>
                     <tbody>
                        <?php $number = 1;
                        foreach ($kecamatan as $value) :
                        ?>
                           <tr>
                              <td><?= $number++ ?></td>
                              <td><?= $value->kecamatan ?></td>
                              <td>
                              <a href="#edit<?= $value->id ?>" data-bs-toggle="modal" class="btn btn-info btn-sm" title="Edit data ini">
                                    <span class="uil-edit"></span>
                                 </a>
                                 <form action="/data-kecamatan/<?= $value->id ?>" method="post" class="d-inline">
                                    <?= csrf_field(); ?>
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus data ini?')"><span class="uil-trash"></span></button>
                                 </form>
                              </td>
                           </tr>
                        <?php
                        endforeach;
                        ?>
                     </tbody>
                  </table>
               </div>
            </div>
         </div> <!-- end col -->
      </div> <!-- end row -->

   </div>
</div>
<div class="modal fade" id="tambah" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
   <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Form Tambah Kecamatan</h5>
            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
               <span aria-hidden="true">&times;</span>
            </button>
         </div>
         <div class="modal-body">
            <form class="custom-validation" action="/data-kecamatan/insert" method="post">
               <?= csrf_field() ?>
               <div class="mb-3">
                  <label for="kecamatan" class="form-label">Kecamatan</label></label>
                  <input type="text" class="form-control bg-light" id="kecamatan" name="kecamatan" required/>
               </div>
               <div>
                     <button type="submit" class="btn btn-primary waves-effect waves-light me-1">
                        Simpan
                     </button>
               </div>
            </form>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
         </div>
      </div>
   </div>
</div>

<?php foreach($kecamatan as $edit) { ?>
   <div class="modal fade" id="edit<?= $edit->id ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
         <div class="modal-content">
            <div class="modal-header">
               <h5 class="modal-title" id="exampleModalLabel">Form Edit Kecamatan</h5>
               <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
               </button>
            </div>
            <div class="modal-body">
               <form class="custom-validation" action="/data-kecamatan/update/<?= $edit->id ?>" method="post">
                  <?= csrf_field() ?>
                  <div class="mb-3">
                     <label for="kecamatan" class="form-label">Kecamatan</label></label>
                     <input type="hidden" name="id" value="<?= $edit->id ?>">
                     <input type="text" class="form-control bg-light" id="kecamatan" value="<?= $edit->kecamatan ?>" name="kecamatan" required/>
                  </div>
                  <div>
                        <button type="submit" class="btn btn-primary waves-effect waves-light me-1">
                           Simpan
                        </button>
                  </div>
               </form>
            </div>
            <div class="modal-footer">
               <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
            </div>
         </div>
      </div>
   </div>
<?php } ?>
<?= $this->endSection() ?>
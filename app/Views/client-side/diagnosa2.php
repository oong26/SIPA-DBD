<?= $this->extend('/layouts-user/main-layouts') ?>

<?= $this->section('content') ?>
<!-- Content -->
<div class="container">
  <div class="row">
    <div class="col-md-12 mt-5">
      <form action="/insert-diagnosa" method="post">
        <?= csrf_field() ?>
        <div class="card text-center mb-5 border-0 mt-5">
          <div class="card-header p-3 bg-transparent">
            <h1 class="text-center fw-bold">Gejala yang Di Derita</h1>
          </div>
          <div class="card-body">
            <table class="table text-center">
              <thead>
                <tr class="fw-bold">
                  <td>No</td>
                  <td>Nama Gejala</td>
                  <td>Nilai Keyakinan</td>
                </tr>
              </thead>

              <tbody>
                <?php
                $conn = mysqli_connect('localhost', 'root', '', 'sistem_pakar');
                
                
                // var_dump(count($getKode) <= 1);die;
                if(count($getKode) <= 1) {
                  header("Refresh:0; url=/diagnosa");
                }
                for ($i = 0; $i < count($getKode); $i++) {
                ?>
                  <tr>
                    <td><?= $numbtable++ ?></td>

                    <td><?= $getGejala[$i] ?></td>
                    <td><?= $getKeyakinan[$i] ?></td>
                  </tr>

                <?php
                }

                ?>
              </tbody>
            </table>
            <div id="send-data" hidden>
              <input type="text" value="<?= $autocode ?>" name="kode_diagnosa">
              <input type="text" value="<?= user()->id ?>" name="id_user">
              <input type="text" value="<?= date('Y-m-d') ?>" name="tanggal_diagnosa">
              <textarea cols="100" rows="4" name="gejala"><?php for ($index = 0; $index < count($getGejala); $index++) { echo "<li>$getGejala[$index]</li>"; } ?></textarea>
            </div>
          </div>
          <div class="card-footer p-3 border-0 bg-transparent">
            <div class="alert alert-danger" role="alert">
              <h2 class="mb-3" align="left">Hasil Diagnosa!</h2>
              <div class="row">
                <!-- hitung cf -->
                <?= $result_view ?>
              </div>
            </div>
            <div class="float-end">
              <a href="/diagnosa" class="btn btn-primary">Diagnosa Ulang</a>
              <button type="submit" class="btn btn-success">Simpan Hasil Diagnosa</button>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>


<div class="container">
  <div class="row">
    <div class="col-md-12"></div>
  </div>
</div>




<div class="footer fst-italic p-2">
  <p class="text-center mt-2"> 2023 © Novita Sari. Website Sistem Pakar DBD(Demam Berdarah Dengue).</p>
</div>
<!-- End Content -->

<?= $this->endSection() ?>
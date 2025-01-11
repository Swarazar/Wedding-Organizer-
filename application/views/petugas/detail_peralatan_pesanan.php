<div class="col-lg-12 main-chart">
  <div class="border-head">
    <h3>DATA PERALATAN</h3>
  </div>
  <div class="row">

    <div class="col-lg-12">
      <div class="form-panel">
        <div id="dftrans">
          <table class="table table-bordered table-striped" id="dtabel">
            <thead style="background-color:white;">
              <tr>
                <th>No</th>
                <th>Nama Peralatan</th>
                <th>Jumlah Yang dibutuhkan</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $no = 1;
              foreach ($peralatan as $r) {

                ?>
                <tr>
                  <td>
                    <?php echo $no++; ?>
                  </td>
                  <td>
                    <?php echo $r->nama_peralatan; ?>
                  </td>
                  <td>
                    <?php echo $r->stok_dibutuhkan . " " . $r->unit ?>
                  </td>
                </tr>
              <?php } ?>

            </tbody>
          </table>
        </div>
      </div><!-- /form-panel -->
    </div><!-- /col-lg-12 -->
  </div><!-- /row -->

</div>
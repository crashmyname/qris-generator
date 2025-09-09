
    <div class="container">
        <hr>
        <center>
            <div class="card">
                <div class="card-header">
                <h4>QRIS Decoder</h4>
                </div>
                <?php if($data):?>
                <p class="text-muted"><?= $data?></p>
                <?php endif;?>
                <form action="<?= url('decoder')?>" method="post" enctype="multipart/form-data">
                    <?= csrf()?>
                    <div class="card-body">
                        <div class="form-group row">
                        <label for="inputImage" class="col-sm-3 col-form-label">Upload File Qris</label>
                        <input type="file" class="form-control" name="image" id="image">
                    </div>
                    <div class="card-footer">       
                        <button type="submit" class="btn btn-primary" id="submit">Generate</button>
                    </div>
                </form>
            </div>
        </center>
    </div>
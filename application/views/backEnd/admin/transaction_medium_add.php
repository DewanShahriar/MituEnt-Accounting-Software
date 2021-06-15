<section class="content">
    <div class="row">
        <div class="col-md-12">
            <!-- Horizontal Form -->
            <div class="box box-purple box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title"><?php echo $this->lang->line('transaction_medium_add'); ?>  </h3>
                    <div class="box-tools pull-right">
                        <a href="<?php echo base_url() ?>admin/transaction-medium/list" type="submit" class="btn bg-primary btn-sm" style="color: white;"> <i class="fa fa-list"></i> <?php echo $this->lang->line('transaction_medium_list'); ?>  </a>
                    </div>
                </div>
                <div class="box-body">
                    <br>
                    <div class="row">
                        <form action="<?php echo base_url("admin/transaction_medium/add");?>" method="post" enctype="multipart/form-data" class="form-horizontal">

                            <div class="col-md-9">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <label><?php echo $this->lang->line('transaction_medium_name'); ?> </label>
                                            <input name="name" placeholder="<?php echo $this->lang->line('transaction_medium_name'); ?> " class="form-control inner_shadow_purple"  type="text" required autocomplete="off">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <label><?php echo $this->lang->line('transaction_medium_number'); ?> </label>
                                            <input name="number" placeholder="<?php echo $this->lang->line('transaction_medium_number'); ?> " class="form-control inner_shadow_purple"  type="text" required autocomplete="off">
                                        </div>
                                    </div>
                                </div>
                                
        
                                 
                                
                            </div>

                            <div class="col-md-3">
                                <!-- Profile Image -->
                                <div class="box box-purple box-solid">
                                    <div class="box-header"> <label><?php echo $this->lang->line('transaction_medium_icon'); ?></label> </div>
                                    <div class="box-body box-profile">
                                        <center>
                                            <img id="transaction_medium_picture_change" class="img-responsive" src="//placehold.it/400x400" alt="profile picture" style="max-width: 120px;"><small style="color: gray">width : 400px, Height : 400px</small>
                                            <br>
                                            <input type="file" name="photo" onchange="readpicture(this);">
                                        </center>
                                    </div>
                                    <!-- /.box-body -->
                                </div>
                                <!-- /.box -->
                            </div>

                            <div class="col-md-12">
                                <center>
                                    <button type="reset" class="btn btn-sm btn-danger"><?php echo $this->lang->line('reset'); ?></button>
                                    <button type="submit" class="btn btn-sm bg-purple"><?php echo $this->lang->line('save'); ?></button>
                                </center>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- /.box -->
        </div>
        <!--/.col (right) -->
    </div>
</section>
<script>
    // profile picture change
    function readpicture(input) {
      if (input.files && input.files[0]) {
          var reader = new FileReader();
    
          reader.onload = function (e) {
            $('#transaction_medium_picture_change')
            .attr('src', e.target.result)
            .width(100)
            .height(100);
        };
    
        reader.readAsDataURL(input.files[0]);
    }
    }
    
</script>

<script>
    $(function(){
    
        $('.date').datepicker({
            autoclose: true,
            changeYear:true,
            changeMonth:true,
            dateFormat: "dd-mm-yy",
            yearRange: "-10:+10"
        });

        $('.timepicker').timepicker({
            showInputs: false
        });

    });
</script>


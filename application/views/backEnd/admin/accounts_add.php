<section class="content">
    <div class="row">
        <div class="col-md-12">
            <!-- Horizontal Form -->
            <div class="box box-primary box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title"> <?php echo $this->lang->line('account_add'); ?> </h3>
                    <div class="box-tools pull-right">
                        <a href="<?php echo base_url() ?>admin/accounts/list" type="submit" class="btn bg-green btn-sm" style="color: white;"> <i class="fa fa-list"></i> <?php echo $this->lang->line('account_list'); ?> </a>
                    </div>
                </div>
                <div class="box-body">
                    <div class="row">
                        <form action="<?php echo base_url('admin/accounts/add') ?>" method="post" enctype="multipart/form-data" class="form-horizontal">
                            <div class="col-md-12">
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <label> <?php echo $this->lang->line('date'); ?> </label>
                                            <input name="date" id="date" class="form-control inner_shadow_purple" placeholder="<?php echo $this->lang->line('date'); ?>" required="" type="text" autocomplete="off" onkeypress="return false;" value="<?= date('d-m-Y'); ?>">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <label> <?php echo $this->lang->line('account_type'); ?> </label>
                                            <select name="accounts_type" id="accounts_type"  class="form-control select2" style="width:100%" onchange="load_account_head(this.value)">
                                                <option value=""><?php echo $this->lang->line('select_type'); ?></option>
                                                <option value="1"><?php echo $this->lang->line('income'); ?></option>
                                                <option value="0"><?php echo $this->lang->line('cash_out'); ?></option>
                                                
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <div class="col-md-12">
                                        <table class="table table-bordered table-striped table_th_primary" style="width: 100%;">
                                            <thead>
                                                <tr>
                                                    <th style="width: 5%;"><?php echo $this->lang->line('sl'); ?></th>
                                                    
                                                    <th style="width: 40%;"><?php echo $this->lang->line('account_head'); ?></th>
                                                    <th style="width: 30%;"><?php echo $this->lang->line('description'); ?></th>
                                                    
                                                    <th style="width: 15%;"><?php echo $this->lang->line('amount'); ?></th>
                                                    
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <input type="hidden" name="showrowid" id="showrowid" value="10">
                                                <?php
                                                    // 61 is the max limit, change to javascript also from botom of the code.
                                                    
                                                    for ($i=1; $i < 61 ; $i++) { ?>
                                                <tr id="trid<?= $i; ?>" style="<?php if($i > 10) echo 'display: none'; ?>">
                                                    <td><?php echo $i; ?></td>
                                                    
                                                    <td>
                                                        <select name="account_head_id[]" id="account_head_id<?= $i; ?>" class="form-control select2" style="width:100%">
                                                            <option value=""><?php echo $this->lang->line('select_account_head'); ?></option>
                                                            
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <textarea name="description[]" id="" class="form-control inner_shadow_purple" cols="" rows="1"></textarea> 
                                                    </td>
                                                    
                                                    <td>
                                                        <input type="number" class="form-control inner_shadow_purple numberconvert" name="amount[]" value="0" min="0" id="amount<?= $i; ?>" placeholder="<?php echo $this->lang->line('amount'); ?>" onkeyup="amountshow(<?= $i; ?>)">
                                                    </td>
                                                    
                                                    
                                                </tr>
                                                <?php } ?>
                                                <tr>
                                                    <td colspan="3" style="text-align: right; font-size: 18px; font-weight: bold;"><?php echo $this->lang->line('total') ?>: </td>
                                                    <td>
                                                        <input type="text" readonly id="total_amount_id" style="border: none;">
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <br><br>
                            <div class="col-sm-12">
                                <center>
                                    <a href="<?php echo current_url(); ?>" class="btn bg-purple"><?php echo $this->lang->line('reset') ?></a>
                                    <button type="submit" class="btn btn-success"><?php echo $this->lang->line('save') ?></button>
                                    <a class="btn btn-info" onclick="makerowvisible();"><i class="fa fa-plus"></i> </a>
                                </center>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- /.box-body -->
                <div class=" box-footer">
                </div>
                <!-- /.box-footer --> 
            </div>
            <!-- /.box -->
        </div>
        <!--/.col (right) -->
    </div>
</section>
<script type="text/javascript">
    $(function () {
    
      $("#userListTable").DataTable();
    
    });
    
    
    
</script>
<script>
    $(function () {
    
        $('#date').datepicker({
    
            autoclose: true,
    
            changeYear:true,
    
            changeMonth:true,
    
            dateFormat: "dd-mm-yy",
    
            yearRange: "-100:+5"
    
        });
    
    });
    
    
    
</script>

<script>

    function amountshow(id) {
    
        
        var total_amount = 0;
    
        // same as php for loop from up.
    
        for(var i = 1; i < 61; i++){
    
            var tempamount = $('#amount'+i).val(); 
            total_amount+= Number(tempamount);
        }
    
        $('#total_amount_id').val(total_amount);
    }
    
    function makerowvisible(){
        
        var nextrownumber = $("#showrowid").val();
        $("#trid"+Number(nextrownumber)).show();
        $("#showrowid").val(Number(nextrownumber)+1);
    }
</script>


<script type="text/javascript">
    function load_account_head(type) {
    
    
        $.post("<?php echo base_url() . "admin/get_account_head/"; ?>" + type,
    
            {'nothing': 'nothing'},
    
            function (data2) {
    
                var data = JSON.parse(data2);
                console.log(data);
                for(var key = 1; key<61; key++){
                    $("#account_head_id"+key).find('option').remove().end();
        
                    $.each(data, function (i, item) {
        
                            $("#account_head_id"+key).append($('<option>', {
        
                                    value: this.id,
        
                                    text: this.name,
        
                            }));
        
                    });
                }
    
            });
    
    }
</script>

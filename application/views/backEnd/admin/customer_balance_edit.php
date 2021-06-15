<section class="content">
    <div class="row">
        <div class="col-md-12">
            <!-- Horizontal Form -->
            <div class="box box-primary box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title"><?php echo $this->lang->line('customer_balance_edit'); ?>  </h3>
                    <div class="box-tools pull-right">
                        <a href="<?php echo base_url() ?>admin/customer-balance/list" type="submit" class="btn bg-purple btn-sm" style="color: white;"> <i class="fa fa-list"></i> <?php echo $this->lang->line('customer_balance_list'); ?>  </a>
                    </div>
                </div>
                <div class="box-body">
                    <br>
                    <div class="row">
                        <form action="<?php echo base_url("admin/customer-balance/edit/".$edit_info->id);?>" method="post" enctype="multipart/form-data" class="form-horizontal">

                            <div class="col-md-12">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <label> <?php echo $this->lang->line('transaction_date'); ?> </label>
                                            <input name="transaction_date" id="date" class="form-control inner_shadow_primary date" placeholder="<?php echo $this->lang->line('transaction_date'); ?>" required="" type="text" autocomplete="off" value="<?= $edit_info->transaction_date ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <label><?php echo $this->lang->line('customer_name'); ?> </label>
                                            <select name="customer_id"  class="form-control select2" style="width:100%">
                                                <option value=""><?php echo $this->lang->line('select_customer'); ?></option>
                                                <?php foreach($customer_list as $list){?>
                                                    <option value="<?php echo $list->id; ?>" <?php if($edit_info->customer_id == $list->id) echo "selected";?>><?php echo $list->name; ?></option>
                                                <?php }?>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <label><?php echo $this->lang->line('account_type'); ?> </label>
                                            <select name="accounts_type"  class="form-control select2" style="width:100%">
                                                <option value=""><?php echo $this->lang->line('select_type'); ?></option>
                                                <option value="1" <?php if($edit_info->accounts_type == 1) echo "selected";?>><?php echo $this->lang->line('income'); ?></option>
                                                <option value="0" <?php if($edit_info->accounts_type == 0) echo "selected";?>><?php echo $this->lang->line('expense'); ?></option>
                                                
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <label><?php echo $this->lang->line('amount'); ?> </label>
                                            <input name="amount" placeholder="<?php echo $this->lang->line('amount'); ?> " class="form-control inner_shadow_primary"  type="amount" required autocomplete="off" value="<?= $edit_info->amount; ?>" >
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <label><?php echo $this->lang->line('receiver'); ?> </label>
                                            <input name="receiver" placeholder="<?php echo $this->lang->line('receiver'); ?> " class="form-control inner_shadow_primary"  type="text" required autocomplete="off" value="<?= $edit_info->receiver ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <label><?php echo $this->lang->line('transaction_medium'); ?> </label>
                                            <select name="transaction_medium_id"  class="form-control select2" style="width:100%">
                                                <option value=""><?php echo $this->lang->line('select_transaction_medium'); ?></option>
                                                <?php foreach($transaction_medium_list as $list){?>
                                                    <option value="<?php echo $list->id; ?>" <?php if($edit_info->transaction_medium_id == $list->id) echo "selected";?>><?php echo $list->name; ?></option>
                                                <?php }?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <label><?php echo $this->lang->line('description'); ?> </label>
                                            <textarea name="description" class="form-control inner_shadow_primary" ><?= $edit_info->description ?></textarea>
                                        </div>
                                    </div>
                                </div>
 
                            </div>

                            <div class="col-md-12">
                                <center>
                                    <button type="reset" class="btn btn-sm btn-danger"><?php echo $this->lang->line('reset'); ?></button>
                                    <button type="submit" class="btn btn-sm bg-purple"><?php echo $this->lang->line('update'); ?></button>
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


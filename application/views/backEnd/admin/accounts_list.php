<section class="content">
    <div class="row">
        <div class="col-md-12">
            <!-- Horizontal Form -->
            <div class="box box-primary box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title"> <?php echo $this->lang->line('account_list'); ?> </h3>
                    <div class="box-tools pull-right">
                        
                        <a href="<?php echo base_url() ?>admin/accounts/add" type="submit" class="btn bg-green btn-sm" style="color: white;"> <i class="fa fa-plus"></i> <?php echo $this->lang->line('account_add'); ?> </a>
                    </div>
                </div>

                <div class="row" style="box-shadow: 0px 0px 10px 0px #605ca8; margin: 8px 53px 20px 55px; padding:20px 4px 20px 4px;">
                   <form action="<?php echo base_url('admin/accounts/list') ?>" method="post" enctype="multipart/form-data" class="form-horizontal">
                        <div class="col-md-12">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <label><?php echo $this->lang->line('start_date'); ?></label>
                                        <input name="start_date" placeholder="<?php echo $this->lang->line('start_date'); ?> " class="form-control inner_shadow_primary date"  type="text" autocomplete="off" value="<?= $search['start_date']; ?>">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <label><?php echo $this->lang->line('end_date'); ?></label>
                                        <input name="end_date" placeholder="<?php echo $this->lang->line('end_date'); ?> " class="form-control inner_shadow_primary date"  type="text" autocomplete="off" value="<?= $search['end_date']; ?>">
                                    </div>
                                </div>
                            </div>

                            
                            
                        </div>
                        <div class="col-md-12">
                            <br>
                         <center>
                            <button type="submit" class="btn btn-sm btn bg-purple"><?php echo $this->lang->line('search'); ?></button>
                         </center>
                        </div>
                   </form>
                </div>

                <div class="box-body custom_table_box">
                    
                    <div class="row">
                        <div class="col-sm-12">
                            <table id="userListTable" class="table table-bordered table-striped table_th_primary custom_table">
                                <thead>
                                    <tr>
                                        <th style="width: 5%"><?php echo $this->lang->line('sl'); ?></th>
                                        
                                        <th style="width: 10%"><?php echo $this->lang->line('date'); ?></th>
                                        <th style="width: 30%"><?php echo $this->lang->line('account_head'); ?></th>
                                        <th style="width: 25%"><?php echo $this->lang->line('description'); ?></th>
                                        
                                        <th style="width: 10%"><?php echo $this->lang->line('amount'); ?></th>
                                        <th style="width: 10%"><?php echo $this->lang->line('action'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $sl = 1;
                                        foreach ($accounts_list as $key => $value) {
                                            ?>
                                    <tr>
                                        <td> <?php echo $sl++; ?> </td>
                                        
                                        <td> <?= date('d M Y',strtotime($value->date)); ?> </td>
                                        <td> <?= $value->hname; ?></td>
                                        <td> <?= $value->description; ?> </td>
                                       
                                       
                                        <td> <?=  number_format((float)$value->amount, 2, '.', ''); ?> </td>
                                        <td><?php if($value->status == 1) {?>
                                            <a href="<?= base_url('admin/accounts/edit/'.$value->id); ?>" class="btn btn-sm bg-green"> <i class="fa fa-edit"></i> </a>
                                            <a href="<?= base_url('admin/accounts/delete/'.$value->id); ?>" onclick="return confirm('Are you sure?')" class="btn btn-sm bg-orange"> <i class="fa fa-trash"></i> </a>
                                        <?php }?>
                                        </td>
                                    </tr>
                                    <?php
                                        }
                                        ?>

                                </tbody>
                            </table>
                            <?php echo $links; ?>
                            
                            
                        </div>
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
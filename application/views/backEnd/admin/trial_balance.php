<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-info box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title"><?php echo $this->lang->line('trial_balance'); ?></h3>
                    <div class="box-tools pull-right">
                        
                    </div>
                </div>
                <div class="row" style="box-shadow: 0px 0px 10px 0px #00c0ef; margin: 8px 53px 20px 55px; padding:20px 4px 20px 4px;">
                   <form action="<?php echo base_url('admin/trial-balance') ?>" method="post" enctype="multipart/form-data" class="form-horizontal">
                        <div class="col-md-12">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <label><?php echo $this->lang->line('start_date'); ?></label>
                                        <input name="start_date" placeholder="<?php echo $this->lang->line('start_date'); ?> " class="form-control inner_shadow_primary date"  type="text" autocomplete="off" value="<?= $search['start_date']; ?>">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-5">
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <label><?php echo $this->lang->line('end_date'); ?></label>
                                        <input name="end_date" placeholder="<?php echo $this->lang->line('end_date'); ?> " class="form-control inner_shadow_primary date"  type="text" autocomplete="off" value="<?= $search['end_date']; ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <label> <br> </label>
                                        <button type="submit" class="form-control btn bg-purple"> <?php echo $this->lang->line('go'); ?> </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <label> <br> </label>
                                        <?php if($print){?>
                                        <a href="<?php echo base_url('admin/trial-balance-print?start_date='.$search['start_date'].'&end_date='.$search['end_date']) ?>" target="_blank" class="btn bg-green btn-sm"><i class="fa fa-print"></i> <?php echo $this->lang->line('print'); ?></a>
                                    <?php }?>
                                    </div>
                                </div>
                            </div>
                            
                            
                        </div>
                        
                   </form>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <?php if($print){?>
                    <table id="userListTable" class="table table-bordered table-striped table_th_info">
                        <thead>
                            <tr>
                                <th style="width: 5%;"><?php echo $this->lang->line('sl'); ?></th>
                                <th style="width: 50%;"><?php echo $this->lang->line('account_head'); ?></th>
                                <th style="width: 15%;"><?php echo $this->lang->line('debit'); ?></th>
                                <th style="width: 15%;"><?php echo $this->lang->line('credit'); ?></th>
                                <th style="width: 15%;"><?php echo $this->lang->line('balance'); ?></th>
                                
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $total_cash_in = 0;
                            $total_cash_out = 0;
                            foreach($accounts_head_list as $key=>$value){
                                $total_cash_in += $value->cash_in;
                                $total_cash_out += $value->cash_out;?>
                                <tr>
                                    <td> <?= ++$key;?></td>
                                    <td> <?= $value->name;?></td>
                                    <td style="text-align: right; padding-right: 20px"> <?= number_format($value->cash_in, '2');?></td>
                                    <td style="text-align: right; padding-right: 20px"> <?= number_format($value->cash_out, '2');?></td>
                                    <td style="text-align: right; padding-right: 20px"> <?= number_format($value->cash_in - $value->cash_out, '2');?></td>
                                </tr>
                            <?php }?>
                            <tr>
                                <td colspan="2" style="text-align: right; padding-right: 20px;font-size: 16px; font-weight: bold;"><?php echo $this->lang->line('total')?></td>
                                <td style="text-align: right; padding-right: 20px;font-size: 16px; font-weight: bold;"><?php echo number_format($total_cash_in, '2');?></td>
                                <td style="text-align: right; padding-right: 20px;font-size: 16px; font-weight: bold;"><?php echo number_format($total_cash_out, '2');?></td>
                                <td style="text-align: right; padding-right: 20px;font-size: 16px; font-weight: bold;"><?php echo number_format($total_cash_in - $total_cash_out, '2');?></td>
                               
                            </tr>
                            
                        </tbody>
                    </table>
                    <?php }?>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col -->
    </div>
</section>

<script>
    $(function () {
    
        $('.date').datepicker({
    
            autoclose: true,
    
            changeYear:true,
    
            changeMonth:true,
    
            dateFormat: "dd-mm-yy",
    
            yearRange: "-100:+5"
    
        });
    
    });
    
    
    
</script>





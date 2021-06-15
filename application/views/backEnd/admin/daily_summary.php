<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-info box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title"><?php echo $this->lang->line('daily_summary'); ?></h3>
                    <div class="box-tools pull-right">
                        <a href="<?php echo base_url('admin/daily-summary-print') ?>" target="_blank" class="btn bg-purple btn-sm"><i class="fa fa-print"></i> <?php echo $this->lang->line('print'); ?></a>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <table id="userListTable" class="table table-bordered table-striped table_th_info">
                        <thead>
                            <tr>
                                <th style="width: 5%;"><?php echo $this->lang->line('sl'); ?></th>
                                <th style="width: 80%;"><?php echo $this->lang->line('account_head'); ?></th>
                                <th style="width: 15%;"><?php echo $this->lang->line('rest_amount'); ?></th>
                                
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $total = 0;
                            foreach($accounts_head_list as $key=>$value){
                                $total += $value->rest_amount;?>
                                <tr>
                                    <td> <?= ++$key;?></td>
                                    <td> <?= $value->name;?></td>
                                    <td style="text-align: right; padding-right: 20px"> <?= number_format($value->rest_amount, '2');?></td>
                                </tr>
                            <?php }?>
                            <tr>
                                <td colspan="2" style="text-align: right; padding-right: 20px;font-size: 16px; font-weight: bold;"><?php echo $this->lang->line('total')?></td>
                                <td style="text-align: right; padding-right: 20px;font-size: 16px; font-weight: bold;"><?php echo number_format($total, '2');?></td>
                               
                            </tr>
                            
                        </tbody>
                    </table>
                    
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col -->
    </div>
</section>



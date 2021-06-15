<section class="content">
    <div class="row">
        <div class="col-md-12">
            <!-- Horizontal Form -->
            <div class="box box-primary box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title"> <?php echo $this->lang->line('customer_balance_report'); ?> </h3>
                    <div class="box-tools pull-right">
                        
                        <a href="<?php echo base_url() ?>admin/customer-balance/list" type="submit" class="btn bg-green btn-sm" style="color: white;"> <i class="fa fa-list"></i> <?php echo $this->lang->line('customer_balance_list'); ?> </a>
                    </div>
                </div>

                <div class="row" style="box-shadow: 0px 0px 10px 0px #605ca8; margin: 8px 53px 20px 55px; padding:20px 4px 20px 4px;">
                   <form action="<?php echo base_url('admin/customer-balance/report') ?>" method="post" enctype="multipart/form-data" class="form-horizontal">
                        <div class="col-md-12">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <label><?php echo $this->lang->line('customer_name'); ?> </label>
                                        <select name="customer_id"  class="form-control select2" style="width:100%" required="">
                                            <option value=""><?php echo $this->lang->line('select_customer'); ?></option>
                                            <?php foreach($customer_list as $list){?>
                                                <option value="<?php echo $list->id; ?>" <?php if($list->id == $search['customer_id']) echo 'selected';?>><?php echo $list->name; ?></option>
                                            <?php }?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <label><?php echo $this->lang->line('account_type'); ?> </label>
                                        <select name="accounts_type"  class="form-control select2" style="width:100%">
                                            <option value="9"><?php echo $this->lang->line('select_type'); ?></option>
                                            <option value="1" <?php if($search['accounts_type'] == 1) echo 'selected';?>><?php echo $this->lang->line('income'); ?></option>
                                            <option value="0" <?php if($search['accounts_type'] == 0) echo 'selected';?>><?php echo $this->lang->line('cash_out'); ?></option>
                                            
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <label><?php echo $this->lang->line('start_date'); ?></label>
                                        <input name="start_date" placeholder="<?php echo $this->lang->line('start_date'); ?> " class="form-control inner_shadow_primary date"  type="text" autocomplete="off" value="<?= $search['start_date']; ?>">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
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
                            <?php if($print){?>
                                <a href="<?php echo base_url('admin/customer-balance-report-print?customer_id='.$search['customer_id'].'&accounts_type='.$search['accounts_type'].'&start_date='.$search['start_date'].'&end_date='.$search['end_date']) ?>" target='_blank' class="btn btn-sm btn bg-green"><i class="fa fa-print"></i> <?php echo $this->lang->line('print'); ?></a>
                            <?php }?>
                         </center>
                        </div>
                   </form>
                </div>
                
                <div class="box-body">
                    
                    <div class="row">
                        <div class="col-sm-12">
                            <?php if($print){?>
                            <table id="userListTable" class="table table-bordered table-striped table_th_primary">
                                <thead>
                                    <tr>
                                        <th style="width: 5%;"><?php echo $this->lang->line('sl'); ?></th>
                                        <th style="width: 10%;"><?php echo $this->lang->line('transaction_date'); ?></th>
                                        
                                        
                                        <th style="width: 20%;"><?php echo $this->lang->line('description'); ?></th>
                                        
                                        <th style="width: 10%;"><?php echo $this->lang->line('medium'); ?></th>
                                        <th style="width: 10%;"><?php echo $this->lang->line('receiver'); ?></th>
                                        <th style="width: 10%;"><?php echo $this->lang->line('cash_in'); ?></th>
                                        <th style="width: 10%;"><?php echo $this->lang->line('cash_out'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <?php 
                                $sl = 1;
                                $total_cash_in = 0;
                                $total_cash_out = 0;
                                foreach ($customer_balance_list as $value) {
                                    
                                    if($value->accounts_type == 1) $total_cash_in += $value->amount;
                                    else{
                                        $total_cash_out += $value->amount;
                                    }
                                    ?>
                            <tr>
                                <td> <?php echo $sl++ ; ?> </td>
                                <td> <?php echo date('d M Y', strtotime($value->transaction_date)); ?> </td>
                                
                                
                                <td> <?php echo $value->description; ?> </td>
                                <td> <?php echo $value->transaction_name; ?> </td>
                                <td> <?php echo $value->receiver; ?> </td>

                                <td style="text-align: right; padding-right: 20px"> <?php if($value->accounts_type == 1) echo number_format($value->amount, '2'); ?> </td>
                                <td style="text-align: right; padding-right: 20px"> <?php if($value->accounts_type == 0) echo number_format($value->amount, '2'); ?> </td>
                               
                            </tr>
                            <?php
                                }
                                ?>
                                <tr>
                                    <td colspan="5" style="text-align: right; padding-right: 20px;font-size: 16px; font-weight: bold;"><?php echo $this->lang->line('total')?></td>
                                    <td style="text-align: right; padding-right: 20px;font-size: 16px; font-weight: bold;"><?php echo number_format($total_cash_in, '2');?></td>
                                    <td style="text-align: right; padding-right: 20px;font-size: 16px; font-weight: bold;"><?php echo number_format($total_cash_out, '2');?></td>
                                </tr>
                                    

                                </tbody>
                            </table>
                            <?php }?>
                            
                            
                            
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
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title"><?php echo $this->lang->line('customer_balance_list'); ?></h3>
                    <div class="box-tools pull-right">
                        <a href="<?php echo base_url('admin/customer-balance/add') ?>" class="btn bg-purple btn-sm"><i class="fa fa-plus"></i> <?php echo $this->lang->line('customer_balance_add'); ?></a>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body custom_table_box">
                    <table id="userListTable" class="table table-bordered table-striped table_th_primary custom_table">
                        <thead>
                            <tr>
                                <th style="width: 5%;"><?php echo $this->lang->line('sl'); ?></th>
                                <th style="width: 10%;"><?php echo $this->lang->line('date'); ?></th>
                                <th style="width: 20%;"><?php echo $this->lang->line('customer_name'); ?></th>
                                <th style="width: 10%;"><?php echo $this->lang->line('account_type'); ?></th>
                                <th style="width: 15%;"><?php echo $this->lang->line('description'); ?></th>
                                <th style="width: 10%;"><?php echo $this->lang->line('amount'); ?></th>
                                
                                <th style="width: 10%;"><?php echo $this->lang->line('medium'); ?></th>
                                <th style="width: 10%;"><?php echo $this->lang->line('receiver'); ?></th>
                                
                                <th style="width: 10%;"><?php echo $this->lang->line('action'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                                $sl = 1;
                                foreach ($customer_balance_list as $value) {
                                	?>
                            <tr>
                                <td> <?php echo $sl++ ; ?> </td>
                                <td> <?php echo date('d M Y', strtotime($value->transaction_date)); ?> </td>
                                <td> <?php echo $value->customer_name; ?> </td>
                                <td> <?php if($value->accounts_type == 0) echo "Cash Out"; else echo "Cash In" ; ?> </td>
                                <td> <?php echo $value->description; ?> </td>
                                <td> <?php echo $value->amount; ?> </td>
                                <td> <?php echo $value->transaction_name; ?> </td>
                                <td> <?php echo $value->receiver; ?> </td>
                               
                                
                                <td> 
                                    <a href="<?php echo base_url('admin/customer-balance/edit/'.$value->id); ?>" class="btn btn-sm bg-teal"><i class="fa fa-edit"></i></a>
                                    <a href="<?php echo base_url('admin/customer-balance/delete/'.$value->id); ?>" class="btn btn-sm btn-danger" onclick = 'return confirm("Are You Sure?")'><i class="fa fa-trash"></i></a>
                                </td>
                            </tr>
                            <?php
                                }
                                ?>
                        </tbody>
                    </table>
                    <?php  echo $links; ?>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col -->
    </div>
</section>



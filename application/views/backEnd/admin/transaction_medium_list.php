<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-purple box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title"><?php echo $this->lang->line('transaction_medium_list'); ?></h3>
                    <div class="box-tools pull-right">
                        <a href="<?php echo base_url('admin/transaction-medium/add') ?>" class="btn bg-primary btn-sm"><i class="fa fa-plus"></i> <?php echo $this->lang->line('transaction_medium_add'); ?></a>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body custom_table_box">
                    <table id="userListTable" class="table table-bordered table-striped table_th_purple custom_table">
                        <thead>
                            <tr>
                                <th style="width: 5%;"><?php echo $this->lang->line('sl'); ?></th>
                                <th style="width: 20%;"><?php echo $this->lang->line('transaction_medium_name'); ?></th>
                                <th style="width: 20%;"><?php echo $this->lang->line('transaction_medium_number'); ?></th>
                               
                                
                                <th style="width: 10%;"><?php echo $this->lang->line('icon'); ?></th>
                                
                                <th style="width: 10%;"><?php echo $this->lang->line('action'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                                $sl = 1;
                                foreach ($transaction_medium_list as $value) {
                                	?>
                            <tr>
                                <td> <?php echo $sl++ ; ?> </td>
                                <td> <?php echo $value->name; ?> </td>
                                <td> <?php echo $value->number; ?> </td>
                                
                                
                                <td>
                                    <img src="<?php echo base_url($value->icon) ?>" alt="" width="50px" height="50px">
                                </td>
                                
                                <td> 
                                    <a href="<?php echo base_url('admin/transaction-medium/edit/'.$value->id); ?>" class="btn btn-sm bg-teal"><i class="fa fa-edit"></i></a>
                                    <a href="<?php echo base_url('admin/transaction-medium/delete/'.$value->id); ?>" class="btn btn-sm btn-danger" onclick = 'return confirm("Are You Sure?")'><i class="fa fa-trash"></i></a>
                                </td>
                            </tr>
                            <?php
                                }
                                ?>
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
<script type="text/javascript">
    $(function () {
      $("#userListTable").DataTable();
    });
    
</script>


<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-info box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title"><?php echo $this->lang->line('customer_list'); ?></h3>
                    <div class="box-tools pull-right">
                        <a href="<?php echo base_url('admin/customer/add') ?>" class="btn bg-purple btn-sm"><i class="fa fa-plus"></i> <?php echo $this->lang->line('customer_add'); ?></a>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body custom_table_box">
                    <table id="userListTable" class="table table-bordered table-striped table_th_info custom_table">
                        <thead>
                            <tr>
                                <th style="width: 5%;"><?php echo $this->lang->line('sl'); ?></th>
                                <th style="width: 10%;"><?php echo $this->lang->line('image'); ?></th>
                                <th style="width: 20%;"><?php echo $this->lang->line('customer_name'); ?></th>
                                <th style="width: 15%;"><?php echo $this->lang->line('customer_number'); ?></th>
                                <th style="width: 15%;"><?php echo $this->lang->line('email'); ?></th>
                                <th style="width: 15%;"><?php echo $this->lang->line('address'); ?></th>
                                
                                
                                <th style="width: 10%;"><?php echo $this->lang->line('action'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                                $sl = 1;
                                foreach ($customer_list as $value) {
                                	?>
                            <tr>
                                <td> <?php echo $sl++ ; ?> </td>
                                <td>
                                    <img src="<?php echo base_url($value->photo) ?>" alt="" width="30px" height="30px">
                                </td>
                                <td> <?php echo $value->name; ?> </td>
                                <td> <?php echo $value->phone; ?> </td>
                                <td> <?php echo $value->email; ?> </td>
                                <td> <?php echo $value->address; ?> </td>
                                
                                
                                <td> 
                                    <a href="<?php echo base_url('admin/customer/edit/'.$value->id); ?>" class="btn btn-sm bg-teal"><i class="fa fa-edit"></i></a>
                                    <a href="<?php echo base_url('admin/customer/delete/'.$value->id); ?>" class="btn btn-sm btn-danger" onclick = 'return confirm("Are You Sure?")'><i class="fa fa-trash"></i></a>
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



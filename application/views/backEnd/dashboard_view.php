<section class="content">
	
	
	<div class="row">
		<div class="col-xs-12">
            <div class="box box-primary box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title"><?php echo $this->lang->line('customer_balance'); ?></h3>
                    
                </div>
                <!-- /.box-header -->
                <div class="box-body" style="overflow: scroll; overflow-x:auto; height: 300px;">
                	<div class="col-md-6">
                		<h4 class="box-title"><?php echo $this->lang->line('income'); ?><a href="<?php echo base_url() ?>admin/customer-balance/list" class="pull-right"><?php echo $this->lang->line('see_more'); ?></a></h4>

	                    <table id="cowTypeList" class="table table-bordered table-striped table_th_primary">
	                        <thead>
	                            <tr>
	                                <th style="width: 5%;"><?php echo $this->lang->line('sl'); ?></th>
	                                <th style="width: 30%;"><?php echo $this->lang->line('date'); ?></th>
	                                <th style="width: 40%;"><?php echo $this->lang->line('person'); ?></th>
	                                <th style="width: 20%;"><?php echo $this->lang->line('amount'); ?></th>
	                               
	                            </tr>
	                        </thead>
	                        <tbody>
	                        	<?php foreach($balance_income_list as $key=>$list){?>
	                        		<tr>
	                        			<td> <?= ++$key;?></td>
	                        			<td> <?= date('d M Y', strtotime($list->transaction_date));?></td>
	                        			<td> <?= $list->customer_name;?></td>
	                        			<td> <?= $list->amount;?></td>
	                        		</tr>
	                        	<?php }?>
	                        </tbody>
	                        
	                    </table>
	                    
                	</div>
                	<div class="col-md-6">
                		<h4 class="box-title"><?php echo $this->lang->line('cash_out'); ?><a href="<?php echo base_url() ?>admin/customer-balance/list" class="pull-right"><?php echo $this->lang->line('see_more'); ?></a></h4>
	                    <table id="cowTypeList" class="table table-bordered table-striped table_th_primary">
	                        <thead>
	                            <tr>
	                                <th style="width: 5%;"><?php echo $this->lang->line('sl'); ?></th>
	                                <th style="width: 30%;"><?php echo $this->lang->line('date'); ?></th>
	                                <th style="width: 40%;"><?php echo $this->lang->line('person'); ?></th>
	                                <th style="width: 20%;"><?php echo $this->lang->line('amount'); ?></th>
	                               
	                            </tr>
	                        </thead>
	                        <tbody>
	                        	<?php foreach($balance_expense_list as $key=>$list){?>
	                        		<tr>
	                        			<td> <?= ++$key;?></td>
	                        			<td> <?= date('d M Y', strtotime($list->transaction_date));?></td>
	                        			<td> <?= $list->customer_name;?></td>
	                        			<td> <?= $list->amount;?></td>
	                        		</tr>
	                        	<?php }?>

	                        	
	                        </tbody>
	                        
	                    </table>
	                    
                	</div>
                </div>
                <!-- /.box-body -->

                <div class="box-header">
                    
                    <h4 class="box-title"><?php echo $this->lang->line('total_income'); ?> : <strong><?php echo $total_income;?></strong></h4>
                    <h4 class="box-title" style="color: yellow;position: relative;left:270px;"><?php echo $this->lang->line('total_cash_hand'); ?> : <strong><?php echo $total_income - $total_expense;?></strong></h4>
                    <h4 class="box-title pull-right"><?php echo $this->lang->line('total_expense'); ?> : <strong><?php echo $total_expense;?></strong></h4>
                </div>
            </div>
            <!-- /.box -->
        </div>
        
    </div>

</section>

<script>
	

$(document).ready(function(){
   $('select[name="income_project_id"]').change(function(){
       $(".expense_income_submit1").submit();
    });
   $('select[name="expense_project_id"]').change(function(){
       $(".expense_income_submit2").submit();
    });
});
</script>
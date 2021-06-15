<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>Customer Balance Report</title>
		<style>
			.table_two{
				float:right;
				text-align: right;
			}
			.header{
				font-size:32px;
				font-weight: bold;
			}
			.hdr_txt{
				font-size:18px;
				font-weight: bold;
				width: 95px;
				text-align:right;
			}.hdr_txt_left{
				font-size:18px;
				font-weight: bold;
				width: 95px;
			}
			table{
				border-collapse: collapse;
                width: 100%;
			}
			.table_three tr td, .table_three tr th{
				border:1px solid #000;
				border-collapse: none;
				margin:none;
				padding:8px;
				text-align: center;

			}
		</style>
	</head>
	<body>

		<div style="width:90%;padding:0% 5%;">
			<div style="width: 55%;float:left;">
				<table class="table_one">
					<tr>
						<td class="hdr_txt_left">Name</td>
						<td>:</td>
						<td> <?= $customer_info->name;?></td>
					</tr>
					<tr>
						<td class="hdr_txt_left">Phone</td>
						<td>:</td>
						<td> <?= $customer_info->phone;?></td>
					</tr>
					<?php if($search['start_date'] != ''){?>
					<tr>
						<td class="hdr_txt_left">Date From</td>
						<td>:</td>
						<td><?= date('d M Y', strtotime($search['start_date']));?></td>
					</tr>
					<?php }?>
					<?php if($search['end_date'] != ''){?>
					<tr>
						<td class="hdr_txt_left">Date To</td>
						<td>:</td>
						<td><?= date('d M Y', strtotime($search['end_date']));?></td>
					</tr>
					<?php }?>
			    </table>
			</div>
			<div style="width: 40%;float:right;">
				<table class="table_two">
					<tr>
						<td colspan="3" style="
	text-align: center;
	font-size: 22px;
	font-weight: bold;">Mitu Enterprise</td>
					</tr>
					<tr>
						<td class="hdr_txt">Address</td>
						<td style="text-align:left;">:</td>
						<td style="text-align:left;">12/6 solimollah road mohammadpur dhaka</td>
					</tr>
					<tr>
						<td class="hdr_txt">Phone</td>
						<td style="text-align:left;">:</td>
						<td style="text-align:left;">01711258258</td>
					</tr>
					<tr>
						<td class="hdr_txt">Print Date</td>
						<td style="text-align:left;">:</td>
						<td style="text-align:left;"><?= date('d M Y h:i A');?></td>
					</tr>
			    </table>
			</div>
		</div>
		<div style="width:100%;">
			<table class="table_three">
				<caption style="font-size:28px; font-weight:bold; margin:30px 0px 10px 0px;">Transaction Details</caption>
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
		</div>
		<div style="width:100%;padding:20px 0px;">
			<div style="width:50%;float:left;">
				<p style="font-size:20px; border-bottom: 1px dotted #000;display: inline-block;">Authorized By</p>
			</div>
			<div style="width:50%;float:right;text-align: right;">
				<p style="font-size:20px; border-bottom: 1px dotted #000;display: inline-block;">Received By</p>
			</div>
			
		</div>

		
	</body>
</html>
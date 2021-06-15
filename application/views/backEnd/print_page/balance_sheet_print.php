<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>Balance Sheet</title>
		<style>
			.table_two{
				float:right;
				text-align: right;
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

			}
			.txt_right{
				text-align: right;
			}
			.txt_border{
				border:none!important;
			}
			.txt_left{
				text-align:left;
			}
		</style>
	</head>
	<body>

		<div style="width:90%;padding:0% 5%;">
			<div style="width: 40%;float:left;">
				<table class="table_one">
					
					<tr>
						<td class="hdr_txt_left">Date From</td>
						<td>:</td>
						<?php if($search['start_date'] != ''){?>
						<td><?= date('d M Y', strtotime($search['start_date']));?></td>
						<?php }?>
					</tr>
					
					
					<tr>
						<td class="hdr_txt_left">Date To</td>
						<td>:</td>
						<?php if($search['end_date'] != ''){?>
						<td><?= date('d M Y', strtotime($search['end_date']));?></td>
						<?php }?>
					</tr>
					
					<tr>
						<td class="hdr_txt_left">Print Date</td>
						<td style="text-align:left;">:</td>
						<td style="text-align:left;"><?= date('d M Y h:i A');?></td>
					</tr>
			    </table>
			</div>
			<div style="width: 55%;float:right;">
				<table class="table_two">
					<tr>
						<td colspan="3" style="text-align: center;font-size: 22px;font-weight: bold;">Mitu Enterprise</td>
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
					
			    </table>
			</div>
		</div>
		<div style="width:100%;">
			<table class="table_three">
				<caption style="font-size:28px; font-weight:bold; margin:30px 0px 10px 0px;">Balance Sheet</caption>
				<thead>
					<tr>
						<th style="width: 5%;"><?php echo $this->lang->line('sl'); ?></th>
                        <th style="width: 65%;"><?php echo $this->lang->line('account_head'); ?></th>
                        <th style="width: 15%;"><?php echo $this->lang->line('cash_in'); ?></th>
                        <th style="width: 15%;"><?php echo $this->lang->line('cash_out'); ?></th>
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
                            </tr>
                        <?php }?>
                        <tr>
                            <td colspan="2" style="text-align: right; padding-right: 20px;font-size: 16px; font-weight: bold;"><?php echo $this->lang->line('total')?></td>
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
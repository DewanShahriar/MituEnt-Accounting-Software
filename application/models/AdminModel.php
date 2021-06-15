<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php
	class AdminModel extends CI_Model{
		
	// $returnmessage can be num_rows, result_array, result
		public function isRowExist($tableName,$data, $returnmessage, $user_id = NULL){
		
				$this->db->where($data);
				if($user_id !== NULL) {
						$this->db->where('userId',$user_id);
				}
				if($returnmessage == 'num_rows'){
						return $this->db->get($tableName)->num_rows();
				}else if($returnmessage == 'result_array'){
						return $this->db->get($tableName)->result_array();
				}else{
						return $this->db->get($tableName)->result();
				}
		}
			// saveDataInTable table name , array, and return type is null or last inserted ID.
		public function saveDataInTable($tableName, $data, $returnInsertId = 'false'){
		
				$this->db->insert($tableName,$data);
				if($returnInsertId == 'true'){
						return $this->db->insert_id();
				}else{
						return -1;
				}
		}
			
		public function check_campaign_ambigus($start_date, $end_date){
					
				if(date_format(date_create($start_date),"Y-m-d") > date_format(date_create($end_date),"Y-m-d")){
						return -2;
					}
		
				$this -> db -> limit(1);
				$this -> db -> where('end_date >=', $start_date);
				$this -> db -> where('available_status', 1);
				$query = $this->db->get('create_campaign')->num_rows();
				if($query > 0){
						return -1;
				}
				return 1;
		}
		
		public function end_date_extends($end_date, $id){
		
				$this -> db -> limit(1);
				$this -> db -> where('start_date >=', $end_date);
				$this -> db -> where('id', $id);
				$this -> db -> where('available_status', 1);
				$query = $this->db->get('create_campaign')->num_rows();
				if($query > 0){
						return -1;
				}
				$this -> db -> limit(1);
				$this -> db -> where('end_date >=', $end_date);
				$this -> db -> where('id !=', $id);
				$this -> db -> where('available_status', 1);
				$query2 = $this->db->get('create_campaign')->num_rows();
				if($query2 > 0){
						return -1;
				}
				return 1;
		}
		public function fetch_data_pageination($limit, $start, $table, $search=NULL, $approveStatus=NULL, $user_id =NULL) {
				
				$this->db->limit($limit, $start);
	
			if($approveStatus!==NULL ){
						$this->db->where('approveStatus',$approveStatus);
				}
	
				if($user_id !== NULL ){
						$this->db->where('userId', $user_id);
				}
	
				if($search !== NULL){
						$this->db->like('title',$search);
						$this->db->or_like('body',$search);
						$this->db->or_like('date',$search);
				}
	
				$this->db->order_by('date','desc');
				$query = $this->db->get($table);
	
				if ($query->num_rows() > 0) {
						foreach ($query->result_array() as $row) {
								$data[] = $row;
						}
						return $data;
				}
				return false;
		}
		public function fetch_images($limit=18, $start=0, $table, $search=NULL,$where_data=NULL) {
				
				$this->db->limit($limit, $start);
	
				if($search !== NULL){
						$this->db->like('date',$search);
						$this->db->or_like('photoCaption',$search);
				}
				if($where_data !== NULL){
						$this->db->where($where_data);
				}
				$this->db->group_by('photo');
				$this->db->order_by('date','desc');
				$query = $this->db->get($table);
	
				if ($query->num_rows() > 0) {
						foreach ($query->result_array() as $row) {
								$data[] = $row;
						}
						return $data;
				}
				return false;
		}
		
		public function usersCategory($userId){
	
				$this->db->select('category.*');
				$this->db->join('category' , 'category_user.categoryId = category.id', 'left');
				$this->db->where('category_user.userId',$userId);
				return $this->db->get('category_user')->result_array();
		}
		
		
		public function get_user($user_id)
		{
			 $query = $this->db->select('user.*,tbl_upozilla.*')
							->where('user.id',$user_id)
							->from('user')
							->join('tbl_upozilla','user.address = tbl_upozilla.id', 'left')
							->get();
	
				return $query->row();
		}
		
		public function update_pro_info($update_data, $user_id)
		{
			 return $this->db->where('id',$user_id)->update('user',$update_data);
		}


		public function project_lists()
		{
			$this->db->select('tbl_project.id, tbl_project.name, tbl_project.remark, tbl_project.address, tbl_project.project_start_date');

         	$this->db->group_by('tbl_project.id'); 
         	$this->db->order_by('tbl_project.id', 'desc');

         	$result = $this->db->get('tbl_project'); 

         	if($result->num_rows() > 0){
         		
         		return $result->result();

         	}else {

         		return array();

         	} 
		}


		public function add_project($data)
		{
			return $this->db->insert('tbl_project',$data);
		}

		public function project_update($data,$id)
		{
			return $this->db->where('id',$id)->update('tbl_project',$data);
		}


		public function add_accounthead($data)
		{
			return $this->db->insert('tbl_account_head',$data);
		}

		public function accounthead_update($data, $id)
		{
			return $this->db->where('id',$id)->update('tbl_account_head',$data);
		}

		public function add_accounts($data)
		{
			return $this->db->insert('tbl_accounts',$data);
		}

		public function all_accounts_lists($limit, $start, $data)
		{
			$this->db->select('tbl_accounts.id,  tbl_accounts.date, tbl_accounts.account_head_id, tbl_accounts.description, tbl_accounts.amount, tbl_accounts.status, tbl_account_head.name AS hname, tbl_account_head.id as head_id')
			
				->limit($limit, $start)
				->from('tbl_accounts')
				
				->join('tbl_account_head','tbl_accounts.account_head_id = tbl_account_head.id','left')
				->group_by('tbl_accounts.id')
				->order_by('id','desc');

			

			if(($data['start_date']) != ''){

				$this->db->where('tbl_accounts.date >=', $data['start_date']);
			}

			if(($data['end_date']) != ''){

				$this->db->where('tbl_accounts.date <=', $data['end_date']);
			}

         	$result = $this->db->get(); 

         	if($result->num_rows() > 0){
         		
         		return $result->result();

         	}else {

         		return array();

         	}
		}

		public function all_accounts_count($data)
		{
			$this->db->select('tbl_accounts.id,  tbl_accounts.date, tbl_accounts.account_head_id, tbl_accounts.description, tbl_accounts.amount, tbl_accounts.status, tbl_account_head.name AS hname, tbl_account_head.id as head_id')
			
				->from('tbl_accounts')
				
				->join('tbl_account_head','tbl_accounts.account_head_id = tbl_account_head.id','left')
				->group_by('tbl_accounts.id')
				->order_by('id','desc');

			

			if(($data['start_date']) != ''){

				$this->db->where('tbl_accounts.date >=', $data['start_date']);
			}

			if(($data['end_date']) != ''){

				$this->db->where('tbl_accounts.date <=', $data['end_date']);
			}

         	$result = $this->db->get(); 

         	if($result->num_rows() > 0){
         		
         		return $result->num_rows();

         	} else {

         		return 0;

         	}
		}

		public function accounts_update($data,$id)
		{
			return $this->db->where('id',$id)->update('tbl_accounts',$data);
		}

		public function project_search($project_id)
		{
			return $this->db->select('*')
							->from('tbl_project')
							->where('id',$project_id)
							->get()
							->row();
		}


		public function get_account($project_id, $start_date='', $end_date='')
		{
			$this->db->select('tbl_accounts.id, tbl_accounts.project_id, tbl_accounts.date, tbl_accounts.account_head_id, tbl_accounts.description, tbl_accounts.quantity, tbl_accounts.rate, tbl_accounts.amount, tbl_accounts.status, tbl_account_head.name,tbl_account_head.id as head_id,tbl_account_head.category,tbl_project.name as pname,tbl_project.id as pid');
			$this->db->from('tbl_accounts');
			$this->db->where('tbl_accounts.project_id',$project_id);
			$this->db->join('tbl_project','tbl_accounts.project_id = tbl_project.id', 'left');
			$this->db->join('tbl_account_head','tbl_accounts.account_head_id = tbl_account_head.id');

			if($start_date!='' && $end_date!=''){

				$this->db->where('tbl_accounts.date >=',$start_date);
				$this->db->where('tbl_accounts.date <=',$end_date);

			}
			
			$this->db->order_by('tbl_accounts.date', 'asc');
			$this->db->group_by('tbl_accounts.id');
			return	$this->db->get()->result();
				
		}

		public function income_expence_search($income_expance = '',$start_date='', $end_date='')
		{
			$this->db->select('tbl_accounts.id, tbl_accounts.project_id, tbl_accounts.date, tbl_accounts.account_head_id, tbl_accounts.description, tbl_accounts.quantity, tbl_accounts.rate, tbl_accounts.amount, tbl_accounts.status, tbl_account_head.name,tbl_account_head.id as head_id,tbl_account_head.category,tbl_project.name as pname,tbl_project.id as pid');
			$this->db->from('tbl_accounts');

			if ($income_expance!='') {
			$this->db->where('tbl_account_head.category',$income_expance);
			}

			$this->db->join('tbl_project','tbl_accounts.project_id = tbl_project.id', 'left');
			$this->db->join('tbl_account_head','tbl_accounts.account_head_id = tbl_account_head.id', 'left');

			if($start_date!='' && $end_date!='') {

				$this->db->where('tbl_accounts.date >=',$start_date);
				$this->db->where('tbl_accounts.date <=',$end_date);

			}
			
			$this->db->group_by('tbl_accounts.id');
			return	$this->db->get()->result();
		}

		public function project_cost_analysis_search($project_id, $account_head_id, $start_date='', $end_date='')
		{
			$this->db->select('tbl_accounts.id, tbl_accounts.project_id, tbl_accounts.date, tbl_accounts.account_head_id, tbl_accounts.description, tbl_accounts.quantity, tbl_accounts.rate, tbl_accounts.amount, tbl_accounts.status,  tbl_accounts.description as account_desc,tbl_account_head.name as hname,tbl_account_head.id as head_id,tbl_account_head.category, tbl_project.id as pid');

			$this->db->join('tbl_project','tbl_accounts.project_id = tbl_project.id', 'left');
			$this->db->join('tbl_account_head','tbl_accounts.account_head_id = tbl_account_head.id', 'left');

			$this->db->where('tbl_accounts.project_id', $project_id);
			if($account_head_id > 0) $this->db->where('tbl_accounts.account_head_id', $account_head_id);

			$this->db->from('tbl_accounts');


			if($start_date!='' && $end_date!=''){

				$this->db->where('tbl_accounts.date >=',$start_date);
				$this->db->where('tbl_accounts.date <=',$end_date);

			}
			
			$this->db->group_by('tbl_accounts.date', 'asc');
			$this->db->group_by('tbl_accounts.id');
			return	$this->db->get()->result();
		}
		
		

		public function search_project($accounthead_id, $project_id)
		{
			$this->db->select('tbl_accounts.id, tbl_accounts.project_id, tbl_accounts.date, tbl_accounts.account_head_id, tbl_accounts.description, tbl_accounts.quantity, tbl_accounts.rate, tbl_accounts.amount, tbl_accounts.status, tbl_account_head.name,tbl_account_head.id as head_id, tbl_account_head.category,tbl_project.name as pname');
			$this->db->from('tbl_accounts');
			$this->db->join('tbl_project','tbl_accounts.project_id = tbl_project.id', 'left');
			$this->db->join('tbl_account_head','tbl_accounts.account_head_id = tbl_account_head.id', 'left');
			$this->db->where('tbl_accounts.project_id',$project_id);
			$this->db->where('tbl_accounts.account_head_id',$accounthead_id);
			return	$this->db->get()->result();
		}

		public function cash_in_withdraw_join_search($accounthead_id, $withraw_id, $project_id)
		{
			$this->db->select('tbl_accounts.id, tbl_accounts.project_id, tbl_accounts.date, tbl_accounts.account_head_id, tbl_accounts.description, tbl_accounts.quantity, tbl_accounts.rate, tbl_accounts.amount, tbl_accounts.status, tbl_account_head.id as head_id, tbl_account_head.name,tbl_account_head.category,tbl_project.name as pname');
			$this->db->from('tbl_accounts');
			$this->db->join('tbl_project','tbl_accounts.project_id = tbl_project.id', 'left');
			$this->db->join('tbl_account_head','tbl_accounts.account_head_id = tbl_account_head.id', 'left');
			$this->db->where('tbl_accounts.project_id',$project_id);
			$this->db->group_start()
                    ->where('tbl_accounts.account_head_id',$accounthead_id)
                    ->or_where('tbl_accounts.account_head_id',$withraw_id)
                    ->group_end();
			$this->db->order_by('tbl_accounts.date', 'asc');
			return	$this->db->get()->result();
		}

		public function todays_income()
		{
			$date = date('Y-m-d');
			$category = 1;
			return $this->db->select('sum(tbl_accounts.amount) as income, tbl_account_head.category')
							->from('tbl_accounts')
							->join('tbl_account_head','tbl_accounts.account_head_id = tbl_account_head.id','left')
							->where('DATE(tbl_accounts.date)', $date)
							->where('tbl_account_head.category',$category)
							->group_by('tbl_accounts.id')
							->get()
							->row();

		}

		public function todays_expance()
		{
			$date = date('Y-m-d');
			$category = 0;
			return $this->db->select('sum(tbl_accounts.amount) as expance, tbl_account_head.category')
							->from('tbl_accounts')
							->join('tbl_account_head','tbl_accounts.account_head_id = tbl_account_head.id','left')
							->where('DATE(tbl_accounts.date)', $date)
							->where('tbl_account_head.category',$category)
							->group_by('tbl_accounts.id')
							->get()
							->row();
		}

		public function project_income_expense($project_id, $category)
		{

			return $this->db->select('SUM(tbl_accounts.amount) as amount, tbl_account_head.name,tbl_account_head.id')
							->from('tbl_accounts')
							->join('tbl_account_head','tbl_accounts.account_head_id = tbl_account_head.id','left')
							->where('tbl_accounts.project_id',$project_id)
							->where('tbl_account_head.category',$category)
							->group_by('tbl_accounts.project_id')
							->group_by('tbl_accounts.account_head_id')
							->get()
							->result();
		}

		public function get_withdrawn_money($project_id, $withdraw_id)
		{

			$tempvar =  $this->db->select('SUM(tbl_accounts.amount) as amount')
							->from('tbl_accounts')
							->where('project_id',$project_id)
							->where('account_head_id',$withdraw_id)
							->group_by('project_id')
							->group_by('account_head_id')
							->get(); 
			if($tempvar->num_rows() > 0) return $tempvar->row()->amount;
			else return 0;
		}

		
		public function previous_accounts($project_id, $last_date, $category)
		{
			return $this->db->select('sum(tbl_accounts.amount) as amount')
							->from('tbl_accounts')
							->join('tbl_account_head','tbl_accounts.account_head_id = tbl_account_head.id','left')
							->where('DATE(tbl_accounts.date) < ', $last_date)
							->where('tbl_accounts.project_id', $project_id)
							->where('tbl_account_head.category',$category)
							->get()
							->row();

		}

		public function todays_depostite($project_id,$start_date,$category = 1)
		{
			return $this->db->select('sum(tbl_accounts.amount) as income, tbl_account_head.category')
							->from('tbl_accounts')
							->join('tbl_account_head','tbl_accounts.account_head_id = tbl_account_head.id','left')
							->where('DATE(tbl_accounts.date)', $start_date)
							->where('tbl_accounts.project_id', $project_id)
							->where('tbl_account_head.category', $category) 
							->get()
							->row();

		}

		public function todays_all_depostite($project_id,$start_date,$category = 1)
		{
			return $this->db->select('tbl_accounts.amount, tbl_account_head.category,tbl_account_head.name')
							->from('tbl_accounts')
							->join('tbl_account_head','tbl_accounts.account_head_id = tbl_account_head.id','left')
							->where('DATE(tbl_accounts.date)', $start_date)
							->where('tbl_accounts.project_id', $project_id)
							->where('tbl_account_head.category', $category) 
							->get()
							->result();

		}

		public function todays_expenditure($project_id, $start_date)
		{
			$this->db->select('tbl_accounts.id, tbl_accounts.project_id, tbl_accounts.date, tbl_accounts.account_head_id, tbl_accounts.description, tbl_accounts.quantity, tbl_accounts.rate, tbl_accounts.amount, tbl_accounts.status, tbl_account_head.name,tbl_account_head.category');
			$this->db->from('tbl_accounts');
			$this->db->join('tbl_account_head','tbl_accounts.account_head_id = tbl_account_head.id', 'left');
			$this->db->where('tbl_accounts.project_id',$project_id);
			$this->db->where('DATE(tbl_accounts.date)',$start_date);
			$this->db->where('tbl_account_head.category', 0);
			$this->db->order_by('tbl_accounts.date', 'asc');
			$this->db->group_by('tbl_accounts.id');
			return	$this->db->get()->result();
		}
		
		public function todays_withdraw($project_id, $start_date)
		{
			$this->db->select('tbl_accounts.id, tbl_accounts.project_id, tbl_accounts.date, tbl_accounts.account_head_id, tbl_accounts.description, tbl_accounts.quantity, tbl_accounts.rate, tbl_accounts.amount, tbl_accounts.status, tbl_account_head.name,tbl_account_head.category');
			$this->db->from('tbl_accounts');
			$this->db->join('tbl_account_head','tbl_accounts.account_head_id = tbl_account_head.id', 'left');
			$this->db->where('tbl_accounts.project_id',$project_id);
			$this->db->where('DATE(tbl_accounts.date)',$start_date);
			$this->db->where('tbl_account_head.category', 2);
			$this->db->order_by('tbl_accounts.date', 'asc');
			$this->db->group_by('tbl_accounts.id');
			return	$this->db->get()->result();
		}

		public function get_all_report($project_id)
		{
			$this->db->select('tbl_accounts.id, tbl_accounts.project_id, tbl_accounts.date, tbl_accounts.account_head_id, tbl_accounts.description, tbl_accounts.quantity, tbl_accounts.rate, tbl_accounts.amount, tbl_accounts.status, tbl_account_head.name,tbl_account_head.id as head_id,tbl_account_head.category,tbl_project.name as pname,tbl_project.id as pid');
			$this->db->from('tbl_accounts');
			$this->db->where('tbl_accounts.project_id',$project_id);
			$this->db->join('tbl_project','tbl_accounts.project_id = tbl_project.id', 'left');
			$this->db->join('tbl_account_head','tbl_accounts.account_head_id = tbl_account_head.id');
			$this->db->order_by('tbl_accounts.date', 'asc');
			$this->db->group_by('tbl_accounts.id');
			return	$this->db->get()->result();
				
		}

		public function updateCustomer($updateCustomer, $param2)
		{
		 	if (isset($updateCustomer['photo']) && file_exists($updateCustomer['photo'])) {
	
				$result = $this->db->select('photo')
								   ->from('tbl_customers')
								   ->where('id',$param2)
								   ->get()
								   ->row()->photo;

				if (file_exists($result)) {
				    unlink($result);
				}  
		 }
	
		 return $this->db->where('id',$param2)->update('tbl_customers',$updateCustomer);
		}
	
		public function delete_customer($param2)
		{
			$result = $this->db->select('photo')
							   ->from('tbl_customers')
							   ->where('id',$param2)
							   ->get()
							   ->row()->photo;

			if (file_exists($result)) {
			   unlink($result);
			}   
	
			return $this->db->where('id',$param2)->delete('tbl_customers'); 
		}

		public function updateTransactionMedium($updateData, $param2)
		{
		 	if (isset($updateData['photo']) && file_exists($updateData['photo'])) {
	
				$result = $this->db->select('icon')
								   ->from('tbl_transaction_medium')
								   ->where('id',$param2)
								   ->get()
								   ->row()->icon;

				if (file_exists($result)) {
				    unlink($result);
				}  
		 }
	
		 return $this->db->where('id',$param2)->update('tbl_transaction_medium',$updateData);
		}
	
		public function delete_transaction_medium($param2)
		{
			$result = $this->db->select('icon')
							   ->from('tbl_transaction_medium')
							   ->where('id',$param2)
							   ->get()
							   ->row()->icon;

			if (file_exists($result)) {
			   unlink($result);
			}   
	
			return $this->db->where('id',$param2)->delete('tbl_transaction_medium'); 
		}

		public function get_customer_balance_list($limit, $start)
		{
			
			$this->db->select('tbl_customer_balance.id, tbl_customer_balance.accounts_type, tbl_customer_balance.transaction_date, tbl_customer_balance.description, tbl_customer_balance.receiver, tbl_customers.name AS customer_name, tbl_transaction_medium.name AS transaction_name, tbl_accounts.amount')

				->limit($limit, $start)
         		->from('tbl_customer_balance')
         		->join('tbl_customers', 'tbl_customers.id = tbl_customer_balance.customer_id', 'left')
         		->join('tbl_transaction_medium', 'tbl_transaction_medium.id = tbl_customer_balance.transaction_medium_id', 'left')
         		->join('tbl_accounts', 'tbl_accounts.id = tbl_customer_balance.accounts_id', 'left')
       
         		->order_by('tbl_customer_balance.id', 'DESC')
         		->group_by('tbl_customer_balance.id');

         	$result = $this->db->get();

         	if($result->num_rows() > 0){
         		
         		return $result->result();

         	}else {

         		return array();

         	}
		}

		public function get_customer_balance_report($data)
		{
			
			$this->db->select('tbl_customer_balance.id, tbl_customer_balance.accounts_type, tbl_customer_balance.transaction_date, tbl_customer_balance.description, tbl_customer_balance.receiver, tbl_customers.name AS customer_name, tbl_transaction_medium.name AS transaction_name, tbl_accounts.amount')
			
				
         		->from('tbl_customer_balance')
         		->join('tbl_customers', 'tbl_customers.id = tbl_customer_balance.customer_id', 'left')
         		->join('tbl_transaction_medium', 'tbl_transaction_medium.id = tbl_customer_balance.transaction_medium_id', 'left')
         		->join('tbl_accounts', 'tbl_accounts.id = tbl_customer_balance.accounts_id', 'left')
       
         		->order_by('tbl_customer_balance.id', 'DESC')
         		->group_by('tbl_customer_balance.id');

         	if(($data['start_date']) != ''){

				$this->db->where('tbl_customer_balance.transaction_date >=', $data['start_date']);
			}

			if(($data['end_date']) != ''){

				$this->db->where('tbl_customer_balance.transaction_date <=', $data['end_date']);
			}

			if(($data['customer_id']) != ''){

				$this->db->where('tbl_customer_balance.customer_id', $data['customer_id']);
			}

			if(($data['accounts_type']) != '9'){

				$this->db->where('tbl_customer_balance.accounts_type', $data['accounts_type']);
			}

         	$result = $this->db->get();

         	if($result->num_rows() > 0){
         		
         		return $result->result();

         	}else {

         		return array();

         	}
		}

		public function get_income_balance_list()
		{
			
			$this->db->select('tbl_customer_balance.id, tbl_customer_balance.accounts_type, tbl_customer_balance.transaction_date, tbl_customer_balance.description, tbl_customer_balance.receiver, tbl_customers.name AS customer_name, tbl_transaction_medium.name AS transaction_name, tbl_accounts.amount')

				->limit('10')
         		->from('tbl_customer_balance')
         		->join('tbl_customers', 'tbl_customers.id = tbl_customer_balance.customer_id', 'left')
         		->join('tbl_transaction_medium', 'tbl_transaction_medium.id = tbl_customer_balance.transaction_medium_id', 'left')
         		->join('tbl_accounts', 'tbl_accounts.id = tbl_customer_balance.accounts_id', 'left')
       			->where('tbl_customer_balance.accounts_type', '1')
         		->order_by('tbl_customer_balance.id', 'DESC')
         		->group_by('tbl_customer_balance.id');

         	$result = $this->db->get();

         	if($result->num_rows() > 0){
         		
         		return $result->result();

         	}else {

         		return array();

         	}
		}

		public function get_expense_balance_list()
		{
			
			$this->db->select('tbl_customer_balance.id, tbl_customer_balance.accounts_type, tbl_customer_balance.transaction_date, tbl_customer_balance.description, tbl_customer_balance.receiver, tbl_customers.name AS customer_name, tbl_transaction_medium.name AS transaction_name, tbl_accounts.amount')
			
				->limit('10')
         		->from('tbl_customer_balance')
         		->join('tbl_customers', 'tbl_customers.id = tbl_customer_balance.customer_id', 'left')
         		->join('tbl_transaction_medium', 'tbl_transaction_medium.id = tbl_customer_balance.transaction_medium_id', 'left')
         		->join('tbl_accounts', 'tbl_accounts.id = tbl_customer_balance.accounts_id', 'left')
       			->where('tbl_customer_balance.accounts_type', '0')
         		->order_by('tbl_customer_balance.id', 'DESC')
         		->group_by('tbl_customer_balance.id');

         	$result = $this->db->get();

         	if($result->num_rows() > 0){
         		
         		return $result->result();

         	}else {

         		return array();

         	}
		}

		public function get_daily_summary()
		{
			
			$this->db->select('tbl_account_head.id, tbl_account_head.name')
			
				
         		->from('tbl_account_head')
         		
         		->join('tbl_accounts', 'tbl_accounts.account_head_id = tbl_account_head.id', 'left')
       			->where('tbl_account_head.category', '2')
         		->order_by('tbl_account_head.id', 'DESC')
         		->group_by('tbl_account_head.id');

         	$result = $this->db->get();

         	if($result->num_rows() > 0){
         		
         		return $result->result();

         	}else {

         		return array();

         	}
		}

		public function get_rest_amount($accounts_id)
		{
			$date = new DateTime("now");
 
 			$curr_date = $date->format('Y-m-d ');
			
			$this->db->select_sum('tbl_accounts.amount')
		
         		->from('tbl_accounts')
         		
         		->join('tbl_account_head', 'tbl_accounts.account_head_id = tbl_account_head.id', 'left')
         		->where('tbl_accounts.accounts_type', '1')
         		->where('tbl_accounts.date', $curr_date)
       			->where('tbl_account_head.id', $accounts_id)
         		
         		->group_by('tbl_account_head.id');

         	$result = $this->db->get();

         	$this->db->select_sum('tbl_accounts.amount')
		
         		->from('tbl_accounts')
         		
         		->join('tbl_account_head', 'tbl_accounts.account_head_id = tbl_account_head.id', 'left')
         		->where('tbl_accounts.accounts_type', '0')
         		->where('tbl_accounts.date', $curr_date)
       			->where('tbl_account_head.id', $accounts_id)
         		
         		->group_by('tbl_account_head.id');

         	$expense = $this->db->get();
         	

         	if($result->num_rows() > 0 && $expense->num_rows() >0){
         		
         		return $result->row()->amount - $expense->row()->amount;

         	} elseif($result->num_rows() > 0){

         		return $result->row()->amount;

         	} elseif($expense->num_rows() > 0){

         		return -$expense->row()->amount;
         		
         	} else {

         		return 0;

         	}
		}

		public function get_cash_in_amount($accounts_id, $data)
		{
			
			$this->db->select_sum('tbl_accounts.amount')
		
         		->from('tbl_accounts')
         		
         		->join('tbl_account_head', 'tbl_accounts.account_head_id = tbl_account_head.id', 'left')
         		->where('tbl_accounts.accounts_type', '1')
         		
       			->where('tbl_account_head.id', $accounts_id)
         		
         		->group_by('tbl_account_head.id');

         	if(($data['start_date']) != ''){

				$this->db->where('tbl_accounts.date >=', $data['start_date']);
			}

			if(($data['end_date']) != ''){

				$this->db->where('tbl_accounts.date <=', $data['end_date']);
			}

         	$result = $this->db->get();

  
         	if($result->num_rows() > 0 ){
         		
         		return $result->row()->amount;

         	} else {

         		return 0;

         	}
		}

		public function get_cash_out_amount($accounts_id, $data)
		{

         	$this->db->select_sum('tbl_accounts.amount')
		
         		->from('tbl_accounts')
         		
         		->join('tbl_account_head', 'tbl_accounts.account_head_id = tbl_account_head.id', 'left')
         		->where('tbl_accounts.accounts_type', '0')
         		
       			->where('tbl_account_head.id', $accounts_id)
         		
         		->group_by('tbl_account_head.id');
         		
         	if(($data['start_date']) != ''){

				$this->db->where('tbl_accounts.date >=', $data['start_date']);
			}

			if(($data['end_date']) != ''){

				$this->db->where('tbl_accounts.date <=', $data['end_date']);
			}

         	$expense = $this->db->get();
         	

         	if($expense->num_rows() >0){
         		
         		return $expense->row()->amount;

         	} else {

         		return 0;

         	}
		}

	


	}
	
?>


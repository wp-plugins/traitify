<?php
/**
 * new table class that will extend the WP_List_Table
 */
class Traitify_List_Table extends WP_List_Table
{
	public $limit = 5;

	/**
	 * Prepare the items for the table to process
	 *
	 * @return Void
	 */
	public function prepare_items()
	{
		$columns = $this->get_columns();
		$hidden = $this->get_hidden_columns();
		$sortable = $this->get_sortable_columns();

		$data = $this->table_data();
		usort( $data, array( &$this, 'sort_data' ) );

		$perPage = $this->limit;
        	$currentPage = $this->get_pagenum();
        	$totalItems = count($data);

		$this->set_pagination_args( array(
            		'total_items' => $totalItems,
            		'per_page'    => $perPage
        	));

		$data = array_slice($data, (($currentPage-1) * $perPage), $perPage);

		$this->_column_headers = array($columns, $hidden, $sortable);
		$this->items = $data;
	}

	/**
	 * Override the parent columns method. Defines the columns to use in your listing table
	 *
	 * @return Array
	 */
	public function get_columns()
	{
		$columns = array(
				'id'          		=> 'ID',
				'user_name'       	=> 'User Name',
				'type' 			=> 'Type',
				'assessment_id'		=> 'Assessment Id',
				'total_number_test'     => 'Total Number of Test Attended',
				'actions'		=> 'Actions',
				);

		return $columns;
	}

	/**
	 * Allows you to sort the data by the variables set in the $_GET
	 *
	 * @return Mixed
	 */
	private function sort_data( $a, $b )
	{
		// Set defaults
		$orderby = 'user_name';
		$order = 'asc';

		// If orderby is set, use this as the sort column
		if(!empty($_GET['orderby']))
		{
			$orderby = $_GET['orderby'];
		}

		// If order is set use this as the order
		if(!empty($_GET['order']))
		{
			$order = $_GET['order'];
		}

		$result = strnatcmp( $a[$orderby], $b[$orderby] );

		if($order === 'asc')
		{
			return $result;
		}

		return -$result;
	}

	/**
	 * Define the sortable columns
	 *
	 * @return Array
	 */
	public function get_sortable_columns()
	{
		return array('user_name' => array('user_name', false));
	}

	/**
	 * Define which columns are hidden
	 *
	 * @return Array
	 */
	public function get_hidden_columns()
	{
		return array();
	}


	/**
	 * Get the table data
	 *
	 * @return Array
	 */
	private function table_data()
	{
		$data = array();
		$page = isset($_REQUEST['page_count']) ? $_REQUEST['page_count'] : 0;
		$users_list = $this->getUsersList($this->limit, $page);
		foreach($users_list as $single_user_list)	{
			$user_info = array(); $total_test = $user_name = null;
			$user_info = get_userdata($single_user_list->user_id);
			$user_name = $user_info->user_login;
			$total_test = $this->getTotalTest($single_user_list->user_id);
			$data[] = array(
					'id' => $single_user_list->id,
					'user_name' => $user_name,
					'type' => $single_user_list->type,
					'assessment_id' => $single_user_list->assessment_id,
					'total_number_test' => $total_test,
					'actions' => $single_user_list->user_id,
				); 
		} 
		return $data;
	}

	/**
	 * return list of tests
	 * @parma integer $user_id
	 * @return array $getTests;
	 */
	public function getListOfTest($user_id)	{
		global $wpdb, $traitify;
		$user_tests = $wpdb->get_results("select type, assessment_id from {$wpdb->prefix}{$traitify->table_name} where user_id = '$user_id'");
		return $user_tests;
	}

	/**
	 * return total test count
	 * @param integer $user_id
	 * @return integer $count
	 */
	public function getTotalTest($user_id)	{
		global $wpdb, $traitify;
		$count = 0;
		$getCount = $wpdb->get_results("select count(*) as count from {$wpdb->prefix}{$traitify->table_name} where user_id = '$user_id'");
		if(!empty($getCount))
			$count = $getCount[0]->count;

		return $count;
	}

	/**
         * return assessment id
         * @param string $type
         * @param integer $user_id
         * @return string $assessmentId
         */
        public function getUsersList($limit = 10, $page = 0)        {
                global $wpdb, $traitify;
	        $getUsersList = $wpdb->get_results("select * from {$wpdb->prefix}{$traitify->table_name} group by user_id");
                return $getUsersList;
        }

	// Used to display the value of the id column
	public function column_id($item)
	{
		return $item['id'];
	}

	/**
	 * Define what data to show on each column of the table
	 *
	 * @param  Array $item        Data
	 * @param  String $column_name - Current column name
	 *
	 * @return Mixed
	 */
	public function column_default($item, $column_name)
	{
		switch($column_name)	{
			case 'id':
				return $item[$column_name];
			case 'user_name':
				return $item[$column_name];
			case 'type':
				return $item[$column_name];
			case 'assessment_id':
				return $item[$column_name];
			case 'total_number_test':
				return $item[$column_name];
			case 'actions':	
				$html = '<div class="modal fade" id="showlist_' . $item[$column_name] . '" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  						<div class="modal-dialog">
    							<div class="modal-content">
      								<div class="modal-header">
        								<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        									<h4 class="modal-title" id="myModalLabel"> List of Tests </h4>
      								</div>
      								<div class="modal-body">';

				// show list of test he has attended
				$test_lists = $this->getListOfTest($item[$column_name]);
				$html .= '<table class = "table table-striped">';
				$html .= '<tr> <th> Assessment Id </th> <th> Type </th> <th> Actions </th> </tr>';
				foreach($test_lists as $single_list)	{
					$html .= "<tr> <td> {$single_list->assessment_id} </td> <td> {$single_list->type} </td> <td> <a target = '_blank' href = 'admin.php?page=traitify-list&assessment_id={$single_list->assessment_id}'> Result </a> </td> </tr>";
				}
				$html .= '</table>';
      				$html .= '			</div>
      								<div class="modal-footer">
        								<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      								</div>
    							</div>
  						</div>
					</div>
					<button class="trigger_list_' . $item[$column_name] . '" style = "cursor:pointer"> View Lists </button>
					<script type="text/javascript">
						jQuery(document).ready(function(){
    							jQuery(".trigger_list_' . $item[$column_name] . '").click(function()	{
        							jQuery("#showlist_' . $item[$column_name] . '").modal("show");
    							});
						});
					</script>
				';

				return $html;
			default:
				return print_r($item, true);

		}
	}
}

<?php
if( ! class_exists( 'WP_List_Table' ) ) 
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );

class BMembershipLevels extends WP_List_Table{
    function __construct(){
        parent::__construct(array(
            'singular'=>'Membership Level',
            'plural'  => 'Membership Levels',
            'ajax'    => false
        ));
    }
    function get_columns(){
        return array(
            'cb' => '<input type="checkbox" />'
            ,'id'=>'ID'
            ,'alias'=>'Membership Level'
            ,'role'=>'Role'
            ,'valid_for'=>'Subscription Valid For'
            );
    }
    function get_sortable_columns(){
        return array(
            'alias'=>array('alias',true)
        );
    }
    function get_bulk_actions() {
        $actions = array(
            'bulk_delete'    => 'Delete'
        );
        return $actions;
    }
    function column_default($item, $column_name){
        if($column_name == 'valid_for')
            return bUtils::calculate_subscription_period($item['subscription_period'],
                                                         $item['subscription_unit']);
        if($column_name == 'role') return ucfirst($item['role']);
    	return stripslashes($item[$column_name]);
    }
    function column_id($item){
        $actions = array(
            'edit'  	=> sprintf('<a href="admin.php?page=%s&level_action=edit&id=%s">Edit</a>',
									$_REQUEST['page'],$item['id']),
            'delete'    => sprintf('<a href="?page=%s&level_action=delete&id=%s" 
                                    onclick="return confirm(\'Are you sure you want to delete this entry?\')">Delete</a>',
                                    $_REQUEST['page'],$item['id']),
        );
        return $item['id'] . $this->row_actions($actions);
    }
    function column_cb($item) {
        return sprintf(
            '<input type="checkbox" name="ids[]" value="%s" />', $item['id']
        );    
    }
    function prepare_items() {
        global $wpdb; 
        $query  = "SELECT * FROM " .$wpdb->prefix . "wp_eMember_membership_tbl WHERE  id !=1 ";
        if(isset($_POST['s'])) $query .= " AND alias LIKE '%" . strip_tags($_POST['s']). "%' ";    
        $orderby = !empty($_GET["orderby"]) ? mysql_real_escape_string($_GET["orderby"]) : 'id';
        $order = !empty($_GET["order"]) ? mysql_real_escape_string($_GET["order"]) : 'DESC';
        if(!empty($orderby) && !empty($order)){ $query.=' ORDER BY '.$orderby.' '.$order; }
        $totalitems = $wpdb->query($query); //return the total number of affected rows
        $perpage = 20;
        $paged = !empty($_GET["paged"]) ? mysql_real_escape_string($_GET["paged"]) : '';
        if(empty($paged) || !is_numeric($paged) || $paged<=0 ){ $paged=1; }
        $totalpages = ceil($totalitems/$perpage);
        if(!empty($paged) && !empty($perpage)){
            $offset=($paged-1)*$perpage;
	        $query.=' LIMIT '.(int)$offset.','.(int)$perpage;
        }
        $this->set_pagination_args( array(
            "total_items" => $totalitems,
            "total_pages" => $totalpages,
            "per_page" => $perpage,
        ) );

        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();

        $this->_column_headers = array($columns, $hidden, $sortable);   
        $this->items = $wpdb->get_results($query, ARRAY_A);
    }
    function no_items() {
      _e( 'No membership levels found, dude.' );
    }
	function process_form_request(){		
		if(isset($_REQUEST['id']))
			return $this->edit($_REQUEST['id']);
		return $this->add();
		
	}
	function add(){
		global $wpdb; 
	    $member = BTransfer::$default_fields;
		if(isset($_POST['createswpmlevel'])){
			$member = $_POST;
		}
		extract($member, EXTR_SKIP);
		include_once(SIMPLE_WP_MEMBERSHIP_PATH.'views/admin_add_level.php');
		return false;
	}
	function edit($id){
		global $wpdb;
		$id = absint($id); 
		$query = "SELECT * FROM {$wpdb->prefix}wp_eMember_membership_tbl WHERE id = $id";
		$member = $wpdb->get_row($query, ARRAY_A);
		extract($member, EXTR_SKIP);
        $noexpire = bUtils::calculate_subscription_period($subscription_period,$subscription_unit) == 'noexpire';
		include_once(SIMPLE_WP_MEMBERSHIP_PATH.'views/admin_edit_level.php');
		return false;
	}
	function delete(){
		global $wpdb;
		if(isset($_REQUEST['id'])){
			$id = absint($_REQUEST['id']);	
			$query = "DELETE FROM " .$wpdb->prefix . "wp_eMember_membership_tbl WHERE id = $id";
			$wpdb->query($query);			
		}
		else if (isset($_REQUEST['ids'])){
			$members = $_REQUEST['ids']; 
			if(!empty($members)){
				$members = array_map('absint', $members);
				$members = implode(',', $members);
				$query = "DELETE FROM " .$wpdb->prefix . "wp_eMember_membership_tbl WHERE id IN (" . $members . ")";
				$wpdb->query($query);
			}
		}
	}
	function show(){
            $selected = 1;
            include_once(SIMPLE_WP_MEMBERSHIP_PATH.'views/admin_membership_levels.php');
	}
        function manage(){
            $selected = 2;
             include_once(SIMPLE_WP_MEMBERSHIP_PATH.'views/admin_membership_manage.php');
        }
}

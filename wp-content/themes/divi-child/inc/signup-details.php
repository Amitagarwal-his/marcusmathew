<?php 

if(is_admin())
{
    new Paulund_Wp_List_Table();
}
/**
 * Paulund_Wp_List_Table class will create the page to load the table
 */


class Paulund_Wp_List_Table
{
    /**
     * Constructor will create the menu item
     */
    public function __construct()
    {
        add_action( 'admin_menu', array($this, 'add_menu_example_list_table_page' ));
       
    }
    /**
     * Menu item will allow us to load the page to display the table
     */
    public function add_menu_example_list_table_page()
    {
        add_menu_page( 'Singup Details', 'Singup Details', 'manage_options', 'signup_details', array($this, 'custom_sign_details'), 'dashicons-schedule', 35 );
    }

    /**
     * Display the list table page
     *
     * @return Void
     */
    public function custom_sign_details()
    {
        $exampleListTable = new Example_List_Table();
        
        $exampleListTable->signup_delete();
        $exampleListTable->prepare_items();
        ?>
            <div class="wrap">
                <div id="icon-users" class="icon32"></div>
                <h2>Signup Details</h2>
                <?php $exampleListTable->display(); ?>
            </div>
        <?php
    }
}
// WP_List_Table is not loaded automatically so we need to load it in our application
if( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}
/**
 * Create a new table class that will extend the WP_List_Table
 */
class Example_List_Table extends WP_List_Table
{
    /**
     * Prepare the items for the table to process
     *
     * @return Void
     */

        private function get_sql_results()
        {
        	global $wpdb;
	        $book_table = $wpdb->prefix . "signup_details";
	        $sql_results = $wpdb->get_results("SELECT * FROM $book_table");
            return $sql_results; 
        }

        /**
         * @see WP_List_Table::ajax_user_can()
         */
        public function ajax_user_can()
        {
            return current_user_can('edit_posts');
            return current_user_can('delete_posts');
        }

        /**
         * @see WP_List_Table::no_items()
         */
        public function no_items()
        {
            _e('No signup found.');
        }

        /**
         * @see WP_List_Table::get_views()
         */
        public function get_views()
        {
            return array();
        }

        /**
         * @see WP_List_Table::get_columns()
         */
        public function get_columns()
        {
            $columns = array(
                'id' => __('Id'),
                'first_name' => __('First name'),
                'last_name' => __('Last name'),
                'email' => __('Email'),
                'create_at' => __('Created on'),
                'delete' => __('Delete')
            );
            return $columns;
        }

        /**
         * @see WP_List_Table::get_sortable_columns()
         */
        public function get_sortable_columns()
        {

            /*$sortable = array(
                'id' => array('id', true),
                'book_from' => array('book_from', true),
                'book_to' => array('book_to', true),
                'email' => array('email', true),
                'book_instruction' => array('book_instruction', true),
                'book_publish' => array('book_publish', true),
                'book_status' => array('book_status', true),
                'amount' => array('amount', true),
                'coupon_code' => array('coupon_code', true),
                'payment_status' => array('payment_status', true),
                'create_at' => array('create_at', true),
            );
            return $sortable;*/
        }

        public function signup_delete()
	    {
	        global $wpdb;
	        $table_name = $wpdb->prefix . 'signup_details'; // do not forget about tables prefix
	        $ids = $_REQUEST['signup_id'];
	        
            $wpdb->delete($table_name, array('id' => $ids));	        
	    }

        /**
         * Prepare data for display
         * @see WP_List_Table::prepare_items()
         */
        public function prepare_items()
        {
            $columns = $this->get_columns();
            $hidden = array();
            $sortable = $this->get_sortable_columns();
            $this->_column_headers = array(
                $columns,
                $hidden,
                $sortable
            );

            // SQL results
            $posts = $this->get_sql_results();
            empty($posts) AND $posts = array();

            # >>>> Pagination
            $per_page = 10;
            $current_page = $this->get_pagenum();
            $total_items = count($posts);
            $this->set_pagination_args(array(
                'total_items' => $total_items,
                'per_page' => $per_page,
                'total_pages' => ceil($total_items / $per_page)
            ));
            $last_post = $current_page * $per_page;
            $first_post = $last_post - $per_page + 1;
            $last_post > $total_items AND $last_post = $total_items;
            $range = array_flip(range($first_post - 1, $last_post - 1, 1));
            $posts_array = array_intersect_key($posts, $range);
            # <<<< Pagination
            // Prepare the data
            $permalink = __('Edit:');
            
            $this->items = $posts_array;

        }

        /**
         * A single column
         */
        public function column_default($item, $column_name)
        {
        	$item_json = json_decode(json_encode($item), true);
        	switch( $column_name ) { 
			    case 'id':
			    	return $item->$column_name;
			    case 'first_name':
			    	return $item->$column_name;
			    case 'last_name':
			    	return $item->$column_name;
			    case 'email':
			    	return $item->$column_name;
			    case 'create_at':
			      return date('d-m-Y',$item->$column_name);
		        case 'delete':
			      return sprintf('<a href="?page=%s&action=%s&signup_id=%s">Delete</a>', $_REQUEST['page'], 'delete', $item_json['id']);
			    default:
			      return print_r( $item, true ) ; //Show the whole array for troubleshooting purposes
			  }

        	//return $item->$column_name;
        }

		
}
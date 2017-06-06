<?php
	class erBookingsPages{

		function __construct() {

			add_action('admin_menu', array($this,'erBookingsAdminMenu'));		// Create an administrator menu

		}

		/* erBookingsAdminMenu:  Create the page definitions, call the functions */

		function erBookingsAdminMenu(){

			add_menu_page('Bookings','Bookings','edit_pages','erBookingsMainMenu',array($this,'erBookingsCreateAdminPage'));
			add_submenu_page('erBookingsMainMenu', 'Bookings', 'Settings', 'edit_pages','erBookingsSettingsMenu', array($this,'erBookingsCreateSettingsPage'));

		}

		/* erBookingsCreateAdminPage:  Generate the pages required, in the case of this plugin all that's required is a save and add page */

		function erBookingsCreateAdminPage(){

			global $wpdb;		// Initialise WordpressDB class
			$table_name = $wpdb->prefix . "erBookings";		// Set table name variable - this will be used for accessing info stored by this plugin

?>
			<div class="wrap">
				<h2>Bookings</h2>
						<table width="100%" class="wp-list-table widefat fixed pages">
							<thead>
								<tr>
									<th width="20">#</th>
									<th width="220">Name</th>
									<th>Email</th>
									<th>Phone</th>
									<th>Service</th>
									<th>Booking date</th>
									<th>Booking time</th>
									<th>Attendees</th>
								</tr>
							</thead>
							<tbody>
								<?php
									$i=1;
									$rows = $wpdb->get_results("SELECT * FROM `".$table_name."` ORDER BY booking_date");
									foreach($rows as $row){
								?>
									<tr>
										<td><strong><?=$i?></strong></td>
										<td><?php echo $row->booking_name; ?></td>
										<td><?php echo $row->booking_email; ?></td>
										<td><?php echo $row->booking_phone; ?></td>
										<td><?php echo $row->booking_service; ?></td>
										<td><?php echo $row->booking_date; ?></td>
										<td><?php echo $row->booking_time; ?></td>
										<td><?php echo $row->booking_people; ?></td>
									</tr>
								<?php
									$i++;
									}
								?>
							</tbody>
						</table>

						<p>To put the booking form any page simply place [bookingform] within the content area.</p>


			</div>

<?php
		}

		function erBookingsCreateSettingsPage(){
		$post_email = $_POST['gtNotificationEmail'];
		$erBookingCompanyPhone = $_POST['erBookingCompanyPhone'];
		$erBookingCompanyName = $_POST['erBookingCompanyName'];
		$erBookingCompanyOpeningServices = $_POST['erBookingCompanyOpeningServices'];
		$erBookingCompanyOpeningTimes = $_POST['erBookingCompanyOpeningTimes'];


		if(isset($post_email) && !empty($post_email)){
			$openingtimes = serialize($erBookingCompanyOpeningTimes);
			$opening_services = serialize($erBookingCompanyOpeningServices);

			update_option('erBookingCompanyOpeningTimes',$erBookingCompanyOpeningTimes);
			update_option('erBookingCompanyOpeningServices',$erBookingCompanyOpeningServices);
			update_option('erBookingCompanyName',$erBookingCompanyName);
			update_option('erBookingCompanyPhone',$erBookingCompanyPhone);
			update_option('erBookingNotificationEmail',$post_email);
			echo "<div class='updated settings-error'><p><strong>Your settings were saved successfully.</strong></p></div>";
		}

		$get_email = get_option('erBookingNotificationEmail');
		$company_name = get_option('erBookingCompanyName');
		$company_phone = get_option('erBookingCompanyPhone');
		$opening_services = get_option('erBookingCompanyOpeningServices');
		$opening_times = get_option('erBookingCompanyOpeningTimes');
	?>

			<div class="wrap">
				<h2>Bookings Settings</h2>

				<form method="post" action="admin.php?page=erBookingsSettingsMenu">

				<table class="form-table">
					<tbody>
						<tr>
							<td>Notification email:</td>
							<td><input type="text" name="gtNotificationEmail" value="<?php echo $get_email; ?>" /></td>
						</tr>
						<tr>
							<td>Company name:</td>
							<td><input type="text" name="erBookingCompanyName" value="<?php echo $company_name; ?>" /></td>
						</tr>
						<tr>
							<td>Company phone:</td>
							<td><input type="text" name="erBookingCompanyPhone" value="<?php echo $company_phone; ?>" /></td>
						</tr>
						<tr>
							<td>Opening Services:</td>
							<td><select multiple="multiple" name="erBookingCompanyOpeningServices[]" style="width:140px;height:140px;">
								<?php
									$service = array('Corporate','Group','Retreat','Accommodation','food','other');
									for($i=0;$i<=5;$i++){
								?>
									<option value="<?php echo $service[$i]; ?>" <?php if(@in_array($service[$i],$opening_services)){ ?>selected="selected"<?php } ?>><?php echo $service[$i]; ?></option>
								<?php
									}
								?>
							</select></td>
						</tr>
						<tr>
							<td>Opening times:</td>
							<td><select multiple="multiple" name="erBookingCompanyOpeningTimes[]" style="width:140px;height:140px;">
								<?php
									for($i=0;$i<=23;$i++){
										if(strlen($i) == 1){
											$time = "0".$i.":00";
										}else{
											$time = $i.":00";
										}
								?>
									<option value="<?php echo $time; ?>" <?php if(@in_array($time,$opening_times)){ ?>selected="selected"<?php } ?>><?php echo $time; ?></option>
								<?php
									}
								?>
							</select></td>
						</tr>
					</tbody>
				</table>

				<input type="hidden" name="submitUpdate" value="1">

				<p class="submit"><input type="submit" name="submit" id="submit" class="button-primary" value="Save Changes"></p>

				</form>

			</div>

<?php
		}
	}
?>

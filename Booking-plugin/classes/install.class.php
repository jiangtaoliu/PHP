<?php
	class erBookingsInstall{

		/* erBookingsInstallPlugin:  used when the plugin is first activated (called from erBookings.php) */

		function erBookingsInstallPlugin(){

			global $wpdb;

			$table_name = $wpdb->prefix . "erBookings";
			$sql = "CREATE TABLE `".$table_name."` (
			`booking_id` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
			`booking_name` VARCHAR( 255 ) NOT NULL ,
			`booking_email` VARCHAR( 255 ) NOT NULL,
			`booking_phone` VARCHAR( 255 ) NOT NULL,
			`booking_service` VARCHAR(255),
			`booking_date` VARCHAR(255),
			`booking_time` VARCHAR(255),
			`booking_people` VARCHAR(255),
			`booking_datecreated` DATETIME,
			`booking_ip` VARCHAR(255)

			);";

			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
			dbDelta($sql);

		}

		/* erBookingsUninstallPlugin:  used when the plugin is deactivated (called from erBookings.php) */

		function erBookingsUninstallPlugin(){

			global $wpdb;

		}

	}
?>

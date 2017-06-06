<?php
/*
Plugin Name: Equine Reflections Booking
Description: Simple booking form, perfect for Equine Reflections. Outputs to an email and database.
Version: 0.1
Author: Jett Liu
License: GPL2
*/

//error_reporting(0);		// Change to 1 to enable debugging

/*--------------- LOAD CLASSES ---------------*/

require_once("classes/install.class.php");			// Contains all functions related to install/uninstall plugin
require_once("classes/booking.class.php");			// Contains all functions related to bookings
require_once("classes/pages.class.php");			// Contains all functions related to administration pages

/*--------------- CREATE INSTANCES ---------------*/

$install = new erBookingsInstall;
$bookings = new erBookings;
$pages = new erBookingsPages;

/*--------------- INSTALL PLUGIN ---------------*/

register_activation_hook(__FILE__, array($install,'erBookingsInstallPlugin')); 			// Run installation function
register_deactivation_hook( __FILE__, array($install,'erBookingsUninstallPlugin') );		// Run uninstallation function


?>

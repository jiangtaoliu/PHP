<?php
	class erBookings{

		function __construct(){
			add_action('get_header', array($this,'erBookingHeaders'));
			add_option('erBookingNotificationEmail','bookings@yourwebsite.com');
			add_option('erBookingCompanyName','Equine Reflections');
			add_option('erBookingCompanyPhone','01234567890');
			add_option('erBookingCompanyOpeningServices','');
			add_option('erBookingCompanyOpeningTimes','');
			add_shortcode('bookingform',array($this,'generateBookingForm'));
		}

		function erBookingHeaders(){
		}
		
		function generateConfirmationEmail($name,$email,$people,$Service,$date,$time){

			$from_email = get_option('erBookingNotificationEmail');
			$company_name = get_option('erBookingCompanyName');
			$company_phone = get_option('erBookingCompanyPhone');

			$subject = "Thank you for your booking.";
			$headers = "From: ".$from_email."\r\n";
			$headers .= "MIME-Version: 1.0\r\n";
			$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
			$message .= "Hello <strong>".$name."</strong>!<br /><br />";
			$message .= "Thank you for your booking!<br /><br />";
			$message .= "You have booked for <strong>".$people."</strong> people on the <strong>".$date."</strong> at <strong>".$time."</strong>.<br /><br />";
			$message .= "We look forward to your visit!<br /><br />";
			$message .= $company_name."<br /><br />";
			$message .= "<i><strong>Please note:</strong> Any bookings will only be held for 15 minutes after the time booked, should you not arrive for this time your table may be given to another customer.<br /><br />";
			$message .= "<i>If you have any other queries, feel free to contact us on: ".$company_phone;

			mail($email,$subject,$message,$headers);

		}

		function generateCompanyEmail($name,$email,$people,$Service,$date,$time){

			$from_email = get_option('erBookingNotificationEmail');
			$company_name = get_option('erBookingCompanyName');
			$company_phone = get_option('erBookingCompanyPhone');

			$subject = $company_name." | New booking! | ".$date." - ".$time;
			$headers = "From: ".$email."\r\n";
			$headers .= "MIME-Version: 1.0\r\n";
			$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
			$message .= "<strong>Booking details:</strong><br /><br />";

			$message .= "<strong>Name:</strong> ".$name."<br /><br />";
			$message .= "<strong>Email:</strong> ".$email."<br /><br />";
			$message .= "<strong>Number of attendees:</strong> ".$people."<br /><br />";
			$message .= "<strong>Date:</strong> ".$date."<br /><br />";
			$message .= "<strong>Time:</strong> ".$time."<br /><br />";

			mail($from_email,$subject,$message,$headers);


		}

		function generateBookingForm(){

			global $wpdb;

			$erBookingFormName = $_POST['erBookingFormName'];
			$erBookingFormEmail = $_POST['erBookingFormEmail'];
			$erBookingFormPhone = $_POST['erBookingFormPhone'];
			$erBookingFormPeople = $_POST['erBookingFormPeople'];
			$erBookingFormService = $_POST['erBookingFormService'];
			$erBookingFormDate = $_POST['erBookingFormDate'];
			$erBookingFormTime = $_POST['erBookingFormTime'];

			$table_name = $wpdb->prefix . "erBookings";

			if(isset($_POST['erBookingFormSend']) && !empty($_POST['erBookingFormSend'])){
				echo "<p class='erBookingFormSuccessMsg'>Thank you, we have sent an email to you with details of your booking!</p>";
				$wpdb->insert($table_name,array('booking_name'=>$erBookingFormName,'booking_email'=>$erBookingFormEmail,'booking_phone'=>$erBookingFormPhone,'booking_service'=>$erBookingFormService,'booking_date'=>$erBookingFormDate,'booking_time'=>$erBookingFormTime,'booking_people'=>$erBookingFormPeople));
				$this->generateConfirmationEmail($erBookingFormName,$erBookingFormEmail,$erBookingFormPeople,$erBookingFormService,$erBookingFormDate,$erBookingFormTime);
				$this->generateCompanyEmail($erBookingFormName,$erBookingFormEmail,$erBookingFormPeople,$erBookingFormService,$erBookingFormDate,$erBookingFormTime);
			}

			$opening_times = get_option('erBookingCompanyOpeningTimes');
			$opening_services  = get_option("erBookingCompanyOpeningServices");

			$html .= "<div class='erBookingFormSurround'><h1>Booking form</h1>";
			$html .= "<form method='post' method='".$_SERVER['PHP_SELF']."'>
			<table class='erBookingFormTable'>
			<tbody>
			<tr>
				<td><strong>Your name:</strong></td>
				<td><input name='erBookingFormName' /></td>
			</tr>
			<tr>
				<td><strong>Email address:</strong></td>
				<td><input name='erBookingFormEmail' /</td>
			</tr>
			<tr>
				<td><strong>Mobile Phone:</strong></td>
				<td><input name='erBookingFormPhone' /</td>
			</tr>
			<tr>
				<td><strong>Number of people:</strong></td>
				<td><input name='erBookingFormPeople' /</td>
			</tr>
			<tr>
				<td><strong>Booking Services:</strong></td>
				<td><select name='erBookingFormService'>";
				foreach($opening_services as $services){
					$html .= "<option value='".$services."'>".$services."</option>";
				}
				$html .= "</select></td>
			</tr>
			<tr>
				<td><strong>Booking date:</strong></td>
				<td><input name='erBookingFormDate'></td>
			</tr>
			<tr>
				<td><strong>Booking time:</strong></td>
				<td><select name='erBookingFormTime'>";
				foreach($opening_times as $time){
					if(substr($time,0,2) < 12){
						$meridiem = "AM";
					}else{
						$meridiem = "PM";
					}
					$html .= "<option value='".$time."'>".$time." ".$meridiem."</option>";
				}
				$html .= "</select></td>
			</tr>
			</tbody>
			</table>

			<p><input class='erBookingFormSubmit' type='submit' value='Submit your booking' /></p><input type='hidden' name='erBookingFormSend' value='1' /></form></div>";
			echo  $html;


		}

	}
?>

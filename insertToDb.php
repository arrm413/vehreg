<?php

	// Define function to handle basic user input
	function parse_input($data) 
	{
		$data = trim($data);
		$data = stripslashes($data);
		$data = htmlspecialchars($data);
		return $data;
	}

	// Define function to check that inputted expense number has a maximum of 2 decimal places
	function validateTwoDecimals($number)
	{
	   return (preg_match('/^[0-9]+(\.[0-9]{1,2})?$/', $number));
	}
 
	// PHP script used to connect to backend Azure SQL database
	require 'ConnectToDatabase.php';

	// Start session for this particular PHP script execution.
	session_start();

	// Define ariables and set to empty values
	$v_make = $v_model = $v_year = $start_dt = $end_dt = $name = $errorMessage = NULL;

	// Get input variables
	$v_make= parse_input($_POST['v_make']);
	$v_model= parse_input($_POST['v_model']);
	$v_year= parse_input($_POST['v_year']);
	$start_dt= parse_input($_POST['start_dt']);
	$end_dt= parse_input($_POST['end_dt']);
	$name= parse_input($_POST['name']);

	// Get the authentication claims stored in the Token Store after user logins using Azure Active Directory
	$claims= json_decode($_SERVER['MS_CLIENT_PRINCIPAL'])->claims;
	foreach($claims as $claim)
	{		
		if ( $claim->typ == "http://schemas.xmlsoap.org/ws/2005/05/identity/claims/emailaddress" )
		{
			$userEmail= $claim->val;
			break;
		}
	}

	///////////////////////////////////////////////////////
	//////////////////// INPUT VALIDATION /////////////////
	///////////////////////////////////////////////////////

	//Initialize variable to keep track of any errors
	$anyErrors= FALSE;

	// Check category validity
	if ($name == '-1') {$errorMessage= "Error: Invalid Category Selected"; $anyErrors= TRUE;}
/*	
	// Check date validity
	$isValidDate= checkdate($v_model, $v_make, $v_year);
	if (!$isValidDate) {$errorMessage= "Error: Invalid Date"; $anyErrors= TRUE;}

	// Check that the expense amount input has maximum of 2 decimal places (check against string input, not the float parsed input)
	$isValidstart_dt= validateTwoDecimals(parse_input($_POST['start_dt']));
	if (!$isValidstart_dt) {$errorMessage= "Error: Invalid Expense Amount"; $anyErrors= TRUE;}
*/

	///////////////////////////////////////////////////////
	////////// INPUT PARSING AND WRITE TO SQL DB //////////
	///////////////////////////////////////////////////////

	// Only input information into database if there are no errors
	if ( !$anyErrors ) 
	{
		// Create a DateTime object based on inputted data
		//$dateObj= DateTime::createFromFormat('Y-m-d', $v_year . "-" . $v_model . "-" . $v_make);

		// Get the name of the month (e.g. January) of this expense
		//$v_modelName= $dateObj->format('F');

		// Get the day of the week (e.g. Tuesday) of this expense
		//$v_makeOfWeekNum= $dateObj->format('w');
		//$days = array('Sunday', 'Monday', 'Tuesday', 'Wednesday','Thursday','Friday', 'Saturday');
		//$v_makeOfWeek = $days[$v_makeOfWeekNum];

		// Connect to Azure SQL Database
		$conn = ConnectToDabase();

		// Build SQL query to insert new expense data into SQL database
		$tsql=
		"INSERT INTO VehicleReg (	
				v_make,
				v_model,
				v_year,
				start_dt,
				end_dt,
				name
				)
		VALUES ('
				'" . $v_make . "', 
				'" . $v_model . "', 
				'" . $v_year . "', 
				'" . $start_dt . "', 
				'" . $end_dt . "',
				'" . $name . "',)";

		// Run query
		$sqlQueryStatus= sqlsrv_query($conn, $tsql);

		// Close SQL database connection
		sqlsrv_close ($conn);
	}
/*
	// Initialize an array of previously-posted info
	$prevSelections = array();

	// Populate array with key-value pairs
	$prevSelections['errorMessage']= $errorMessage;
	$prevSelections['prevv_make']= $v_make;
	$prevSelections['prevv_model']= $v_model;
	$prevSelections['prevv_year']= $v_year;
	$prevSelections['prevname']= $name;
	$prevSelections['prevstart_dt']= $start_dt;
	$prevSelections['prevend_dt']= $end_dt;

	// Store previously-selected data as part of info to carry over after URL redirection
	$_SESSION['prevSelections'] = $prevSelections;
*/
	/* Redirect browser to home page */
	header("Location: /"); 
?>

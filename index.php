<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en-US"> 

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<title> 
		Vehicle Registration Form 
	</title>


	<style>
		.error {color: #FF0000;}
	</style>

	<!-- Include CSS for different screen sizes -->
	<link rel="stylesheet" type="text/css" href="defaultstyle.css">
</head>

<body>

<?php
	
	require 'connectToDatabase.php';

	// Connect to Azure SQL Database
	$conn = ConnectToDabase();

	// Get data for expense categories
	$tsql="SELECT * FROM VehicleReg";
	$VehicleReg= sqlsrv_query($conn, $tsql);

	// Populate dropdown menu options 
	/*
	$options = '';
	while($row = sqlsrv_fetch_array($VehicleReg)) {
		$options .="<option>" . $row['CATEGORY'] . "</option>";
	}
*/

	// Close SQL database connection
	sqlsrv_close ($conn);

?>

<div class="intro">

	<h2> Vehicle Registration Form </h2>

	<!-- Display redundant error message on top of webpage if there is an error -->
	<h3> <span class="error"> <?php echo $prevSelections['errorMessage'] ?> </span> </h3>

</div>

<!-- Define web form. 
The array $_POST is populated after the HTTP POST method.
The PHP script insertToDb.php will be executed after the user clicks "Submit"-->
<div class="container">
	<form action="insertToDb.php" method="post">

		<label>Vehicle Make:</label>
		<input type="text" name="vehicle_make" >

		<label>Vehicle Model:</label>
		<input type="text" name="vehicle_model" >

		<label>Vehicle Year:</label>
		<input type="text" name="vehicle_year" >
		
		<label>Start Date:</label>
		<input type="text" name="start_dt" >

		<label>End Date:</label>
		<input type="text" name="end_dt" ><br>

		<label>Employee Name:</label>
		<input type="text" name="employee_name" >

		<button type="submit">Submit</button>
	</form>
</div>

</body>
</html>

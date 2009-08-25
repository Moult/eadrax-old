<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<meta name="keywords" content="" />
<meta name="Description" content="" />
<title>Formo Demo</title>
<style>
	body {font-family: Arial, Helvetica, sans-serif;font-size:.8em;}
	
	.standardform label {
		float: left;
		width: 100px;
		margin-right: 10px;
	}
	
	.standardform p {
		margin: .5em 0;
		clear: both;
	}
	
	.standardform .input {
		border: 1px solid #ccc;
	}
	
	.standardform .errorInput {
		border: 1px solid red;
	}
	
	.standardform .errorMessage {
		margin-left: 15px;
		font-size: 10px;
		color: red;
	}
	
	.submit {
		margin-left: 110px;
	}
	
	.fileLink {
		border: none;
		padding: 5px;
		cursor: pointer;
	}
	
	.fileLink:hover {
		background: #fefeee;
	}
	
	.comment {
		font-size: 10px;
		margin-left: 15px;
		color: #3e60f5;
	}
	
	
</style>
</head>
<body>
	<?=$content?>
	<div style="margin-top:30px">Executed in {execution_time}</div>
</body>
</html>

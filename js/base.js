/**
 * Create all functions in here and initalize them from here.
 */
$(document).ready(function() {
	// Calling Datepicker on Date of birth field.
	$('#dob').datepicker({
			yearRange: '1964:1990',
			dateFormat: 'dd-mm-yy',
			changeMonth: true,
			changeYear: true,
			showButtonPanel: true
		});
});

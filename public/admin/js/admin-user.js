function getSelectedRoles() {
    // Get the select element by ID
    var selectElement = document.getElementById('role');
    
    // Get all selected options
    var selectedOptions = Array.from(selectElement.selectedOptions);
    
    // Extract the value of each selected option
    var selectedValues = selectedOptions.map(option => option.value);
    
    // Output or use the selected values
    if(selectedValues == 2)
    {
    	$(".organizer-class").show();
    }
    else
    {
    	$(".organizer-class").hide();
    }
    
    // Optionally, return the selected values
    return selectedValues;
}
var onloadFunctions = [];
var maxFields     = 4; // maximum number of fields to display
var fieldsCount   = 0;  // keep track of number of fields
var fieldsNumber  = 1;  // used to give name to fields

function addOnLoadFunc ( onloadFunction )
{
    onloadFunctions[onloadFunctions.length] = onloadFunction;

    window.onload = function ( )
    {
        for ( var i = 0; i < onloadFunctions.length; i++ )
        {
            onloadFunctions[i]();
        }
    }
}

function getObj ( id )
{
	return document.getElementById ( id );
}

/* Display default number of fields */
uploadFunc = function ( )
{
    uplContainer  = getObj ( 'upload_fields_container' );
}

addOnLoadFunc ( uploadFunc );

function addUploadFields ( iCount, offset )
{
	maxFields = 5 - offset;
	fieldsNumber = offset;
    for ( var i = 0; i < iCount; i++ ) addUploadField ( );
}

function addUploadField ( )
{
	if ( uplContainer && fieldsCount < maxFields )
	{
		var newField = document.createElement ( 'div' );
		newField.innerHTML = '<img src="http://wipup.org/images/icons/delete.png" style="float: left; margin-left: 100px; margin-bottom: 10px; clear: both;" onclick="removeUploadField(this.parentNode);" /> <input type="file" style="float: left; height: 23px; margin-left: 5px;" name="attachment' + fieldsNumber + '" id="attachment' + fieldsNumber + '" />' + ' ';
		uplContainer.appendChild ( newField );
		fieldsCount++;
		fieldsNumber++;
	}
}

function removeUploadField ( oField )
{
	uplContainer.removeChild ( oField );
	fieldsCount--;
}

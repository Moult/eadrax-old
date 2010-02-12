var arrInput = new Array(0);
var arrInputValue = new Array(0);

function addInput() {
  arrInput.push(arrInput.length);
  arrInputValue.push("");
  display();
}

function display() {
  document.getElementById('more_files').innerHTML="";
  for (intI=0;intI<arrInput.length;intI++) {
	if(intI<3) {
	  document.getElementById('delete').style.display = 'inline';
	  document.getElementById('add').style.display = 'inline';
	} else {
	  document.getElementById('add').style.display = 'none';
	}
	  document.getElementById('more_files').innerHTML+=createInput(arrInput[intI], arrInputValue[intI]);
  }

  if (intI == 0) {
	document.getElementById('delete').style.display = 'none';
  }
}

function saveValue(intId,strValue) {
  arrInputValue[intId]=strValue;
}  

function createInput(id,value) {
  return "<input type='file' name='attachment"+ id +"' onChange='javascript:saveValue("+ id +",this.value)' value='"+ value +"'><br>";
}

function deleteInput() {
  if (arrInput.length > 0) { 
     arrInput.pop(); 
     arrInputValue.pop();
  }
  display(); 
}


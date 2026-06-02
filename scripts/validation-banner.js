/***********Validation international number*************************************/

// Declaring required variables
var digits = "0123456789";
// non-digit characters which are allowed in phone numbers
var phoneNumberDelimiters = "() -";
// characters which are allowed in international phone numbers
// (a leading + is OK)
var validWorldPhoneChars = phoneNumberDelimiters + "+";

var minDigitsInIPhoneNumber = 6;

function isInteger(s)
{   var i;
    for (i = 0; i < s.length; i++)
    {   
        // Check that current character is number.
        var c = s.charAt(i);
        if (((c < "0") || (c > "9"))) return false;
    }
    // All characters are numbers.
    return true;
}
function trim(s)
{   var i;
    var returnString = "";
    // Search through string's characters one by one.
    // If character is not a whitespace, append to returnString.
    for (i = 0; i < s.length; i++)
    {   
        // Check that current character isn't whitespace.
        var c = s.charAt(i);
        if (c != " ") returnString += c;
    }
    return returnString;
}
function stripCharsInBag(s, bag)
{   var i;
    var returnString = "";
    // Search through string's characters one by one.
    // If character is not in bag, append to returnString.
    for (i = 0; i < s.length; i++)
    {   
        // Check that current character isn't whitespace.
        var c = s.charAt(i);
        if (bag.indexOf(c) == -1) returnString += c;
    }
    return returnString;
}

function checkInternationalPhone(strPhone){
var bracket=3
// Minimum no of digits in an international phone no.
strPhone=trim(strPhone)
if(strPhone.indexOf("+")>1) return false
if(strPhone.indexOf("-")!=-1)bracket=bracket+1
if(strPhone.indexOf("(")!=-1 && strPhone.indexOf("(")>bracket)return false
var brchr=strPhone.indexOf("(")
if(strPhone.indexOf("(")!=-1 && strPhone.charAt(brchr+2)!=")")return false
if(strPhone.indexOf("(")==-1 && strPhone.indexOf(")")!=-1)return false
s=stripCharsInBag(strPhone,validWorldPhoneChars);
return (isInteger(s) && s.length >= minDigitsInIPhoneNumber);
}

/*************************************************************************************************/

function send_message(form1){
var Phone=document.form1.telefono
r=1;
	if (document.form1.nombre.value==""){r=0;alert("Ingrese su nombre");document.form1.nombre.focus();return false;}
	if (document.form1.email.value==""){r=0;alert("Ingrese su email");document.form1.email.focus();return false;}
	if (document.form1.email.value.indexOf('@',0)==-1){r=0;alert("Ingrese un mail válido");document.form1.email.focus();return false;}
	if (document.form1.email.value.indexOf('.',0)==-1){r=0;alert("Ingrese un mail válido");document.form1.email.focus();return false;}
	
	
	//if (isNaN(document.form1.phone.value)){r=0;alert("Please Enter a Valid Phone Number");document.form1.phone.focus;return false;}
	/*******************************************************************************************/
	
	/*****************************************************/
	if ((Phone.value==null)||(Phone.value=="")){
		alert("Ingrese su número de teléfono")
		Phone.focus()
		return false
	}
	
	if (checkInternationalPhone(Phone.value)==false){
		alert("Ingrese un número de teléfono válido")
		Phone.value=""
		Phone.focus()
		return false
	}
	
	/********************************************************************************************/
	
	
}


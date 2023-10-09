
<?php

if (!empty($contact->serialNumber)){
    //serial number exists so get contact details and display form ?>
    <fieldset>
    <legend>Your Contact Details</legend>
      <div class="contactDetailConfirmation" style="padding:10px;">
                            <div class="contactDetailConfirmationStatement">
                                We currently hold the following contact information for you:
                            </div>
                            <div class="contactDetailConfirmationDetail" style="margin:10px;font-weight:bold;font-size:1.1em;">
                                <?= $contact->title ?> <?= $contact->firstName ?> <?= $contact->keyname ?><br/><?= $contact->address ?>
                            </div>
                            <div class="contactDetailConfirmationCorrect" style="margin-top:10px;">
                                If these are incorrect,
    please <a href="../../member/contact.aspx?Edit&amp;type=needAddress&amp;return=%2fpublic%2fdonate%2fdonate.aspx%3fedited%3dok" title="Visit the contact details page">click here</a> to correct them.
                            </div>

                        </div>
        
    </fieldset>
    <div class="submit">
                      <a type="submit" href="." name="ctl00$cp1$PREV1" value="previous" id="prevButton"  class="btn btn-primary">Back</a>
                            <button type="submit" name="ctl00$cp1$NEXT1" value="continue" id="nextButton" class="btn btn-primary" >Next</button>
                        </div>
<?php
} else {
    // no contact so show contact details form
?>
                    <fieldset>
                <legend>Name</legend>
                <div id="CONTACTTITLE_d" class="SSArow">
                    <label for="CONTACTTITLE_freeAmount">title&nbsp;&nbsp;</label>
                    <table id="CONTACTTITLE_radio" border="0">
                        <tbody>
                            <tr>
                                <?php foreach($nycosAPI->titleArray as $title) { ?>
                                <td>
                                    <input
                                        id="CONTACTTITLE_radio_0"
                                        type="radio"
                                        name="title"
                                        value="<?=$title?>"/>
                                    <label for="CONTACTTITLE_radio_0"><?=$title?></label>
                                </td>
                                <?php } ?>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div id="FIRSTNAME_d" class="tqRow form-group">
                    <label for="FIRSTNAME"><span class="esitAsterisk" title="Complusory field">*</span>First name</label>
                    <input name="firstName" type="text" value="" maxlength="50" id="FIRSTNAME" class="form-control" required />
                </div>
                <div id="KEYNAME_d" class="tqRow form-group">
                    <label for="KEYNAME"><span class="esitAsterisk" title="Complusory field">*</span>Surname</label>
                    <input name="keyname" type="text" value="" maxlength="100" id="KEYNAME" class="form-control" required />
                </div>
            </fieldset>
            <fieldset>
             <label for="postcode"><span class="esitAsterisk" title="Complusory field">*</span>Postcode Search</label>

                               <div id="postcode-holder" class="input-group">
<div class="content">
	
	<div class="fieldWrap">
		<input type="text" autocomplete="postal-code" name="search" class="searchInput" id="searchBox" placeholder="Enter Address" onchange="showClear()" onkeypress="return enterSearch(event)">
  	<button type="button" class="btn btn-primary" onClick="findAddress()">Search</button>
		<div class="clear" id="clearButton" onClick="clearSearch()">X</div>
	</div>
	
	<div class="fieldWrap">
		<div class="error" id="errorMessage"></div>
	</div>
	
	<div class="fieldWrap">
		<div id="result"></div>
	</div>	
	
	<div class="seperator" id="seperator"></div> 
	
	<div class="fieldWrap">
		<div class="outputArea" id="output"></div>
	</div>
	
</div>

</div>
            
                <div id="ADDRESSLINE1" class="tqRow form-group">
                    <label for="address"><span class="esitAsterisk" title="Complusory field">*</span>Address</label>
                    <input autocomplete="address-line1" required name="address" type="text" value="" maxlength="100" id="address" class="form-control" />
                </div>                
                <div id="ADDRESSLINE3_d" class="tqRow form-group">
                    <label for="ADDRESSLINE3"><span class="esitAsterisk" title="Complusory field">*</span>Town / City</label>
                    <input name="town" required type="text" value="" maxlength="100" id="town" class="form-control" />
                </div>
                <div id="ADDRESSLINE4_d" class="tqRow form-group">
                    <label for="county">County</label>
                    <input name="county" type="text" value="" maxlength="100" id="county" class="form-control" style="margin-bottom: 10px;" />
                </div>
                 <div id="Postcode_d" class="tqRow form-group">
                    <label for="postcode">Postcode</label>
                    <input autocomplete="postal-code" required name="postcode" type="text" value="" maxlength="100" id="postcode" class="form-control" style="margin-bottom: 10px;" />
                </div>
                <div id="COUNTRY_d" class="tqRow form-group">
                    <label for="country"><span class="esitAsterisk" title="Complusory field">*</span>&nbsp;country&nbsp;&nbsp;</label>
                    <select name="country" id="country" class="form-control">
                        <?php foreach($nycosAPI->countries as $country) { ?>
                         <option value="<?=$country?>"><?=$country?></option>
             <?php } ?>          
                    </select>
                </div>
            </fieldset>
            <fieldset>
                <legend>Email</legend>
                <div class="tqRow">
                    <label for="EMAILADDRESS"><span class="esitAsterisk" title="Complusory field">*</span>&nbsp;email address&nbsp;&nbsp;</label>
                    <input name="emailAddress" type="email" value="" maxlength="100" id="emailAddress" class="form-control" required/>
      
                </div>
            </fieldset>
            <fieldset>
                <legend>Telephone</legend>
                <div id="DAYTELEPHONE_d" class="tqRow form-group">
                    <label for="DAYTELEPHONE">telephone (day)&nbsp;&nbsp;</label><input name="dayTelephone" value="" type="text" maxlength="30" id="DAYTELEPHONE" class="form-control" />
                </div>
                <div id="EVENINGTELEPHONE_d" class="tqRow form-group">
                    <label for="EVENINGTELEPHONE">telephone (evening)</label><input name="eveTelephone" value="" type="text" maxlength="30" id="EVENINGTELEPHONE" class="form-control" />
                </div>
                <div id="MOBILENUMBER_d" class="tqRow form-group">
                    <label for="MOBILENUMBER">mobile</label><input name="mobileNumber" type="text" value="" maxlength="30" id="MOBILENUMBER" class="form-control" />
                </div>
            </fieldset>
            <fieldset id="futureCorrespondence" class="checkBoxes">
                <legend>Future Correspondence</legend>
                <p>We would like to tell you about related products or services, we will only do so <strong>occasionally</strong> and when <strong>relevant</strong>.</p>
                <div class="form-check">
                    <span class="checkbox"><input id="DMEMAILOPTIN" type="checkbox" name="consent[Email]" checked="checked" class="form-check-input" data-parsley-multiple="ctl00cp1DMEMAILOPTIN" /></span>
                    <label for="DMEMAILOPTIN" class="form-check-label">I am happy for you to contact me by email for marketing purposes</label>
                </div>
                <div class="form-check">
                    <span class="checkbox"><input id="DMMAILOPTIN" type="checkbox" name="consent[Mail]" checked="checked" class="form-check-input" data-parsley-multiple="ctl00cp1DMMAILOPTIN" /></span>
                    <label for="DMMAILOPTIN" class="form-check-label">I am happy for you to contact me by post for marketing purposes</label>
                </div>
            </fieldset>
            <fieldset>
                <legend>Privacy Notice</legend>
                <small>
                    NYCOS collects personal information when you register, donate or place an order for products or services. We will use this information to provide the services requested, maintain records and, if you agree, to send you
                    marketing information. We do not share your information with third parties. For a more detailed explanation of how we use your information see our <a href="../public/terms/privacy.aspx">full privacy notice</a>.
                </small>
            </fieldset>
        <div class="submit">
 <button onclick="window.location.href = window.location.href += '&nextStep=0';" id="prevButton" class="btn btn-primary">Back</button>
            <button type="submit" name="ctl00$cp1$NEXT1" value="continue" id="nextButton" class="btn btn-primary" >Next</button>
        </div>
<?php
}
?>
<style>
    
    .content{   
     
    	background-color: white;
    	padding: 15px;
    	border: 1px solid #ddefee;
    }

    .fieldWrap{
    	width: 100%;
    	clear: both;
    }

    label{
    	
    	display: inline-block;
    }

    .seperator{
    	margin-bottom: 15px;
    	display: none;
    }

    .outputArea{
    	display: none;
    	padding-left: 5px;
    }

    .searchInput{
    	width: 70%;
    	height: 25px;
    	padding: 5px;
    	font-size: 18px;
    	border: 1px solid transparent;
    	border-bottom: 1px solid #D9D8D7;
    	background: white;
    	font-family: monospace;
    }

    .searchInput::placeholder{
    	font-size: 18px;
    }

    .searchInput:focus {
    	border-bottom: 1px solid #12ada6;
    	outline: 0;
    }

    select{
    	width: 100%;
    	height: 35px;
    	padding-left: 5px;
    	border: 1px solid #D9D8D7;
    	color: #1f2d3d;
    }

    select:focus{
    	outline: 0;
    	border: 1px solid #12ada6;
    }
    /*
    select > option:selected {
    	cursor: pointer;
    	background: #14C5BD;
    }
    */

    select > option:focus{
    	background:#14C5BD ;
    	outline: none;
    }

    .clear{
    	display: none;
    	position: relative;
    	background: #bbb8b8;
    	width: 18px;
    	height: 18px;
    	text-align: center;
    	border-radius: 20px;
    	float: right;
    	padding: 2px;
    	color: white;
    	font-size: 14px;
    	right: 30px;
    	top: 8px;
    	line-height: 18px;
    }

    .clear:hover{
    	background: #969696;
    	cursor: pointer;
    }

.error{
	background: #f5b355; 
	padding: 10px;
	margin-top: 15px;
	display: none; 
}
</style>
<script>
function showClear() {
	document.getElementById("clearButton").style.display = "block";
}

function clearSearch() {
	var input = document.getElementById("searchBox");
	input.value = "";
	document.getElementById("clearButton").style.display = "none";	
}

function showError(message) {
	var error = document.getElementById("errorMessage");
	error.innerText = message;
	error.style.display = "block";
	
	setTimeout(function(){
		error.style.display = "none";
	}, 10000)
}

function enterSearch(e) {
	if (e.keyCode == 13){
		findAddress();	
	}
}

function findAddress(SecondFind) {
  var Text = document.getElementById("searchBox").value;
	
	if (Text === "") {
		showError("Please enter an address");
		return;
	}
	
	var Container = "";			
			
	if (SecondFind !== undefined){
		 Container = SecondFind;
	} 
	
var Key = "RW96-XB39-YG52-HN46",
    IsMiddleware = false,
    Origin = "",
    Countries = "GBR",
    Limit = "10",
    Language = "en-gb",  
		url = 'https://services.postcodeanywhere.co.uk/Capture/Interactive/Find/v1.10/json3.ws';
var params = '';
    params += "&Key=" + encodeURIComponent(Key);
    params += "&Text=" + encodeURIComponent(Text);
    params += "&IsMiddleware=" + encodeURIComponent(IsMiddleware);
    params += "&Container=" + encodeURIComponent(Container);
    params += "&Origin=" + encodeURIComponent(Origin);
    params += "&Countries=" + encodeURIComponent(Countries);
    params += "&Limit=" + encodeURIComponent(Limit);
    params += "&Language=" + encodeURIComponent(Language);
var http = new XMLHttpRequest();
http.open('POST', url, true);
http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
http.onreadystatechange = function() {
  if (http.readyState == 4 && http.status == 200) {
      var response = JSON.parse(http.responseText);
      if (response.Items.length == 1 && typeof(response.Items[0].Error) != "undefined") {
         showError(response.Items[0].Description);
      }
      else {
        if (response.Items.length == 0)
            showError("Sorry, there were no results");

        else {
					var resultBox = document.getElementById("result");
					
					if (resultBox.childNodes.length > 0) {
						var selectBox = document.getElementById("mySelect");
						selectBox.parentNode.removeChild(selectBox)
					}
							
          var resultArea = document.getElementById("result");
          var list = document.createElement("select");
              list.id = "selectList";
              list.setAttribute("id", "mySelect");
              resultArea.appendChild(list);
					
					var defaultOption = document.createElement("option");
					 defaultOption.text = "Select Address";
					defaultOption.setAttribute("value", "");
					defaultOption.setAttribute("selected", "selected");
					list.appendChild(defaultOption);

          for (var i = 0; i < response.Items.length; i++){  	
            var option = document.createElement("option"); 
            option.setAttribute("value", response.Items[i].Id)
            option.text = response.Items[i].Text + " " + response.Items[i].Description;
						option.setAttribute("class", response.Items[i].Type)
																												
            list.appendChild(option);
          }
					selectAddress(Key);				          
        }
    }
  }
}
	http.send(params);
};  

function selectAddress(Key){
		var resultList = document.getElementById("result");
	
		if (resultList.childNodes.length > 0) {		
				var elem = document.getElementById("mySelect");
					
				//IE fix
							elem.onchange = function (e) {
								
								var target = e.target[e.target.selectedIndex];
								
								if (target.text === "Select Address") {
									return;
								}		

								if (target.className === "Address"){
									retrieveAddress(Key, target.value);
								}
								
								else {
								  findAddress(target.value)
								}							
						};				
					}
};

function retrieveAddress(Key, Id){
	var Field1Format = "";
	var url = 'https://services.postcodeanywhere.co.uk/Capture/Interactive/Retrieve/v1.00/json3.ws';
	var params = '';
			params += "&Key=" + encodeURIComponent(Key);
			params += "&Id=" + encodeURIComponent(Id);
			params += "&Field1Format=" + encodeURIComponent(Field1Format);
   
var http = new XMLHttpRequest();
http.open('POST', url, true);
http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
http.onreadystatechange = function() {
  if (http.readyState == 4 && http.status == 200) {
      var response = JSON.parse(http.responseText);

      if (response.Items.length == 1 && typeof(response.Items[0].Error) != "undefined") {
        showError(response.Items[0].Description);
      }
      else {
        if (response.Items.length == 0)
            showError("Sorry, there were no results");
        else {           
					var res = response.Items[0];
					//var resBox = document.getElementById("output");
					//resBox.innerText = res.Label;			
				  //document.getElementById("output").style.display = "block";
            document.getElementById("seperator").style.display = "block";

            document.getElementById("postcode").value = response.Items[0].PostalCode;
            document.getElementById("address").value = response.Items[0].Line1;
            document.getElementById("town").value = response.Items[0].City;
            document.getElementById("country").value = response.Items[0].CountryName;

       }
    }
  }
}
	http.send(params); 
}


    document.addEventListener("DOMContentLoaded", function (event) {


    });
</script>


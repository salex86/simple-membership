    <h2 class="font-normal">Payments</h2>
                <!-- Step 3 input fields -->
                <div class="mt-3">
                  <div id="ctl00_cp1_DirectDebitQuestions">
<fieldset>
<legend>Direct Debit</legend>
<p>Use the form below to set up a Direct Debit from a UK bank or building society account where you are the only person required to authorise debits from the account.</p>
<div id="ACCOUNTNAME_d" class="tqRow form-group">
<label for="ctl00_cp1_ACCOUNTNAME"><span class="esitAsterisk" title="Complusory field">*</span>&nbsp;Name on the Account</label><input name="accountName" type="text" maxlength="100" id="ctl00_cp1_ACCOUNTNAME" class="form-control" required="">
</div>
<div id="SORTCODE_d" class="tqRow form-group">
<label for="ctl00_cp1_SORTCODE"><span class="esitAsterisk" title="Complusory field">*</span>&nbsp;Sort Code</label><input name="sortCode" type="text" maxlength="10" id="sortCode" class="form-control" required="" data-parsley-whitespace="trim" data-parsley-pattern="^(?!(?:0{6}|00-00-00))(?:\d{6}|\d\d-\d\d-\d\d)$" data-parsley-pattern-message=" enter a UK sort code">
</div>
<div id="ACCOUNTNUMBER_d" class="tqRow form-group">
<label for="ctl00_cp1_ACCOUNTNUMBER"><span class="esitAsterisk" title="Complusory field">*</span>&nbsp;Account Number</label><input name="accountNumber" type="text" maxlength="26" id="accountNumber" class="form-control" required="" data-parsley-type="digits" data-parsley-length="[8, 8]" data-parsley-error-message=" enter a 8 digit account number">
</div>
<span id="ctl00_cp1_ACCOUNTNUMBERValidator" style="color:Red;display:none;"></span>
 <input type="hidden" id="bankName" name="bankName"  />
     <input type="hidden" id="bankAddress" name="bankAddress"  />
     <input type="hidden" id="bankPostCode" name="bankPostCode"  />

<div style="color:Red" id="bankMessage">

</div>
<br>
<br>
<div>
<div class="ddImg">
<img src="../../res/image/dd.jpg" id="ctl00_cp1_ctl00_Img1" class="float-right" alt="Direct Debit Logo">
</div>
<h4>About Your Direct Debit</h4>
<p>NYCOS uses the Charities Aid Foundation's (CAF) Direct Debit processing service.</p>
<ul>
<li>
When you pledge a recurring donation we will set up your Direct Debit with CAF on the next working day.
</li>
<li>
It takes some time to set up a Direct Debit with your bank or building society. Your donation will be debited from your account on either the 1st or 15th day of the month, whichever is the earliest available date. Reply to your confirmation email if you would like to specify which day of the month your account is debited.
</li>
<li>
CAF will send you confirmation of your Direct Debit at least 10 working days in advance of your account being debited for the first time.
</li>
<li>The words 'Charity Donation' will appear on your bank statement against your Direct Debit.</li>
</ul>
</div>
</fieldset>
</div>
                </div>
                <div class="mt-3">
                    <a type="submit" href="." name="ctl00$cp1$PREV1" value="previous" id="prevButton"  class="button">Back</a>

                    <button class="btn btn-primary" type="submit">Save</button>
                </div>

<script>
       document.getElementById("debitForm").onsubmit = function (form) {
        form.preventDefault();
        let sort = document.getElementById("sortCode").value;
        let accNo = document.getElementById("accountNumber").value;
          var url = 'https://api.addressy.com/BankAccountValidation/Interactive/Validate/v2.00/json3.ws';
var params = '';
    params += "&Key=" + encodeURIComponent("UW14-GP58-YA59-KD73");
    params += "&AccountNumber=" + encodeURIComponent(accNo);
    params += "&SortCode=" + encodeURIComponent(sort);
var http = new XMLHttpRequest();
http.open('POST', url, true);
http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
http.onreadystatechange = function() {
  if(http.readyState == 4 && http.status == 200) {
      var response = JSON.parse(http.responseText);
      // Test for an error
      if (response.Items.length == 1 && typeof(response.Items[0].Error) != "undefined") {
        // Show the error message
          document.getElementById("bankMessage").innerHTML = response.Items[0].Description;
      }
      else {
        // Check if there were any items found
        if (response.Items.length == 0)
            document.getElementById("bankMessage").innerHTML = "Sorry, there were no results for these details";
        else {
            if (response.Items[0].IsCorrect) {
                document.getElementById("bankName").value = response.Items[0].Bank;
                document.getElementById("bankAddress").value = response.Items[0].ContactAddressLine1 + ', ' + response.Items[0].ContactPostTown;
                document.getElementById("bankPostCode").value = response.Items[0].ContactPostcode;
                // PUT YOUR CODE HERE
                //FYI: The output is an array of key value pairs (e.g. response.Items[0].IsCorrect), the keys being:
                //IsCorrect
                document.getElementById("debitForm").submit();
            } else {

                document.getElementById("bankMessage").innerHTML = "Sorry, there were no results for these details. Try again";
            }
        }
    }
  }
}
http.send(params);
     
    }
</script>
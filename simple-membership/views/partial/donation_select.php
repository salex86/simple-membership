 <h2 class="font-normal">Donation</h2>

<div><div id="ctl00_cp1_UpdatePanel1" class="form-row">
<fieldset id="ctl00_cp1_SOURCECODEfs" class="col-md-6">
<legend>About Your Donation</legend>
<div class="form-group">
<div id="destinationCodeSelector" class="destinationCodeSelector" style="clear:both;">
<div id="destinationCode">
<label>What you would like your donation to support?</label>
<div id="destinationCodesDDrow" class="tqRow">
<select name="destinationCode" id="destinationCode" class="form-control">
<option selected="selected" value="4003-CEN-60">Give to NYCOS</option>
    <option value="4004-CEN-60">Nurturing Talent</option>
<option value="4035-47">Tour Fund 2023</option>
</select>
<a class="btn btn-primary" href="https://www.nycos.co.uk/support-us/donate/">More Info</a>
<span id="destinationCodesDDSummaryHelper"></span>
</div>
<p class="ownLineValidationMessage hidden">
<span id="destinationCodeValidate" style="color:Red;visibility:hidden;">* - please select a destination</span>
</p>
</div>
</div>
</div>
<p id="DD">Allow us to use your donation where it will make the greatest difference.</p>
</fieldset>
<fieldset class="col-md-6">
<legend>Amount to Donate</legend>
<div id="PAYMENTAMOUNT_d" class="tqRow form-group">
<label for="ctl00_cp1_PAYMENTAMOUNT_freeAmount">How much would you like to donate?&nbsp;</label>
    <input name="amount" min="1" type="number" id="amount" class="SSAfreeAmount form-control">
    <span id="ctl00_cp1_PAYMENTAMOUNT_ctl00_cp1_PAYMENTAMOUNT_rfv" style="color:Red;">*</span>
</div>
</fieldset>
</div></div>   

 <?php include(SIMPLE_WP_MEMBERSHIP_PATH.'/views/partial/giftaid_add.php'); ?>

                <div class="mt-3">
                    <button class="btn btn-primary" type="submit" >Next</button>
                </div>
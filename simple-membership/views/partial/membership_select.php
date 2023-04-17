    <p class="lead">
    Support Scotland's young singers by becoming a NYCOS Friend
    </p>

    <span class="switch d-flex justify-content-center mb-2" style="height: 31px">
    <label for="switch-id" class="form-check-label">Pay Annually&nbsp;&nbsp;</label>
    <input type="checkbox" class="switch form-check-input" id="switch-id" checked="" data-parsley-multiple="switch-id">
    <label for="switch-id" class="form-check-label">&nbsp;Pay Monthly</label>
    </span>
    <table class="table table-borderless">
     <tbody><tr>
    <th scope="col" align="left"></th>
    </tr>
        <?php foreach($item as $scheme) { 
                  $schemeValue= $nycosAPI->getMembershipScheme($scheme->schemeId);      
                        
                      $monthPriceString="Pay &#163;".round($schemeValue->bandsAvailable[0]->fixedFees[0]->joiningAmount/12)." a Month";
                      $monthPrice=round($schemeValue->bandsAvailable[0]->fixedFees[0]->joiningAmount/12);
                
                      $yearPriceString="Pay &#163;".$schemeValue->bandsAvailable[0]->fixedFees[0]->joiningAmount." a Year";
                      $yearPrice=$schemeValue->bandsAvailable[0]->fixedFees[0]->joiningAmount;
              if (stripos($scheme->membershipType, "Friend") !== false ) {
                      ?>     
        
            <tr class="Monthly">
    <td class="tdWide">
    <h3 class="mt-2"><?= $scheme->membershipType ?>
    <small><?= $monthPriceString ?></small>
    </h3>
    <?= $scheme->webDescription ?>    
    <div id="ctl03_buttons" class="form-group">
         <form name="userAccountSetupForm" enctype="multipart/form-data" method="POST">
               <input type="hidden" name="nextStep" value="2" />
    <?= $monthPriceString ?>
    <span id="ctl03_selectPaymentType">
    <input type="hidden" name="period" value="Monthly" />
    <input type="hidden" name="schemename" value="<?= $scheme->membershipType ?>" />
    <input type="hidden" name="amount" value="<?= $monthPrice ?>" /> 
    <label for="ctl03_myPaymentTypes">by&nbsp;</label>
    <select name="paymentMethod" id="ctl03_myPaymentTypes" class="form-control d-inline-block input-medium" style="width:135px">
       
    <option value="Direct Debit">Direct Debit</option>
 
    </select>
       
    </span>
    <input type="submit" name="ctl03$select" value="continue" onclick="" id="ctl03_select" class="btn btn-primary">
    </form>
    </div>
    </td>
    </tr>

                 <tr class="Annually hidden">
    <td class="tdWide">
    <h3 class="mt-2"><?= $scheme->membershipType ?>
    <small><?= $yearPriceString ?></small>
    </h3>
    <?= $scheme->webDescription ?>    
    <div id="ctl03_buttons" class="form-group">
         <form name="userAccountSetupForm" enctype="multipart/form-data" method="POST">
               <input type="hidden" name="nextStep" value="2" />
    <?= $yearPriceString ?>
    <span id="ctl03_selectPaymentType">
    <input type="hidden" name="period" value="Monthly" />
    <input type="hidden" name="schemename" value="<?= $scheme->membershipType ?>" />
    <input type="hidden" name="amount" value="<?= $yearPrice ?>" /> 
    <label for="ctl03_myPaymentTypes">by&nbsp;</label>
    <select name="paymentMethod" id="ctl03_myPaymentTypes" class="form-control d-inline-block input-medium" style="width:135px">
        <?php foreach($scheme->webPaymentMethods as $method){ ?>
    <option value="<?=$method?>"><?=$method?></option>
  <?php } ?>
    </select>
       
    </span>
    <input type="submit" name="ctl03$select" value="continue" onclick="" id="ctl03_select" class="btn btn-primary">
    </form>
    </div>
    </td>
    </tr>
        <?php } } ?>
    </tbody>
    </table>


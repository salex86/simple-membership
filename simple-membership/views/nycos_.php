<?php
$auth = SwpmAuth::get_instance();
$user_data = (array) $auth->userData;
$user_data['membership_level_alias'] = $auth->get('alias');
extract($user_data, EXTR_SKIP);
$settings=SwpmSettings::get_instance();
$force_strong_pass=$settings->get_value('force-strong-passwords');
if (!empty($force_strong_pass)) {
    $pass_class="validate[custom[strongPass],minSize[8]]";
} else {
    $pass_class="";
}
SimpleWpMembership::enqueue_validation_scripts();

if ($_REQUEST['formSubmit']){
    foreach($_REQUEST as $key => $val)
    {
        echo $key.''.$val;
        $data[$key] = $val;
    }    
}


//The admin ajax causes an issue with the JS validation if done on form submission. The edit profile doesn't need JS validation on email. There is PHP validation which will catch any email error.
//SimpleWpMembership::enqueue_validation_scripts(array('ajaxEmailCall' => array('extraData'=>'&action=swpm_validate_email&member_id='.SwpmAuth::get_instance()->get('member_id'))));
$contact = json_decode( SwpmUtils::getAPI('contacts/'.$extra_info,''));
print_r($contact);

?>

<main class="site-main" id="main" role="main">
    <h5 class="section-title">
    Your Account
    </h5>
    <div class="swpm-edit-profile-form">
    <form id="swpm-editprofile-form" name="swpm-editprofile-form" method="post" action="" class="swpm-validate-form">
    <div id="normalPage">

        <p>
            Please complete the details on this page and then click the <strong>submit</strong>
            button.
        </p>
        <p>All fields marked with a <strong>*</strong> must be completed.</p>
        <div>
            <fieldset>
                <legend>Name</legend>
                <div id="CONTACTTITLE_d" class="SSArow">
                    <label for="CONTACTTITLE_freeAmount">* title&nbsp;&nbsp;</label>
                    <table id="CONTACTTITLE_radio" border="0">
                        <tbody>
                            <tr>
                                <td>
                                    <input
                                        id="CONTACTTITLE_radio_0"
                                        type="radio"
                                        name="contactTitle"
                                        value="Mr"
                                        onclick="document.getElementById('CONTACTTITLE_freeAmount').value='';"
                                        data-parsley-multiple="ctl00cp1CONTACTTITLEradio"
                                    />
                                    <label for="CONTACTTITLE_radio_0">Mr</label>
                                </td>
                                <td>
                                    <input
                                        id="CONTACTTITLE_radio_1"
                                        type="radio"
                                        name="contactTitle"
                                        value="Mrs"
                                        checked="checked"
                                        onclick="document.getElementById('CONTACTTITLE_freeAmount').value='';"
                                        data-parsley-multiple="ctl00cp1CONTACTTITLEradio"
                                    />
                                    <label for="CONTACTTITLE_radio_1">Mrs</label>
                                </td>
                                <td>
                                    <input
                                        id="CONTACTTITLE_radio_2"
                                        type="radio"
                                        name="contactTitle"
                                        value="Ms"
                                     
                                        data-parsley-multiple="ctl00cp1CONTACTTITLEradio"
                                    />
                                    <label for="CONTACTTITLE_radio_2">Ms</label>
                                </td>
                                <td>
                                    <input
                                        id="CONTACTTITLE_radio_3"
                                        type="radio"
                                        name="contactTitle"
                                        value="Miss"
                                       
                                        data-parsley-multiple="ctl00cp1CONTACTTITLEradio"
                                    />
                                    <label for="CONTACTTITLE_radio_3">Miss</label>
                                </td>
                                <td>
                                    <input
                                        id="CONTACTTITLE_radio_4"
                                        type="radio"
                                        name="contactTitle"
                                        value="Dr"
                                     
                                        data-parsley-multiple="ctl00cp1CONTACTTITLEradio"
                                    />
                                    <label for="CONTACTTITLE_radio_4">Dr</label>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="5">
                                    <input id="CONTACTTITLE_radio_5" type="radio" name="contactTitle" value="" data-parsley-multiple="ctl00cp1CONTACTTITLEradio" />
                                    <label for="CONTACTTITLE_radio_5">other - please specify&nbsp;</label>
                                    <input
                                        name="ctl00$cp1$CONTACTTITLE$freeAmount"
                                        type="text"
                                        maxlength="30"
                                        id="CONTACTTITLE_freeAmount"
                                        class="SSAfreeAmount form-control"
                                        onblur="if (this.value.replace(/ /g,'').length > 0) document.getElementById('CONTACTTITLE_radio_5').checked=true;"
                                    />
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div id="FIRSTNAME_d" class="tqRow form-group">
                    <label for="FIRSTNAME"><span class="esitAsterisk" title="Complusory field">*</span>&nbsp;first name&nbsp;&nbsp;</label>
                    <input name="firstName" type="text" value="<?php echo $contact->firstName; ?>" maxlength="50" id="FIRSTNAME" class="form-control" />
                </div>
                <div id="KEYNAME_d" class="tqRow form-group">
                    <label for="KEYNAME"><span class="esitAsterisk" title="Complusory field">*</span>&nbsp;surname&nbsp;&nbsp;</label>
                    <input name="surName" type="text" value="<?php echo $contact->surName; ?>" maxlength="100" id="KEYNAME" class="form-control" />
                </div>
            </fieldset>
            <fieldset>
                <legend>Address</legend>
                <div id="ADDRESSLINE1_d" class="tqRow">
                    <label for="ADDRESSLINE1"><span class="esitAsterisk" title="Complusory field">*</span>&nbsp;address&nbsp;&nbsp;</label>
                    <textarea
                        name="address"
                        rows="3"
                        cols="20"
                        id="ADDRESSLINE1"
                        class="form-control">
<?php echo $contact->address; ?>
                    </textarea>
                </div>
                <div id="ADDRESSLINE3_d" class="tqRow form-group">
                    <label for="ADDRESSLINE3"><span class="esitAsterisk" title="Complusory field">*</span>&nbsp;town / city&nbsp;&nbsp;</label>
                    <input name="town" type="text" value="<?php echo $contact->town; ?>" maxlength="100" id="ADDRESSLINE3" class="form-control" />
                </div>
                <div id="ADDRESSLINE4_d" class="tqRow form-group">
                    <label for="county">county&nbsp;&nbsp;</label>
                    <input name="county" type="text" value="<?php echo $contact->county; ?>" maxlength="100" id="ADDRESSLINE4" class="form-control" style="margin-bottom: 10px;" />
                </div>
                <div id="postcode" class="tqRow form-group">
                    <label for="postcode"><span class="esitAsterisk" title="Complusory field">*</span>&nbsp;postcode&nbsp;&nbsp;</label>
                    <input name="postcode" type="text" value="<?php echo $contact->postcode; ?>" maxlength="10" id="postcode" class="form-control" />
                </div>
                <div id="COUNTRY_d" class="tqRow form-group">
                    <label for="country"><span class="esitAsterisk" title="Complusory field">*</span>&nbsp;country&nbsp;&nbsp;</label>
                    <select name="country" id="country" class="form-control">
                        <option value="Afghanistan">Afghanistan</option>
                        <option value="Aland Islands">Aland Islands</option>
                        <option value="Albania">Albania</option>
                        <option value="Algeria">Algeria</option>
                        <option value="American Samoa">American Samoa</option>
                        <option value="Andorra">Andorra</option>
                        <option value="Angola">Angola</option>
                        <option value="Anguilla">Anguilla</option>
                        <option value="Antarctica">Antarctica</option>
                        <option value="Antigua and Barbuda">Antigua and Barbuda</option>
                        <option value="Argentina">Argentina</option>
                        <option value="Armenia">Armenia</option>
                        <option value="Aruba">Aruba</option>
                        <option value="Australia">Australia</option>
                        <option value="Austria">Austria</option>
                        <option value="Azerbaijan">Azerbaijan</option>
                        <option value="Bahamas">Bahamas</option>
                        <option value="Bahrain">Bahrain</option>
                        <option value="Bangladesh">Bangladesh</option>
                        <option value="Barbados">Barbados</option>
                        <option value="Belarus">Belarus</option>
                        <option value="Belgium">Belgium</option>
                        <option value="Belize">Belize</option>
                        <option value="Benin">Benin</option>
                        <option value="Bermuda">Bermuda</option>
                        <option value="Bhutan">Bhutan</option>
                        <option value="Bolivia">Bolivia</option>
                        <option value="Bosnia and Herzegovina">Bosnia and Herzegovina</option>
                        <option value="Botswana">Botswana</option>
                        <option value="Bouvet Island">Bouvet Island</option>
                        <option value="Brazil">Brazil</option>
                        <option value="British Indian Ocean Territory">British Indian Ocean Territory</option>
                        <option value="British Virgin Islands">British Virgin Islands</option>
                        <option value="Brunei">Brunei</option>
                        <option value="Bulgaria">Bulgaria</option>
                        <option value="Burkina Faso">Burkina Faso</option>
                        <option value="Burundi">Burundi</option>
                        <option value="Cambodia">Cambodia</option>
                        <option value="Cameroon">Cameroon</option>
                        <option value="Canada">Canada</option>
                        <option value="Cape Verde">Cape Verde</option>
                        <option value="Cayman Islands">Cayman Islands</option>
                        <option value="Central African Republic">Central African Republic</option>
                        <option value="Chad">Chad</option>
                        <option value="Chile">Chile</option>
                        <option value="China">China</option>
                        <option value="Christmas Island">Christmas Island</option>
                        <option value="Cocos (Keeling) Islands">Cocos (Keeling) Islands</option>
                        <option value="Colombia">Colombia</option>
                        <option value="Comoros">Comoros</option>
                        <option value="Congo">Congo</option>
                        <option value="Congo, Democratic Republic of">Congo, Democratic Republic of</option>
                        <option value="Cook Islands">Cook Islands</option>
                        <option value="Costa Rica">Costa Rica</option>
                        <option value="Côte d'Ivoire">Côte d'Ivoire</option>
                        <option value="Croatia">Croatia</option>
                        <option value="Cuba">Cuba</option>
                        <option value="Cyprus">Cyprus</option>
                        <option value="Czech Republic">Czech Republic</option>
                        <option value="Denmark">Denmark</option>
                        <option value="Djibouti">Djibouti</option>
                        <option value="Dominica">Dominica</option>
                        <option value="Dominican Republic">Dominican Republic</option>
                        <option value="East Timor">East Timor</option>
                        <option value="Ecuador">Ecuador</option>
                        <option value="Egypt">Egypt</option>
                        <option value="El Salvador">El Salvador</option>
                        <option value="Equatorial Guinea">Equatorial Guinea</option>
                        <option value="Eritrea">Eritrea</option>
                        <option value="Estonia">Estonia</option>
                        <option value="Ethiopia">Ethiopia</option>
                        <option value="Falkland Islands (Malvinas)">Falkland Islands (Malvinas)</option>
                        <option value="Faroe Islands">Faroe Islands</option>
                        <option value="Fiji">Fiji</option>
                        <option value="Finland">Finland</option>
                        <option value="France">France</option>
                        <option value="French Guiana">French Guiana</option>
                        <option value="French Polynesia">French Polynesia</option>
                        <option value="French Southern Territories">French Southern Territories</option>
                        <option value="Gabon">Gabon</option>
                        <option value="Gambia, The">Gambia, The</option>
                        <option value="Georgia">Georgia</option>
                        <option value="Germany">Germany</option>
                        <option value="Ghana">Ghana</option>
                        <option value="Gibraltar">Gibraltar</option>
                        <option value="Greece">Greece</option>
                        <option value="Greenland">Greenland</option>
                        <option value="Grenada">Grenada</option>
                        <option value="Guadeloupe">Guadeloupe</option>
                        <option value="Guam">Guam</option>
                        <option value="Guatemala">Guatemala</option>
                        <option value="Guernsey">Guernsey</option>
                        <option value="Guinea">Guinea</option>
                        <option value="Guinea-Bissau">Guinea-Bissau</option>
                        <option value="Guyana">Guyana</option>
                        <option value="Haiti">Haiti</option>
                        <option value="Heard and McDonald Islands">Heard and McDonald Islands</option>
                        <option value="Honduras">Honduras</option>
                        <option value="Hong Kong">Hong Kong</option>
                        <option value="Hungary">Hungary</option>
                        <option value="Iceland">Iceland</option>
                        <option value="India">India</option>
                        <option value="Indonesia">Indonesia</option>
                        <option value="Iran">Iran</option>
                        <option value="Iraq">Iraq</option>
                        <option value="Ireland">Ireland</option>
                        <option value="Isle of Man">Isle of Man</option>
                        <option value="Israel">Israel</option>
                        <option value="Italy">Italy</option>
                        <option value="Jamaica">Jamaica</option>
                        <option value="Japan">Japan</option>
                        <option value="Jersey">Jersey</option>
                        <option value="Jordan">Jordan</option>
                        <option value="Kazakhstan">Kazakhstan</option>
                        <option value="Kenya">Kenya</option>
                        <option value="Kiribati">Kiribati</option>
                        <option value="Korea, North">Korea, North</option>
                        <option value="Korea, South">Korea, South</option>
                        <option value="Kosovo">Kosovo</option>
                        <option value="Kuwait">Kuwait</option>
                        <option value="Kyrgyzstan">Kyrgyzstan</option>
                        <option value="Laos">Laos</option>
                        <option value="Latvia">Latvia</option>
                        <option value="Lebanon">Lebanon</option>
                        <option value="Lesotho">Lesotho</option>
                        <option value="Liberia">Liberia</option>
                        <option value="Libya">Libya</option>
                        <option value="Liechtenstein">Liechtenstein</option>
                        <option value="Lithuania">Lithuania</option>
                        <option value="Luxembourg">Luxembourg</option>
                        <option value="Macau">Macau</option>
                        <option value="Macedonia, The Former Yugoslav">Macedonia, The Former Yugoslav</option>
                        <option value="Madagascar">Madagascar</option>
                        <option value="Malawi">Malawi</option>
                        <option value="Malaysia">Malaysia</option>
                        <option value="Maldives">Maldives</option>
                        <option value="Mali">Mali</option>
                        <option value="Malta">Malta</option>
                        <option value="Marshall Islands">Marshall Islands</option>
                        <option value="Martinique">Martinique</option>
                        <option value="Mauritania">Mauritania</option>
                        <option value="Mauritius">Mauritius</option>
                        <option value="Mayotte">Mayotte</option>
                        <option value="Mexico">Mexico</option>
                        <option value="Micronesia, Federated States o">Micronesia, Federated States o</option>
                        <option value="Moldova">Moldova</option>
                        <option value="Monaco">Monaco</option>
                        <option value="Mongolia">Mongolia</option>
                        <option value="Montenegro">Montenegro</option>
                        <option value="Montserrat">Montserrat</option>
                        <option value="Morocco">Morocco</option>
                        <option value="Mozambique">Mozambique</option>
                        <option value="Myanmar">Myanmar</option>
                        <option value="Namibia">Namibia</option>
                        <option value="Nauru">Nauru</option>
                        <option value="Nepal">Nepal</option>
                        <option value="Netherlands">Netherlands</option>
                        <option value="Netherlands Antilles">Netherlands Antilles</option>
                        <option value="New Caledonia">New Caledonia</option>
                        <option value="New Zealand">New Zealand</option>
                        <option value="Nicaragua">Nicaragua</option>
                        <option value="Niger">Niger</option>
                        <option value="Nigeria">Nigeria</option>
                        <option value="Niue">Niue</option>
                        <option value="Norfolk Island">Norfolk Island</option>
                        <option value="Northern Mariana Islands">Northern Mariana Islands</option>
                        <option value="Norway">Norway</option>
                        <option value="Oman">Oman</option>
                        <option value="Pakistan">Pakistan</option>
                        <option value="Palau">Palau</option>
                        <option value="Palestinian Territory, Occupie">Palestinian Territory, Occupie</option>
                        <option value="Panama">Panama</option>
                        <option value="Papua New Guinea">Papua New Guinea</option>
                        <option value="Paraguay">Paraguay</option>
                        <option value="Peru">Peru</option>
                        <option value="Philippines">Philippines</option>
                        <option value="Pitcairn">Pitcairn</option>
                        <option value="Poland">Poland</option>
                        <option value="Portugal">Portugal</option>
                        <option value="Puerto Rico">Puerto Rico</option>
                        <option value="Qatar">Qatar</option>
                        <option value="Reunion">Reunion</option>
                        <option value="Romania">Romania</option>
                        <option value="Russia">Russia</option>
                        <option value="Rwanda">Rwanda</option>
                        <option value="S. Georgia and S. Sandwich Isl">S. Georgia and S. Sandwich Isl</option>
                        <option value="Saint Helena">Saint Helena</option>
                        <option value="Saint Lucia">Saint Lucia</option>
                        <option value="Saint Martin">Saint Martin</option>
                        <option value="Saint Vincent &amp; the Grenadines">Saint Vincent &amp; the Grenadines</option>
                        <option value="San Marino">San Marino</option>
                        <option value="Sao Tome and Principe">Sao Tome and Principe</option>
                        <option value="Saudi Arabia">Saudi Arabia</option>
                        <option value="Senegal">Senegal</option>
                        <option value="Serbia">Serbia</option>
                        <option value="Seychelles">Seychelles</option>
                        <option value="Sierra Leone">Sierra Leone</option>
                        <option value="Singapore">Singapore</option>
                        <option value="Slovakia">Slovakia</option>
                        <option value="Slovenia">Slovenia</option>
                        <option value="Solomon Islands">Solomon Islands</option>
                        <option value="Somalia">Somalia</option>
                        <option value="South Africa">South Africa</option>
                        <option value="Spain">Spain</option>
                        <option value="Sri Lanka">Sri Lanka</option>
                        <option value="St. Kitts &amp; Nevis">St. Kitts &amp; Nevis</option>
                        <option value="Sudan">Sudan</option>
                        <option value="Suriname">Suriname</option>
                        <option value="Svalbard &amp; Jan Mayen Islands">Svalbard &amp; Jan Mayen Islands</option>
                        <option value="Swaziland">Swaziland</option>
                        <option value="Sweden">Sweden</option>
                        <option value="Switzerland">Switzerland</option>
                        <option value="Syria">Syria</option>
                        <option value="Taiwan">Taiwan</option>
                        <option value="Tajikistan">Tajikistan</option>
                        <option value="Tanzania">Tanzania</option>
                        <option value="Thailand">Thailand</option>
                        <option value="Togo">Togo</option>
                        <option value="Tokelau">Tokelau</option>
                        <option value="Tonga">Tonga</option>
                        <option value="Trinidad and Tobago">Trinidad and Tobago</option>
                        <option value="Tunisia">Tunisia</option>
                        <option value="Turkey">Turkey</option>
                        <option value="Turkmenistan">Turkmenistan</option>
                        <option value="Turks and Caicos Islands">Turks and Caicos Islands</option>
                        <option value="Tuvalu">Tuvalu</option>
                        <option value="Uganda">Uganda</option>
                        <option value="Ukraine">Ukraine</option>
                        <option value="United Arab Emirates">United Arab Emirates</option>
                        <option selected="selected" value="United Kingdom">United Kingdom</option>
                        <option value="United States">United States</option>
                        <option value="Uruguay">Uruguay</option>
                        <option value="US Minor Outlying Islands">US Minor Outlying Islands</option>
                        <option value="Uzbekistan">Uzbekistan</option>
                        <option value="Vanuatu">Vanuatu</option>
                        <option value="Vatican City State (Holy See)">Vatican City State (Holy See)</option>
                        <option value="Venezuela">Venezuela</option>
                        <option value="Vietnam">Vietnam</option>
                        <option value="Virgin Islands (U.S.)">Virgin Islands (U.S.)</option>
                        <option value="Wallis and Futuna Islands">Wallis and Futuna Islands</option>
                        <option value="Western Sahara">Western Sahara</option>
                        <option value="Western Samoa">Western Samoa</option>
                        <option value="Yemen">Yemen</option>
                        <option value="Yugoslavia (Serbia and Montene">Yugoslavia (Serbia and Montene</option>
                        <option value="Zambia">Zambia</option>
                        <option value="Zimbabwe">Zimbabwe</option>
                    </select>
                </div>
            </fieldset>
            <fieldset>
                <legend>Email</legend>
                <div class="tqRow">
                    <label for="EMAILADDRESS"><span class="esitAsterisk" title="Complusory field">*</span>&nbsp;email address&nbsp;&nbsp;</label>
                    <input name="email" type="text" value="<?php echo $contact->email; ?>" maxlength="100" id="emailAddress" class="form-control" />
      
                </div>
            </fieldset>
            <fieldset>
                <legend>Telephone</legend>
                <div id="DAYTELEPHONE_d" class="tqRow form-group">
                    <label for="DAYTELEPHONE">telephone (day)&nbsp;&nbsp;</label><input name="dayTelephone" value="<?php echo $contact->dayTelephone; ?>" type="text" maxlength="30" id="DAYTELEPHONE" class="form-control" />
                </div>
                <div id="EVENINGTELEPHONE_d" class="tqRow form-group">
                    <label for="EVENINGTELEPHONE">telephone (evening)&nbsp;&nbsp;</label><input name="eveningTelephone" value="<?php echo $contact->eveningTelephone; ?>" type="text" maxlength="30" id="EVENINGTELEPHONE" class="form-control" />
                </div>
                <div id="MOBILENUMBER_d" class="tqRow form-group">
                    <label for="MOBILENUMBER">mobile&nbsp;&nbsp;</label><input name="mobileNumber" type="text" value="<?php echo $contact->mobileNumber; ?>" maxlength="30" id="MOBILENUMBER" class="form-control" />
                </div>
            </fieldset>
            <fieldset id="futureCorrespondence" class="checkBoxes">
                <legend>Future Correspondence</legend>
                <p>We would like to tell you about related products or services, we will only do so <strong>occasionally</strong> and when <strong>relevant</strong>.</p>
                <div class="form-check">
                    <span class="checkbox"><input id="DMEMAILOPTIN" type="checkbox" name="" checked="checked" class="form-check-input" data-parsley-multiple="ctl00cp1DMEMAILOPTIN" /></span>
                    <label for="DMEMAILOPTIN" class="form-check-label">I am happy for you to contact me by email for marketing purposes</label>
                </div>
                <div class="form-check">
                    <span class="checkbox"><input id="DMMAILOPTIN" type="checkbox" name="ctl00$cp1$DMMAILOPTIN" checked="checked" class="form-check-input" data-parsley-multiple="ctl00cp1DMMAILOPTIN" /></span>
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
        </div>
        <fieldset>
            <legend></legend>
            <input
                type="submit"
                name="formSubmit"
                value="submit"
                id="submit2"
                class="btn btn-primary btn-lg"
            />
        </fieldset>
    </div>
        </form>
        </div>
</main>

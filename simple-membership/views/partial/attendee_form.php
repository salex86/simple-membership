
                <input type="hidden" name="nextStep" value="3" />
                
                <main class="site-main" id="main" role="main">

                    <span id="ctl00_cp1_totalPlaceCheck" style="color:Red;display:none;"></span>
                    <span id="ctl00_cp1_maxAttendeesValidator" style="color:Red;display:none;"></span>
                    <div id="ctl00_cp1_pageTop" class="pageTop">
                        <h4 id="ctl00_cp1_eventName">
                            <?= $event->eventName ?>
                        </h4>
                        <p id="ctl00_cp1_eventDates">
                            <?= date("F j Y", strtotime($event->startDate)) ?> - <?= date("F j Y", strtotime($event->endDate)) ?>, <?= $event->startTime ?> - <?= $event->endTime ?>
                        </p>
                        <p id="ctl00_cp1_NYCoS_eventDates"></p>
                        <p id="ctl00_cp1_eventDescription">
                            <?= $event->description ?>
                        </p>
                        <p id="ctl00_cp1_eventCosts" class="">
                            <strong>Prices:</strong>Event Costs
                        </p>
                        <p id="ctl00_cp1_eventLocation">
                            <strong>Location:</strong><?= $event->locationAddressLine ?>
                        </p>
                    </div>

                    <?php $count=0;

                          foreach ($_REQUEST['attendeeType'] as $key=> $attendeeTypeValue){

                              for($n=1;$n<=$attendeeTypeValue;$n++){
                                  //include(SIMPLE_WP_MEMBERSHIP_PATH.'/views/partial/attendee_form.php');
                                  $count++;                                

                                $data = $nycosAPI->getAttendeeType($key); 
                                $attendeeType = new AttendeeTypes($data);
                            
                                ?>
                            <fieldset>
                              <legend></legend>
                              <h4 id="attendeeTitle">Attendee <?= $count ?>: <?= $attendeeType->attendeeType?> &pound; <?= $attendeeType->costs[0]->value ?></h4>
                                <input type="hidden" name="attendeeType[<?= $count ?>][typeId]" value="<?= $attendeeType->attendeeTypeId ?>" />
                                <input type="hidden" name="attendeeType[<?= $count ?>][attendeeType]" value="<?= $attendeeType->attendeeType ?>" />
                                <input type="hidden" name="attendeeType[<?= $count ?>][value]" value="<?= $attendeeType->costs[0]->value ?>" />
                                <input id="isMain" value="isMain" type="checkbox" name="attendeeType[<?= $count ?>][isMain]" class="isMainCheckClass form-check-input">
                                <label for="isMain" class="form-check-label">Is Me <small>Check here if this attendee is yourself</small></label>                                         
                                                    
                                <div id="FIRSTNAME_d" class="tqRow form-group">
                                <label for="FIRSTNAME">
                                  <span class="esitAsterisk" title="Complusory field">*</span>&nbsp;first name </label>
                                <input name="attendeeType[<?= $count ?>][firstName]" type="text" maxlength="50" id="FIRSTNAME" class="firstname form-control trim" required>
                              </div>
                              <div id="KEYNAME_d" class="tqRow form-group">
                                <label for="KEYNAME">
                                  <span class="esitAsterisk" title="Complusory field">*</span>&nbsp;surname </label>
                                <input name="attendeeType[<?= $count ?>][keyname]" type="text" maxlength="100" id="KEYNAME" class="secondname form-control trim" required>
                              </div>
                              <div class="DOB_wrapper">
                                <div id="DATEOFBIRTH_d" class="tqRow form-group">
                                  <label for="DATEOFBIRTH">date of birth</label>
                                  <span id="sp" class="form-row">
                                    <div class="col">
                                      <select name="attendeeType[<?= $count ?>][DOBDay]" id="DATEOFBIRTH_Day" class="form-control">
                                        <option value=""></option>
                                        <option value="01">01</option>
                                        <option value="02">02</option>
                                        <option value="03">03</option>
                                        <option value="04">04</option>
                                        <option value="05">05</option>
                                        <option value="06">06</option>
                                        <option value="07">07</option>
                                        <option value="08">08</option>
                                        <option value="09">09</option>
                                        <option value="10">10</option>
                                        <option value="11">11</option>
                                        <option value="12">12</option>
                                        <option value="13">13</option>
                                        <option value="14">14</option>
                                        <option value="15">15</option>
                                        <option value="16">16</option>
                                        <option value="17">17</option>
                                        <option value="18">18</option>
                                        <option value="19">19</option>
                                        <option value="20">20</option>
                                        <option value="21">21</option>
                                        <option value="22">22</option>
                                        <option value="23">23</option>
                                        <option value="24">24</option>
                                        <option value="25">25</option>
                                        <option value="26">26</option>
                                        <option value="27">27</option>
                                        <option value="28">28</option>
                                        <option value="29">29</option>
                                        <option value="30">30</option>
                                        <option value="31">31</option>
                                      </select>
                                    </div>&nbsp; <div class="col">
                                      <select name="attendeeType[<?= $count ?>][DOBMonth]" id="DATEOFBIRTH_Month" class="form-control">
                                        <option value=""></option>
                                        <option value="Jan">Jan</option>
                                        <option value="Feb">Feb</option>
                                        <option value="Mar">Mar</option>
                                        <option value="Apr">Apr</option>
                                        <option value="May">May</option>
                                        <option value="Jun">Jun</option>
                                        <option value="Jul">Jul</option>
                                        <option value="Aug">Aug</option>
                                        <option value="Sep">Sep</option>
                                        <option value="Oct">Oct</option>
                                        <option value="Nov">Nov</option>
                                        <option value="Dec">Dec</option>
                                      </select>
                                    </div>&nbsp; <div class="col">
                                      <select name="attendeeType[<?= $count ?>][DOBYear]" id="DATEOFBIRTH_Year" class="form-control">
                                        <option selected="selected" value=""></option>
                                        <option value="2022">2022</option>
                                        <option value="2021">2021</option>
                                        <option value="2020">2020</option>
                                        <option value="2019">2019</option>
                                        <option value="2018">2018</option>
                                        <option value="2017">2017</option>
                                        <option value="2016">2016</option>
                                        <option value="2015">2015</option>
                                        <option value="2014">2014</option>
                                        <option value="2013">2013</option>
                                        <option value="2012">2012</option>
                                        <option value="2011">2011</option>
                                        <option value="2010">2010</option>
                                        <option value="2009">2009</option>
                                        <option value="2008">2008</option>
                                        <option value="2007">2007</option>
                                        <option value="2006">2006</option>
                                        <option value="2005">2005</option>
                                        <option value="2004">2004</option>
                                        <option value="2003">2003</option>
                                        <option value="2002">2002</option>
                                        <option value="2001">2001</option>
                                        <option value="2000">2000</option>
                                        <option value="1999">1999</option>
                                        <option value="1998">1998</option>
                                        <option value="1997">1997</option>
                                        <option value="1996">1996</option>
                                        <option value="1995">1995</option>
                                        <option value="1994">1994</option>
                                        <option value="1993">1993</option>
                                        <option value="1992">1992</option>
                                        <option value="1991">1991</option>
                                        <option value="1990">1990</option>
                                        <option value="1989">1989</option>
                                        <option value="1988">1988</option>
                                        <option value="1987">1987</option>
                                        <option value="1986">1986</option>
                                        <option value="1985">1985</option>
                                        <option value="1984">1984</option>
                                        <option value="1983">1983</option>
                                        <option value="1982">1982</option>
                                        <option value="1981">1981</option>
                                        <option value="1980">1980</option>
                                        <option value="1979">1979</option>
                                        <option value="1978">1978</option>
                                        <option value="1977">1977</option>
                                        <option value="1976">1976</option>
                                        <option value="1975">1975</option>
                                        <option value="1974">1974</option>
                                        <option value="1973">1973</option>
                                        <option value="1972">1972</option>
                                        <option value="1971">1971</option>
                                        <option value="1970">1970</option>
                                        <option value="1969">1969</option>
                                        <option value="1968">1968</option>
                                        <option value="1967">1967</option>
                                        <option value="1966">1966</option>
                                        <option value="1965">1965</option>
                                        <option value="1964">1964</option>
                                        <option value="1963">1963</option>
                                        <option value="1962">1962</option>
                                        <option value="1961">1961</option>
                                        <option value="1960">1960</option>
                                        <option value="1959">1959</option>
                                        <option value="1958">1958</option>
                                        <option value="1957">1957</option>
                                        <option value="1956">1956</option>
                                        <option value="1955">1955</option>
                                        <option value="1954">1954</option>
                                        <option value="1953">1953</option>
                                        <option value="1952">1952</option>
                                        <option value="1951">1951</option>
                                        <option value="1950">1950</option>
                                        <option value="1949">1949</option>
                                        <option value="1948">1948</option>
                                        <option value="1947">1947</option>
                                        <option value="1946">1946</option>
                                        <option value="1945">1945</option>
                                        <option value="1944">1944</option>
                                        <option value="1943">1943</option>
                                        <option value="1942">1942</option>
                                        <option value="1941">1941</option>
                                        <option value="1940">1940</option>
                                        <option value="1939">1939</option>
                                        <option value="1938">1938</option>
                                        <option value="1937">1937</option>
                                        <option value="1936">1936</option>
                                        <option value="1935">1935</option>
                                        <option value="1934">1934</option>
                                        <option value="1933">1933</option>
                                        <option value="1932">1932</option>
                                        <option value="1931">1931</option>
                                        <option value="1930">1930</option>
                                        <option value="1929">1929</option>
                                        <option value="1928">1928</option>
                                        <option value="1927">1927</option>
                                        <option value="1926">1926</option>
                                        <option value="1925">1925</option>
                                        <option value="1924">1924</option>
                                        <option value="1923">1923</option>
                                        <option value="1922">1922</option>
                                        <option value="1921">1921</option>
                                        <option value="1920">1920</option>
                                        <option value="1919">1919</option>
                                        <option value="1918">1918</option>
                                        <option value="1917">1917</option>
                                        <option value="1916">1916</option>
                                        <option value="1915">1915</option>
                                        <option value="1914">1914</option>
                                        <option value="1913">1913</option>
                                        <option value="1912">1912</option>
                                        <option value="1911">1911</option>
                                        <option value="1910">1910</option>
                                        <option value="1909">1909</option>
                                        <option value="1908">1908</option>
                                        <option value="1907">1907</option>
                                        <option value="1906">1906</option>
                                        <option value="1905">1905</option>
                                        <option value="1904">1904</option>
                                        <option value="1903">1903</option>
                                        <option value="1902">1902</option>
                                        <option value="1901">1901</option>
                                        <option value="1900">1900</option>
                                      </select>
                                    </div>
                                  </span>
                                </div>
                              </div>
                              <div id="GENDER_d" class="tqRow form-group">
                                <label for="GENDER">gender</label>
                                <select name="attendeeType[<?= $count ?>][gender]" id="GENDER" class="form-control">
                                  <option selected="selected" value=""></option>
                                  <option value="Female">Female</option>
                                  <option value="Male">Male</option>
                                  <option value="Other">Other</option>
                                </select>
                              </div>
                              <div id="REGION_d" class="tqRow form-group">
                                <label for="REGION">local authority</label>
                                <select name="attendeeType[<?= $count ?>][region]" id="REGION" class="form-control regionselector">
                                  <option selected="selected" value=""></option>
                                  <option value="Aberdeen City Council">Aberdeen City Council</option>
                                  <option value="Aberdeenshire Council">Aberdeenshire Council</option>
                                  <option value="Angus Council">Angus Council</option>
                                  <option value="Argyll and Bute Council">Argyll and Bute Council</option>
                                  <option value="Clackmannanshire Council">Clackmannanshire Council</option>
                                  <option value="Comhairle nan Eilean Siar (Western Isles Council)">Comhairle nan Eilean Siar (Western Isles Council)</option>
                                  <option value="Dumfries and Galloway Council">Dumfries and Galloway Council</option>
                                  <option value="Dundee City Council">Dundee City Council</option>
                                  <option value="East Ayrshire Council">East Ayrshire Council</option>
                                  <option value="East Dunbartonshire Council">East Dunbartonshire Council</option>
                                  <option value="East Lothian Council">East Lothian Council</option>
                                  <option value="East Renfrewshire Council">East Renfrewshire Council</option>
                                  <option value="Edinburgh City Council">Edinburgh City Council</option>
                                  <option value="Falkirk Council">Falkirk Council</option>
                                  <option value="Fife Council">Fife Council</option>
                                  <option value="Glasgow City Council">Glasgow City Council</option>
                                  <option value="Highland Council">Highland Council</option>
                                  <option value="Inverclyde Council">Inverclyde Council</option>
                                  <option value="Midlothian Council">Midlothian Council</option>
                                  <option value="Moray Council">Moray Council</option>
                                  <option value="North Ayrshire Council">North Ayrshire Council</option>
                                  <option value="North Lanarkshire Council">North Lanarkshire Council</option>
                                  <option value="Orkney Islands Council">Orkney Islands Council</option>
                                  <option value="Other (Not Scotland)">Other (Not Scotland)</option>
                                  <option value="Perth and Kinross Council">Perth and Kinross Council</option>
                                  <option value="Renfrewshire Council">Renfrewshire Council</option>
                                  <option value="Scottish Borders Council">Scottish Borders Council</option>
                                  <option value="Shetland Islands">Shetland Islands</option>
                                  <option value="South Ayrshire Council">South Ayrshire Council</option>
                                  <option value="South Lanarkshire Council">South Lanarkshire Council</option>
                                  <option value="Stirling Council">Stirling Council</option>
                                  <option value="West Dunbartonshire">West Dunbartonshire</option>
                                  <option value="West Dunbartonshire Council">West Dunbartonshire Council</option>
                                  <option value="West Lothian Council">West Lothian Council</option>
                                </select>
                              </div>
                             
                              <div id="SPECIALNEEDS_d" class="tqRow form-group">
                                <label for="SPECIALNEEDS">special requirements <br>
                                  <small>Let us know if there is anything we can do to make your attendance at this event easier.</small>
                                </label>
                                <textarea name="attendeeType[<?= $count ?>][specialNeeds]" rows="3" cols="30" id="SPECIALNEEDS" class="form-control" wm_ignoreifnovalue="true" wm_summmarybehaviour2="1" summarybehaviour="ignore" onkeydown="if (this.value.length > 255) {this.value = this.value.substring(0, 255)};" onkeyup="if (this.value.length > 255) {this.value = this.value.substring(0, 255)};" onblur="if (this.value.length > 255) {this.value = this.value.substring(0, 255)};"></textarea>
                              </div>
                            <?php //set the heading dependent upon the eventName 
                            $trainingCourseText = "";
                            $workshopText ="";
                            $seminarText="";
                            $activityText ="";
                            $accomodationText="";
                                if (strpos($event->eventName, "Mini") !== false or strpos($event->eventName, "Summer School") !== false) {
                                    $trainingCourseText = $event->trainingCourses[0]->notes;
                                    $workshopText = $event->workshops[0]->notes;
                                    $seminarText = $event->seminars[0]->notes;
                                    $activityText = $event->activities[0]->notes;
                                    $accomodationText = $event->accommodation[0]->notes;
                                } else {
                                    $trainingCourseText = "Training Course";
                                    $workshopText = "Workshops";
                                    $seminarText = "Seminars";
                                    $activityText = "Activities";
                                    $accomodationText = "Accommodation";
                                }
                            
                            ?>
                              <span id="TicketType"></span>
                              <input type="hidden" id="TicketType" name="TicketType" value="<?= $attendeeType->attendeeType ?>">
                              <div id="structureHolder">
                                <h4 class="MMM_rules"></h4>
                             <?php if (!empty($event->trainingCourses)) { ?>
                                <div class="eventStructureDiv structureActivity">
                                  <div></div>
                                  <h4><?= $trainingCourseText ?></h4>
                                  <p class="structureActivity_rules error"></p>
                                  <table id="structureActivity_eventStructure" class="cbList form-check tqRow" border="0">
                                    <tbody>
                                        <?php foreach($event->trainingCourses as $course) { ?> 
                                      <tr>
                                        <td>                                           
                                          <input id="structureActivity_eventStructure_0" data-description="<?= $course->description ?>"  data-structureId="<?= $course->structureId ?>" data-remaining="<?= $course->placesRemaining ?>" value="<?= $course->costs[0]->value?>" type="checkbox" name="attendeeType[<?= $count ?>][Structure][<?= $course->structureId ?>]" class="form-check-input" >
                                          <label for="structureActivity_eventStructure_0" class="form-check-label"><?= $course->description ?> <small> - <strong><?= $course->StartDate ?> </strong> <?= $course->startTime ?> - <?= $course->endTime ?> - <?php ($course->costs[0]->value >0)? print "&pound;". $course->costs[0]->value:"" ?> </small> 
                                          </label>
                                        </td>
                                      </tr>
                                      <?php } ?>
                                    </tbody>
                                  </table>
                                  <span id="structureWorkshop_eventStructureSummaryHelper"></span>
                                </div>
                                  <?php } ?>

                                         <?php if (!empty($event->workshops)) { ?>
                                <div class="eventStructureDiv structureWorkshop">
                                  <div></div>
                                  <h4><?= $workshopText ?></h4>
                                  <p class="structureWorkshop_rules error"></p>
                                  <table id="structureWorkshop_eventStructure" class="cbList form-check tqRow" border="0">
                                    <tbody>
                                        <?php foreach($event->workshops as $workshop) {
                                                  $workshop = new Workshops($workshop); ?> 
                                      <tr>
                                        <td>
                                          <input id="structureWorkshop_eventStructure_0" data-description="<?= $workshop->description ?>"  data-structureId="<?= $workshop->structureId ?>" data-remaining="<?= $workshop->placesRemaining ?>" value="<?= $workshop->costs[0]->value?>" type="checkbox" name="attendeeType[<?= $count ?>][Structure][<?= $workshop->structureId ?>]" class="form-check-input" >
                                          <label for="structureWorkshop_eventStructure_0" class="form-check-label"><?= $workshop->description ?> <small> - <strong><?= $workshop->StartDate ?> </strong> <?= $workshop->startTime ?> - <?= $workshop->endTime ?> - <?php ($workshop->costs[0]->value >0)? print "&pound;". $workshop->costs[0]->value:"" ?></small> 
                                          </label>
                                        </td>
                                      </tr>
                                      <?php } ?>
                                    </tbody>
                                  </table>
                                  <span id="structureWorkshop_eventStructureSummaryHelper"></span>
                                </div>
                                  <?php } ?>

                                            <?php if (!empty($event->seminars)) { ?>
                                <div class="eventStructureDiv structureSeminar">
                                  <div></div>
                                  <h4><?= $seminarText ?></h4>
                                  <p class="structureSemi_rules error"></p>
                                  <table id="structureSeminar_eventStructure" class="cbList form-check tqRow" border="0">
                                    <tbody>
                                        <?php foreach($event->seminars as $item) {
                                                  $item = new Seminars($item); ?> 
                                      <tr>
                                        <td>
                                          <input id="structureSeminar_eventStructure_0" data-description="<?= $item->description ?>" data-structureId="<?= $item->structureId ?>" data-remaining="<?= $item->placesRemaining ?>" value="<?= $item->costs[0]->value?>" type="checkbox" name="attendeeType[<?= $count ?>][Structure][<?= $item->structureId ?>]" class="form-check-input" >
                                          <label for="structureSeminar_eventStructure_0" class="form-check-label"><?= $item->description ?> <small> - <strong><?= $item->startDate ?> </strong> <?= $item->startTime ?> - <?= $item->endTime ?> - <?php ($item->costs[0]->value >0)? print "&pound;". $item->costs[0]->value:"" ?></small> 
                                          </label>
                                        </td>
                                      </tr>
                                      <?php } ?>
                                    </tbody>
                                  </table>
                                  <span id="structureSeminar_eventStructureSummaryHelper"></span>
                                </div>
                                  <?php } ?>

                                            <?php if (!empty($event->activities)) { ?>
                                <div class="eventStructureDiv structureActivity">
                                  <div></div>
                                  <h4><?= $activityText ?></h4>
                                  <p class="structureSemi_rules error"></p>
                                  <table id="structureActivity_eventStructure" class="cbList form-check tqRow" border="0">
                                    <tbody>
                                        <?php foreach($event->activities as $item) {
                                                  $item = new Activities($item); ?> 
                                      <tr>
                                        <td>
                                          <input id="structureActivity_eventStructure_0" data-description="<?= $item->description ?>"  data-structureId="<?= $item->structureId ?>" data-remaining="<?= $item->placesRemaining ?>" value="<?= $item->costs[0]->value?>" type="checkbox" name="attendeeType[<?= $count ?>][Structure][<?= $item->structureId ?>]" class="form-check-input" >
                                          <label for="structureActivity_eventStructure_0" class="form-check-label"><?= $item->description ?> <small> - <strong><?= $item->StartDate ?> </strong> <?= $item->startTime ?> - <?= $item->endTime ?> - <?php ($item->costs[0]->value >0)? print "&pound;". $item->costs[0]->value:"" ?></small> 
                                          </label>
                                        </td>
                                      </tr>
                                      <?php } ?>
                                    </tbody>
                                  </table>
                                  <span id="structureActivity_eventStructureSummaryHelper"></span>
                                </div>
                                  <?php } ?>

                                    <?php if (!empty($event->accommodation)) { ?>
                                <div class="eventStructureDiv structureAccommodation">
                                  <div></div>
                                      <h4><?= $accomodationText ?></h4>
                                  <p class="structureSemi_rules error"></p>
                                  <table id="structureAccommodation_eventStructure" class="cbList form-check tqRow" border="0">
                                    <tbody>
                                        <?php foreach($event->accommodation as $item) {
                                                  $item = new Accommodation($item);  ?> 
                                      <tr>
                                        <td>
                                          <input id="structureAccommodation_eventStructure_0" data-description="<?= $item->description ?>"  data-structureId="<?= $item->structureId ?>" data-remaining="<?= $item->placesRemaining ?>" value="<?= $item->costs[0]->value?>" type="checkbox" name="attendeeType[<?= $count ?>][Structure][<?= $item->structureId ?>]" class="form-check-input" >
                                          <label for="structureAccommodation_eventStructure_0" class="form-check-label"><?= $item->description ?> <small> - <strong><?= $item->StartDate ?> </strong> <?= $item->startTime ?> - <?= $item->endTime ?> - <?php ($item->costs[0]->value >0)? print "&pound;". $item->costs[0]->value:"" ?></small> 
                                          </label>
                                        </td>
                                      </tr>
                                      <?php } ?>
                                    </tbody>
                                  </table>
                                  <span id="structureAccommodation_eventStructureSummaryHelper"></span>
                                </div>
                                  <?php } ?>  
  
                              </div>
                            </fieldset>

                          <?php    }
                          } ?>

                    <br />
                    <fieldset>
                        <legend></legend>
                        <div class="submit">
                            <button type="button" onclick="window.location.reload(true);" id="prevButton" class="btn btn-primary">Back</button>
                            <button type="submit" name="ctl00$cp1$NEXT1" value="continue" id="nextButton" class="btn btn-primary" >Next</button>     
                        </div>
                    </fieldset>
                    <div id="pageErrorContainer"></div>

                </main>
 <script>
     document.getElementById("pageForm").onsubmit = function (e) {
         var checkboxes = document.querySelectorAll('.isMainCheckClass:checked');
         if (checkboxes.length > 1) {
               e.preventDefault();
               document.getElementById("pageErrorContainer").innerHTML = "You can only choose one attendee as yourself";         
         }
     }

     document.getElementById("isMain").onclick = function (e) {
         var check = document.getElementById('isMain');
         var firstNameContainer = div.nextSibling; //first div
         var secondNameContainer = firstNameContainer.nextSibling; //second div

         if (firstNameContainer.style.display === "none") {
             firstNameContainer.style.display = "block";
             secondNameContainer.style.display = "block";
         } else {
             firstNameContainer.style.display = "none";
             secondNameContainer.style.display = "none";
         }
     }

        document.getElementById("pageForm").onsubmit = function (form) {

            let totalRequired = +0;
            let structureArray = {};
            document.querySelectorAll('.form-check-input:checked').forEach(function(structure) {
                // Now do something with my button
                console.log(structure);
                if (!(structure.dataset.structureid in structureArray)) {
                    //set value to 1
                    structureArray[structure.dataset.structureid] = 1;
                } else {
                    structureArray[structure.dataset.structureid]++;
                }
                
                if (structureArray[structure.dataset.structureid] > structure.dataset.remaining) {
                    form.preventDefault();
                    document.getElementById("pageErrorContainer").innerHTML = "there are only "+structure.dataset.remaining+" "+structure.dataset.description+" left";   
                }
            });

       };
     
 </script>

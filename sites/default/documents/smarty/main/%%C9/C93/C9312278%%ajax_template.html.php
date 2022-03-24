<?php /* Smarty version 2.6.31, created on 2022-03-14 12:42:45
         compiled from default/views/week/ajax_template.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'config_load', 'default/views/week/ajax_template.html', 11, false),array('function', 'xla', 'default/views/week/ajax_template.html', 168, false),array('function', 'xlt', 'default/views/week/ajax_template.html', 168, false),array('modifier', 'date_format', 'default/views/week/ajax_template.html', 382, false),array('modifier', 'string_format', 'default/views/week/ajax_template.html', 383, false),)), $this); ?>
<?php echo smarty_function_config_load(array('file' => "default.conf"), $this);?>

<?php echo smarty_function_config_load(array('file' => "lang.".($this->_tpl_vars['USER_LANG'])), $this);?>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => ($this->_tpl_vars['TPL_NAME'])."/views/header.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php 
/* if you change these be sure to change their matching values in
 * the CSS for the calendar, found in interface/themes/ajax_calendar.css
 */
$timeslotHeightVal=20;
$timeslotHeightUnit="px";

$chevron_icon_left = $_SESSION['language_direction'] == 'ltr' ? 'fa-chevron-circle-left' : 'fa-chevron-circle-right';
$chevron_icon_right = $_SESSION['language_direction'] == 'ltr' ? 'fa-chevron-circle-right' : 'fa-chevron-circle-left';

 ?>

<script>
 // This is called from the event editor popup.
 function refreshme() {
  top.restoreSession();
  document.forms[0].submit();
 }

 function newEvt(startampm, starttimeh, starttimem, eventdate, providerid, catid) {
  dlgopen('add_edit_event.php?startampm=' + encodeURIComponent(startampm) +
   '&starttimeh=' + encodeURIComponent(starttimeh) + '&starttimem=' + encodeURIComponent(starttimem) +
   '&date=' + encodeURIComponent(eventdate) + '&userid=' + encodeURIComponent(providerid) + '&catid=' + encodeURIComponent(catid)
      ,'_blank', 780, 675, '', '', {onClosed: 'refreshme'});
 }

 function oldEvt(eventdate, eventid, pccattype) {
  dlgopen('add_edit_event.php?date=' + encodeURIComponent(eventdate) + '&eid=' + encodeURIComponent(eventid) + '&prov=' + encodeURIComponent(pccattype), '_blank', 780, 675);
 }

 function oldGroupEvt(eventdate, eventid, pccattype){
  top.restoreSession();
  dlgopen('add_edit_event.php?group=true&date=' + encodeURIComponent(eventdate) + '&eid=' + encodeURIComponent(eventid) + '&prov=' + encodeURIComponent(pccattype), '_blank', 780, 675);
 }

 function goPid(pid) {
  top.restoreSession();
<?php 
		 echo "  top.RTop.location = '../../patient_file/summary/demographics.php' " .
		   "+ '?set_pid=' + encodeURIComponent(pid);\n";
 ?>
 }

 function goGid(gid) {
  top.restoreSession();
<?php 
        echo "  top.RTop.location = '" . $GLOBALS['rootdir'] . "/therapy_groups/index.php' " .
        "+ '?method=groupDetails&group_id=' + encodeURIComponent(gid) \n ";
 ?>
 }


 function GoToToday(theForm){
  var todays_date = new Date();
  var theMonth = todays_date.getMonth() + 1;
  theMonth = theMonth < 10 ? "0" + theMonth : theMonth;
  theForm.jumpdate.value = todays_date.getFullYear() + "-" + theMonth + "-" + todays_date.getDate();
  top.restoreSession();
  theForm.submit();
 }

 function ShowImage(src)
 {
     var img = document.getElementById('popupImage');
     var div = document.getElementById('popup');
     img.src = src;
     div.style.display = "block";
 }

 function HideImage()
 {
     document.getElementById('popup').style.display = "none";
 }
</script>

<?php 

 // this is my proposed setting in the globals config file so we don't
 // need to mess with altering the pn database AND the config file
 //pnModSetVar(__POSTCALENDAR__, 'pcFirstDayOfWeek', $GLOBALS['schedule_dow_start']);

  $openhour= $GLOBALS['schedule_start'];
  $closehour= $GLOBALS['schedule_end'];

 // build a day-of-week (DOW) list so we may properly build the calendars later in this code
 $DOWlist = array();
 $tmpDOW = pnModGetVar(__POSTCALENDAR__, 'pcFirstDayOfWeek');
 // bound check and auto-correction
 if ($tmpDOW <0 || $tmpDOW >6) {
    pnModSetVar(__POSTCALENDAR__, 'pcFirstDayOfWeek', '0');
    $tmpDOW = 0;
 }
 while (count($DOWlist) < 7) {
    array_push($DOWlist, $tmpDOW);
    $tmpDOW++;
    if ($tmpDOW > 6) $tmpDOW = 0;
 }

 // A_CATEGORY is an ordered array of associative-array categories.
 // Keys of interest are: id, name, color, desc, event_duration.
 //
 // echo "<!-- A_CATEGORY = "; print_r($this->_tpl_vars['A_CATEGORY']); echo " -->\n"; // debugging
 // echo "<!-- A_EVENTS = "; print_r($this->_tpl_vars['A_EVENTS']); echo " -->\n"; // debugging

 $A_CATEGORY  =& $this->_tpl_vars['A_CATEGORY'];

 $A_EVENTS  =& $this->_tpl_vars['A_EVENTS'];
 $providers =& $this->_tpl_vars['providers'];
 $times     =& $this->_tpl_vars['times'];
 $interval  =  $this->_tpl_vars['interval'];
 $viewtype  =  $this->_tpl_vars['VIEW_TYPE'];
 $PREV_WEEK_URL = $this->_tpl_vars['PREV_WEEK_URL'];
 $NEXT_WEEK_URL = $this->_tpl_vars['NEXT_WEEK_URL'];
 $PREV_DAY_URL  = $this->_tpl_vars['PREV_DAY_URL'];
 $NEXT_DAY_URL  = $this->_tpl_vars['NEXT_DAY_URL'];
 $PREV_MONTH_URL = $this->_tpl_vars['PREV_MONTH_URL'];
 $NEXT_MONTH_URL = $this->_tpl_vars['NEXT_MONTH_URL'];

 $Date =  postcalendar_getDate();
 if (!isset($y)) $y = substr($Date, 0, 4);
 if (!isset($m)) $m = substr($Date, 4, 2);
 if (!isset($d)) $d = substr($Date, 6, 2);

 // echo "<!-- There are " . count($A_EVENTS) . " A_EVENTS days -->\n";

 //==================================
 //FACILITY FILTERING (CHEMED)
 $facilities = getUserFacilities($_SESSION['authUserID']); // from users_facility
 if ( $_SESSION['pc_facility'] ) {
    $provinfo = getProviderInfo('%', true, $_SESSION['pc_facility']);
 } else {
    $provinfo = getProviderInfo();
 }
 //EOS FACILITY FILTERING (CHEMED)
 //==================================

 ?>
<div id="wrapper">
<!-- stuff form element here to avoid the margin padding it introduces into the page in some browsers -->
<form name='theform' id='theform' action='index.php?module=PostCalendar&func=view&tplview=default&pc_category=&pc_topic=' method='post' onsubmit='return top.restoreSession()'>
<div class="container-fluid sticky-top">
<div id="topToolbarRight" class="bgcolor2">  <!-- this wraps some of the top toolbar items -->
<div id="functions">
  <a id="menu-toggle" href="#" class="btn btn-outline-dark"><i class="fas fa-bars"></i></a>
  <input type="hidden" name="jumpdate" id="jumpdate" value="" />
  <input type="hidden" name="viewtype" id="viewtype" value="<?php echo attr($viewtype); ?>" />
    <?php 
    echo "<a href='#' title='" . xla("New Appointment") . "' onclick='newEvt(1, 9, 00, " . attr_js($Date) . ", 0, 0)' class='btn btn-primary'><i class='fa fa-plus'></i></a>\n";
    echo "<a href='#' title='" . xla("Search Appointment") . "' onclick='top.restoreSession();location=\"index.php?module=PostCalendar&func=search\"' class='btn btn-primary'><i class='fa fa-search'></i></a>\n";
    if ($Date <> date('Ymd')) {
     ?>
    <a href='#' name='bnsubmit' value='<?php echo smarty_function_xla(array('t' => 'Today'), $this);?>
' onClick='GoToToday(theform);' class='btn btn-primary'><?php echo smarty_function_xlt(array('t' => 'Today'), $this);?>
</a>
    <?php 
    }
     ?>

</div>
<div id="dateNAV">
<?php 
echo "   <a id='prevweek' href='$PREV_WEEK_URL' onclick='top.restoreSession()' title='" . xla("Previous Week") . "'><i class='fa " . attr($chevron_icon_left) . " chevron_color'></i></a>\n";
$atmp = array_keys($A_EVENTS);
echo text(date('d', strtotime($atmp[0]))) . " " . xlt(date('M', strtotime($atmp[0]))) . " " . text(date('Y', strtotime($atmp[0])));
echo " - ";
echo text(date('d', strtotime($atmp[count($atmp)-1]))) . " " . xlt(date('M', strtotime($atmp[count($atmp)-1]))) . " " . text(date('Y', strtotime($atmp[count($atmp)-1])));
echo "   <a id='nextweek' href='$NEXT_WEEK_URL' onclick='top.restoreSession()' title='" . xla("Next Week") . "'><i class= 'fa " . attr($chevron_icon_right) . " chevron_color'></i></a>\n";
 ?>
</div>

<div id="viewPicker">
<?php 

echo "  <a href='#' id='printview' title='" . xla("View Printable Version") . "' class='btn btn-primary'><i class='fa fa-print'></i></a>\n";
echo "   <a href='#' title='" . xla("Refresh") . "' onclick='javascript:refreshme()' class='btn btn-primary'><i class='fa fa-sync'></i></a>\n";
echo "   <a href='#' type='button' id='dayview' title='" . xla('Day View') . "' class='btn btn-primary'>" . xlt('Day') . "</a>\n";
echo "   <a href='#' type='button' id='weekview' title='" . xla('Week View') . "' class='btn btn-primary'>" . xlt('Week') . "</a>\n";
echo "   <a href='#' type='button' id='monthview' title='" . xla('Month View') . "' class='btn btn-primary'>" . xlt('Month') . "</a>\n";
 ?>
</div>
</div> <!-- end topToolbarRight -->
</div>
<div class="sticky-top">
<div id="bottomLeft" class="sidebar-wrapper">
<div id="datePicker" class="table-responsive">
<?php 
$atmp = array_keys($A_EVENTS);
$caldate = strtotime($atmp[0]);
$cMonth = date("m", $caldate);
$cYear = date("Y", $caldate);
$cDay = date("d", $caldate);

    include_once($GLOBALS['fileroot'].'/interface/main/calendar/modules/PostCalendar/pntemplates/default/views/monthSelector.php');
 ?>

<table class='table table-sm table-borderless'>
<tbody><tr>
<?php 

// compute the previous month date
// stay on the same day if possible
$pDay = $cDay;
$pMonth = $cMonth - 1;
$pYear = $cYear;
if ($pMonth < 1) { $pMonth = 12; $pYear = $cYear - 1; }
while (! checkdate($pMonth, $pDay, $pYear)) { $pDay = $pDay - 1; }
$prevMonth = sprintf("%d%02d%02d",$pYear,$pMonth,$pDay);

// compute the next month
// stay on the same day if possible
$nDay = $cDay;
$nMonth = $cMonth + 1;
$nYear = $cYear;
if ($nMonth > 12) { $nMonth = 1; $nYear = $cYear + 1; }
while (! checkdate($nMonth, $nDay, $nYear)) { $nDay = $nDay - 1; }
$nextMonth = sprintf("%d%02d%02d",$nYear,$nMonth,$nDay);
 ?>
<td class="tdDOW-small tdDatePicker tdNav" id="<?php echo attr($prevMonth) ?>" title="<?php echo xla(date("F", strtotime($prevMonth))); ?>">&lt;</td>
<td colspan="5" class="tdMonthName-small">
<?php 
echo xlt(date('F', $caldate));
 ?>
</td>
<td class="tdDOW-small tdDatePicker tdNav" id="<?php echo attr($nextMonth) ?>" title="<?php echo xla(date("F", strtotime($nextMonth))); ?>">&gt;</td>
</tr><tr>
<?php 
foreach ($DOWlist as $dow) {
    echo "<td class='tdDOW-small'>" . text($this->_tpl_vars['A_SHORT_DAY_NAMES'][$dow]) . "</td>";
}
 ?>
</tr>
<?php 
$atmp = array_keys($A_EVENTS);
$caldate = strtotime($atmp[0]);
$caldateEnd = strtotime($atmp[6]);

// to make a complete week row we need to compute the real
// start and end dates for the view
list ($year, $month, $day) = explode(" ", date('Y m d', $caldate));
$startdate = strtotime($year.$month."01");
$enddate = strtotime($year.$month.date("t", $startdate)." 23:59");
while (date('w', $startdate) != $DOWlist[0]) { $startdate -= 60*60*24; }
while (date('w', $enddate) != $DOWlist[6]) { $enddate += 60*60*24; }

$currdate = $startdate;
while ($currdate <= $enddate) {
    if (date('w', $currdate) == $DOWlist[0]) {
        // start of week row
        $tr = "<tr class='trDateRow'>";
        echo $tr;
    }

    // set the TD class
    $tdClass = "tdMonthDay-small";
    if (date('m', $currdate) != $month) {
        $tdClass = "tdOtherMonthDay-small";
    }
    if (is_weekend_day(date('w', $currdate))) {
        $tdClass = "tdWeekend-small";
    }
    if (is_holiday(date('Y-m-d', $currdate))) {
        $tdClass = "tdHoliday-small";
    }

    if ((date('Ymd',$currdate) >= date('Ymd', $caldate)) &&
        (date('Ymd',$currdate) <= date('Ymd', $caldateEnd)))
    {
        // add a class that highlights the 'current date'
        $tdClass .= " currentWeek";
    }

    if (date('Ymd',$currdate) == $Date) {
        // $Date is defined near the top of this file
        // and is equal to whatever date the user has clicked
        $tdClass .= " currentDate";
    }

    // add a class so that jQuery can grab these days for the 'click' event
    $tdClass .= " tdDatePicker";

    // output the TD
    $td = "<td ";
    $td .= "class=\"" . attr($tdClass) . "\" ";
    //$td .= "id=\"" . attr(date("Ymd", $currdate)) . "\" ";
    $td .= "id=\"" . attr(date("Ymd", $currdate)) . "\" ";
    $td .= "title=\"" . xla('Go to week of') . " " . attr(date('M d, Y', $currdate)) . "\" ";
    $td .= "> " . text(date('d', $currdate)) . "</td>\n";
    echo $td;

    // end of week row
    if (date('w', $currdate) == $DOWlist[6]) echo "</tr>\n";

    // time correction = plus 1000 seconds, for some unknown reason
    //$currdate += (60*60*24)+1000;

    ////////
    $currdate = strtotime("+1 day", $currdate);
    ////////
}
 ?>
</tbody>
</table>
</div>

<div id="bigCalHeader">
</div>

<div id="providerPicker">
<?php  echo xlt('Providers');  ?>
<div>
<?php 
// ==============================
// FACILITY FILTERING (lemonsoftware)
// $facilities = getFacilities();
if (!empty($_SESSION['authorizeduser']) && ($_SESSION['authorizeduser'] == 1)) {
  $facilities = getFacilities();
} else {
  $facilities = getUserFacilities($_SESSION['authUserID']); // from users_facility
 	if (count($facilities) == 1)
    OpenEMR\Common\Session\SessionUtil::setSession('pc_facility', key($facilities));
}
if (count($facilities) > 1) {
    echo "   <select name='pc_facility' id='pc_facility'  class='view1 form-control' >\n";
    if ( !$_SESSION['pc_facility'] ) $selected = "selected='selected'";
    // echo "    <option value='0' $selected>" . xlt('All Facilities') . "</option>\n";
    if (!$GLOBALS['restrict_user_facility']) echo "    <option value='0' $selected>" . xlt('All Facilities') . "</option>\n";
    foreach ($facilities as $fa) {
        $selected = ( $_SESSION['pc_facility'] == $fa['id']) ? "selected='selected'" : "" ;
        echo "    <option class='bg-info' value='" . attr($fa['id']). "' $selected>" . text($fa['name']). "</option>\n";
    }
    echo "   </select>\n";
}
// EOS FF
// ==============================
 echo "</div>";
 echo "   <select multiple size='5' name='pc_username[]' id='pc_username' class='view2 form-control'>\n";
 echo "    <option value='__PC_ALL__'>" . xlt("All Users") . "</option>\n";
 foreach ($provinfo as $doc) {
  $username = $doc['username'];
  echo "    <option value='" . attr($username) . "'";
  foreach ($providers as $provider)
   if ($provider['username'] == $username) echo " selected";
  echo ">" . text($doc['lname']) . ", " . text($doc['fname']) . "</option>\n";
 }
 echo "   </select>\n";

 ?>
</div>
<?php 
if($_SESSION['pc_facility'] == 0){
 ?>
<div id="facilityColor" class="table-responsive">
 <table class="table table-borderless">
<?php 
foreach ($facilities as $f){
echo "   <tr>\n";
echo "   <td>\n";
echo "   <div class='view1 font-weight-bold text-ovr-dark p-1 rounded' style='background-color:".$f['color'].";'>" . text($f['name'])."</div>";
echo "   </td>\n";
echo "   </tr>\n";
}
 ?>
 </table>
</div>
<?php 
}
 ?>
<?php $this->assign('dayname', ((is_array($_tmp=$this->_tpl_vars['DATE'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%w") : smarty_modifier_date_format($_tmp, "%w"))); ?>
<?php $this->assign('day', ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['DATE'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%d") : smarty_modifier_date_format($_tmp, "%d")))) ? $this->_run_mod_handler('string_format', true, $_tmp, "%1d") : smarty_modifier_string_format($_tmp, "%1d"))); ?>
<?php $this->assign('month', ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['DATE'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%m") : smarty_modifier_date_format($_tmp, "%m")))) ? $this->_run_mod_handler('string_format', true, $_tmp, "%1d") : smarty_modifier_string_format($_tmp, "%1d"))); ?>
<?php $this->assign('year', ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['DATE'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%Y") : smarty_modifier_date_format($_tmp, "%Y")))) ? $this->_run_mod_handler('string_format', true, $_tmp, "%4d") : smarty_modifier_string_format($_tmp, "%4d"))); ?>

<div id="popup" class="pop-up">
<img id="popupImage" alt="" />
</div>
</div><!-- end bottomLeft -->
</div>

<div class="page-content-wrapper">
<div class="container-fluid calsearch_body">
<div id="bigCal">
<?php 
/* used in debugging
foreach ($A_EVENTS as $date => $events) {
    echo $date." = ";
    foreach ($events as $oneE) {
        echo text(print_r($oneE, true));
        echo "<br /><br />";
    }
    echo "<hr class='w-100'>";
}
*/


// This loops once for each provider to be displayed.
//
foreach ($providers as $provider) {
    $providerid = $provider['id'];

    // to specially handle the IN/OUT events I'm doing something new here
    // for each IN event it will have a duration lasting until the next
    // OUT event or until the end of the day
    $tmpTime = $times[0];
    $calStartMin = ($tmpTime['hour'] * 60) + $tmpTime['minute'];
    $tmpTime = $times[count($times)-1];
    $calEndMin = ($tmpTime['hour'] * 60) + $tmpTime['minute'];

    echo "<div class='table-responsive'>\n";
    echo "<table class='table border-0'>\n";
    echo " <tr>\n";
    echo "  <td colspan='8' class='providerheader'>";
    echo text($provider['fname']) . " " . text($provider['lname']);
    echo "</td>\n";
    echo " </tr>\n";

    // output column (date) headers
    $colWidth = 100/7; // intentionally '7' and not '8'
    echo " <tr>\n";
    echo " <td>&nbsp;</td>"; // blank TD for the header above the Times column
    $defaultDate = ""; // used when creating link for a 'new' event
    $in_cat_id = 0; // used when creating link for a 'new' event
    foreach ($A_EVENTS as $date => $events) {
        $dateFmt = date("Ymd", strtotime($date));
        $gotoURL = pnModURL(__POSTCALENDAR__,'user','view',
                        array('tplview'=>($template_view ?? null),
                        'viewtype'=>'day',
                        'Date'=> $dateFmt,
                        'pc_username'=>($pc_username ?? null),
                        'pc_category'=>($category ?? null),
                        'pc_topic'=>($topic ?? null)));
        if ($defaultDate == "") $defaultDate = $dateFmt;
        $currclass = "";
        if ($Date == $dateFmt) { $currclass= "week_currday"; }
        echo "<td class='text-center week_dateheader " . attr($currclass) . "' style='width:".$colWidth."%;' >";
        echo "<a href='$gotoURL'>";
        echo xlt(date("D", strtotime($date))) . " " . text(oeFormatShortDate($date, false));
        echo "</a></td>";
    }
    echo " </tr>\n";

    // output the TD with the times DIV
    echo "<tr>";
    echo "<td id='times'><div class='table-responsive'><table class='table'>\n";
//==================================================================================================================
     if ($GLOBALS['time_display_format'] == 1) {
         $timeformat = 12;
     } else {
         $timeformat = 0;
     }


    foreach ($times as $slottime) {
        $startampm = ($slottime['mer']) == "pm" ? 2 : 1;
        $starttimeh = $slottime['hour'];
        $disptimeh = ($starttimeh > $timeformat) ? ($starttimeh - $timeformat) : $starttimeh;
        $starttimem = $slottime['minute'];
        $slotendmins = $starttimeh * 60 + $starttimem + $interval;

        echo "<tr><td class='timeslot'>";
//         echo "<a href='javascript:newEvt(" . attr_js($startampm) . "," . attr_js($starttimeh) . "," . attr_js($starttimem) . "," . attr_js($Date) . "," . attr_js($providerid) . "," . attr_js($in_cat_id) . ")' title='New Appointment' alt='New Appointment'>";
        echo "<a href='javascript:newEvt(" . attr_js($startampm) . "," . attr_js($starttimeh) . "," . attr_js($starttimem) . "," . attr_js($defaultDate) . "," . attr_js($providerid) . "," . attr_js($in_cat_id) . ")' title='" . xla("New Appointment") . "' alt='" . xla("New Appointment") . "'>";

        //echo $slottime['hour'] * 60 + $slottime['minute'];
        echo text($disptimeh.":".$starttimem) . "</a>";
        echo "</td></tr>\n";
    }
    echo "</table></div></td>";

    // For each day...
    // output a TD with an inner containing DIV positioned 'relative'
    // within that DIV we place our event DIVs using 'absolute' positioning
    foreach ($A_EVENTS as $date => $events) {
        $eventdate = substr($date, 0, 4) . substr($date, 5, 2) . substr($date, 8, 2);

        // having a 'title' for the TD makes the date appear by the mouse pointer
        // this is nice when all you see are times on the left side and no head
        // row with the dates or day-of-week (DOW)
        $headDate=strtotime($date);
        $classForWeekend = is_weekend_day(date('w',$headDate)) ? 'weekend-day' : 'work-day';
        echo "<td class='schedule " . attr($classForWeekend) . "' title='" . attr(date("l, d M Y",$headDate )) . "' date='" . attr(date("Ymd",$headDate )) . "' provider='" . attr($providerid) . "'>";
            echo "<div class='calendar_day'>";

        // determine if events overlap and adjust their width and left position as needed
        // 26 Feb 2008 - This needs fine tuning or total replacement
        //             - it doesn't work as well as I'd like - JRM
        $eventPositions = array();
        foreach ($times as $slottime) {
            $starttimeh = $slottime['hour'];
            $starttimem = $slottime['minute'];

            $slotstartmins = $starttimeh * 60 + $starttimem;
            $slotendmins = $starttimeh * 60 + $starttimem + $interval;

            $events_in_timeslot = array();
            foreach ($events as $e1) {
                // ignore IN and OUT events
                if (!empty($e1['catid']) && (($e1['catid'] == 2) || ($e1['catid'] == 3))) { continue; }
                // skip events without an ID (why they are in the loop, I have no idea)
                if (empty($e1['eid'])) { continue; }
                // skip events for other providers
                if ($providerid != $e1['aid']) { continue; }

                // specially handle all-day events
                if ($e1['alldayevent'] == 1) {
                    $tmpTime = $times[0];
                    if (strlen($tmpTime['hour']) < 2) { $tmpTime['hour'] = "0".$tmpTime['hour']; }
                    if (strlen($tmpTime['minute']) < 2) { $tmpTime['minute'] = "0".$tmpTime['minute']; }
                    $e1['startTime'] = $tmpTime['hour'].":".$tmpTime['minute'].":00";
                    $e1['duration'] = ($calEndMin - $calStartMin) * 60;  // measured in seconds
                }

                // create a numeric start and end for comparison
                $starth = substr($e1['startTime'], 0, 2);
                $startm = substr($e1['startTime'], 3, 2);
                $e1Start = ($starth * 60) + $startm;
                $e1End = $e1Start + $e1['duration']/60;

                // three ways to overlap:
                // start-in, end-in, span
                if ((($e1Start >= $slotstartmins) && ($e1Start < $slotendmins)) // start-in
                   || (($e1End > $slotstartmins) && ($e1End <= $slotendmins)) // end-in
                   || (($e1Start < $slotstartmins) && ($e1End > $slotendmins))) // span
                {
                    array_push($events_in_timeslot, $e1['eid']);
                }
            }

            $leftpos = 0;
            $width = 100;
            if (!empty($events_in_timeslot)) {
                $width = 100 / count($events_in_timeslot);

                // loop over the events in this timeslot and adjust their width
                foreach ($events_in_timeslot as $eid) {
                    $eventPositions[$eid] = new stdClass();

                    // set the width if not already set or if the current width is smaller
                    // than was was previously set
                    if (! isset($eventPositions[$eid]->width)) { $eventPositions[$eid]->width = $width; }
                    else if ($eventPositions[$eid]->width > $width) { $eventPositions[$eid]->width = $width; }

                    // set the left position if not already set or if the current left is
                    // greater than what was previously set
                    if (! isset($eventPositions[$eid]->leftpos)) { $eventPositions[$eid]->leftpos = $leftpos; }
                    else if ($eventPositions[$eid]->leftpos < $leftpos) { $eventPositions[$eid]->leftpos = $leftpos; }

                    // increment the leftpos by the width
                    $leftpos += $width;
                }
            }

        } // end overlap detection

        // now loop over the events for the day and output their DIVs
        foreach ($events as $event) {
                // skip events for other providers
            // yeah, we've got that sort of overhead here... it ain't perfect
            // $event['aid']!=0 :With the holidays we included clinic events, they have provider id =0
            // we dont want to exclude those events from being displayed
            if (!empty($event['aid']) && ($providerid != $event['aid'])) { continue; }

            // skip events without an ID (why they are in the loop, I have no idea)
            if (empty($event['eid'])) { continue; }

            // specially handle all-day events
            if ($event['alldayevent'] == 1) {
                $tmpTime = $times[0];
                if (strlen($tmpTime['hour']) < 2) { $tmpTime['hour'] = "0".$tmpTime['hour']; }
                if (strlen($tmpTime['minute']) < 2) { $tmpTime['minute'] = "0".$tmpTime['minute']; }
                $event['startTime'] = $tmpTime['hour'].":".$tmpTime['minute'].":00";
                $event['duration'] = ($calEndMin - $calStartMin) * 60; // measured in seconds
            }

            // figure the start time and minutes (from midnight)
            $starth = substr($event['startTime'], 0, 2);

            $startm = substr($event['startTime'], 3, 2);
            $eStartMin = $starth * 60 + $startm;
            $dispstarth = ($starth > 12) ? ($starth - $timeformat) : $starth;

            // determine the class for the event DIV based on the event category
            $evtClass = "event_appointment";

            //fix bug 456 and 455
            //check to see if the event is in the clinic hours range, if not it will not be displayed
            if  ( (int)$starth < (int)$openhour || (int)$starth > (int)$closehour ) { continue; }

            switch ($event['catid']) {
                case 1:  // NO-SHOW appt
                    $evtClass = "event_noshow";
                    break;
                case 2:  // IN office
                    $evtClass = "event_in";
                    break;
                case 3:  // OUT of office
                    $evtClass = "event_out";
                    break;
                case 4:  // VACATION
                    $evtClass = "event_reserved";
                    break;
                case 6:  // HOLIDAY
                    $evtClass = "event_holiday";
                    break;
                case 8:  // LUNCH
                    $evtClass = "event_reserved";
                    break;
                case 11: // RESERVED
                    $evtClass = "event_reserved";
                    break;
                case 99: // HOLIDAY
                $evtClass = "event_holiday";
                break;
                default: // some appointment
                    $evtClass = "event_appointment";
                    break;
            }

            // if this is an IN or OUT event then we have some extra special
            // processing to be done
            // the IN event creates a DIV until the OUT event
            // or, without an OUT DIV matching the IN event
            // then the IN event runs until the end of the day
            if ($event['catid'] == 2) {
                // locate a matching OUT for this specific IN
                $found = false;
                $outMins = 0;
                foreach ($events as $outevent) {
                    // skip events for other providers
                    if (!empty($outevent['aid']) && ($providerid != $outevent['aid'])) { continue; }
                    // skip events with blank IDs
                    if (empty($outevent['eid'])) { continue; }

                    if ($outevent['eid'] == $event['eid']) { $found = true; continue; }
                    if (($found == true) && ($outevent['catid'] == 3)) {
                        // calculate the duration from this event to the outevent
                        $outH = substr($outevent['startTime'], 0, 2);
                        $outM = substr($outevent['startTime'], 3, 2);
                        $outMins = ($outH * 60) + $outM;
                        $event['duration'] = ($outMins - $eStartMin) * 60; // duration is in seconds
                        $found = 2;
                        break;
                    }
                }
                if ($outMins == 0) {
                    // no OUT was found so this event's duration goes
                    // until the end of the day
                    $event['duration'] = ($calEndMin - $eStartMin) * 60; // duration is in seconds
                }
            }

            // calculate the TOP value for the event DIV
            // diff between event start and schedule start
            $eMinDiff = $eStartMin - $calStartMin;
            // diff divided by the time interval of the schedule
            $eStartInterval = $eMinDiff / $interval;
            // times the interval height
            $eStartPos = $eStartInterval * $timeslotHeightVal;
            $evtTop = $eStartPos.$timeslotHeightUnit;
            // calculate the HEIGHT value for the event DIV
            // diff between end and start of event
            $eEndMin = $eStartMin + ($event['duration']/60);
            // prevent the overall height of the event from going beyond the bounds
            // of the time table
            if ($eEndMin > $calEndMin) { $eEndMin = $calEndMin + $interval; }
            $eMinDiff = $eEndMin - $eStartMin;
            // diff divided by the time interval of the schedule
            $eEndInterval = $eMinDiff / $interval;
            // times the interval height
            $eHeight = $eEndInterval * $timeslotHeightVal;
            $evtHeight = $eHeight.$timeslotHeightUnit;

            // determine the DIV width based on any overlapping events
            // see further above for the overlapping calculation code
            $divWidth = "";
            $divLeft = "";
            if (isset($eventPositions[$event['eid']])) {
                $divWidth = "width: ".$eventPositions[$event['eid']]->width."%";
                $divLeft = "left: ".$eventPositions[$event['eid']]->leftpos."%";
            }

            $eventid = $event['eid'] ?? null;
	        $eventtype = sqlQuery("SELECT pc_cattype FROM openemr_postcalendar_categories as oc LEFT OUTER JOIN openemr_postcalendar_events as oe ON oe.pc_catid=oc.pc_catid WHERE oe.pc_eid='".$eventid."'");
	        $pccattype = '';
	        if($eventtype['pc_cattype']==1)
	        $pccattype = 'true';
            $patientid = $event['pid'];
            $commapos = strpos($event['patient_name'], ",");
            $lname = substr($event['patient_name'], 0, $commapos);
	        $fname = substr($event['patient_name'], $commapos + 2);
            $patient_dob = oeFormatShortDate($event['patient_dob']);
            $patient_age = $event['patient_age'];
            $catid = $event['catid'] ?? '';
            $comment = $event['hometext'];
            $catname = $event['catname'];
            $title = "Age $patient_age ($patient_dob)";

            //Variables for therapy groups
            $groupid = $event['gid'];
            if($groupid) $patientid = '';
            $groupname = $event['group_name'];
            $grouptype = $event['group_type'];
            $groupstatus = $event['group_status'];
            $groupcounselors = '';
            foreach($event['group_counselors'] as $counselor){
                $groupcounselors .= getUserNameById($counselor) . " \n ";
            }

            $content = "";

            if ($comment && $GLOBALS['calendar_appt_style'] < 4) $title .= " " . $comment;

            // the divTitle is what appears when the user hovers the mouse over the DIV
            $divTitle = date("D, d M Y", strtotime($date));
	    $result = sqlStatement("SELECT name,id,color FROM facility WHERE id=(SELECT pc_facility FROM openemr_postcalendar_events WHERE pc_eid=?)", [$eventid]);
	    $row = sqlFetchArray($result);
	    $color=$event["catcolor"];
	    if($GLOBALS['event_color']==2)
	    $color=$row['color'];
	      $divTitle .= "\n" . $row['name'];
            if ($catid == 2 || $catid == 3 || $catid == 4 || $catid == 8 || $catid == 11) {
                if      ($catid ==  2) $catname = xl("IN");
                else if ($catid ==  3) $catname = xl("OUT");
                else if ($catid ==  4) $catname = xl("VACATION");
                else if ($catid ==  8) $catname = xl("LUNCH");
                else if ($catid == 11) $catname = xl("RESERVED");

                $atitle = $catname;
                if ($comment) $atitle .= " $comment";
                //$divTitle .= "\n[".$atitle ."] ".$divTitle;
                $divTitle .= "\n[".$atitle ."]";
                //$content .= "<a href='javascript:oldEvt(" . attr_js($eventdate) . "," . attr_js($eventid) . ")' title='" . attr($atitle) . "'>";
                $content .= text($catname);
                if ($event['recurrtype'] > 0) $content .= " <img class='border-0' src='{$this->_tpl_vars['TPL_IMAGE_PATH']}/repeating8.png' style='margin: 0 2px 0 2px;' title='Repeating event' alt='Repeating event' />";
                if ($comment) $content .= " " . text($comment);
                //$content .= "</a>";
            }
            else {
                // some sort of patient appointment
                if($groupid){
                    $divTitle .= "\n" . xl('Counselors') . ": \n" . $groupcounselors . " \n";
                    $divTitle .= "\r\n[" . $catname . ' ' . $comment . "]" . $groupname;
                }
                else
                    $divTitle .= "\r\n[" . $catname . ' ' . $comment . "]" .$fname . " " . $lname;

                $content .= "<span class='appointment" . attr($apptToggle ?? "") . "'>";
                $content .= create_event_time_anchor($dispstarth . ':' . $startm);
                if ($event['recurrtype'] > 0) $content .= "<img src='{$this->_tpl_vars['TPL_IMAGE_PATH']}/repeating8.png' class='border-0' style='margin:0 2px 0 2px;' title='Repeating event' alt='Repeating event' />";
                $content .= text($event['apptstatus']);
                if ($patientid) {
                    // include patient name and link to their details
                    $link_title = $fname . " " . $lname . " \n";
                    $link_title .= xl('Age') . ": " . $patient_age . "\n" . xl('DOB') . ": " . $patient_dob . $comment . "\n";
                    $link_title .= "(" . xl('Click to view') . ")";
                    $content .= "<a href='javascript:goPid(" . attr_js($patientid) . ")' title='" . attr($link_title) . "'>";
                    $content .= "<i class='fas fa-user text-success' onmouseover=\"javascript:ShowImage(" . attr_js($GLOBALS['webroot']."/controller.php?document&retrieve&patient_id=".$patientid."&document_id=-1&as_file=false&original_file=true&disable_exit=false&show_original=true&context=patient_picture") . ");\" onmouseout=\"javascript:HideImage();\" title='". attr($link_title) . "'></i>";

                    if ($catid == 1) $content .= "<s>";
                    $content .= text($lname);
                    if ($GLOBALS['calendar_appt_style'] != 1) {
                        $content .= "," . text($fname);
                        if ($event['title'] && $GLOBALS['calendar_appt_style'] >= 3) {
                            $content .= "(" . text($event['title']);
                            if ($event['hometext'] && $GLOBALS['calendar_appt_style'] >= 4)
                            $content .= ": <span class='text-success'>" . text(trim($event['hometext'])) . "</span>";
                            $content .= ")";
                        }
                    }
                    if ($catid == 1) $content .= "</s>";
                    $content .= "</a>";
                    $content .= '<a class="show-appointment shown"></a>';
                }
                elseif($groupid){
                    $divTitle .= "\n" . getTypeName($grouptype) . "\n";
                    $link_title = '';
                    $link_title .= $divTitle ."\n";
                    $link_title .= "(" . xl('Click to view') . ")";
                    $content .= "<a href='javascript:goGid(" . attr_js($groupid) . ")' title='" . attr($link_title) . "'>";
                    $content .= "<i class='fas fa-user text-primary' title='" . attr($link_title) . "'></i>";
                    if ($catid == 1) $content .= "<s>";
                    $content .= text($groupname);
                    if ($GLOBALS['calendar_appt_style'] != 1) {
                        if ($event['title'] && $GLOBALS['calendar_appt_style'] >= 3) {
                            $content .= "(" . text($event['title']);
                            if ($event['hometext'] && $GLOBALS['calendar_appt_style'] >= 4)
                            $content .= ": <span class='text-success'>" . text(trim($event['hometext'])) . "</span>";
                            $content .= ")";
                        }
                    }
                    if ($catid == 1) $content .= "</s>";
                    $content .= "</a>";

                    //Add class to wrapping div so EditEvent js function can differentiate between click on group and patient
                    $evtClass .= ' groups ';

                }
                else {
                      //Category Clinic closaed or holiday take the event title
                    if ( $catid == 6 || $catid == 7){
                         $content .= xlt($event['title']);
                    }else{
                        // no patient id, just output the category name
                        $content .= text(xl_appt_category($catname));
                    }
                }
                $content .= "</span>";
            }

            $divTitle .= "\n(" . xl('double click to edit') . ")";



            // output the DIV and content
			if($_SESSION['pc_facility'] == 0){
                //This is to differentiate between the events of holiday(6) or vacation(4) in order to disable
                //the ability to double click and edit this events
                if ($event['catid']!="6" && $event['catid']!="4" )
                {
                // output the DIV
                echo "<div class='" . attr($evtClass) . " event' style='top:".$evtTop."; height:".$evtHeight.
						"; background-color:".$color.
						"; $divWidth".
						"; $divLeft".
						"' title='" . attr($divTitle) . "'".
                " id='" . attr($eventdate) . "-" . attr($eventid) . "-" . attr($pccattype) . "'".
                ">";
                }
                else
                {
                // output the DIV
                echo "<div class='" . attr($evtClass) . " hiddenevent' style='top:".$evtTop."; height:".$evtHeight.
						"; background-color:".$color.
						"; $divWidth".
						"; $divLeft".
						"' title='" . attr($divTitle) . "'".
                " id='" . attr($eventdate) . "-" . attr($eventid) . "-" . attr($pccattype) . "'".
                ">";
                }
                //output the content
				echo $content;
				echo "</div>\n";
			}
			elseif($_SESSION['pc_facility'] == $row['id']){
				echo "<div class='" . attr($evtClass) . " event' style='top:".$evtTop."; height:".$evtHeight.
						"; background-color:".$event["catcolor"].
						"; $divWidth".
						"; $divLeft".
						"' title='" . attr($divTitle) ."'".
						" id='" . attr($eventdate) . "-" . attr($eventid) . "-" . attr($pccattype) . "'".
						">";
            // output the content
				echo $content;
				echo "</div>\n";
			}
			else{
				echo "<div class='" . attr($evtClass) . " event' style='top:".$evtTop."; height:".$evtHeight.
						"; background-color: var(--gray300)".
						"; $divWidth".
						"; $divLeft".
						"' title='" . attr($divTitle) . "'".
						" id='" . attr($eventdate) . "-" . attr($eventid) . "-" . attr($pccattype) . "'".
						">";
				echo "<span class='text-center text-danger font-weight-bold'>" . text($row['name']) . "</span>";
				echo "</div>\n";
			}
     } // end EVENT loop

        echo "</div>";
        echo "</td>\n";

    } // end date
//==================================================================================================================
    echo " </tr>\n";

    echo "</table>\n";
    echo "</div>\n";
} // end provider

 ?>
</div>  <!-- end bigCal DIV -->
</div>  <!-- end bottom DIV -->
</div>
</form>
</div>

<script>
    var tsHeight=<?php  echo js_escape($timeslotHeightVal.$timeslotHeightUnit);  ?>;
    var tsHeightNum=<?php  echo js_escape($timeslotHeightVal);  ?>;

    $(function () {
        setupDirectTime();
        $("#pc_username").change(function() { ChangeProviders(this); });
        $("#pc_facility").change(function() { ChangeProviders(this); });
        $("#dayview").click(function() { ChangeView(this); });
        //$("#weekview").click(function() { ChangeView(this); });
        $("#monthview").click(function() { ChangeView(this); });
        //$("#yearview").click(function() { ChangeView(this); });
        $(".tdDatePicker").click(function() {
          ChangeDate(this);
        });
        $("#datePicker .tdDatePicker").mouseover(function() {
          $(this).toggleClass("tdDatePickerHighlightCurrent");
        });
        $("#datePicker .tdDatePicker").mouseout(function() {
          $(this).toggleClass("tdDatePickerHighlightCurrent");
        });
        $("#datePicker .tdNav").mouseover(function() {
          $(this).toggleClass("tdDatePickerHighlight");
        });
        $("#datePicker .tdNav").mouseout(function() {
          $(this).toggleClass("tdDatePickerHighlight");
        });
        $("#printview").click(function() { PrintView(this); });
        $(".event").dblclick(function() { EditEvent(this); });
        $(".event").mouseover(function() { $(this).toggleClass("event_highlight"); });
        $(".event").mouseout(function() { $(this).toggleClass("event_highlight"); });

        $(".show-appointment").on('click', function() {
            var self = $( this );
            if (self.hasClass('shown')) {
                self.parent().parent('.event_appointment').css('opacity' , '0.1').css('pointer-events' , 'none');
                self.removeClass('shown').addClass('unshown').css('pointer-events' , 'all');
            } else {
                self.parent().parent('.event_appointment').css('opacity' , '1').css('pointer-events' , 'unset');
                self.removeClass('unshown').addClass('shown').css('pointer-events' , 'unset');
            }
        })

        $(".tdMonthName-small").click(function() {
            dpCal = $("#datePicker > table");
            mp = $("#monthPicker");
            mp.width(dpCal.width());
            mp.toggle();
        });
    });

    /* edit an existing event */
    var EditEvent = function(eObj) {
        //alert ('editing '+eObj.id);
        // split the object ID into date and event ID
        objID = eObj.id;
        var parts = new Array();
        parts = objID.split("-");
        editing_group = $(eObj).hasClass("groups");
        if(editing_group){
            oldGroupEvt(parts[0], parts[1], parts[2]);
            return true;
        }
        // call the oldEvt function to bring up the event editor
        oldEvt(parts[0], parts[1], parts[2]);
        return true;
    }

    /* change the current date based upon what the user clicked in
     * the datepicker DIV
     */
    var ChangeDate = function(eObj) {
        baseURL = "<?php echo pnModURL(__POSTCALENDAR__,'user','view',
                        array('tplview'=>($template_view ?? ''),
                        'viewtype'=>$viewtype,
                        'Date'=> '~REPLACEME~',
                        'pc_username'=>($pc_username ?? ''),
                        'pc_category'=>($category ?? ''),
                        'pc_topic'=>($topic ?? ''))); ?>";
        newURL = baseURL.replace(/~REPLACEME~/, eObj.id);
        document.location.href=newURL;
    }

    /* pop up a window to print the current view
     */
    var PrintView = function (eventObject) {
        printURL = "<?php echo pnModURL(__POSTCALENDAR__,'user','view',
                        array('tplview'=>($template_view ?? ''),
                        'viewtype'=>$viewtype,
                        'Date'=> $Date,
                        'print'=> 1,
                        'pc_username'=>($pc_username ?? ''),
                        'pc_category'=>($category ?? ''),
                        'pc_topic'=>($topic ?? ''))); ?>";
        window.open(printURL,'printwindow','width=740,height=480,toolbar=no,location=no,directories=no,status=no,menubar=yes,scrollbars=yes,copyhistory=no,resizable=yes');
        return false;
    }

    /* change the provider(s)
     */
    var ChangeProviders = function (eventObject) {
        $('#theform').submit();
    };

    /* change the calendar view
     */
    var ChangeView = function (eventObject) {
        if (eventObject.id == "dayview") {
            $("#viewtype").val('day');
        }
        else if (eventObject.id == "weekview") {
            $("#viewtype").val('week');
        }
        else if (eventObject.id == "monthview") {
            $("#viewtype").val('month');
        }
        else if (eventObject.id == "yearview") {
            $("#viewtype").val('year');
        }
        $('#theform').submit();
    };

    $("#menu-toggle").click(function(e) {
        e.preventDefault();
        $("#wrapper").toggleClass("toggled");
    });
</script>

</body>
</html>
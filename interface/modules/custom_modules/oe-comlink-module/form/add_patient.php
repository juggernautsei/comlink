<?php

require_once "../../../../globals.php";
require_once dirname(__FILE__, 2)."/controller/Container.php";

use OpenEMR\Modules\Comlink\Container;
use OpenEMR\Core\Header;

$container = new Container();
$loadDb = $container->getDatabase();

$facilities = $loadDb->getFacilities();
$providers = $loadDb->getProviders();
if($_POST){
    if($_POST['pro'] == "autocomplete"){
        $search_list = [];
        $type = $_POST['type'];
        if($type == 'name'){
            $sql='SELECT `lname`,`fname` FROM `patient_data`';
            $list = sqlStatement($sql);
            while ($row = sqlFetchArray($list)) {
                $search_list[] = $row['lname'].", ".$row['fname'];
            }
        }else{
            $sql='SELECT `pid` FROM `patient_data`';
            $list = sqlStatement($sql);
            while ($row = sqlFetchArray($list)) {
                $search_list[] = $row['pid'];
            }
        }
       
        echo(json_encode($search_list));
    }

    
    

}else{
?>
<!DOCTYPE html>
<head>
    <?php Header::setupHeader(['report-helper','opener']); ?>
    <meta charset="utf-8" />
    <title><?php echo xlt('Add Patients');?></title>
    <script>
    function sel_patient() {
        let title = '<?php echo xlt('Patient Search'); ?>';
        dlgopen( '<? echo dirname(__FILE__,5) ?>\main\calendar\find_patient_popup.php', '_blank', 650, 300, '', title);
    }
    </script>
    <style type="text/css">
        .autocomplete-items {
        position: absolute;
        border: 1px solid #d4d4d4;
        border-bottom: none;
        border-top: none;
        z-index: 99;
        width:37.5%;
        margin:5.8% 0% 0% 37%;
        }

        .autocomplete-items div {
        padding: 10px;
        cursor: pointer;
        background-color: #fff; 
        border-bottom: 1px solid #d4d4d4; 
        position: static;
        }

        /*when hovering an item:*/
        .autocomplete-items div:hover {
        background-color: #e9e9e9; 
        }

        /*when navigating through the items using the arrow keys:*/
        .autocomplete-active {
        background-color: DodgerBlue !important; 
        color: #ffffff; 
        }
    </style>
</head>
<body >
<form role="form" method='post' name='theform' id='theform' action='add_patient.php'>
    <div class="form-row mx-2">
        <div class="col-sm form-group">
            <label for='form_facility'><?php echo xlt('Facility'); ?>:</label>
            <select class='form-control' name='form_category' id='form_category'>
                <?php 
                foreach($facilities as $facility) {
                    echo "<option value='".$facility['id']."'>".$facility['name']."</option>";
                }              
                ?>
            </select>
        </div>
        <div class="col-sm form-group">
            <label for='form_title'><?php echo xlt('Provider'); ?>:</label>
            <select class='form-control' name='form_provider' id='form_provider'>
                <?php 
                foreach($providers as $provider) {
                    if($provider['fname']){
                        echo "<option value='".$provider['id']."'>".$provider['lname'].", ".$provider['fname']."</option>";
                    }else{
                        echo "<option value='".$provider['id']."'>".$provider['lname']."</option>";
                    }   
                }              
                ?>
            </select>
        </div>
    </div>
    <div class="form-row mx-2">
        <div class="col-sm form-group">
            <label for="form_patient"><?php echo xlt('Patient'); ?>:</label><br>
            <div class="jumbotron jumbotron-fluid py-2" style="border-radius:5px;padding:10px;margin:0px;">
            <div>
            <label for="form_search" style="float:left;margin-top: 1%;"><?php echo xlt('Search By '); ?>:</label>
            <select class='form-control' name='form_search' id='form_search' onchange="onChange()"style="float:left;width:25%;margin: 0 0 1% 1%;">
                <option value="name">Name</option>
                <option value="pid">ID</option>
            </select>
            <input class='form-control' type='text' name='search_name' id="search_name"  style="float:left;margin: 0 0 1% 1%;width:40%;" autocomplete="off" placeholder='<?php echo xla('Search here'); ?>' />
            </div>
            <input class="btn btn-secondary btn-save" type="button" id="select"  style="float:left;margin: 0 0 1% 1%;width:15%;" value="Select" onclick="addName()">
            <input class='form-control' type='text' name='form_patient' id="form_patient" placeholder='<?php echo xla('Patient Name'); ?>' disabled />
            <input type="hidden" name="form_dob" id="form_dob">
            </div>

        </div>
    </div>
    <div class="form-row mx-2 mt-3">
        <input class="col-sm mx-sm-2 my-2 my-sm-auto btn btn-primary" type="button" name="form_save" id="form_save" value="Add Patient">
        <input class="col-sm mx-sm-2 my-2 my-sm-auto btn btn-secondary" type="button" id="cancel" onclick="dlgclose()" value="Cancel">
    </div>
</form>
</body>
<script>
function autocomplete(inp, arr) {
  var currentFocus;
  
  inp.addEventListener("input", function(e) {
      var a, b, i, val = this.value;
      closeAllLists();
      if (!val) { return false;}
      currentFocus = -1;
      a = document.createElement("DIV");
      a.setAttribute("id", this.id + "autocomplete-list");
      a.setAttribute("class", "autocomplete-items");
      this.parentNode.appendChild(a);
      
      for (i = 0; i < arr.length; i++) {
       
        if (arr[i].substr(0, val.length).toUpperCase() == val.toUpperCase()) {
         
          b = document.createElement("DIV");
          b.innerHTML = "<strong>" + arr[i].substr(0, val.length) + "</strong>";
          b.innerHTML += arr[i].substr(val.length);
          b.innerHTML += "<input type='hidden' value='" + arr[i] + "'>";
          
          b.addEventListener("click", function(e) {
              inp.value = this.getElementsByTagName("input")[0].value;
              closeAllLists();
          });
          a.appendChild(b);
        }
      }
  });
  inp.addEventListener("keydown", function(e) {
      var x = document.getElementById(this.id + "autocomplete-list");
      if (x) x = x.getElementsByTagName("div");
      if (e.keyCode == 40) {
        currentFocus++;
        addActive(x);
      } else if (e.keyCode == 38) { 
        currentFocus--;
        addActive(x);
      } else if (e.keyCode == 13) {
        e.preventDefault();
        if (currentFocus > -1) {
          if (x) x[currentFocus].click();
        }
      }
  });
  function addActive(x) {
    if (!x) return false;
    removeActive(x);
    if (currentFocus >= x.length) currentFocus = 0;
    if (currentFocus < 0) currentFocus = (x.length - 1);
    x[currentFocus].classList.add("autocomplete-active");
  }
  function removeActive(x) {
    for (var i = 0; i < x.length; i++) {
      x[i].classList.remove("autocomplete-active");
    }
  }
  function closeAllLists(elmnt) {
    var x = document.getElementsByClassName("autocomplete-items");
    for (var i = 0; i < x.length; i++) {
      if (elmnt != x[i] && elmnt != inp) {
        x[i].parentNode.removeChild(x[i]);
      }
    }
  }
  document.addEventListener("click", function (e) {
      closeAllLists(e.target);
  });
}


function onChange(){
    var search_list = [];
    var search = document.getElementById("form_search").value;
    var pro = "autocomplete";

    $.ajax({
            type: 'POST', 
            url: "add_patient.php", 
            dataType:'text', 
            data:{pro:pro ,type:search}, 
            async: false,
            success:function(response){
                console.log(response);
                result = JSON.parse(response);
                search_list = result;
                console.log(search_list);
            },
            error:function (xhr, ajaxOptions, thrownError){
                alert(xhr + " " + ajaxOptions + " " + thrownError); 
            }
        });
        autocomplete(document.getElementById("search_name"), search_list);
}
window.onload = onChange();
function addName(){
    var search = document.getElementById("form_search").value;
    var sel_search = document.getElementById("search_name").value;
    var data = "pro=add&type="+search;
    $.ajax({
            type: 'POST', 
            url: "add_patient.php", 
            dataType:'text', 
            data:data, 
            async: false,
            success:function(response){
                // console.log(response);
                result = JSON.parse(response);
                search_list = result;
                // console.log(search_list);
            },
            error:function (xhr, ajaxOptions, thrownError){
                alert(xhr + " " + ajaxOptions + " " + thrownError); 
            }
        });
}


</script>
</html>
<?php } ?>
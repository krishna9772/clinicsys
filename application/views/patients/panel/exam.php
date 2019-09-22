<div class="tab-pane" id="exam" style="padding-top: 10px;">

	<style type="text/css">
		tbody td{

			font:17px solid ;
		}
	</style>

	<script>
    $(document).ready(function(){
      //script of this section
      $('input[id=save]').click(function(){
        
          $.post($('#examBox').attr('action'),$('#examBox').serialize(),
            function(data){
              $('#examGroup').prepend(data);
              $('#exam').val('');
            });
          return false; 
        
      });

      $("#examGroup span >i >a").on('click',examinationAction);
         
      function examinationAction(e)
      {
        e.preventDefault();
        var csrfVal=$('form input[name="csrf_test_name"]').val();
        var eid    =$(this).attr('eid');
        var pi     =$(this).attr('pi');
        if($(this).attr('action')=='delete'){
      
            $.post('<?php echo site_url("examinations/delete")."/";?>'+eid,'csrf_test_name='+csrfVal+'&examination_id='+eid+'&patient_id='+pi,function(data){
            if(data=='ok'){
              $('#exam'+eid).fadeOut();
            }else if(data=='mismatch'){
              alert('Delete data mismatch');
            }else if(data=='invalid'){
              alert('Invalid data');
            }
          });
        }
      }
    });
  </script>
  <div class="">
       <div class="row">

		<?php if($patient_data['id'] == TRUE){

			echo form_open('examinations/create/'.$patient_data['id'],array('id'=>'examBox'));
			echo form_hidden('patient_id',$patient_data['id']);
	     ?>
	     	<div class="col-xs-2">
	     		<label>BP:</label>
	     		<?php echo form_input('s_bp','','class="form-control" id="s_bp" placeholder="Systolic Bp"');?>
              <center>/</center>
	     		<?php echo form_input('d_bp','','class="form-control" id="d_bp" placeholder="Diastolic Bp"');?>
	     		 <label>BMI:</label>
	     	   <?php echo form_input('bmi','','class="form-control" id="bmi" placeholder="BMI"')?><input type="button" name="" class="btn btn-small" id="calbmi" value="Calculate">
	     	</div>
	     	<div class="col-xs-2">
	     	   <label>PR:</label>
	     	   <?php echo form_input('pr','','class="form-control" id="pr" placeholder="Pulse Rate"')?>
	        </div>	
	        <div class="col-xs-2">
	     	   <label>TEMP (&#8457;):</label>
	     	   <?php echo form_input('temp','98','class="form-control" id="temp" placeholder="Temperature"')?><br>
	        </div>
	        <div class="col-xs-2">
	     	   <label>SPO<sub>2</sub></label>
	     	   <?php echo form_input('spo2','','class="form-control" id="spo2" placeholder=""')?>
	        </div>	
	         <div class="col-xs-2">
	     	   <label>WEIGHT (lbs):</label>
	     	   <?php echo form_input('weight','','class="form-control" id="weight" placeholder="lbs"')?><br>
	     	  
	     	   <input type="button" name="" class="btn btn-warning" id="wconvert" value="Convert">  <small>(from kg)</small>
	        </div>
	         <div class="col-xs-2">
	     	   <label>HEIGHT (ft):</label>
	     	   <?php echo form_input('height','','class="form-control" id="height" placeholder="ft"')?><br>
	   	   <input type="button" name="" class="btn btn-warning" id="hconvert" value="Convert">  <small>(from cm)</small>
	        </div>
	    </div><br>
	    <input class="btn btn-primary" type="button" value="Save" id="save"><br><br>

      <?php } ?>


	    <?php 

	     echo "<div id='examGroup'>";
       $i=1;
  foreach($examination as $exam) {

      $pr = ($exam->pr != 0) ? $exam->pr."  <sub><small>bpm</small></sub>" : '';
      $spo2 = ($exam->spo2 != 0) ? $exam->spo2." <small>%<small>":'';
      $weight = ($exam->weight != 0) ? $exam->weight." <sub><small>lbs</small></sub>    |  <span id='kg".$i."'></span>":'';
      $height = ($exam->height != '') ? $exam->height." <sub><small>ft</small></sub>     |  <span id='cm".$i."'></span>":'';
      $bmi = ($exam->bmi != 0) ? $exam->bmi : '';

      echo "<div id='exam".$exam->id."' class='well well-md'>";
      echo "<div class='examBody'>";
      echo "<table class='table table-striped' id='exam_table'>";
      echo "<thead>";
      echo "<tr>";
      echo "<th>BP</th>";
      echo "<th>PR</th>";
      echo "<th>Temp</th>";
      echo "<th>SPO<sub>2</sub></th>";
      echo "<th>Body.Wt</th>";
      echo "<th>Body.Ht</th>";
      echo "<th>BMI</th>";
      echo "<th><span class='badge badge-warning pull-right'><i class='fa fa-trash'>
           ".anchor('#', 'Del ',array('eid'=>$exam->id,'pi'=>$exam->patient_id,'action'=>'delete'))."</i></span></th>";
      echo "</tr>";
      echo "</thead>";
      echo "<tbody>";
      echo "<tr>";
      echo "<td>".$exam->s_bp." /  ".$exam->d_bp." <sub><small>mmHg</small></sub></td>";
      echo "<td>".$pr."</td>";
      echo "<td>".$exam->temp." &#8457;";
      echo "<td>".$spo2."</td>";
   echo "<td>".$weight."</td>";
    echo "<td>".$height."</td>";
      echo "<td>".$bmi."</td>";
      echo "<td>";
      echo "</tr>";
      echo "</tbody>";
      echo "</table>";

            echo "</div>";
      echo "<div class='pull-right'>Create Date:".$exam->created_date."</div>";
    echo "</div>";
   echo "<input type='hidden' value=".$exam->weight." id='lb".$i."'>"; 
   echo "<input type='hidden' value=".$exam->height." id='ft".$i."'>";
   $i++;
  }
   echo "</div>";
  
	     ?>

  </div>
</div>
<script type="text/javascript">

$(document).ready(function() {

  var count_table_tbody_tr = $("#exam_table tbody tr").length;

  for(var row_id=1;row_id<=count_table_tbody_tr;row_id++){

    var lb  = $("#lb"+row_id).val();
    var ft  = $("#ft"+row_id).val();

     // var faren = parseFloat((cel*1.8)+32).toFixed(0); 
     // $("#feren"+row_id).html(faren+" &#8457;");

     var kg = parseFloat(lb*0.45).toFixed(1);
     $("#kg"+row_id).html(kg+" <sub><small>kg</small></sub>");

     var cm = parseFloat(ft*30.48).toFixed(0);
     $("#cm"+row_id).html(cm+" <sub><small>cm</small></sub>");

   }


    $("#wconvert").click(function() {
        
   	 $("#weight").val(parseFloat($("#weight").val() / 0.45).toFixed(0));
         
    });

    // $("#tconvert").click(function() {

    //  var temp = $("#temp").val();

    //  $("#temp").val(parseFloat((temp-32) * 5/9).toFixed(0));
    // })

    $("#hconvert").click(function(){
      var n = $("#height").val();
      var realFeet = ((n*0.393700) / 12);
      var feet = Math.floor(realFeet);
      var inches = Math.round((realFeet - feet) * 12);
     $("#height").val(feet+'.'+inches);

  });


    var height = document.querySelector("#height");
    var weight = document.querySelector("#weight");
    var submit = document.querySelector("#calbmi");
    var bmi    = document.querySelector("#bmi");

    submit.addEventListener("click",function(){

        runit();

    });


   function runit()
   {

      var ht = Number(height.value);
      var wt = Number(weight.value);

      var actht = ht * 12;

      bmi.textContent = Math.round(wt / actht / actht * 703 * 10) / 10;
      $("#bmi").val(bmi.textContent);

   }

});
</script>
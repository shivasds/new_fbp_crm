<?php 
    defined('BASEPATH') OR exit('No direct script access allowed');
    if($this->session->userdata("user_type") == "admin")
        $this->load->view('inc/admin_header');
    else
        $this->load->view('inc/header');    
?>
<style>
.offset-2{
    margin-left:30px;
}
.display-lead{
    border: 1px solid #aaa;
    padding: 5px;
}
.display-lead td {
    border: 1px solid #aaa;
    padding: 5px;
}
.target{
    color: #002561;
    font-weight: bold; 
}
</style>

<body>
	 <div class="se-pre-con"></div>
   <div class="page-container">
   <!--/content-inner-->
	<div class="left-content">
	   <div class="inner-content">
		<!-- header-starts -->
			<div class="header-section">
						<!--menu-right-->
						<div class="top_menu">
						        <!--<div class="main-search">
											<form>
											   <input type="text" value="Search" onFocus="this.value = '';" onBlur="if (this.value == '') {this.value = 'Search';}" class="text"/>
												<input type="submit" value="">
											</form>
									<div class="close"><img src="<?php echo base_url()?>assets/images/cross.png" /></div>
								</div>
									<div class="srch"><button></button></div>
									<script type="text/javascript">
										 $('.main-search').hide();
										$('button').click(function (){
											$('.main-search').show();
											$('.main-search text').focus();
										}
										);
										$('.close').click(function(){
											$('.main-search').hide();
										});
									</script>
							<!--/profile_details-->
								<div class="profile_details_left">
									<?php $this->load->view('notification');?>
							</div>
							<div class="clearfix"></div>	
							<!--//profile_details-->
						</div>
						<!--//menu-right-->
					<div class="clearfix"></div>
				</div>
					<!-- //header-ends -->
						<div class="outter-wp">


<style type="text/css">
	.display td {
	    border: 1px solid #aaa;
	    padding: 5px
	  }
</style>
<div class="container">
	<div class="page-header">
	  <h1><?php echo $heading; ?></h1>
	</div>
	<div class="alert alert-success" style="display: none;">
		<strong>Success!</strong> Email Sent.
	</div>
	<div class="alert alert-danger" style="display: none;">
		<strong>Error!</strong> Email not Sent.
	</div>
	<form method="GET" action="<?php echo base_url()?>admin/generate_report">
		<div class="col-sm-3 form-group">
			<label for="emp_code">Dept:</label>
            <select  class="form-control"  id="dept" name="dept" required >
                <option value="">Select</option>
                <?php $all_department=$this->common_model->all_active_departments();
                foreach($all_department as $department){ ?>
                    <option value="<?php echo $department->id; ?>" <?php if($department->id==$dept) echo 'selected'; ?>><?php echo $department->name; ?></option>
                <?php }?>              
            </select>
		</div>
		<div class="col-sm-3 form-group">
			<label for="emp_code">City:</label>
            <select  class="form-control"  id="city" name="city" >
                <option value="">Select</option>
                <?php $cities= $this->common_model->all_active_cities(); 
                foreach( $cities as $c){ ?>
                    <option value="<?php echo $c->id; ?>" <?php if($c->id==$city) echo 'selected'; ?> ><?php echo $c->name ?></option>
                <?php } ?>               
            </select>
		</div>
		<div class="col-sm-3 form-group">
            <button type="submit" id="Generate" class="btn btn-success btn-block">Filter</button>
        </div>
        <div class="col-sm-3 form-group">
            <button id="email_this_report" class="btn btn-default btn-block">Email this report</button>
        </div>
    </form>
    <br>
    <div class="col-md-12">
    	<div class="col-md-4">
    		<table class="display" cellspacing="0" width="100%">
				<thead>
					<tr>
						<th>Sl.No</th>
						<th>Advisor</th>
						<th>No. of callbacks Assigned</th>
                        <th class="hidden">key</th>
                        <th class="hidden" >fromDate</th>
                        <th class="hidden">todate</th>
                         
					</tr>
				</thead>
				<tbody>
					<?php if(count($advisors)>0){
						$i = 1;
						$total = 0;
						foreach ($advisors as $key => $value) { 
							$name = $this->user_model->get_user_fullname($key); 
							$total += $value; ?>
						 	<tr>
						 		<td><?php echo $i; ?></td>
						 		<td><?php echo $name; ?></td>
						 		<td class="target" onclick="getrowvalue(this)"><?=$value?> </td> 
                                 <!-- <td><?=$key?></td> -->
                                 <td class="hidden" id ="key"><?=$key?></td>
                                 <div class="hidden" id="ls<?=$key?>"></div>
                                 <td class="hidden">
                                 <?=$this->input->get('fromDate');?>                              
                                 </td>
                                 <td class="hidden">
                                 <?=$this->input->get('toDate');?>                              
                                 </td>
                                
                                 </tr>
                             
						<?php $i++; } ?>
						<tr>
							<td colspan="2">Total</td>
							<td><a href="<?php echo base_url().'view_callbacks?report='.urlencode($reportType).'&dept='.urlencode($dept).'&city='.urlencode($city).'&fromDate='.urlencode($fromDate).'&toDate='.urlencode($toDate); ?>"><?php echo $total; ?></a></td>
						</tr>
					<?php } else { ?>
						<tr>
							<td colspan="3"> No entries </td>
						</tr>
					<?php } ?>
				</tbody>
			</table>
    	</div>
	    	<script>
           function getrowvalue(id){
           var trid = $(id).parent('tr').children();
           var a=$(trid)[3];
             var userid= $(a).text();
             var fromDate=$($(trid)[4]).text();
             var toDate=$($(trid)[5]).text();
             $("#customer_detail").empty();
             $("#customer-theadtr").empty();
             var tbody='';
             var theadtr='';
             
             $.ajax({
                 "Type":"GET",
                  "url":'http://'+window.location.host+'/admin/lead_report?user_id='+userid+'&fromDate='+fromDate+'&toDate='+toDate,
                  success: function (response) {
                      var data=JSON.parse(response)
                     var thead= Object.keys(data[0])
                     // for(j=0;j<=thead.length-1;j++){
                     //    theadtr+='<th>'+thead[j]+'</th>'
                     // }
                     theadtr+='<th>Count</th><th>Lead Source</th>'; 
                      $("#customer-theadtr").append(theadtr);
                    for(i=0;i<=data.length-1;i++){
                        
                    tbody +='<tr><td style="display:none;">'+data[i].lead_source_id+'</td><td class="target" onclick="get_lead_source_id(this)">'+data[i].count+'</td><td style="display:none;">'+data[i].user_id+'</td><td style="display:none;">'+fromDate+'</td><td style="display:none;">'+toDate+'</td><td>'+data[i].lead_source+'</td></tr>'
                }
                $("#customer_detail").append(tbody)
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(textStatus, errorThrown);
                }
             })
               
                
           
             }

             function get_lead_source_id(id){
                // $(".display-lead").hide();
                var trid = $(id).parent('tr').children();
                var fromDate=$($(trid)[3]).text();
                var toDate=$($(trid)[4]).text();
                var lead_source_id=$($(trid)[0]).text();
                var user_id=$($(trid)[2]).text();
               
                $("#customer_detail").empty();
                $("#customer-theadtr").empty();
             var tbody='';
             var theadtr='';
                $.ajax({
                 "Type":"GET",
                 "url":'http://'+window.location.host+'/admin/lead_report?user_id='+user_id+'&fromDate='+fromDate+'&toDate='+toDate+'&lead_source_id='+lead_source_id,
                  success: function (response) {
                      var data=JSON.parse(response)
                      console.log(data)
                      var thead= Object.keys(data[0])
                     // for(j=0;j<=thead.length-1;j++){
                     //    theadtr+='<th>'+thead[j]+'</th>'
                     // }
                     theadtr+='<th>Count</th><th>Project Name</th>';
                      $("#customer-theadtr").append(theadtr);
                    for(i=0;i<=data.length-1;i++){
                    	var url_of_project = 'http://'+window.location.host+'/admin/lead_report?user_id='+user_id+'&fromDate='+fromDate+'&toDate='+toDate+'&lead_source_id='+lead_source_id+'&project_id='+data[i].project_id;
                    tbody +='<tr><td style="display:none;">'+data[i].project_id+'</td><td style="display:none;">'+data[i].user_id+'</td><td><a href="'+url_of_project+'"  target="_blank">'+data[i].count+'</a></td><td style="display:none;">'+lead_source_id+'</td><td style="display:none;">'+fromDate+'</td><td style="display:none;">'+toDate+'</td><td>'+data[i].project+'</td></tr>'
                }
                $("#customer_detail").append(tbody)
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(textStatus, errorThrown);
                }
             })
             }

         
            </script>

            
    <div class="offset-2 col-md-5">
            <table class="display-lead" cellspacing="0" width="100%">
				<thead id="customer-thead">
					<tr id="customer-theadtr">
						<!-- <th>Lead Source</th>
						<th>Count</th>
                        <th>key</th>
                        <th>fromdate</th>
                        <th>todate</th> -->
					</tr>
				</thead>
				<tbody id="customer_detail"> </tbody>
			</table>
     </div>

     
   


		<!-- <div class="col-md-4">
            <table class="display" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>Sl.No</th>
                        <th>Project</th>
                        <th>No. of callbacks Assigned</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(count($projects)>0){
                        $i = 1;
                        $total = 0;
                        foreach ($projects as $key => $value) { 
                            $name = $this->common_model->get_project_name($key); 
                            $total += $value; ?>
                            <tr>
                                <td><?php echo $i; ?></td>
                                <td><?php echo $name; ?></td>
                                <td><a href="<?php echo base_url().'view_callbacks?report='.urlencode($reportType).'&project='.urlencode($key).'&dept='.urlencode($dept).'&city='.urlencode($city).'&fromDate='.urlencode($fromDate).'&toDate='.urlencode($toDate); ?>"><?php echo $value; ?></a></td>
                            </tr>
                        <?php $i++; } ?>
                        <tr>
                            <td colspan="2">Total</td>
                            <td><a href="<?php echo base_url().'view_callbacks?report='.urlencode($reportType).'&dept='.urlencode($dept).'&city='.urlencode($city).'&fromDate='.urlencode($fromDate).'&toDate='.urlencode($toDate); ?>"><?php echo $total; ?></a></td>
                        </tr>
                    <?php } else { ?>
                        <tr>
                            <td colspan="3"> No entries </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
		</div>

		<div class="col-md-4">
            <table class="display" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>Sl.No</th>
                        <th>Lead Source</th>
                        <th>No. of callbacks Assigned</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(count($lead_sources)>0){
                        $i = 1;
                        $total = 0;
                        foreach ($lead_sources as $key => $value) { 
                            $name = $this->common_model->get_leadsource_name($key);
                            $total += $value; ?>
                            <tr>
                                <td><?php echo $i; ?></td>
                                <td><?php echo $name; ?></td>
                                <td><a href="<?php echo base_url().'view_callbacks?report='.urlencode($reportType).'&lead_source='.urlencode($key).'&dept='.urlencode($dept).'&city='.urlencode($city).'&fromDate='.urlencode($fromDate).'&toDate='.urlencode($toDate); ?>"><?php echo $value; ?></a></td>
                            </tr>
                        <?php $i++; } ?>
                        <tr>
                            <td colspan="2">Total</td>
                            <td><a href="<?php echo base_url().'view_callbacks?report='.urlencode($reportType).'&dept='.urlencode($dept).'&city='.urlencode($city).'&fromDate='.urlencode($fromDate).'&toDate='.urlencode($toDate); ?>"><?php echo $total; ?></a></td>
                        </tr>
                    <?php } else { ?>
                        <tr>
                            <td colspan="3"> No entries </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
		</div> -->
    </div>

		
</div>
</body>
<script type="text/javascript">
	$("#email_this_report").click(function(e){
		e.preventDefault();
		$(".alert-success").hide();
		$(".alert-danger").hide();
		$.get("<?php echo base_url().'admin/email_report?fromDate='.urlencode($fromDate).'&toDate='.urlencode($toDate).'&city='.urlencode($city).'&dept='.urlencode($dept).'&reportType='.urlencode($reportType); ?>", function(response){
			if(response == "Success")
				$(".alert-success").show();
			else
				$(".alert-danger").show();
		});
	});
</script>
										 <div class="tab-main">
											 <!--/tabs-inner-->
												
												</div>
											  <!--//tabs-inner-->

									 <!--footer section start-->
										<footer>
										   <p>&copy <?= date('Y')?> Fullbasket Property . All Rights Reserved | Design by <a href="https://secondsdigital.com/" target="_blank">Seconds Digital Solutions.</a></p>
										</footer>
									<!--footer section end-->
								</div>
							</div>
				<!--//content-inner-->
			<!--/sidebar-menu-->
				<div class="sidebar-menu">
					<header class="logo">
					<a href="#" class="sidebar-icon"> <span class="fa fa-bars"></span> </a>  <span id="logo"> <h1>FBP</h1></span> 
					<!--<img id="logo" src="" alt="Logo"/>--> 
				  </a> 
				</header>
			<div style="border-top:1px solid rgba(69, 74, 84, 0.7)"></div>
			<!--/down-->
							<div class="down">	
									  <?php $this->load->view('profile_pic');?>
									  <span class=" name-caret"><?php echo $this->session->userdata('user_name'); ?></span>
									   <p><?php echo $this->session->userdata('user_type'); ?></p>
									
									<ul>
									<li><a class="tooltips" href="<?= base_url('dashboard/profile'); ?>"><span>Profile</span><i class="lnr lnr-user"></i></a></li>
										<li><a class="tooltips" style=" color: #00C6D7 !important; " href="#"><span>Team Size</span><?php if($this->session->userdata("manager_team_size")) echo $this->session->userdata("manager_team_size")?$this->session->userdata("manager_team_size"):''?></a></li>
										<li><a class="tooltips" href="<?php echo base_url()?>login/logout"><span>Log out</span><i class="lnr lnr-power-switch"></i></a></li>
										</ul>
									</div>
							   <!--//down-->
                           <?php $this->load->view('inc/header_nav'); ?>
							  </div>
							  <div class="clearfix"></div>		
							</div>
							<script>
							var toggle = true;
										
							$(".sidebar-icon").click(function() {                
							  if (toggle)
							  {
								$(".page-container").addClass("sidebar-collapsed").removeClass("sidebar-collapsed-back");
								$("#menu span").css({"position":"absolute"});
							  }
							  else
							  {
								$(".page-container").removeClass("sidebar-collapsed").addClass("sidebar-collapsed-back");
								setTimeout(function() {
								  $("#menu span").css({"position":"relative"});
								}, 400);
							  }
											
											toggle = !toggle;
										});
							</script>
<!--js -->
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/vroom.css">
<script type="text/javascript" src="<?php echo base_url()?>assets/js/vroom.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>assets/js/TweenLite.min.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>assets/js/CSSPlugin.min.js"></script>

<!--<script src="<?php echo base_url()?>assets/js/scripts.js"></script>-->

<!-- Bootstrap Core JavaScript -->
 
   <script>
    $(document).ready(function() {
         $('#example').DataTable({
              "paging":   false,
              "info": false
 
        });
        if (!Modernizr.inputtypes.date) {
            // If not native HTML5 support, fallback to jQuery datePicker
            $('input[type=date]').datepicker({
                // Consistent format with the HTML5 picker
                    dateFormat : 'dd/mm/yy'
                }
            );
        }
        if (!Modernizr.inputtypes.time) {
            // If not native HTML5 support, fallback to jQuery timepicker
            $('input[type=time]').timepicker({ 'timeFormat': 'H:i' });
        }
        $('#revenueMonth').MonthPicker({
            Button: false
        });
        get_revenues();

        $('.view_callbacks').click(function(){
            var type = $(this).data('type');
            var data = {};
            switch (type)
            {
                case "user_total":
                    data.advisor = "<?php echo $user_id; ?>";
                    data.due_date = "<?php echo date('Y-m-d'); ?>";
                    data.access = 'read_write'; 
                    break;

                case "user_overdue":
                    data.advisor = "<?php echo $user_id; ?>";
                    data.due_date_to = "<?php echo date('Y-m-d H:i:s'); ?>";
                    data.for = "dashboard";
                    data.access = 'read_write'; 
                    break;

                case "user_active": 
                    data.advisor = "<?php echo $user_id; ?>";
                    data.for = "dashboard";
                    data.access = 'read_write'; 
                    break;

                case "user_close": 
                    data.advisor = "<?php echo $user_id; ?>";
                    data.status = "close";
                    break;

                case "user_important":
                    data.advisor = "<?php echo $user_id; ?>";
                    data.access = 'read_write'; 
                    data.important = 1;
                    break;

                case "manager_active": 
                    data.advisor = "<?php echo $user_id; ?>";
                    data.for = "dashboard";
                    data.access = 'read_write'; 
                    break;

                case "manager_close":
                    data.advisor = "<?php echo $user_id; ?>";
                    data.status = "close";
                    break;
            }
            
            view_callbacks(data,'post');

        });

        $("#refresh").click(function(){
            $(".se-pre-con").show();
            $.get("<?php echo base_url(); ?>dashboard/get_live_feed_back", function(response){
                $("#live_feed_back_body").html(response);
                $(".se-pre-con").hide("slow");
            });
        });

        $("#overdue_lead_count").click(function(){
            var form = document.createElement('form');
            form.method = "POST";
            form.action = "<?php echo base_url()."dashboard/generate_report" ?>";
            
            var input = document.createElement('input');
            input.type = "text";
            input.name = "toDate";
            input.value = $(this).data('datetime');
            form.appendChild(input);

            input = document.createElement('input');
            input.type = "text";
            input.name = "reportType";
            input.value = "due";
            form.appendChild(input);

            document.body.appendChild(form);
            form.submit();
        });

        $('.emailSiteVisit').on('click', function(){
            $(".se-pre-con").show();
            $.ajax({
                type : 'POST',
                url : "<?= base_url('site-visit-report-mail');?>",
                data:1,
                success: function(res){
                    $(".se-pre-con").hide("slow");
                    if(res == 1)
                        alert('Email Sent Successfully.');
                    else
                        alert('Email Sent fail!');
                }
            });
        });

    });
    // $('#filter_revenue').click(get_revenues());
    function get_revenues(){
        $.get( "<?php echo base_url()."dashboard/get_revenue/" ?>"+$('#revenueMonth').val(), function( data ) {
            $('#revenue_data').html(data);
        });
    }
    function view_callbacks(data, method) {
        var form = document.createElement('form');
        form.method = method;
        form.action = "<?php echo base_url()."view_callbacks?" ?>"+jQuery.param(data);
        for (var i in data) {
            var input = document.createElement('input');
            input.type = "text";
            input.name = i;
            input.value = data[i];
            form.appendChild(input);
        }
        //console.log(form);
        document.body.appendChild(form);
        form.submit();
    }

</script>
<script>
    $(document).ready(function() {
         $('#example').DataTable({
              "paging":   false,
              "info": false
 
        });
        if (!Modernizr.inputtypes.date) {
            // If not native HTML5 support, fallback to jQuery datePicker
            $('input[type=date]').datepicker({
                // Consistent format with the HTML5 picker
                    dateFormat : 'dd/mm/yy'
                }
            );
        }
        if (!Modernizr.inputtypes.time) {
            // If not native HTML5 support, fallback to jQuery timepicker
            $('input[type=time]').timepicker({ 'timeFormat': 'H:i' });
        }
        $('#revenueMonth').MonthPicker({
            Button: false
        });
        get_revenues();

        $('.view_callbacks').click(function(){
            var type = $(this).data('type');
            var data = {};
            switch (type)
            {
                case "user_total":
                    data.advisor = "<?php echo $user_id; ?>";
                    data.due_date = "<?php echo date('Y-m-d'); ?>";
                    data.access = 'read_write'; 
                    break;

                case "user_overdue":
                    data.advisor = "<?php echo $user_id; ?>";
                    data.due_date_to = "<?php echo date('Y-m-d H:i:s'); ?>";
                    data.for = "dashboard";
                    data.access = 'read_write'; 
                    break;

                case "user_active": 
                    data.advisor = "<?php echo $user_id; ?>";
                    data.for = "dashboard";
                    data.access = 'read_write'; 
                    break;

                case "user_close": 
                    data.advisor = "<?php echo $user_id; ?>";
                    data.status = "close";
                    break;

                case "user_important":
                    data.advisor = "<?php echo $user_id; ?>";
                    data.access = 'read_write'; 
                    data.important = 1;
                    break;

                case "manager_active": 
                    data.advisor = "<?php echo $user_id; ?>";
                    data.for = "dashboard";
                    data.access = 'read_write'; 
                    break;

                case "manager_close":
                    data.advisor = "<?php echo $user_id; ?>";
                    data.status = "close";
                    break;
            }
            
            view_callbacks(data,'post');

        });

        $("#refresh").click(function(){
            $(".se-pre-con").show();
            $.get("<?php echo base_url(); ?>dashboard/get_live_feed_back", function(response){
                $("#live_feed_back_body").html(response);
                $(".se-pre-con").hide("slow");
            });
        });

        $("#overdue_lead_count").click(function(){
            var form = document.createElement('form');
            form.method = "POST";
            form.action = "<?php echo base_url()."dashboard/generate_report" ?>";
            
            var input = document.createElement('input');
            input.type = "text";
            input.name = "toDate";
            input.value = $(this).data('datetime');
            form.appendChild(input);

            input = document.createElement('input');
            input.type = "text";
            input.name = "reportType";
            input.value = "due";
            form.appendChild(input);

            document.body.appendChild(form);
            form.submit();
        });

        $('.emailSiteVisit').on('click', function(){
            $(".se-pre-con").show();
            $.ajax({
                type : 'POST',
                url : "<?= base_url('site-visit-report-mail');?>",
                data:1,
                success: function(res){
                    $(".se-pre-con").hide("slow");
                    if(res == 1)
                        alert('Email Sent Successfully.');
                    else
                        alert('Email Sent fail!');
                }
            });
        });

    });
    // $('#filter_revenue').click(get_revenues());
    function get_revenues(){
        $.get( "<?php echo base_url()."dashboard/get_revenue/" ?>"+$('#revenueMonth').val(), function( data ) {
            $('#revenue_data').html(data);
        });
    }
    function view_callbacks(data, method) {
        var form = document.createElement('form');
        form.method = method;
        form.action = "<?php echo base_url()."view_callbacks?" ?>"+jQuery.param(data);
        for (var i in data) {
            var input = document.createElement('input');
            input.type = "text";
            input.name = i;
            input.value = data[i];
            form.appendChild(input);
        }
        //console.log(form);
        document.body.appendChild(form);
        form.submit();
    }

</script>
</body>
</html>
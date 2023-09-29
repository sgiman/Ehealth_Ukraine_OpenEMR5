<?php include 'include/refresh.php'; ?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<!-- Refresh page  - 20 min, (20min * 60sec = 1200sec) -->
<meta http-equiv="refresh" content="1200">

<?php include 'include/description.php'; ?>

<title>Перелік підрозділів</title>

<?php include 'include/header.php'; ?>

</head>

<body>

    <div id="loading">
        <div class="spinner">
            <div class="bounce1"></div>
            <div class="bounce2"></div>
            <div class="bounce3"></div>
        </div>
    </div>

    <div id="page-wrapper">
        <div id="page-header" class="bg-gradient-9">
            <div class="container">
                <div id="header-logo" class="logo-bg">
                    <a class="logo-content-big" title="EHEALTH" href="Login.php">
                        EHEALTH
                        <span>Проект eHealth</span>
                    </a>
                    <a class="logo-content-small" title="EHEALTH" href="Login.php">
                        EHEALTH
                        <span>Проект eHealth</span>
                    </a>
                </div>

                <div id="header-nav-left" class="mrg5A col-8">
					<!-- *********************** (HEADER) *********************** -->               
					<div class="col-12 font-white">
					<?php 
						$file = '../api/LegalEntityData/name.txt';
						$name = file_get_contents($file);
						echo  $name;  
					?>
					</div>				  
					<!-- ********************************************************* -->

                    <div id="main-nav">
                        <ul class="top-nav pad0A">
                            <li class="col-2 pad5A">
                                <a title="Профайл закладу" href="ProfileOfLegalEntity.php">Медзаклад</a>
                            </li>
                            <li class="col-2 pad5A">
                                <a title="Перелік підрозділів" href="ListOfDivisions.php">Підрозділи</a>
                            </li>
                            <li class="col-2 pad5A">
                                <a title="Перелік працівників" href="ListOfEmployees.php">Працівники</a>
                            </li>
                          </ul>
                    </div>
                </div><!-- #header-nav-left -->
                <div id="header-nav-right" class="col-1 pad0A">
                    <a class="hdr-btn pad5L pad5R" id="sign-out-btn" title="Вийти" href="Login.php">
                        Вийти
                    </a>

                </div><!-- #header-nav-right -->
            </div>
        </div>
		
		
<div id="page-content-wrapper" class="poly-bg-8-80" style="min-height: calc(100vh - 170px);">          
	<div class="container">
		<div id="page-content">

        <!-- Sparklines charts -->
        <script  src="design/widgets/charts/sparklines/sparklines.js"></script>
        <script  src="design/widgets/charts/sparklines/sparklines-demo.js"></script>

        <!-- Skycons -->
        <script  src="design/widgets/skycons/skycons.js"></script>

        <!-- Calendar -->
        <script  src="design/widgets/daterangepicker/moment.js"></script>
        <script  src="design/widgets/calendar/calendar.js"></script>
        <script  src="design/widgets/calendar/calendar-demo.js"></script>

        <!-- Bootstrap Datepicker -->
        <script  src="design/widgets/datepicker/datepicker.js"></script>
        <script >
            /* Datepicker bootstrap */
            $(function () {
                "use strict";
                $('.bootstrap-datepicker').bsdatepicker({
                    format: 'dd-mm-yyyy'
                });
            });
        </script>

        <!-- Data tables -->
        <script  src="design/widgets/datatable/datatable.js"></script>
        <script  src="design/widgets/datatable/datatable-bootstrap.js"></script>
        <!--<script  src="design/widgets/datatable/datatable-tabletools.js"></script>-->
        <script  src="design/widgets/datatable/datatable-reorder.js"></script>

<!--
        <script type="text/javascript">
            /* Datatables export */
            $(document).ready(function () {
                var table = $('#datatable-tabletools').DataTable();
                var tt = new $.fn.dataTable.TableTools(table);

                $(tt.fnContainer()).insertBefore('#datatable-tabletools_wrapper div.dataTables_filter');

                $('.DTTT_container a').addClass('btn btn-default btn-md');

                $('.dataTables_filter input').attr("placeholder", "Пошук...");

            });


            /* Datatables reorder */
            $(document).ready(function () {
                $('#datatable-reorder').DataTable({
                    dom: 'Rlfrtip'
                });

                $('#datatable-reorder_length').hide();
                $('#datatable-reorder_filter').hide();

            });
        </script>
-->

        <!-- Input masks -->
        <script  src="design/widgets/input-mask/inputmask.js"></script>

        <script type="text/javascript">
            /* Input masks */
            $(function () {
                "use strict";
                $(".input-mask").inputmask();
            });
        </script>
		
		<?php 
			//--- org_id ---
			$org_id = file_get_contents('home/org_id.txt');
			echo '<input id="org_id" hidden value='  .  "{$org_id}"  .  '/>';
		?>
       
	   <div class="panel">
            <div class="panel-body">
			
                <div id="page-title">				
                    <h2 class="bg-default mrg20T mrg20B">					
								Перелік підрозділів								
								<!-- DivisionRegister -->
								<div class="header-buttons ">				
									<a class="btn size-md btn-success" href="DivisionRegister.php"
											title="Додати підрозділ">
											<i class="glyph-icon icon-plus"></i>
											Додати підрозділ
									</a> 
								</div>								
                    </h2>					
                </div>

				<!-- ====================== Filter block (???) ====================== -->
                <div class="box-wrapper" hidden>
                    <div class="content-box">
					
                        <h3 class="content-box-header bg-default">
                            <i class="glyph-icon icon-filter"></i>
                            Фільтр для пошуку підрозділу
                            <div class="header-buttons-separator" onclick="showSearchForm();">
                                <a href="#" class="icon-separator toggle-button">
                                    <i class="glyph-icon icon-chevron-down"></i>
                                </a>
                            </div>
                        </h3>
						
                        <div class="content-box-wrapper clearfix hide" id="searchForm">
                            <form class="form-horizontal pad15L pad15R pad20T pad20B justify-content-center">
                                <div class="row">
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label class="control-label col-md-4">Назва</label>
                                            <div class="col-xs-12 col-md-8"><input class="form-control" type="text"></div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-4">Адреса</label>
                                            <div class="col-xs-12 col-md-8"><input class="form-control" type="text"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label class="control-label col-md-4">Телефон</label>
                                            <div class="col-xs-12 col-md-8"><input class="form-control" type="text"></div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-4">Статус</label>
                                            <div class="col-xs-12 col-md-8">
                                                <select class="form-control single">
                                                    <option value="ACTIVE">Відкритий</option>
                                                    <option value="INACTIVE">Закритий</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2 text-center">
                                        <button type="button" class="col-12 btn btn-primary mrg20T" data-bind="click: openResults">
                                            Пошук
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div> <!-- searchForm -->
						
                    </div>
                </div>
				<!-- ===================== END Filter block (???) ===================== -->

             <!-- //////////////////////////////// LIST DIVISIONS //////////////////////////////// -->   
				<div class="example-box-wrapper">
                    <div class="remove-columns" id="datatable-hide-columns">
                        <table class="table table-striped table-bordered table-hover" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>Назва</th>
                                    <th>Адреса</th>
                                    <th>Телефон</th>
                                    <th>Статус</th>
                                </tr>
                            </thead>
                            <tbody data-bind="foreach: divisions">
                                <tr>
								
                                    <td data-title="Назва">
                                        <a target="_self"
                                           data-bind="text: name, attr: {href: href}"></a>
                                    </td>
									
                                    <td data-title="Адреса" data-bind="foreach: addresses">
                                        <p data-bind="text: addressFull"></p>
                                    </td>
									
                                    <td data-title="Телефон" data-bind="foreach: phones">
                                        <p data-bind="text: number"></p>
                                    </td>
									
                                    <td data-title="Статус"
                                        data-bind="text: $root.convertStatus(status())">
                                    </td>
									
                                </tr>
                            </tbody>
                        </table>
                    </div>
					
                </div><!-- example-box-wrapper -->	
					<!-- //////////////////////////////// END LIST DIVISIONS //////////////////////////////// -->   
				
            </div><!-- panel-body -->
        </div><!-- panel -->
    </div><!-- end #page-content -->
</div><!-- end .container -->


<!-- showSearchForm (???) -->
<script>
    function showSearchForm() {
        if (document.getElementById("searchForm").classList.contains("hide")) {
            var active = document.querySelector("#searchForm");
            active.classList.remove("hide");
        } else {
            var d = document.getElementById("searchForm");
            d.className += " hide";
        }
    }
</script>


</div><!-- page-content-wrapper -->
</div><!-- page-wrapper -->

<?php include 'include/footer.php'; ?>
<?php include 'include/notification_modal.php'; ?>
       
    <script src="js/viewmodels/location.js"></script>
    <script src="js/viewmodels/phone.js"></script>
    <script src="js/viewmodels/address_read.js"></script>
    <script src="js/viewmodels/division_read.js"></script>
    <script src="js/pages/listOfDivisionsViewModel.js"></script>


   <!-- openResults (Filter) ???? --> 
	<script type="text/javascript">
        var model = new ListOfDivisionsViewModel();
        ko.applyBindings(model);
   		var org_id = loadFile("home/org_id.txt");	//org_id (client_id)
		model.openResults('org_id');
        $(document).ready(function () {
        });
    </script>


<!-- WIDGETS -->
    <script type="text/javascript" src="design/tether/js/tether.js"></script>
    <script type="text/javascript" src="design/widgets/progressbar/progressbar.js"></script>
    <script type="text/javascript" src="design/widgets/superclick/superclick.js"></script>

    <!-- Input switch alternate -->
    <script type="text/javascript" src="design/widgets/input-switch/inputswitch-alt.js"></script>

    <!-- Slim scroll -->
    <script type="text/javascript" src="design/widgets/slimscroll/slimscroll.js"></script>

    <!-- PieGage -->
    <script type="text/javascript" src="design/widgets/charts/piegage/piegage.js"></script>
    <script type="text/javascript" src="design/widgets/charts/piegage/piegage-demo.js"></script>

    <!-- Screenfull -->
    <script type="text/javascript" src="design/widgets/screenfull/screenfull.js"></script>

    <!-- Content box -->
    <script type="text/javascript" src="design/widgets/content-box/contentbox.js"></script>

    <!-- Overlay -->
    <script type="text/javascript" src="design/widgets/overlay/overlay.js"></script>

    <!-- Widgets init for demo -->
    <script type="text/javascript" src="design/js-init/widgets-init.js"></script>

    <!-- Theme layout -->
    <script type="text/javascript" src="design/themes/admin/layout.js"></script>

    <!-- Theme switcher -->
    <script type="text/javascript" src="design/widgets/theme-switcher/themeswitcher.js"></script>

    <script>
        (function ($) { // Avoid conflicts with other libraries
            'use strict';
            $(function () {
                var settings = {
                        min: 160,
                        scrollSpeed: 400
                    },
                    toTop = $('.scroll-btn'),
                    toTopHidden = true;
                $(window).scroll(function () {
                    var pos = $(this).scrollTop();
                    if (pos > settings.min && toTopHidden) {
                        toTop.stop(true, true).fadeIn();
                        toTopHidden = false;
                    } else if (pos <= settings.min && !toTopHidden) {
                        toTop.stop(true, true).fadeOut();
                        toTopHidden = true;
                    }
                });
                toTop.bind('click touchstart', function () {
                    $('html, body').animate({
                        scrollTop: 0
                    }, settings.scrollSpeed);
                });
            });
        })(jQuery);
    </script>

</body>
</html>

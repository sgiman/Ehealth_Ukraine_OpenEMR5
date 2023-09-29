<?php include 'include/refresh.php'; ?>

<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="UTF-8">

<!-- Refresh page  - 20 min, (20min * 60sec = 1200sec) -->
<meta http-equiv="refresh" content="1200">
	
<?php include 'include/description.php'; ?>

<title>Перелік працівників</title>

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
                    <a class="logo-content-big" title="SCSMED" href="Login.php">
                        EHEALTH
                        <span>Проект eHealth</span>
                    </a>
                    <a class="logo-content-small" title="SCSMED" href="Login.php">
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
                    <a class="hdr-btn pad5L pad5R" id="sign-out-btn" title="Вийти" href="../index.php">
                        Вийти
                    </a>

                </div><!-- #header-nav-right -->
            </div>
        </div>
        <div id="page-content-wrapper" class="poly-bg-8-80" style="min-height: calc(100vh - 170px);">
            
<div class="container">
    <div id="page-content">

        <!-- Data tables -->
		<?php 
			//--- org_id ---
			$org_id = file_get_contents('home/org_id.txt');
			echo '<input id="org_id" hidden value='  .  "{$org_id}"  .  '/>';
		?>

        <div class="panel">
            <div class="panel-body">

                <div id="page-title">				
                    <h2 class="bg-default mrg20T mrg20B">					
								Перелік працівників
								<div class="header-buttons ">				
									<a class="btn size-md btn-success" href="EmployeeRegister.php"
											title="Додати працівника">
											<i class="glyph-icon icon-plus"></i>
											Додати працівника
									</a> 
								</div>						
                    </h2>					
                </div>				

                <!-- /////////////////////////////// Filter block //////////////////////////////-->
                <div class="box-wrapper">
                    <div class="content-box">
                        <h3 class="content-box-header bg-blue">
                            <i class="glyph-icon icon-filter"></i>
                            Фільтр для пошуку
                            <div class="header-buttons-separator" onclick="showSearchForm();">
                                <a href="#" class="icon-separator toggle-button">
                                    <i class="glyph-icon icon-chevron-down"></i>
                                </a>
                            </div>
                        </h3>
                        <div class="content-box-wrapper clearfix" id="searchForm">
                            <form class="form-horizontal">
                                <div class="row">
                                    <div class="col-md-10">
                                        <div class="form-group">
                                            <label class="control-label col-md-2"> Підрозділ </label>
                                            <div class="col-md-10">
                                                <select class="form-control single"
                                                        data-bind="options: divisions,
                                                                optionsText: 'text',
                                                                optionsValue: 'value',
                                                                value: filter_division_id">
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-2">Посада</label>
                                            <div class="col-md-10">
                                                <select class="form-control single"
                                                        data-bind="options: positions,
                                                                optionsCaption: 'Виберіть посаду',
                                                                optionsText: 'text',
                                                                optionsValue: 'value',
                                                                value: filter_position_id">
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="" class="col-md-2 control-label">Дата реєстрації (не раніше)</label>
                                            <div class="col-md-4">
                                                    <input type="date"
                                                           class="form-control"
                                                           data-bind="value: filter_start_date">
                                            </div>
                                            <label class="control-label col-md-2">Статус</label>
                                            <div class="col-md-4">
                                                <select class="form-control single"
                                                        data-bind="value: filter_status">
                                                    <option value="APPROVED">Активний</option>
                                                    <option value="DISMISSED">Звільнений</option>
                                                    <option value="NEW">Новий</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-2">ПІБ працівника</label>
                                            <div class="col-md-10">
                                                <input class="form-control" type="text"
                                                       data-bind="value: filter_name">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2 text-center">
                                        <div class="form-group">
                                            <button type="submit" class="col-10 btn btn-primary mrg20T"
                                                    data-bind="click: openResults">
                                                Пошук
                                            </button>
                                        </div>
                                        <div class="form-group">
                                            <button type="button" class="col-10 btn btn-danger mrg20T"
                                                    onclick="clear_filter()">
                                                Очистити
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
                <!-- /////////////////////////////// end Filter block //////////////////////////////-->

                <div class="example-box-wrapper">
                    <div class="remove-columns hide" id="datatable-hide-columns">
                        <table class="table table-striped table-bordered table-hover" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th class="numeric">Дата реєстрації</th>
                                    <th>ПІБ Лікаря</th>
                                    <th>Посада</th>
                                    <th class="numeric">Статус</th>
                                    <th class="numeric" data-bind="visible: $root.isFilterDismissed">Дата звільнення</th>
                                    <th>Підрозділ</th>
                                </tr>
                            </thead>

                            <tbody data-bind="foreach: employees">
                                <tr>
                                    <td data-title="Дата реєстрації" data-bind="text: start_date"></td>
                                    <td data-title="ПІБ Лікаря">
                                        <a target="_self"
                                           data-bind="text: fullName, attr: {href: href}"></a>
                                    </td>
                                    <td data-title="Посада" data-bind="text: position"></td>
                                    <td data-title="Статус" data-bind="text: status"></td>
                                    <td data-title="Статус" data-bind="text: end_date, visible: $root.isFilterDismissed"></td>
                                    <td data-title="Підрозділ">
                                        <a target="_self"
                                           data-bind="text: division_name, attr: {href: division_href}"></a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div><!-- end #page-content -->
</div><!-- end .container -->


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
    function clear_filter() {
             model.filter_division_id('-1');
             model.filter_position_id(null);
             model.filter_start_date(null);
             model.filter_status('APPROVED');
             model.filter_name(null);
         }
</script>


</div>
</div>

<?php include 'include/footer.php'; ?>
<?php include 'include/notification_modal.php'; ?>
       
    <script src="js/pages/listOfEmployeesViewModel.js"></script>
    <script>
        var model = new ListOfEmployeesViewModel();
        ko.applyBindings(model);
    </script>

<!-- WIDGETS -->
    <script src="design/tether/js/tether.js"></script>
    <script src="design/widgets/progressbar/progressbar.js"></script>
    <script src="design/widgets/superclick/superclick.js"></script>

    <!-- Input switch alternate -->
    <script src="design/widgets/input-switch/inputswitch-alt.js"></script>

    <!-- Slim scroll -->
    <script src="design/widgets/slimscroll/slimscroll.js"></script>

    <!-- PieGage -->
    <script src="design/widgets/charts/piegage/piegage.js"></script>
    <script src="design/widgets/charts/piegage/piegage-demo.js"></script>

    <!-- Screenfull -->
    <script src="design/widgets/screenfull/screenfull.js"></script>

    <!-- Content box -->
    <script src="design/widgets/content-box/contentbox.js"></script>

    <!-- Overlay -->
    <script src="design/widgets/overlay/overlay.js"></script>

    <!-- Widgets init for demo -->
    <script src="design/js-init/widgets-init.js"></script>

    <!-- Theme layout -->
    <script src="design/themes/admin/layout.js"></script>

    <!-- Theme switcher -->
    <script src="design/widgets/theme-switcher/themeswitcher.js"></script>

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

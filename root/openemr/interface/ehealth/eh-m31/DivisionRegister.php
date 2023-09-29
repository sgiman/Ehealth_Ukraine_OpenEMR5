<?php include 'include/refresh.php'; ?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
	
<?php include 'include/description.php'; ?>

<title>Реєстрація підрозділа</title>
	
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
                    <a class="logo-content-big" title="EMCIMED" href="/Home/Logout">
                        EHEALTH
                        <span>Проект eHealth</span>
                    </a>
                    <a class="logo-content-small" title="EMCIMED" href="/Home/Logout">
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
            
				
<!-- For modal -->
<link href="css/modal/bootstrap-modal-bs3patch.css" rel="stylesheet" />
<link href="css/modal/bootstrap-responsive.min.css" rel="stylesheet" />
<link href="css/modal/bootstrap-modal.css" rel="stylesheet" />
<link href="css/for_loader.css" rel="stylesheet" />

<div class="container">
    <div id="page-content">
        <!-- Bootstrap Wizard -->
        <!--<link rel="stylesheet" type="text/css" href="~/design/widgets/wizard/wizard.css">-->
        <script type="text/javascript" src="design/widgets/wizard/wizard.js"></script>
        <script type="text/javascript" src="design/widgets/wizard/wizard-demo.js"></script>


        <!-- Input masks -->
        <script type="text/javascript" src="design/widgets/input-mask/inputmask.js"></script>

        <script type="text/javascript">
                /* Input masks */

                $(function () {
                    "use strict";
                    $(".input-mask").inputmask();
                });
        </script>


        <div id="page-title">
            <h2 class="mrg20T mrg20B">
                <div id="Division"></div>
						РЕЄСТРАЦІЯ ПІДРОЗДІЛА
            </h2>
        </div>

<!--  =================== DATA DIVISION ==================== -->
        <div class="example-box-wrapper">
            <div id="form-wizard-2">
                <div class="tab-content">
                        <div class="content-box">
                            <h3 class="content-box-header bg-blue">
<!-- ************************************************************************************* -->
<span class="fa-stack">
  <span class="fa fa-circle fa-stack-2x"></span>
  <strong class="fa-stack-1x icolor">1</strong>
</span>
<!-- ************************************************************************************* -->															
                                <!--<i class="glyph-icon icon-info-circle"></i>-->
                                Загальна інформація
                            </h3>
							
                            <div class="content-box-wrapper">
                                <div class="form-horizontal pad15L pad15R pad20T pad20B justify-content-center">
                                    <div class="form-group row">
                                        <label class = "control-label col-md-3" >
                                            <span class = "text-danger" >*&nbsp;</span>
                                            Тип
                                        </label>
                                        <div class="col-xs-12 col-md-6">
                                            <select class="form-control single" 
                                                    data-bind="options: division().divisionTypes,
                                                               optionsText: 'text',
                                                               optionsValue: 'value',
                                                               optionsCaption: 'Оберіть тип підрозділу',
                                                                disable: $root.disable_input(),
                                                               value: division().type"></select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class = "control-label col-md-3" >
                                            <span class = "text-danger" >*&nbsp;</span>
                                            Назва
                                        </label>
                                        <div class = "col-xs-12 col-md-6" >
                                            <input class = "form-control" type = "text"
                                                   data-bind = "disable: $root.disable_input(),
                                                                                    value: division().name" />
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class = "control-label col-md-3" >
                                            <span class = "text-danger" >*&nbsp;</span>
                                            Email
                                        </label>
                                        <div class = "col-xs-12 col-md-6" >
                                            <input class = "form-control" type = "text"
                                                   data-bind = "disable: $root.disable_input(), value: division().email" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="content-box-header bg-blue">
<!-- ************************************************************************************* -->
<span class="fa-stack">
  <span class="fa fa-circle fa-stack-2x"></span>
  <strong class="fa-stack-1x icolor">2</strong>
</span>
<!-- ************************************************************************************* -->															
                                <!--<i class="glyph-icon icon-globe"></i>-->
                                Географічні координати
								
                                <div class="header-buttons">
                                    <a href="#" class="btn size-md btn-success mrg7T" title=""
                                       tabindex="-1"
                                       data-bind="click: division().addLocation,
                                            visible: $root.show_button() && !division().location().length">
                                        <i class="glyph-icon icon-plus"></i> Додати координати
                                    </a>
                                </div>
								
                            </div>
                            <div class="content-box-wrapper">
                                <div class="form-horizontal pad15L pad15R pad20T pad20B justify-content-center">
                                    <!-- ko foreach: division().location -->
                                    <div class="form-group text-center">
                                        <label class="control-label col-md-2"><span class="text-danger">*&nbsp;</span>
                                            Широта</label>
                                        <div class = "col-xs-12 col-md-3" >
                                            <input class = "form-control location" type = "text" 
                                                   data-bind = "disable: $root.disable_input(),  value: latitude" placeholder = "__.____" />
                                        </div>
                                        <label class="control-label col-md-2"><span class="text-danger">*&nbsp;</span>
                                            Довгота</label>
                                        <div class = "col-xs-12 col-md-3" >
                                            <input class = "form-control location" type = "text" 
                                                   data-bind = "disable: $root.disable_input(), value: longitude" placeholder = "__.____" />
                                        </div>
                                        <div class="col-md-1 text-right">
                                            <button type="button" class="btn btn-danger"
                                                    tabindex="-1"
                                                    data-bind="click: $parent.division().removeLocation, visible: $root.show_button()">
                                                <span class="glyph-icon icon-remove"></span>
                                            </button>
                                        </div>
                                    </div>
                                    <!-- /ko -->
                                </div>
                            </div>
                        </div>
                        
							<div class="content-box">
                            <h3 class="content-box-header bg-blue">
<!-- ************************************************************************************* -->
<span class="fa-stack">
  <span class="fa fa-circle fa-stack-2x"></span>
  <strong class="fa-stack-1x icolor">3</strong>
</span>
<!-- ************************************************************************************* -->																	
                          <!--<i class="glyph-icon icon-phone-square"></i>-->
								Телефон
								
                                <div class="header-buttons">
                                    <a href="#" class="btn size-md btn-success mrg7T"
                                       tabindex="-1"
                                       title="" data-bind="click: division().addPhone, visible: $root.show_button()">
                                        <i class="glyph-icon icon-plus"></i> Додати телефон
                                    </a>
                                </div>
                            </h3>
							
                            <div class="content-box-wrapper">
                                <div class="form-horizontal">
                                    <!-- ko foreach: division().phones-->
                                    <div class="form-group row">
                                        <label class="control-label col-md-2"><span class="text-danger">*&nbsp;</span>Тип телефону</label>
                                        <div class="col-xs-12 col-md-3">
                                            <select class="form-control single" 
                                                    data-bind="disable: $root.disable_input(), options: $parent.division().phoneTypes,
                                                                                                           optionsText: 'text',
                                                                                                           optionsValue: 'value',
                                                                                                           optionsCaption: 'Оберіть тип телефону',
                                                                                                           value: type"></select>
                                        </div>
                                        <label class="control-label col-md-2"><span class="text-danger">*&nbsp;</span>Номер</label>
                                        <div class="col-xs-12 col-md-3">
                                            <input type="text" class="input-mask form-control phone" 
                                                   data-bind="disable: $root.disable_input(), value: number">
                                        </div>
                                        <div class="col-md-2 text-right" data-bind="if: $root.division().phones().length > 1">
                                            <button type="button" class="btn btn-danger"
                                                    tabindex="-1"
                                                    data-bind="disable: $root.disable_input(), click: $parent.division().removePhone, visible: $root.show_button()">
                                                <span class="glyph-icon icon-remove"></span>
                                            </button>
                                        </div>
                                    </div>
                                    <!-- /ko -->
												<p style = "text-align: right;">Номера телефонів: тільки один мобільний та один стаціонарний </p>
                                </div>
                            </div>
                        </div>
                        <div class="content-box">
                            <h3 class="content-box-header bg-blue">
<!-- ************************************************************************************* -->
<span class="fa-stack">
  <span class="fa fa-circle fa-stack-2x"></span>
  <strong class="fa-stack-1x icolor">4</strong>
</span>
<!-- ************************************************************************************* -->																	
                         <!--<i class="glyph-icon icon-map-marker"></i>-->
								Адреса
                            </h3>
							
                            <div class="content-box-wrapper">
                                <div class="form-horizontal bordered-row">
                                    <!-- ko foreach: division().addresses-->
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label col-md-3"><span class="text-danger">*&nbsp;</span>Тип адреси</label>
                                                <div class="col-md-9">
                                                    <select class="form-control single" 
                                                            data-bind="disable: $root.disable_input(), options: $parent.division().addressTypes,
												  optionsText: 'text',
												  optionsValue: 'value',
												  value: type"></select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-3"><span class="text-danger">*&nbsp;</span>Індекс</label>
                                                <div class="col-md-3"><input class="form-control zip" 
                                                                             data-bind="disable: $root.disable_input(), value: zip" type="text"></div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-3"><span class="text-danger">*&nbsp;</span>Область</label>
                                                <div class="col-md-9">
                                                    <select class="form-control single col-md-9" 
                                                            data-bind="disable: $root.disable_input(), options: $parent.division().regionsOfCountry,
                                                                                                                    optionsCaption: 'Оберіть область',
                                                                                                                    value: area"></select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-3">Район</label>
                                                <div class="col-md-9">
                                                    <select id="region_div" class="form-control single"
                                                            data-bind="disable: $root.disable_input() || area() == 'М.КИЇВ' || area() == 'М.СЕВАСТОПОЛЬ',
                                                                    options: regions,
                                                                    value: selectedRegion,
                                                                    click: loadRegionsOnClick()"></select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-3"><span class="text-danger">*&nbsp;</span>Тип населеного пункту</label>
                                                <div class="col-md-9">
                                                    <select class="form-control single" data-bind="disable: $root.disable_input(), options: $parent.division().settlementTypes,
                                                  optionsText: 'text',
                                                  optionsValue: 'value',
                                                  value: settlement_type"></select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label col-md-3"><span class="text-danger">*&nbsp;</span>Населений пункт</label>
                                                <div class="col-md-9">
                                                    <select class="form-control single" data-bind="disable: $root.disable_input(), options: settlements,
                                                                       optionsText: 'text',
                                                                       optionsValue: 'value',
                                                                       value: settlement_id,
                                                                       click: loadSettlementsOnClick()"></select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-3"><span class="text-danger">*&nbsp;</span>Тип</label>
                                                <div class="col-md-9">
                                                    <select class="form-control single" data-bind="disable: $root.disable_input(), options: $parent.division().streetTypes,
                                                  optionsText: 'text',
                                                  optionsValue: 'value',
                                                  value: street_type"></select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-3"><span class="text-danger">*&nbsp;</span>Вулиця</label>
                                                <div class="col-md-9" data-bind="disable: $root.disable_input(), click: loadStreetsOnClick()">
                                                    <input class="form-control single" list="street_list"
                                                           type="text" data-bind="disable: $root.disable_input(), value: street, attr: {list: dataListID}">
                                                    <datalist id="street_list" data-bind="foreach: streets, attr: {id: dataListID}">
                                                        <option data-bind="disable: $root.disable_input(), value: value"></option>
                                                    </datalist>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-3">
                                                    <span class="text-danger">*&nbsp;</span>Будинок
                                                </label>
                                                <div class="col-md-3">
                                                    <input class="form-control" data-bind="disable: $root.disable_input(), value: building" type="text">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-3">Квартира/офіс</label>
                                                <div class="col-xs-12 col-md-3"><input class="form-control" data-bind="disable: $root.disable_input(), value: apartment" type="text"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr />
                                    <!-- /ko -->
                                </div>
                            </div>
                        </div>
                </div>
            </div>
			
            <div class="text-center">
                <button type="button" class="btn btn-bright-green mrg10T mrg10B" data-bind="click: createDivision, visible: $root.show_button()">
                    Зберегти підрозділ
                </button>
                <div class="alert alert-danger" role="alert" style="display:none; margin-top: 10px;">
                    <span class="glyphicon  glyphicon glyphicon-remove-circle" aria-hidden="true"></span> Будь-ласка, перевірте усі данні. Можливо деяке поле не було заповнено чи вибрано.
                    <!-- <p>На даний момент кількість помилок: <strong data-bind="text: errors().length"></strong> шт.</p>-->
                </div>
            </div>
			
        </div><!-- example-box-wrapper -->
    </div><!-- end #page-content -->
</div><!-- end .container -->

</div>
</div>

<?php include 'include/footer.php'; ?>
<?php include 'include/notification_modal.php'; ?>

<script src="js/viewmodels/location.js"></script>
<script src="js/viewmodels/document.js"></script>
<script src="js/viewmodels/address.js"></script>
<script src="js/viewmodels/phone.js"></script>
<script src="js/viewmodels/division.js"></script>
<script src="js/pages/createDivision.js"></script>
<script src="js/signing/base.js"></script>

<script src="js/modal/bootstrap-modalmanager.js"></script>
<script src="js/modal/bootstrap-modal.js"></script>
<script src="lib/knockout/dist/knockout-validation.js"></script>
<script src="lib/jquery-validation/dist/jquery.mask.js"></script>
<script src="js/features/koValidationWithExtraFeatures.js"></script>

<script type="text/javascript">
    $('.zip').mask('00000', { placeholder: "- - - - -" });
    $('.phone').mask('+38(o00)000-00-00', {
        placeholder: "+38(___)___-__-__",
        translation: {
            'o': { pattern: /[0]/ }
        }
    });
    $('.location').mask('ZL.000000', {
        placeholder: "__.____",
        translation: {
            'Z': { pattern: /[1-9]/ },
            'L': { pattern: /[0-9]/, optional: true }
        }
    });
    var model = new CreateDivisionViewModel();
    ko.applyBindings(model);
    //console.log(ko.toJSON(FormatDataForSending()));
</script>

<script type="text/javascript">

    isPharmacy = function () {
        return '' == "PHARMACY";
    };

    activateLocation = function () {
//        console.log(isPharmacy());
        if (isPharmacy()) {
            model.division().addLocation();
        }
    };

    activateLocation();
</script>

<!-- WIDGETS -->
    <script type="text/javascript" src="design/tether/js/tether.js"></script>
    <script type="text/javascript" src="design/widgets/progressbar/progressbar.js"></script>
    <script type="text/javascript" src="design/widgets/superclick/superclick.js"></script>

    <!-- Input switch alternate -->
    <script type="text/javascript" src="design/widgets/input-switch/inputswitch-alt.js"></script>

    <!-- Slim scroll -->
    <script type="text/javascript" src="design/widgets/slimscroll/slimscroll.js"></script>

    <!-- Slidebars -->
    <!--<script type="text/javascript" src="design/widgets/slidebars/slidebars.js"></script>-->
    <!--<script type="text/javascript" src="design/widgets/slidebars/slidebars-demo.js"></script>-->

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

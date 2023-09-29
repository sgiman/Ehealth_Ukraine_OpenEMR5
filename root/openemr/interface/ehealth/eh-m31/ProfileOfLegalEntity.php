<?php include 'include/refresh.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>

<meta charset="UTF-8">

<?php include 'include/description.php'; ?>

<title>Профайл закладу</title>

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

		<!-- Calendar -->
		<!--<script src="design/widgets/daterangepicker/moment.js"></script>-->
		<!--<script src="design/widgets/calendar/calendar.js"></script>-->
		<!--<script src="design/widgets/calendar/calendar-demo.js"></script>-->

		<!-- Bootstrap Wizard -->
		<script src="design/widgets/wizard/wizard.js"></script>
		<script src="design/widgets/wizard/wizard-demo.js"></script>

		<!-- Input masks -->
		<script src="design/widgets/input-mask/inputmask.js"></script>

		<?php include 'include/sign.php'; ?>

<!-- ====================== page-content-wrapper ====================== -->
<div id="page-content-wrapper" class="poly-bg-8-80">
    <div class="container">
        <div id="page-content">
                        <div id="page-title">
                            <div class="mrg20T mrg20B">
                                <h2 data-bind="text: legal_entity().LEName()"></h2>
								
                                    <div class="header-buttons">
                                        <h2>
													<a class="btn size-md btn-success" href="/openemr/interface/ehealth/eh-m31/ProfileOfLegalEntity.php"
														title="Оновити">
														<i class="glyph-icon icon-repeat"></i>
														Оновити
													</a>	
													
													<a class="btn size-md btn-success" href="#"										
														data-bind="click: allow_edit, visible: show_edit_button()" >
                                              <i class="glyph-icon icon-pencil"></i>
                                              Редагувати
                                           </a>
										   
												</h2>										
                                    </div>
									
                            </div>
                            <h3 data-bind="text: legal_entity().owner_name()"></h3>
                            <h4 data-bind="text: legal_entity().owner_position()"></h4>
                        </div>

            <div class="box-wrapper">
                <div id="form-wizard-2">
                    <div class="tab-content">
					
					

<!-- ============================== custom-step-1 ============================ -->
                        <div class="" id="custom-step-1">
                            <div class="content-box">

                                <h3 class="content-box-header bg-blue">
<!-- ************************************************************************************* -->
<span class="fa-stack">
  <span class="fa fa-circle fa-stack-2x"></span>
  <strong class="fa-stack-1x icolor">1</strong>
</span>
<!-- ************************************************************************************* -->												
                                    <!-- <i class="glyph-icon icon-info"></i>--> 
									Загальна інформація про заклад
                                </h3>

                                <div class="content-box-wrapper clearfix">
                                    <div class="form-horizontal bordered-row">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="control-label col-md-4"><span class="text-danger">*&nbsp;</span>ЄДРПОУ</label>
                                                    <div class="col-md-8">
                                                        <input type="text" name="edrpouInput" class="input-mask form-control edrpou"
                                                               data-bind="disable: legal_entity().id() || $root.disable_input(), value: legal_entity().edrpou" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="form-group">
                                                    <label class="control-label col-md-4"><span class="text-danger">*&nbsp;</span>Тип закладу</label>
                                                    <div class="col-md-8">
                                                        <select class="form-control single"
                                                                data-bind="disable: $root.disable_input(), options: legal_entity().legalEntityTypes,
                                                                                                           optionsText: 'text',
                                                                                                           optionsValue: 'value',
                                                                                                           optionsCaption: 'Оберіть тип закладу',
                                                                                                           value: legal_entity().type"></select>
                                                    </div>
                                                </div>
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
                                    <!-- <i class="glyph-icon  icon-envelope-o"></i>-->
                                    Контакти
									
                                    <div class="header-buttons">
                                        <a href="#" class="btn size-md btn-success mrg7T" title=""
                                           data-bind="click: legal_entity().addPhone, visible: $root.show_button()">
                                            <i class="glyph-icon icon-plus"></i> Додати телефон
                                        </a>
                                    </div>
                                </div>
								
                                <div class="content-box-wrapper">
                                    <form class="form-horizontal">
                                        <!-- ko foreach: legal_entity().phones-->
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label col-md-4"><span class="text-danger">*&nbsp;</span>Тип телефону</label>
                                                    <div class="col-md-8">
                                                        <select class="form-control single"
                                                                data-bind="disable: $root.disable_input(), options: $parent.legal_entity().phoneTypes,
                                                                                                            optionsText: 'text',
                                                                                                            optionsValue: 'value',
                                                                                                            optionsCaption: 'Оберіть тип телефону',
                                                                                                            value: type"></select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label col-md-4"><span class="text-danger">*&nbsp;</span>Номер</label>
                                                    <div class="col-md-6">
                                                        <input type="text" class="form-control phone" placeholder="+38(___) ___ ____"
                                                               data-bind="disable: $root.disable_input(), textInput: number">
                                                    </div>
                                                    <div class="col-md-2 text-right" data-bind="if: $root.legal_entity().phones().length > 1">
                                                        <button type="button" class="btn btn-danger"
                                                                title="Видалити телефон"
                                                                data-bind="click: $parent.legal_entity().removePhone, visible: $root.show_button()">
                                                            <span class="glyph-icon icon-close"></span>
                                                        </button>
                                                    </div>

                                                </div>
                                                <!-- /ko -->
                                            </div>
                                        </div>
                                        <!-- /ko -->
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label col-md-4"><span class="text-danger">*&nbsp;</span>Електронна пошта</label>
                                                    <div class="col-md-8">
                                                        <input type="text" class="form-control email" placeholder="name@domain.com"
                                                               data-bind="disable: $root.disable_input(), value: legal_entity().email" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label col-md-4">Веб-сайт</label>
                                                    <div class="col-md-8">
                                                        <input type="text" class="form-control"
                                                               data-bind="disable: $root.disable_input(), value: legal_entity().website" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label col-md-4">Код отримувача</label>
                                                    <div class="col-md-8">
                                                        <input type="text" class="form-control"
                                                               data-bind="disable: $root.disable_input(), value: legal_entity().receiver_funds_code" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="control-label col-md-2">Бенефіціар</label>
                                                    <div class="col-md-10">
                                                        <input type="text" class="form-control"
                                                               data-bind="disable: $root.disable_input(), value: legal_entity().beneficiary" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
										<p style = "text-align: right;">Номера телефонів: тільки один мобільний та один стаціонарний </p>
                                </div>


                                <h3 class="content-box-header bg-blue">
<!-- ************************************************************************************* -->
<span class="fa-stack">
  <span class="fa fa-circle fa-stack-2x"></span>
  <strong class="fa-stack-1x icolor">3</strong>
</span>
<!-- ************************************************************************************* -->												
                               <!-- <i class="glyph-icon  icon-map-marker"></i>-->
										Адреса розташування
                                </h3>
								
                                <div class="content-box-wrapper">
                                    <form class="form-horizontal bordered-row">
                                        <!-- ko with: legal_entity().residence_address -->
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label col-md-4"><span class="text-danger">*&nbsp;</span>Тип адреси</label>
                                                    <div class="col-md-8">
                                                        <select class="form-control single"
                                                                data-bind="disable: $root.disable_input(), options: $root.legal_entity().addressTypes,
                                                                                                            optionsText: 'text',
                                                                                                            optionsValue: 'value',
                                                                                                            value: type"></select>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-4"><span class="text-danger">*&nbsp;</span>Область</label>
                                                    <div class="col-md-8">
                                                        <select class="form-control single"
                                                                data-bind="disable: $root.disable_input(), options: $root.legal_entity().regionsOfCountry,
                                                                                                                    optionsCaption: 'Оберіть область',
                                                                                                                    value: area"></select>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-4">Район</label>
                                                    <div class="col-md-8">
                                                        <select class="form-control single"
                                                                data-bind="disable: disabled_region() || $root.disable_input(),
                                                                    options: regions,
                                                                    value: selectedRegion,
                                                                    click: loadRegionsOnClick()"></select>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-4"><span class="text-danger">*&nbsp;</span>Тип населеного пункту</label>
                                                    <div class="col-md-8">
                                                        <select class="form-control single"
                                                                data-bind="disable: $root.disable_input(), options: $root.legal_entity().settlementTypes,
                                                                                                    optionsText: 'text',
                                                                                                    optionsValue: 'value',
                                                                                                    value: settlement_type"></select>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-4">
                                                        <span class="text-danger">*&nbsp;</span>
                                                        Населений пункт
                                                    </label>
                                                    <div class="col-md-8">
                                                        <select class="form-control single"
                                                                data-bind="disable: $root.disable_input(), options: settlements,
                                                                        optionsText: 'text',
                                                                        optionsValue: 'value',
                                                                        value: settlement_id,
                                                                        click: loadSettlementsOnClick()"></select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label col-md-4"><span class="text-danger">*&nbsp;</span>Тип</label>
                                                    <div class="col-md-6">
                                                        <select class="form-control single"
                                                                data-bind="disable: $root.disable_input(), options: $root.legal_entity().streetTypes,
                                                  optionsText: 'text',
                                                  optionsValue: 'value',
                                                  value: street_type"></select>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-4"><span class="text-danger">*&nbsp;</span></label>
                                                    <div class="col-md-8"
                                                         data-bind="click: loadStreetsOnClick()">
                                                        <input id="street_input" class="form-control single" type="text"
                                                               data-bind="disable: $root.disable_input(), value: street, attr: {list: dataListID}" list="street_list">
                                                        <datalist id="street_list"
                                                                  data-bind="foreach: streets, attr: {id: dataListID}">
                                                            <option data-bind="disable: $root.disable_input(), value: value"></option>
                                                        </datalist>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-4"><span class="text-danger">*&nbsp;</span>Будинок</label>
                                                    <div class="col-md-4">
                                                        <input class="form-control"
                                                               data-bind="disable: $root.disable_input(), value: building" type="text">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-4">
                                                        Квартира/офіс
                                                    </label>
                                                    <div class="col-xs-12 col-md-4">
                                                        <input class="form-control"
                                                               data-bind="disable: $root.disable_input(), value: apartment" type="text">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-4">
                                                        <span class="text-danger">*&nbsp;</span>
                                                        Індекс
                                                    </label>
                                                    <div class="col-md-4">
                                                        <input class="form-control zip"
                                                               data-bind="disable: $root.disable_input(), value: zip" type="text">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- /ko -->
                                    </form>
                                </div>
                            </div>
                        </div>

						
<!-- ============================== custom-step-2============================ -->
                        <div class="" id="custom-step-2">
                            <div class="content-box">

                                <h3 class="content-box-header bg-blue">
<!-- ************************************************************************************* -->
<span class="fa-stack">
  <span class="fa fa-circle fa-stack-2x"></span>
  <strong class="fa-stack-1x icolor">4</strong>
</span>
<!-- ************************************************************************************* -->																		
                                <!--<i class="glyph-icon  icon-user"></i>-->
                                Керівник
                                </h3>

                                <div class="content-box-wrapper">
                                    <form class="form-horizontal bordered-row">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label col-md-4">
                                                        <span class="text-danger">*&nbsp;</span>
                                                        Прізвище
                                                    </label>
                                                    <div class="col-xs-12 col-md-8">
                                                        <input class="form-control"
                                                               data-bind="disable: $root.disable_input(), value: legal_entity().owner().last_name" type="text" />
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-4">
                                                        <span class="text-danger">*&nbsp;</span>
                                                        Ім'я
                                                    </label>
                                                    <div class="col-xs-12 col-md-8">
                                                        <input class="form-control"
                                                               data-bind="disable: $root.disable_input(), value: legal_entity().owner().first_name" type="text" />
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-4">
                                                        <span class="text-danger">*&nbsp;</span>
                                                        По батькові
                                                    </label>
                                                    <div class="col-xs-12 col-md-8">
                                                        <input class="form-control"
                                                               data-bind="disable: $root.disable_input(), value: legal_entity().owner().second_name" type="text" />
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-4">
                                                        <span class="text-danger">*&nbsp;</span>
                                                        Стать
                                                    </label>
                                                    <div class="col-xs-12 col-md-8">
                                                        <select class="form-control single"
                                                                data-bind="disable: $root.disable_input(), options: legal_entity().genders,
                                                                                                        optionsText: 'text',
                                                                                                        optionsValue: 'value',
                                                                                                        optionsCaption: 'Оберіть стать',
                                                                                                        value: legal_entity().owner().gender"></select>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label col-md-4">
                                                        <span class="text-danger">*&nbsp;</span>
                                                        Дата народження
                                                    </label>
                                                    <div class="col-xs-12 col-md-6">
                                                        <input type="date" class="form-control"
                                                               data-bind="disable: $root.disable_input(), value: legal_entity().owner().birth_date">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-4">
                                                        <span class="text-danger">*&nbsp;</span>
                                                        РНОКПП (ІПН)
                                                    </label>
                                                    <div class="col-xs-12 col-md-6">
                                                        <input class="input-mask form-control inn" placeholder="_ _ _ _ _ _ _ _ _ _"
                                                               data-bind="disable: $root.disable_input(), value: legal_entity().owner().tax_id" type="text">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-4">
                                                        <span class="text-danger">*&nbsp;</span>
                                                        Посада
                                                    </label>
                                                    <div class="col-xs-12 col-md-6">
                                                        <select class="form-control single"
                                                                data-bind="disable: $root.disable_input(), options: legal_entity().positions,
                                                                                                        optionsText: 'text',
                                                                                                        optionsValue: 'value',
                                                                                                        optionsCaption: 'Оберіть посаду',
                                                                                                        value: legal_entity().owner().position"></select>
                                                    </div>
                                                </div>
                                                <div class="form-group" data-bind="visible: !$root.disable_input()">
                                                    <label class="control-label col-md-4">
                                                        <span class="text-danger">*&nbsp;</span>
                                                        Електронна пошта
                                                    </label>
                                                    <div class="col-xs-12 col-md-6">
                                                        <input class="form-control email"
                                                               data-bind="value: legal_entity().owner().email" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>


                                <div class="content-box-header bg-blue mar20T">
<!-- ************************************************************************************* -->
<span class="fa-stack">
  <span class="fa fa-circle fa-stack-2x"></span>
  <strong class="fa-stack-1x icolor">5</strong>
</span>
<!-- ************************************************************************************* -->																		
                                    <!-- <i class="glyph-icon  icon-envelope-o"></i>-->
                                    Контакти керівника
                                    
											<div class="header-buttons">
                                        <a href="#" class="btn size-md btn-success mrg7T" title=""
                                           data-bind="click: legal_entity().owner().addPhone, visible: $root.show_button()">
                                            <i class="glyph-icon icon-plus"></i>
                                            Додати телефон ?
                                        </a>
                                    </div>
                                </div>
								
                                <div class="content-box-wrapper">
                                    <form class="form-horizontal bordered-row">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <!-- ko foreach: legal_entity().owner().phones-->
                                                <div class="form-group clearfix row">
                                                    <label class="control-label col-md-2">
                                                        <span class="text-danger">*&nbsp;</span>
                                                        Тип телефону
                                                    </label>
                                                    <div class="col-md-4">
                                                        <select class="form-control single"
                                                                data-bind="disable: $root.disable_input(), options: $parent.legal_entity().phoneTypes,
                                                                                                        optionsText: 'text',
                                                                                                        optionsValue: 'value',
                                                                                                        optionsCaption: 'Оберіть тип телефону',
                                                                                                        value: type"></select>
                                                    </div>
                                                    <label class="control-label col-md-2">
                                                        <span class="text-danger">*&nbsp;</span>
                                                        Номер
                                                    </label>
                                                    <div class="col-xs-12 col-md-3">
                                                        <input type="text" class="input-mask form-control phone" placeholder="+380(__) ___ ____"
                                                               data-bind="disable: $root.disable_input(), textInput: number">
                                                    </div>
                                                    <div class="col-md-1 text-right" data-bind="if: $root.legal_entity().owner().phones().length > 1">
                                                        <button type="button" class="btn btn-danger" title="Видалити телефон"
                                                                data-bind="click: $parent.legal_entity().owner().removePhone, visible: $root.show_button()">
                                                            <span class="glyph-icon icon-close"></span>
                                                        </button>
                                                    </div>
                                                </div>
                                                <!-- /ko -->
                                            </div>
                                        </div>
                                    </form>
											<p style = "text-align: right;">Номера телефонів: тільки один мобільний та один стаціонарний </p>
                                </div>


                                <div class="content-box-header bg-blue mar20T">
<!-- ************************************************************************************* -->
<span class="fa-stack">
  <span class="fa fa-circle fa-stack-2x"></span>
  <strong class="fa-stack-1x icolor">6</strong>
</span>
<!-- ************************************************************************************* -->																		
                                    <!-- <i class="glyph-icon  icon-envelope-o"></i>-->
                                    Документи, що засвідчують особу керівника
                                    
											<div class="header-buttons">
                                        <a href="#" class="btn size-md btn-success mrg7T" title=""
                                           data-bind="click: legal_entity().owner().addDocument, visible: $root.show_button()">
                                            <i class="glyph-icon icon-plus"></i>
                                            Додати документ ?
                                        </a>
                                    </div>
                                </div>
                                <div class="content-box-wrapper">
                                    <form class="form-horizontal bordered-row">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <!-- ko foreach: legal_entity().owner().documents -->
                                                <div class="content-box">
                                                    <div class="form-group row">
                                                        <label class="control-label col-md-2"><span class="text-danger">*&nbsp;</span>Тип документу</label>
                                                        <div class="col-xs-12 col-md-4">
                                                            <select class="form-control single"
                                                                    data-bind="disable: $root.disable_input(),
                                                                              options: $parent.legal_entity().documentTypes,
                                                                              optionsText: 'text',
                                                                              optionsValue: 'value',
                                                                              optionsCaption: 'Оберіть тип документу',
                                                                              value: type"></select>
                                                        </div>
                                                        <label class="control-label col-md-2"><span class="text-danger">*&nbsp;</span>Номер та серія</label>
                                                        <div class="col-xs-12 col-md-4">
                                                            <input type="text" class="input-mask form-control"
                                                                   data-bind="disable: $root.disable_input(), value: number">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="control-label col-md-2">
                                                            Ким виданий
                                                        </label>
                                                        <div class="col-xs-12 col-md-10">
                                                            <input class="form-control single"
                                                                   data-bind="disable: $root.disable_input(),
                                                                              value: issued_by" />
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="control-label col-md-2">
                                                            Дата видачі
                                                        </label>
                                                        <div class="col-xs-12 col-md-4">
                                                            <input type="date" class=" form-control"
                                                                   data-bind="disable: $root.disable_input(),
                                                                              value: issued_at" />
                                                        </div>
                                                        <div class="col-md-5" data-bind="if: isExpirationDateRequired_ko">
                                                            <label class="control-label col-md-5">
                                                                <span class="text-danger" data-bind="if: isExpirationDateRequired_ko">*&nbsp;</span>
                                                                Дійсний до
                                                            </label>
                                                            <div class="col-xs-12 col-md-7">
                                                                <input type="date" class=" form-control"
                                                                       data-bind="disable: isExpirationDateRequired_ko() == false || $root.disable_input(),
                                                                              value: expiration_date" />
                                                            </div>
                                                        </div>
                                                        <div class="col-xs-12 col-md-1" data-bind="if: $root.legal_entity().owner().documents().length > 1">
                                                            <button type="button" class="btn btn-danger" title="Видалити документ, що засвідчує особу"
                                                                    data-bind="visible: $root.show_button(),
                                                                                click: $parent.legal_entity().owner().removeDocument">
                                                                <span class="glyph-icon icon-remove"></span>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- /ko -->
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
						

<!-- ============================== custom-step-3============================ -->
                        <div class="" id="custom-step-3">
                            <div class="content-box">

                                <h3 class="content-box-header bg-blue">
<!-- ************************************************************************************* -->
<span class="fa-stack">
  <span class="fa fa-circle fa-stack-2x"></span>
  <strong class="fa-stack-1x icolor">7</strong>
</span>
<!-- ************************************************************************************* -->																		
                                <!--<i class="glyph-icon  icon-list-ul"></i>-->
                                Акредитація
                                </h3>
                                
										<div class="content-box-wrapper">
                                    <form class="form-horizontal" data-bind="with: legal_entity().accreditation">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group clearfix">
                                                    <label class="control-label col-md-2">
                                                        <span class="text-danger">*&nbsp;</span>
                                                        Категорія
                                                    </label>
                                                    <div class="col-xs-12 col-md-4">
                                                        <select class="form-control single"
                                                                data-bind="disable: $root.disable_input(), options: $root.legal_entity().accreditationCategories,
                                                                  optionsText: 'text',
                                                                  optionsValue: 'value',
                                                                  optionsCaption: 'Оберіть категорію',
                                                                  value: category"></select>
                                                    </div>
                                                    <div>
                                                        <label class="control-label col-md-2">
                                                            <span class="text-danger" data-bind="if: category() != 'NO_ACCREDITATION'">*&nbsp;</span>
                                                            Номер наказу
                                                        </label>
                                                        <div class="col-xs-12 col-md-4">
                                                            <input class="form-control"
                                                                   data-bind="enable: category() != 'NO_ACCREDITATION' && !$root.disable_input(),
                                                                        value: order_no" type="text">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-2">
                                                        Дата видачі
                                                    </label>
                                                    <div class="col-xs-12 col-md-4">
                                                        <input type="date" class="form-control"
                                                               data-bind="enable: category() != 'NO_ACCREDITATION' && !$root.disable_input(),
                                                                                value: issued_date">
                                                    </div>
                                                    <label class="control-label col-md-2">
                                                        Дата початку дії
                                                    </label>
                                                    <div class="col-xs-12 col-md-4">
                                                        <input type="date" class="form-control"
                                                               data-bind="enable: category() != 'NO_ACCREDITATION' && !$root.disable_input(),
                                                                                value: order_date">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-2">
                                                        Дата закінчення дії
                                                    </label>
                                                    <div class="col-xs-12 col-md-4">
                                                        <input type="date" class="form-control"
                                                               data-bind="enable: category() != 'NO_ACCREDITATION' && !$root.disable_input(),
                                                                                value: expiry_date">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>

                                <div class="content-box-header bg-blue">
<!-- ************************************************************************************* -->
<span class="fa-stack">
  <span class="fa fa-circle fa-stack-2x"></span>
  <strong class="fa-stack-1x icolor">8</strong>
</span>
<!-- ************************************************************************************* -->																		
                                 <!-- <i class="glyph-icon  icon-list-ul"></i> -->
                                 Ліцензії
                                </div>
                                
										<div class="content-box-wrapper">
                                    <form class="form-horizontal bordered-row" data-bind="with: legal_entity().license">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group clearfix">
                                                    <label class="control-label col-md-2">
                                                        <span class="text-danger">*&nbsp;</span>
                                                        Тип ліцензії
                                                    </label>
                                                    <div class="col-xs-12 col-md-4">
                                                        <select class="form-control single"
                                                                data-bind="disable: $root.disable_input(), options: $root.legal_entity().licenseTypes,
                                                                  optionsText: 'text',
                                                                  optionsValue: 'value',
                                                                  optionsCaption: 'Оберіть тип',
                                                                  value: type"></select>
                                                    </div>
                                                </div>
                                                <div class="form-group clearfix">
                                                    <label class="control-label col-md-2">
                                                        <span class="text-danger">*&nbsp;</span>
                                                        Номер ліцензії
                                                    </label>
                                                    <div class="col-xs-12 col-md-4">
                                                        <input class="form-control"
                                                               data-bind="disable: $root.disable_input(), value: license_number" type="text">
                                                    </div>
                                                    <label class="control-label col-md-2">
                                                        <span class="text-danger">*&nbsp;</span>
                                                        Номер наказу
                                                    </label>
                                                    <div class="col-xs-12 col-md-4">
                                                        <input class="form-control"
                                                               data-bind="disable: $root.disable_input(), value: order_no" type="text">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-2">
                                                        <span class="text-danger">*&nbsp;</span>
                                                        Ким видана
                                                    </label>
                                                    <div class="col-xs-12 col-md-10">
                                                        <input class="form-control"
                                                               data-bind="disable: $root.disable_input(), value: issued_by" type="text">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-2">
                                                        <span class="text-danger">*&nbsp;</span>
                                                        Дата видачі
                                                    </label>
                                                    <div class="col-xs-12 col-md-4">
                                                        <input type="date" class="form-control"
                                                               data-bind="disable: $root.disable_input(), value: issued_date">
                                                    </div>

                                                    <label class="control-label col-md-2">
                                                        <span class="text-danger">*&nbsp;</span>
                                                        Активна з
                                                    </label>
                                                    <div class="col-xs-12 col-md-4">
                                                        <input type="date" class="form-control"
                                                               data-bind="disable: $root.disable_input(), value: active_from_date">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-2">
                                                        Дата закінчення дії
                                                    </label>
                                                    <div class="col-xs-12 col-md-4">
                                                        <input type="date" class="form-control"
                                                               data-bind="disable: $root.disable_input(), value: expiry_date">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-2">
                                                        <span class="text-danger">*&nbsp;</span>
                                                        Вид діяльності
                                                    </label>
                                                    <div class="col-xs-12 col-md-10">
                                                        <textarea class="form-control"
                                                                  data-bind="disable: $root.disable_input(), value: what_licensed" aria-multiline="true" rows="3"></textarea>
                                                    </div>
                                                </div>
                                                <div class="form-group" data-bind="if: $root.legal_entity().medical_service_provider">
                                                    <div class="col-md-12 text-right">
                                                        <button type="button" class="btn btn-danger" title="Видалити ліцензію"
                                                                data-bind="click: $parent.legal_entity().medical_service_provider().removeLicense, visible: $root.show_button()">
                                                            <span class="glyph-icon icon-close"> Видалити ліцензію</span>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div><!-- content-box-wrapper -->
                            </div><!-- content-box -->
                        </div><!-- custom-step-3 -->


<!-- ============================== custom-step-4 (ЄДР) ============================ -->
                        <div class="" id="custom-step-4">
                            <div class="content-box" style="display: none;" data-bind="if: $root.legal_entity().edr().edrpou, visible: legal_entity().edr().edrpou">
                              
										<h3 class="content-box-header bg-blue">
<!-- ************************************************************************************* -->
<span class="fa-stack">
  <span class="fa fa-circle fa-stack-2x"></span>
  <strong class="fa-stack-1x icolor glyph-icon icon-info"></strong>
</span>
<!-- ************************************************************************************* -->												
										<!-- <i class="glyph-icon icon-info"></i> -->
										<span>ЄДР: Інформація з ЄДРПОУ</span>
                                </h3>

                                <div class="content-box-wrapper clearfix">
                                    <div class="form-horizontal bordered-row" data-bind="with: legal_entity().edr">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label col-md-4"><span class="text-danger">*&nbsp;</span>Повне найменування</label>
                                                    <div class="col-md-8">
                                                        <input type="text" class="form-control"
                                                               data-bind="value: name" disabled />
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-4"><span class="text-danger">*&nbsp;</span>Скорочене найменування</label>
                                                    <div class="col-md-8">
                                                        <input type="text" class="form-control"
                                                               data-bind="value: short_name" disabled />
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-4"><span class="text-danger">*&nbsp;</span>Публічне найменування</label>
                                                    <div class="col-md-8">
                                                        <input type="text" class="form-control"
                                                               data-bind="value: public_name" disabled />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label col-md-4"><span class="text-danger">*&nbsp;</span>ЄДРПОУ</label>
                                                    <div class="col-md-8">
                                                        <input type="text" class="input-mask form-control edrpou"
                                                               data-bind="value: edrpou" disabled />
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-4"><span class="text-danger">*&nbsp;</span>Організаційно правова форма</label>
                                                    <div class="col-md-8">
                                                        <select class="form-control single"
                                                                data-bind="options: $root.legal_entity().legalForms,
                                                                                                           optionsText: 'text',
                                                                                                           optionsValue: 'value',
                                                                                                           optionsCaption: 'Оберіть тип підприємства',
                                                                                                           value: legal_form" disabled></select>
                                                    </div>
                                                </div>
															
														<!-- ==================== STATE (HIDE) ====================  -->	
                                                <div class="form-group" hidden>
                                                    <label class="control-label col-md-4"><span class="text-danger">*&nbsp;</span>State</label>
                                                    <div class="col-md-8">
                                                        <input type="text" class="input-mask form-control"
                                                               data-bind="value: state" disabled />
                                                    </div>
                                                </div>
														<!-- =================================================  -->	
												
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="content-box-header bg-blue">
<!-- ************************************************************************************* -->
<span class="fa-stack">
  <span class="fa fa-circle fa-stack-2x"></span>
  <strong class="fa-stack-1x icolor glyph-icon icon-info"></strong>
</span>
<!-- ************************************************************************************* -->																		
                                    <!-- <i class="glyph-icon  icon-list-ul"></i> -->
                                    ЄДР: Коди класифікації видів діяльності (КВЕД)
									
                                </div>
                                <div class="content-box-wrapper">
                                    <form class="form-horizontal bordered-row">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <table>
                                                    <thead>
                                                        <tr>
                                                            <th class="col-md-2">Код</th>
                                                            <th class="col-md-8">Назва</th>
                                                            <th class="col-md-2">Основний вид діяльності</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody data-bind="foreach: legal_entity().edr().kveds">
                                                        <tr>
                                                            <td class="col-md-2" data-title="Код">
                                                                <p data-bind="text: code"></p>
                                                            </td>
                                                            <td class="col-md-8" data-title="Назва">
                                                                <p data-bind="text: name"></p>
                                                            </td>
                                                            <td class="col-md-2" data-title="Основний вид діяльності">
                                                                <input type="checkbox" data-bind="checked: is_primary" disabled >
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </form>
                                </div>

                                <h3 class="content-box-header bg-blue">
<!-- ************************************************************************************* -->
<span class="fa-stack">
  <span class="fa fa-circle fa-stack-2x"></span>
  <strong class="fa-stack-1x icolor glyph-icon icon-info"></strong>
</span>
<!-- ************************************************************************************* -->																		
										<!--<i class="glyph-icon icon-map-marker"></i> -->
										ЄДР: Адреса реєстрації
                                </h3>
                                
										<div class="content-box-wrapper clearfix">
                                    <div class="form-horizontal bordered-row" data-bind="with: legal_entity().edr().registration_address">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <p style="margin-left: 40px" data-bind="text: country() + ', ' + address()"></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div><!-- custom-step-4 -->



<!-- ============================== custom-step-5============================ -->
                        <div class="" id="custom-step-5">
                            <div class="content-box">                              
										<h3 class="content-box-header bg-blue">
<!-- ************************************************************************************* -->
<span class="fa-stack">
  <span class="fa fa-circle fa-stack-2x"></span>
  <strong class="fa-stack-1x icolor glyph-icon icon-check"></strong>
</span>
<!-- ************************************************************************************* -->												
								<!-- <i class="glyph-icon icon-info"></i> -->
								<span>НСЗУ: Статуси закладу</span>
                           </h3>
						   
							<div class="content-box-wrapper clearfix">
								<div class="form-horizontal bordered-row">									
									<div class="row">
										
										<div class="col-md-6">	
									 
												<div class="form-group">
													<label class="control-label col-md-4">
														<span class="text-danger"></span>
														Верифікація ЄДР
													</label>
                                           <div class="col-md-8">
														<input type="text" class="form-control" 
														data-bind="value: legal_entity().edr_verified" disabled />																														
													</div>
                                        </div>
												
                                        <div class="form-group">
													<label class="control-label col-md-4">
														<span class="text-danger"></span>
														Статус розгляду НСЗУ
													</label>
                                           <div class="col-md-8">
														<input type="text" class="form-control"
														data-bind="value: legal_entity().nhs_reviewed" disabled />																
                                           </div>
												</div>
												
                                        <div class="form-group">
													<label class="control-label col-md-4">
														<span class="text-danger"></span>
														Верифікація НСЗУ
													</label>
													<div class="col-md-8">
														<input type="text" class="form-control" 
														data-bind="value: legal_entity().nhs_verified" disabled />																
													</div>
                                        </div>
												
										</div><!-- col-md-6 -->
											
										<div class="col-md-6">
											
												<div class="form-group" >
													<label class="control-label col-md-4">
														<span class="text-danger"></span>
														Status
													</label>                                                   
													<div class="col-md-6">
														<input type="text" class="form-control" 
														data-bind="value: legal_entity().status" disabled />																
													</div>
												</div>

												<div class="form-group" >
													<label class="control-label col-md-4">
														<span class="text-danger" ></span>
														Type
													</label>                                                   
													<div class="col-md-6">
														<input type="text" class="form-control" 
														data-bind="value: legal_entity().type" disabled />																
													</div>
												</div>
												
										</div><!-- col-md-6 -->
											
											
											</div><!-- row -->
										</div><!-- form-horizontal bordered-row -->
									</div><!-- content-box-wrapper clearfix -->


                                <h3 class="content-box-header bg-blue">
<!-- ************************************************************************************* -->
<span class="fa-stack">
  <span class="fa fa-circle fa-stack-2x"></span>
  <strong class="fa-stack-1x icolor glyph-icon icon-check"></strong>
</span>
<!-- ************************************************************************************* -->																		
										<!--<i class="glyph-icon icon-map-marker"></i> -->
										НСЗУ: Коментар
                                </h3>                               
										<div class="content-box-wrapper clearfix">
                                    <div class="form-horizontal bordered-row" data-bind="text: legal_entity().nhs_comment"></div>
                                </div>
                            </div>
                        </div><!-- custom-step-5 -->
<!-- ============================== end custom-step-5 ============================== -->


                    </div><!-- tab-content -->
                </div><!-- form-wizard-2 -->


                <div class="text-center">
                    <button type="button" data-bind="click: showAgreement, visible: $root.show_button()"
                            class="btn btn-bright-green mrg10T mrg10B">
                        Підписати та створити заклад
                    </button>
                    <div class="alert alert-success" role="alert" style="display:none; margin-top: 10px;"> <span class="glyphicon  glyphicon glyphicon-ok-circle" aria-hidden="true"></span> Form has submitted with successful </div>
                    <div class="alert alert-danger" role="alert" style="display:none; margin-top: 10px;">
                        <span class="glyphicon  glyphicon glyphicon-remove-circle" aria-hidden="true"></span> Будь-ласка, перевірте усі данні. Можливо деяке поле не було заповнено чи вибрано.
                        <!-- <p>На даний момент кількість помилок: <strong data-bind="text: errors().length"></strong> шт.</p>-->
                    </div>
                </div>
            </div>
			
			
        </div><!-- end #page-content -->
    </div><!-- end .container -->
</div><!-- end #page-content-wrapper -->

</div><!-- page-wrapper -->
</div><!-- body ? -->

<?php include 'include/footer.php'; ?>
<?php include 'include/notification_modal.php'; ?>
       
    <script src="js/modal/bootstrap-modalmanager.js"></script>
    <script src="js/modal/bootstrap-modal.js"></script>

    <script src="js/viewmodels/accreditation.js"></script>
    <script src="js/viewmodels/document.js"></script>
    <script src="js/viewmodels/address.js"></script>
    <script src="js/viewmodels/phone.js"></script>
    <script src="js/viewmodels/security.js"></script>
    <script src="js/viewmodels/publicoffer.js"></script>
    <script src="js/viewmodels/legalEntityV2/edr.js"></script>
    <script src="js/viewmodels/legalEntityV2/owner.js"></script>
    <script src="js/viewmodels/legalEntityV2/license.js"></script>
    <script src="js/viewmodels/legalEntityV2/legalentity_v2.js"></script>
    <script src="js/features/koValidationWithExtraFeatures.js"></script>

    <script src="lib/knockout/dist/knockout-validation.js"></script>
    <script src="lib/jquery-validation/dist/jquery.validate.js"></script>
    <script src="lib/jquery-validation/dist/jquery.mask.js"></script>

    <script src="js/pages/createlegalentityV2.js"></script>
    <script defer="defer" type="text/JavaScript" src="js/signing/signLib.min.js"></script>
	
    <script>
        function bs_input_file() {
            $(".input-file").before(
                function () {
                    if (!$(this).prev().hasClass('input-ghost')) {
                        var element = $("<input type='file' class='input-ghost' style='visibility:hidden; height:0'>");
                        element.attr("name", $(this).attr("name"));
                        element.change(function () {
                            element.next(element).find('input').val((element.val()).split('\\').pop());
                        });
                        $(this).find("button.btn-choose").click(function () {
                            element.click();
                        });
                        $(this).find("button.btn-reset").click(function () {
                            element.val(null);
                            $(this).parents(".input-file").find('input').val('');
                        });
                        $(this).find('input').css("cursor", "pointer");
                        $(this).find('input').mousedown(function () {
                            $(this).parents('.input-file').prev().click();
                            return false;
                        });
                        return element;
                    }
                }
            );
        }

        var model;
        $(document).ready(function () {
            $('.inn').mask('Z000000000', {
                translation: {
                    'Z': {
                        pattern: /[1-9]/
                    }
                }
            });
            $('.zip').mask('00000', { placeholder: "- - - - -" });
            $('.edrpou').mask('Z0000000LL', {
                translation: {
                    'Z': { pattern: /[0-9]/ },
                    'L': { pattern: /[0-9]/, optional: true }
                }
            });
            //$('.birth').mask('00.00.0000', { placeholder: "__.__.____" });
            //$('.phone2').mask('+38(000)000-00-00', { placeholder: "+380(__)___-__-__" });
            $('.phone').mask('+38(o00)000-00-00', {
                placeholder: "+38(___)___-__-__",
                translation: {
                    'o': {
                        pattern: /[0]/
                    }
                }
            });
		
			
			//--- org_id (client_id) ---
			var org_id = loadFile("home/org_id.txt");
			//alert(org_id);

			//CreateLegalEntityViewModel(org_id)
			model = new CreateLegalEntityViewModel('org_id');
            ko.applyBindings(model);
            bs_input_file();

        });

    </script>

<!-- WIDGETS -->
    <script src="design/tether/js/tether.js"></script>
    <script src="design/widgets/progressbar/progressbar.js"></script>
    <script src="design/widgets/superclick/superclick.js"></script>

    <!-- Input switch alternate -->
    <script src="design/widgets/input-switch/inputswitch-alt.js"></script>

    <!-- Slim scroll -->
    <script src="design/widgets/slimscroll/slimscroll.js"></script>

    <!-- Slidebars -->
    <!--<script src="design/widgets/slidebars/slidebars.js"></script>-->
    <!--<script src="design/widgets/slidebars/slidebars-demo.js"></script>-->

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

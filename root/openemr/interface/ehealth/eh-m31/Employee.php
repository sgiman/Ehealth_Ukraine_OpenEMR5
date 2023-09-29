<?php include 'include/refresh.php'; ?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<?php include 'include/description.php'; ?>

<title>Працівник</title>

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

<div class="modal fade" id="RejectModal" tabindex="-1" data-focus-on="input:first" style="display: none;">
    <div class="modal-header">
        <h4 class="modal-title text-danger">Звільнення працівника</h4>
    </div>
    <div class="modal-body">
        <form class="form-horizontal col-sm-12">
            <div class="form-group">
                <div>
                    Чи дійсно ви бажаєте звільнити працівника?
                </div>
            </div>
        </form>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal" data-bind="click: deactivateEmployee">Звільнити</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Відмовитись</button>
    </div>
</div>



<div class="container">
    <div id="page-content">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.5.1/chosen.jquery.js"></script>
        <!-- Bootstrap Wizard -->
        <link rel="stylesheet" type="text/css" href="design/widgets/wizard/wizard.css">
        <script type="text/javascript" src="design/widgets/wizard/wizard.js"></script>
        <script type="text/javascript" src="design/widgets/wizard/wizard-demo.js"></script>

        <!-- Boostrap Tabs -->
        <!--<script type="text/javascript" src="design/widgets/tabs/tabs.js"></script>-->

        <!-- Calendar -->
        <script type="text/javascript" src="design/widgets/daterangepicker/moment.js"></script>
        <script type="text/javascript" src="design/widgets/calendar/calendar.js"></script>
        <script type="text/javascript" src="design/widgets/calendar/calendar-demo.js"></script>
	
        <!-- Bootstrap Wizard -->
        <script type="text/javascript" src="design/widgets/wizard/wizard.js"></script>
        <script type="text/javascript" src="design/widgets/wizard/wizard-demo.js"></script>

        <!-- Input masks -->
        <script type="text/javascript" src="design/widgets/input-mask/inputmask.js"></script>
        <script type="text/javascript">
            $(function () {
                "use strict";
                $(".input-mask").inputmask();
            });
        </script>

        <div class="panel">
            <div class="panel-body">
                <div class="row" id="page-title">
                    <div class="col-12">
                        <div class="box-wrapper mrg15L mrg15R">
                            <div id="form-wizard-2 text-center">
                                <div class="col-md-12">
                                    <h3 class="meta-heading" data-bind="text: employee_request().party().fullName"></h3>
                                    <h4 class="meta-subheading mrg5T mrg5B" data-bind="text: employee_request().type_name"></h4>
									
                                    <h2 class="bg-default">                                        
                                        <div class="header-buttons">
										
														<a class="btn size-md btn-success" href="/openemr/interface/ehealth/eh-m31/Employee.php?id=
														<?php 
															echo $_GET['id']; 
														?>"
                                                             title="Оновити">
                                                  <i class="glyph-icon icon-repeat"></i>
																	Оновити
														</a>			
                                                  
														<a class="btn size-md btn-success" href="ListOfEmployees.php"
                                                             title="Перелік працівників">
                                                  <i class="glyph-icon icon-list"></i>
																	Перелік працівників
														</a>			

														<a class="btn size-md btn-success" href=""															
                                                            title="Редагувати працівника"
                                                            data-bind="click: allow_edit, visible: show_edit_button()">
                                                  <i class="glyph-icon icon-pencil"></i>
																	Редагувати
                                               </a>
															
                                               <a class="btn size-md btn-danger" href=""
                                                            title="Звільнити працівника"
                                                            data-toggle="modal" data-target="#RejectModal"
                                                            data-bind="visible: 'a49e804c-45c7-4b98-9eda-aa3ffe6ddffd'">
                                                  <i class="glyph-icon icon-remove"></i>
																	Звільнити працівника
                                               </a>
															
                                        </div>										
                                    </h2>
									
                                </div>
                                <div class="col-md-4">

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="box-wrapper mrg15L mrg15R">
                            <div id="form-wizard-2 text-center">
                                <div class="tab-content mrg20B">
                                    <div id="tab-example-1">
                                        <div class="content-box">

                                            <h3 class="content-box-header bg-blue">
<!-- ************************************************************************************* -->
<span class="fa-stack">
  <span class="fa fa-circle fa-stack-2x"></span>
  <strong class="fa-stack-1x icolor">1</strong>
</span>
<!-- ************************************************************************************* -->															                                              
												<!-- <i class="glyph-icon icon-info-circle"></i>--> 
												Загальна інформація
                                            </h3>
											
                                            <div class="content-box-wrapper clearfix">
                                                <form class="form-horizontal clearfix pad15L pad15R pad20T pad20B">
                                                    <div class="form-group">
                                                        <label class="control-label col-md-4"><span class="text-danger">*&nbsp;</span>Прізвище</label>
                                                        <div class="col-xs-12 col-md-8">
                                                            <input class="form-control"
                                                                   data-bind="value: employee_request().party().last_name,
                                                                disable: $root.disable_input()" type="text">
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="control-label col-md-4"><span class="text-danger">*&nbsp;</span>Ім'я</label>
                                                        <div class="col-xs-12 col-md-8">
                                                            <input class="form-control"
                                                                   data-bind="disable: $root.disable_input(), value: employee_request().party().first_name" type="text">
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="control-label col-md-4">По батькові</label>
                                                        <div class="col-xs-12 col-md-8">
                                                            <input class="form-control"
                                                                   data-bind="disable: $root.disable_input(), value: employee_request().party().second_name" type="text">
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="control-label col-md-4"><span class="text-danger">*&nbsp;</span>Дата народження</label>
                                                        <div class="col-sm-4">
                                                            <input type="date" class="bootstrap-datepicker form-control"
                                                                   data-bind="disable: $root.disable_input(), value: employee_request().party().birth_date">
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="control-label col-md-4"><span class="text-danger">*&nbsp;</span>Стать</label>
                                                        <div class="col-xs-12 col-md-8">
                                                            <select class="form-control single"
                                                                    data-bind="disable: $root.disable_input(), options: employee_request().party().genders,
									                                                           optionsText: 'text',
									                                                           optionsValue: 'value',
									                                                           optionsCaption: 'Оберіть стать',
									                                                           value: employee_request().party().gender"></select>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
											
                                            <div class="content-box-header bg-blue mar20T">								
<!-- ************************************************************************************* -->
<span class="fa-stack">
  <span class="fa fa-circle fa-stack-2x"></span>
  <strong class="fa-stack-1x icolor">2</strong>
</span>
<!-- ************************************************************************************* -->																									
                                       <!--<i class="glyph-icon icon-file"></i>-->
												Документи, що засвідчують особу
												
                                                <div class="header-buttons">
                                                    <a href="#" class="btn size-md btn-success mrg7T" title=""
                                                       data-bind="click: employee_request().party().addDocument, visible: $root.show_button()">
                                                        <i class="glyph-icon icon-plus"></i> Додати документ ?
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="content-box-wrapper">
                                                <form class="form-horizontal clearfix pad15L pad15R pad20T pad20B" data-bind="foreach: employee_request().party().documents()">
                                                    <div class="content-box">
                                                        <div class="form-group row">
                                                            <label class="control-label col-md-2"><span class="text-danger">*&nbsp;</span>Тип документу</label>
                                                            <div class="col-xs-12 col-md-4">
                                                                <select class="form-control single"
                                                                        data-bind="disable: $root.disable_input(),
                                                                              options: $root.employee_request().party().documentTypes,
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
                                                            <div class="col-md row" data-bind="if: isExpirationDateRequired_ko">
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
                                                            <div class="col-xs-12 col-md-1">
                                                                <button type="button" class="btn btn-danger"
                                                                        data-bind="visible: $root.employee_request().party().documents().length > 1 && $root.show_button(),
                                                                                click: $root.employee_request().party().removeDocument">
                                                                    <span class="glyph-icon icon-remove"></span>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="tab-example-2">
                                        <div class="content-box">
										
                                            <div class="content-box-header bg-blue mar20T">
<!-- ************************************************************************************* -->
<span class="fa-stack">
  <span class="fa fa-circle fa-stack-2x"></span>
  <strong class="fa-stack-1x icolor">3</strong>
</span>
<!-- ************************************************************************************* -->																										
                                      <!--<i class="glyph-icon icon-envelope"></i>-->
												Контакти
												
                                                <div class="header-buttons">
                                                    <a href="#" class="btn size-md btn-success mrg7T" title=""
                                                       data-bind="click: employee_request().party().addPhone, visible: $root.show_button()">
                                                        <i class="glyph-icon icon-plus"></i> Додати телефон
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="content-box-wrapper">
                                                <form class="form-horizontal">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div data-bind="foreach: employee_request().party().phones">
                                                                <div class="form-group">
                                                                    <label class="control-label col-md-2">
                                                                        <span class="text-danger">*&nbsp;</span>Тип телефону
                                                                    </label>
                                                                    <div class="col-xs-12 col-md-3">
                                                                        <select class="form-control single"
                                                                                data-bind="disable: $root.disable_input(),
                                                                                        options: $root.employee_request().party().phoneTypes,
                                                                                        optionsText: 'text',
                                                                                        optionsValue: 'value',
                                                                                        optionsCaption: 'Оберіть тип телефону',
                                                                                        value: type"></select>
                                                                    </div>
                                                                    <label class="control-label col-md-2"><span class="text-danger">*&nbsp;</span>Номер</label>
                                                                    <div class="col-xs-12 col-md-3">
                                                                        <input type="tel" class="input-mask form-control phone"
                                                                               data-bind="disable: $root.disable_input(), value: number">
                                                                    </div>
                                                                    <div class="col-xs-12 col-md-1"
                                                                         data-bind="visible: $root.employee_request().party().phones().length > 1 && $root.show_button()">
                                                                        <button type="button" class="btn btn-danger"
                                                                                data-bind="click: $parent.employee_request().party().removePhone">
                                                                            <span class="glyph-icon icon-remove"></span>
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label class="control-label col-md-2"><span class="text-danger">*&nbsp;</span>Електрона адреса</label>
                                                                <div class="col-xs-12 col-md-6">
                                                                    <input class="form-control"
                                                                           data-bind="disable: $root.disable_input(),
                                                                                    value: employee_request().party().email">
                                                                </div>
                                                            </div>
																		<p style = "text-align: right;">Номера телефонів: тільки один мобільний та один стаціонарний </p>
                                                        </div>
                                                    </div>
                                                </form>
												
                                            </div>
                                        </div>
                                    </div>
									
									
<!-- ***************************************** Професійна інформація ****************************************** -->									
						
                                    <div id="tab-example-3">
                                        <div class="content-box">										
                                            
											<h3 class="content-box-header bg-blue">
													<span class="fa-stack">
														<span class="fa fa-circle fa-stack-2x"></span>
														<strong class="fa-stack-1x icolor">4</strong>
													</span>
													Професійна інформація
                                            </h3>
											
                                            <div class="row">
                                                <div class="col-md-auto pad20T pad20L mrg10L">
                                                    <ul class="list-group">
                                                        <li class="mrg10B active">
                                                            <a href="#faq-tab-1" data-toggle="tab" class="list-group-item">
                                                                <span class="text-danger">*&nbsp;</span>
                                                                Загальна інформація &nbsp;
                                                                <i class="glyph-icon icon-angle-right mrg0A"></i>
                                                            </a>
                                                        </li>
                                                        <li class="mrg10B" data-bind="if: show_full_ProfInfo()">
                                                            <a href="#faq-tab-2" data-toggle="tab" class="list-group-item">
                                                                <span class="text-danger">*&nbsp;</span>
                                                                Освіта &nbsp;
                                                                <i class="glyph-icon icon-angle-right mrg0A"></i>
                                                            </a>
                                                        </li>
                                                        <li class="mrg10B" data-bind="if: show_full_ProfInfo()">
                                                            <a href="#faq-tab-3" data-toggle="tab" class="list-group-item">
                                                                Кваліфікація &nbsp;
                                                                <i class="glyph-icon icon-angle-right mrg0A"></i>
                                                            </a>
                                                        </li>
                                                        <li class="mrg10B" data-bind="if: show_full_ProfInfo()">
                                                            <a href="#faq-tab-4" data-toggle="tab" class="list-group-item">
                                                                <span class="text-danger">*&nbsp;</span>
                                                                Атестація &nbsp;
                                                                <i class="glyph-icon icon-angle-right mrg0A"></i>
                                                            </a>
                                                        </li>
                                                        <li class="mrg10B" data-bind="if: show_full_ProfInfo()">
                                                            <a href="#faq-tab-5" data-toggle="tab" class="list-group-item">
                                                                Вчена ступінь &nbsp;
                                                                <i class="glyph-icon icon-angle-right mrg0A"></i>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
												
                                                <div class="col-md">
                                                    <div class="tab-content">
                                                        <div class="tab-pane fade active in pad0A" id="faq-tab-1">
                                                            <form class="col-md-12 form-horizontal clearfix pad15L pad15R pad20T pad20B">
                                                                <div class="form-group">
                                                                    <label class="control-label col-md-4">
                                                                        Підрозділ
                                                                    </label>
                                                                    <div class="col-xs-12 col-md-8">
                                                                        <select id="divisionList" class="form-control single"
                                                                                data-bind="disable: $root.disable_input(),
                                                                                                        options: divisions,
                                                                                                        optionsText: 'text',
                                                                                                        optionsValue: 'value',
                                                                                                        value: employee_request().division_id"></select>
                                                                    </div>
                                                                </div>
																
<!-- ******************************************************* employeeTypes (???) *************************************************** -->
                                                                <div class="form-group">
                                                                    <label class="control-label col-md-4">
                                                                        <span class="text-danger">*&nbsp;</span>
                                                                        Тип працівника
                                                                    </label>
                                                                    <div class="col-xs-12 col-md-8">
                                                                        <select class="form-control single"
                                                                                data-bind="disable: $root.disable_input(),
                                                                                                        options: $root.employee_request().employeeTypes,
                                                                                                        optionsText: 'text',
                                                                                                        optionsValue: 'value',
                                                                                                        optionsCaption: 'Оберіть тип',
                                                                                                        value: employee_request().employee_type"></select>
                                                                    </div>
                                                                </div>
<!-- ************************************************************************************************************************************ -->																
																
                                                                <div class="form-group">
                                                                    <label class="control-label col-md-4">
                                                                        <span class="text-danger">*&nbsp;</span>
                                                                        Посада
                                                                    </label>
                                                                    <div class="col-xs-12 col-md-8">
                                                                        <select class="form-control single"
                                                                                data-bind="disable: $root.disable_input(),
                                                                                                                options: $root.employee_request().positionTypes,
                                                                                                                optionsText: 'text',
                                                                                                                optionsValue: 'value',
                                                                                                                optionsCaption: 'Оберіть посаду',
                                                                                                                value: employee_request().position"></select>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="control-label col-md-4">
                                                                        <span class="text-danger">*&nbsp;</span>
                                                                        РНОКПП
                                                                    </label>
                                                                    <div class="col-md-4">
                                                                        <input class="form-control"
                                                                               data-bind="disable: $root.disable_input(),
                                                                                                    textInput: employee_request().party().tax_id"
                                                                               type="text" />
                                                                    </div>
                                                                    <div class="col-xs-12 col-md-4">
                                                                        <label class="control-label col-md-9">
                                                                            без РНОКПП
                                                                        </label>
                                                                        <div class="col-md-3">
                                                                            <div class="checkbox">
                                                                                <label>
                                                                                    <input type="checkbox"
                                                                                           data-bind="disable: $root.disable_input(),
                                                                                                    checked: employee_request().party().no_tax_id" />
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="control-label col-md-6">
                                                                        <span class="text-danger">*&nbsp;</span>
                                                                        Дата заключення контракту
                                                                    </label>
                                                                    <div class="col-sm-4 col-md-4">
                                                                        <input type="date" class="bootstrap-datepicker form-control"
                                                                               data-bind="disable: $root.employee_id(),
                                                                                                    value: employee_request().start_date" />
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="control-label col-md-6">
                                                                        Дата закінчення контракту
                                                                    </label>
                                                                    <div class="col-sm-4 col-md-4">
                                                                        <input type="date" class="bootstrap-datepicker form-control"
                                                                               data-bind="disable: $root.disable_input(),
                                                                                                    value: employee_request().end_date" />
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
														
                                                        <div class="tab-pane fade pad0A" id="faq-tab-2">
                                                            <form class="col-md-12 form-horizontal clearfix pad15L pad15R pad0T pad20B" data-bind="foreach: employee_request().specialist().educations()">
                                                                <div class="content-box">
                                                                    <div class="form-horizontal pad5A">
                                                                        <div class="form-group">
                                                                            <label class="control-label col-md-3">
                                                                                <span class="text-danger">*&nbsp;</span>
                                                                                Країна
                                                                            </label>
                                                                            <div class="col-xs-12 col-md-9">
                                                                                <select class="form-control single"
                                                                                        data-bind="disable: $root.disable_input(),
                                                                                                    options: $root.employee_request().specialist().countries,
                                                                                                    optionsText: 'text',
                                                                                                    optionsValue: 'value',
                                                                                                    value: country"></select>
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label class="control-label col-md-3">
                                                                                <span class="text-danger">*&nbsp;</span>
                                                                                Місто
                                                                            </label>
                                                                            <div class="col-xs-12 col-md-9">
                                                                                <input class="form-control" type="text"
                                                                                       data-bind="disable: $root.disable_input(), value: city" />
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label class="control-label col-md-3">
                                                                                <span class="text-danger">*&nbsp;</span>
                                                                                Учбовий заклад
                                                                            </label>
                                                                            <div class="col-xs-12 col-md-9">
                                                                                <input class="form-control" type="text"
                                                                                       data-bind="disable: $root.disable_input(), value: institution_name" />
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label class="control-label col-md-3">
                                                                                <span class="text-danger">*&nbsp;</span>
                                                                                Номер диплому
                                                                            </label>
                                                                            <div class="col-xs-12 col-md-9">
                                                                                <input class="form-control" type="text"
                                                                                       data-bind="disable: $root.disable_input(), value: diploma_number" />
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label class="control-label col-md-3">
                                                                                Дата видачі
                                                                            </label>
                                                                            <div class="col-sm-4 col-md-4">
                                                                                <input type="date" class="bootstrap-datepicker form-control"
                                                                                       data-bind="disable: $root.disable_input(), value: issued_date" />
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label class="control-label col-md-3">
                                                                                <span class="text-danger">*&nbsp;</span>
                                                                                Вчена ступінь
                                                                            </label>
                                                                            <div class="col-xs-12 col-md-9">
                                                                                <select class="form-control single"
                                                                                        data-bind="disable: $root.disable_input(),
                                                                                                    options: $root.employee_request().specialist().educationDegrees,
                                                                                                    optionsText: 'text',
                                                                                                    optionsValue: 'value',
                                                                                                    optionsCaption: 'Оберіть ступінь',
                                                                                                    value: degree"></select>
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label class="control-label col-md-3">
                                                                                <span class="text-danger">*&nbsp;</span>
                                                                                Спеціальність
                                                                            </label>
                                                                            <div class="col-xs-12 col-md-9">
                                                                                <input class="form-control" type="text"
                                                                                       data-bind="disable: $root.disable_input(), value: speciality" />
                                                                            </div>
                                                                        </div>
																		
                                                                        <div class="form-group">
                                                                            <div class="col-md-auto pad20L"
                                                                                 data-bind="if: $root.show_button()">
                                                                                <button type="button" class="btn btn-danger"
                                                                                        data-bind="click: $parent.employee_request().specialist().removeEducation">
                                                                                    <span class="glyph-icon icon-remove"> Видалити освіту</span>
                                                                                </button>
                                                                            </div>
                                                                        </div>																	
																	
                                                                    </div>
                                                                </div>
                                                            </form>
															
                                                            <div class="form-group">
                                                                <div class="col-md-3 pad25L">
                                                                    <button type="button" class="btn btn-success"
                                                                            data-bind="click: employee_request().specialist().addEducation, visible: $root.show_button()">
                                                                        <span class="glyph-icon icon-plus"> Додати нову освіту</span>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
														
                                                        <div class="tab-pane fade pad0A" id="faq-tab-3">
                                                            <form class="col-md-12 form-horizontal clearfix pad15L pad15R pad15T pad20B" data-bind="foreach: employee_request().specialist().qualifications()">
                                                                <div class="content-box">
                                                                    <div class="form-horizontal pad5A">
																	
																	<!-- ============================== -->																
                                                                        <div class="form-group">
                                                                            <label class="control-label col-md-3">
                                                                                <span class="text-danger">*&nbsp;</span>
                                                                                Тип
                                                                            </label>
                                                                            <div class="col-xs-12 col-md-9">
                                                                                <select class="form-control single"
                                                                                        data-bind="disable: $root.disable_input(),
                                                                                                    options: $root.employee_request().specialist().qualificationTypes,
                                                                                                    optionsText: 'text',
                                                                                                    optionsValue: 'value',
                                                                                                    optionsCaption: 'Оберіть тип',
                                                                                                    value: type"></select>
                                                                            </div>
                                                                        </div>																	
																	<!-- ============================== -->																
																																			
                                                                        <div class="form-group">
                                                                            <label class="control-label col-md-3">
                                                                                <span class="text-danger">*&nbsp;</span>
                                                                                Навчальний заклад
                                                                            </label>
                                                                            <div class="col-xs-12 col-md-9">
                                                                                <input class="form-control" type="text"
                                                                                       data-bind="disable: $root.disable_input(), value: institution_name" />
                                                                            </div>
                                                                        </div>
																		
                                                                        <div class="form-group">
                                                                            <label class="control-label col-md-3">
                                                                                <span class="text-danger">*&nbsp;</span>
                                                                                Спеціальність
                                                                            </label>																			
                                                                            <div class="col-xs-12 col-md-9">
                                                                                <input class="form-control" type="text"
                                                                                       data-bind="disable: $root.disable_input(), value: speciality" />
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label class="control-label col-md-3">
                                                                                Номер сертифікату
                                                                            </label>
                                                                            <div class="col-xs-12 col-md-9">
                                                                                <input class="form-control single" type="text"
                                                                                       data-bind="disable: $root.disable_input(), value: certificate_number" />
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label class="control-label col-md-3">
                                                                                Дата видачі
                                                                            </label>
                                                                            <div class="col-sm-4 col-md-4">
                                                                                <input type="date" class="bootstrap-datepicker form-control"
                                                                                       data-bind="disable: $root.disable_input(), value: issued_date" />
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label class="control-label col-md-3">
                                                                                Придатний до
                                                                            </label>
                                                                            <div class="col-xs-12 col-md-4">
                                                                                <input type="date" class="bootstrap-datepicker form-control"
                                                                                       data-bind="disable: $root.disable_input(), value: valid_to" />
                                                                            </div>
                                                                        </div>
																						
																						<!-- ********************* Додаткова інформація *****************-->
                                                                        <div class="form-group">
                                                                            <label class="control-label col-md-3">
                                                                                Додаткова інформація
                                                                            </label>
                                                                            <div class="col-xs-12 col-md-9">
                                                                                <input type="text" class="form-control"
                                                                                       data-bind="disable: $root.disable_input(), value: additional_info" />
                                                                            </div>
                                                                        </div>
																		
                                                                        <div class="form-group">
                                                                            <div class="col-md-auto pad20L" data-bind="if: $root.show_button()">
                                                                                <button type="button" class="btn btn-danger"
                                                                                        data-bind="click: $parent.employee_request().specialist().removeQualification">
                                                                                    <span class="glyph-icon icon-remove">
                                                                                        Видалити кваліфікацію
                                                                                    </span>
                                                                                </button>
                                                                            </div>
                                                                        </div>
																		
                                                                    </div>
                                                                </div>
                                                            </form>
															
                                                            <div class="form-group">
                                                                <div class="col-md-auto pad25L">
                                                                    <button type="button" class="btn btn-success"
                                                                            data-bind="click: employee_request().specialist().addQualification, visible: $root.show_button()">
                                                                        <span class="glyph-icon icon-plus">
                                                                            Додати нову кваліфікацію
                                                                        </span>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
														
																	<!-- ************************************  Атестація  ************************************-->
														
                                                        <div class="tab-pane fade pad0A" id="faq-tab-4">
                                                            <form class="col-md-12 form-horizontal clearfix pad15L pad15R pad15T pad20B" data-bind="foreach: employee_request().specialist().specialities">
                                                                <div class="content-box">
                                                                    <div class="form-horizontal pad5A">
                                                                        <div class="form-group">
                                                                            <label class="control-label col-md-3">
                                                                                <span class="text-danger">*&nbsp;</span>
                                                                                Спеціалізація
                                                                            </label>
                                                                            <div class="col-xs-12 col-md-9">
                                                                                <select class="form-control single"
                                                                                        data-bind="disable: $root.disable_input(),
                                                                                                            options: $root.employee_request().specialist().specialityTypes,
                                                                                                            optionsText: 'text',
                                                                                                            optionsValue: 'value',
                                                                                                            optionsCaption: 'Оберіть тип',
                                                                                                            value: speciality"></select>
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label class="control-label col-md-3">
                                                                                Cпеціальність за посадою?
                                                                            </label>
                                                                            <div class="col-xs-12 col-md-4">
                                                                                <div class="checkbox">
                                                                                    <label>
                                                                                        <input type="checkbox"
                                                                                               data-bind="disable: $root.disable_input(), checked: speciality_officio" />
                                                                                    </label>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label class="control-label col-md-3">
                                                                                <span class="text-danger">*&nbsp;</span>
                                                                                Категорія
                                                                            </label>
                                                                            <div class="col-xs-12 col-md-9">
                                                                                <select class="form-control single"
                                                                                        data-bind="disable: $root.disable_input(),
                                                                                                            options: $root.employee_request().specialist().specialityLevels,
                                                                                                            optionsText: 'text',
                                                                                                            optionsValue: 'value',
                                                                                                            optionsCaption: 'Оберіть рівень',
                                                                                                            value: level"></select>
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label class="control-label col-md-3">
                                                                                <span class="text-danger">*&nbsp;</span>
                                                                                Тип кваліфікації
                                                                            </label>
                                                                            <div class="col-xs-12 col-md-9">
                                                                                <select class="form-control single"
                                                                                        data-bind="disable: $root.disable_input(),
                                                                                                            options: $root.employee_request().specialist().specificQualificationTypes,
                                                                                                            optionsText: 'text',
                                                                                                            optionsValue: 'value',
                                                                                                            optionsCaption: 'Оберіть тип кваліфікації',
                                                                                                            value: qualification_type"></select>
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label class="control-label col-md-3">
                                                                                <span class="text-danger">*&nbsp;</span>
                                                                                Учбовий заклад
                                                                            </label>
                                                                            <div class="col-xs-12 col-md-9">
                                                                                <input class="form-control" type="text"
                                                                                       data-bind="disable: $root.disable_input(), value: attestation_name" />
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label class="control-label col-md-3">
                                                                                <span class="text-danger">*&nbsp;</span>
                                                                                Дата видачі
                                                                            </label>
                                                                            <div class="col-sm-4">
                                                                                <input type="date" class="bootstrap-datepicker form-control"
                                                                                       data-bind="disable: $root.disable_input(), value: attestation_date" />
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label class="control-label col-md-3">
                                                                                Дійсний до
                                                                            </label>
                                                                            <div class="col-sm-4">
                                                                                <input type="date" class="bootstrap-datepicker form-control"
                                                                                       data-bind="disable: $root.disable_input(), value: valid_to_date" />
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label class="control-label col-md-3">
                                                                                <span class="text-danger">*&nbsp;</span>
                                                                                Номер сертифікату
                                                                            </label>
                                                                            <div class="col-xs-12 col-md-9">
                                                                                <input class="form-control" type="text"
                                                                                       data-bind="disable: $root.disable_input(), value: certificate_number" />
                                                                            </div>
                                                                        </div>
<!--
                                                                        <div class="form-group">
                                                                            <div class="col-md-auto pad20L" data-bind="if: $root.show_button()">
                                                                                <button type="button" class="btn btn-danger"
                                                                                        data-bind="click: $parent.employee_request().specialist().removeSpeciality">
                                                                                    <span class="glyph-icon icon-remove"> Видалити спеціальність</span>
                                                                                </button>
                                                                            </div>
                                                                        </div>
-->																		
                                                                    </div>
                                                                </div>
                                                            </form>
<!--															
                                                            <div class="form-group">
                                                                <div class="col-md-auto pad25L">
                                                                    <button type="button" class="btn btn-success"
                                                                            data-bind="click: employee_request().specialist().addSpeciality, visible: $root.show_button()">
                                                                        <span class="glyph-icon icon-plus">
                                                                            Додати нову спеціальність
                                                                        </span>
                                                                    </button>
                                                                </div>
                                                            </div>
-->															
                                                        </div>
														
 				  												 <!-- ************************************  END "Атестація"  ************************************-->
														
 				  												 <!-- ************************************  Вчена ступінь  ************************************-->
                                                        <div class="tab-pane fade pad0A" id="faq-tab-5">
                                                            <form class="col-md-12 form-horizontal clearfix pad15L pad15R pad0T pad20B" data-bind="foreach: employee_request().specialist().science_degree">
                                                                <div class="content-box">
                                                                    <div class="form-horizontal pad5A">
                                                                        <div class="form-group">
                                                                            <label class="control-label col-md-3">
                                                                                <span class="text-danger">*&nbsp;</span>
                                                                                Країна
                                                                            </label>
                                                                            <div class="col-xs-12 col-md-9">
                                                                                <select class="form-control single"
                                                                                        data-bind="disable: $root.disable_input(),
                                                                                                    options: $root.employee_request().specialist().countries,
                                                                                                    optionsText: 'text',
                                                                                                    optionsValue: 'value',
                                                                                                    value: country"></select>
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label class="control-label col-md-3">
                                                                                <span class="text-danger">*&nbsp;</span>
                                                                                Місто
                                                                            </label>
                                                                            <div class="col-xs-12 col-md-9">
                                                                                <input class="form-control" type="text"
                                                                                       data-bind="disable: $root.disable_input(), value: city" />
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label class="control-label col-md-3">
                                                                                <span class="text-danger">*&nbsp;</span>
                                                                                Заклад освіти
                                                                            </label>
                                                                            <div class="col-xs-12 col-md-9">
                                                                                <input class="form-control" type="text"
                                                                                       data-bind="disable: $root.disable_input(), value: institution_name" />
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label class="control-label col-md-3">
                                                                                <span class="text-danger">*&nbsp;</span>
                                                                                Вчена ступінь
                                                                            </label>
                                                                            <div class="col-xs-12 col-md-9">
                                                                                <select class="form-control single"
                                                                                        data-bind="disable: $root.disable_input(),
                                                                                                    options: $root.employee_request().specialist().scienceDegrees,
                                                                                                    optionsText: 'text',
                                                                                                    optionsValue: 'value',
                                                                                                    optionsCaption: 'Оберіть вчену ступінь',
                                                                                                    value: degree"></select>
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label class="control-label col-md-3">
                                                                                <span class="text-danger">*&nbsp;</span>
                                                                                Номер диплому
                                                                            </label>
                                                                            <div class="col-xs-12 col-md-9">
                                                                                <input class="form-control" type="text"
                                                                                       data-bind="disable: $root.disable_input(), value: diploma_number" />
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label class="control-label col-md-3">
                                                                                <span class="text-danger">*&nbsp;</span>
                                                                                Спеціальність
                                                                            </label>
                                                                            <div class="col-xs-12 col-md-9">
                                                                                <input class="form-control" type="text"
                                                                                       data-bind="disable: $root.disable_input(), value: speciality" />
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label class="control-label col-md-3">
                                                                                <span class="text-danger">*&nbsp;</span>
                                                                                Дата видачі
                                                                            </label>
                                                                            <div class="col-sm-4">
                                                                                <input type="date" class="bootstrap-datepicker form-control"
                                                                                       data-bind="disable: $root.disable_input(), value: issued_date" />
                                                                            </div>
                                                                        </div>
<!--																		
                                                                        <div class="form-group">
                                                                            <div class="col-md-auto pad20L" data-bind="if: $root.employee_request().specialist().science_degree().length >= 1 && $root.show_button()">
                                                                                <button type="button" class="btn btn-danger"
                                                                                        data-bind="click: $parent.employee_request().specialist().removeScienceDegree">
                                                                                    <span class="glyph-icon icon-remove"> Видалити вчену ступінь</span>
                                                                                </button>
                                                                            </div>
                                                                        </div>
-->																		
                                                                    </div>
                                                                </div>
                                                            </form>
<!--															
                                                            <div class="form-group">
                                                                <div class="col-md-auto pad25L" data-bind="if: $root.show_button()">
                                                                    <button type="button" class="btn btn-success"
                                                                            data-bind="click: employee_request().specialist().addScienceDegree">
                                                                        <span class="glyph-icon icon-plus"> Додати вчену ступінь</span>
                                                                    </button>
                                                                </div>
                                                            </div>
-->															
                                                        </div>
														
													
                                                    </div> <!-- tab-content-->
                                                </div><!-- col-md -->
												
                                            </div> <!-- row -->
                                        </div> <!-- content-box -->
                                    </div> <!-- tab-example-3 -->
									
<!-- ******************************************************************************************************************************** -->									


                                </div>
                            </div>
                            <div class="text-center" data-bind="if: $root.show_button()">

                                <!--<button type="button" class="btn btn-bright-green mrg10T mrg10B"
                                        data-bind="click: openSigningModal, visible: $root.show_button()">-->
										
                                <button type="button" class="btn btn-bright-green mrg10T mrg10B"
                                        data-bind="click: createEmployeeRequest, visible: $root.show_button()">
                                    Створити
                                </button>
								
									  <p>Після редагування підтвердити реєстрацію працівника за адресою e-mail</p>
								
                                <div class="alert alert-danger" role="alert" style="display:none; margin-top: 10px;">
                                    <span class="glyphicon  glyphicon glyphicon-remove-circle" aria-hidden="true"></span> Будь-ласка, перевірте усі данні. Можливо деяке поле не було заповнено чи вибрано.
                                    <!-- <p>На даний момент кількість помилок: <strong data-bind="text: errors().length"></strong> шт.</p>-->
                                </div>
								
                            </div>
							
                        </div>
                    </div>
                </div> <!-- / .col-md-12 -->
            </div>
        </div>
    </div><!-- end #page-content -->
</div><!-- end .container -->

</div>
</div>

<?php include 'include/footer.php'; ?>
<?php include 'include/notification_modal.php'; ?>

    <script src="js/viewmodels/document.js"></script>
    <script src="js/viewmodels/phone.js"></script>
    <script src="js/viewmodels/education.js"></script>
    <script src="js/viewmodels/degree.js"></script>
    <script src="js/viewmodels/qualification.js"></script>
    <script src="js/viewmodels/speciality.js"></script>
    
    <script src="js/viewmodels/specialist.js"></script>
	
    <script src="js/viewmodels/party.js"></script>
    <script src="js/viewmodels/employee.js"></script>
    <script src="js/pages/createEmployeeRequest.js"></script>
    
    <script src="js/modal/bootstrap-modalmanager.js"></script>
    <script src="js/modal/bootstrap-modal.js"></script>
    <script src="lib/knockout/dist/knockout-validation.js"></script>
    <script src="lib/jquery-validation/dist/jquery.mask.js"></script>
    <script src="js/features/koValidationWithExtraFeatures.js"></script>

<!-- 
	<script defer="defer" type="text/javascript" src="js/signing/base.js"></script>
    <script defer="defer" type="text/JavaScript" src="js/signing/eusw.js"></script>
    <script defer="defer" type="text/JavaScript" src="js/signing/euswll.js"></script>
    <script defer="defer" type="text/JavaScript" src="js/signing/app.js"></script>
-->

    <script type="text/javascript">
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
    </script>

    <script type="text/javascript">
        $('.phone').mask('+38(o00)000-00-00',
            {
                placeholder: "+38(___)___-__-__",
                translation: {
                    'o': {
                        pattern: /[0]/
                    }
                }
            });
        $('.inn').mask('Z000000000',
            {
                translation: {
                    'Z': {
                        pattern: /[1-9]/
                    }
                }
            });
			
		//--- org_id (client_id) ---
		var org_id = loadFile("home/org_id.txt");
		//alert(org_id);
				
		// CreateEmployeeRequestViewModel(org_id, division_id, employee_id, legalEntityType, employeeTypes)
		var model = new CreateEmployeeRequestViewModel(org_id,  '',  '<?php echo $_GET["id"]; ?>');
        ko.applyBindings(model);
    
	</script>
   
<!--***************************************************** initEmployee *****************************************************-->
   <script type="text/JavaScript">
        $(document).ready(function() {
                model.employee_request().initEmployee('<?php echo $_GET["id"]; ?>');
        });
    </script>
<!--**************************************************************************************************************************-->

<!-- WIDGETS -->
    <script type="text/javascript" src="design/tether/js/tether.js"></script>
    <script type="text/javascript" src="design/widgets/progressbar/progressbar.js"></script>
    <script type="text/javascript" src="design/widgets/superclick/superclick.js"></script>

    <!-- Input switch alternate -->
    <script type="text/javascript" src="design/widgets/input-switch/inputswitch-alt.js"></script>

    <!-- Slim scroll -->
    <script type="text/javascript" src="design/widgets/slimscroll/slimscroll.js"></script>

    <!-- Slidebars -->
   <!-- 
	<script type="text/javascript" src="design/widgets/slidebars/slidebars.js"></script>
    <script type="text/javascript" src="design/widgets/slidebars/slidebars-demo.js"></script>
	-->
	
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

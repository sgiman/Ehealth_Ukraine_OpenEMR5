<?php 
	require_once "../api/Common/config.php";
	require_once "../api/Common/functions.php";
	
	// --  Type Connect  --- 
	$update = "UPDATE `z_ehealth_type_connect` SET `type_connect`= {$type_connect}  WHERE 1"; 
	mysqli_query($db, $update) or die (mysqli_error($db));  
	if( $type_connect == 1) 
	{
		$update = "UPDATE `z_ehealth_type_connect` SET `type_name_connect`= 'MIS'  WHERE 1 ";  		//  type_name_connect for MIS
		mysqli_query($db, $update) or die (mysqli_error($db));  
	}
	else 
	{	
		$update = "UPDATE `z_ehealth_type_connect` SET `type_name_connect`= 'OWNER'  WHERE 1 ";		// type_name_connect for OWNER
		mysqli_query($db, $update) or die (mysqli_error($db));  
	}
	token_refresh();
?> 
 
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">

	<!-- Refresh page  - 20 min, (20min * 60sec = 1200sec) -->
	<meta http-equiv="refresh" content="1200">

	<?php include 'include/description.php'; ?>
	
	<title>Вхід до ЦБД eHealth</title>
   
    <!-- HELPERS -->
    <link rel="stylesheet" type="text/css" href="design/helpers/animate.css">
    <link rel="stylesheet" type="text/css" href="design/helpers/backgrounds.css">
    <link rel="stylesheet" type="text/css" href="design/helpers/boilerplate.css">
    <link rel="stylesheet" type="text/css" href="design/helpers/border-radius.css">
    <link rel="stylesheet" type="text/css" href="design/helpers/grid.css">
    <link rel="stylesheet" type="text/css" href="design/helpers/page-transitions.css">
    <link rel="stylesheet" type="text/css" href="design/helpers/spacing.css">
    <link rel="stylesheet" type="text/css" href="design/helpers/typography.css">
    <link rel="stylesheet" type="text/css" href="design/helpers/utils.css">
    <link rel="stylesheet" type="text/css" href="design/helpers/colors.css">

    <!-- ELEMENTS -->
    <link rel="stylesheet" type="text/css" href="design/elements/badges.css">
    <link rel="stylesheet" type="text/css" href="design/elements/buttons.css">
    <link rel="stylesheet" type="text/css" href="design/elements/content-box.css">
    <link rel="stylesheet" type="text/css" href="design/elements/dashboard-box.css">
    <link rel="stylesheet" type="text/css" href="design/elements/forms.css">
    <link rel="stylesheet" type="text/css" href="design/elements/images.css">
    <link rel="stylesheet" type="text/css" href="design/elements/info-box.css">
    <link rel="stylesheet" type="text/css" href="design/elements/invoice.css">
    <link rel="stylesheet" type="text/css" href="design/elements/loading-indicators.css">
    <link rel="stylesheet" type="text/css" href="design/elements/menus.css">
    <link rel="stylesheet" type="text/css" href="design/elements/panel-box.css">
    <link rel="stylesheet" type="text/css" href="design/elements/response-messages.css">
    <link rel="stylesheet" type="text/css" href="design/elements/responsive-tables.css">
    <link rel="stylesheet" type="text/css" href="design/elements/ribbon.css">
    <link rel="stylesheet" type="text/css" href="design/elements/social-box.css">
    <link rel="stylesheet" type="text/css" href="design/elements/tables.css">
    <link rel="stylesheet" type="text/css" href="design/elements/tile-box.css">
    <link rel="stylesheet" type="text/css" href="design/elements/timeline.css">

    <!-- FRONTEND ELEMENTS -->
    <link rel="stylesheet" type="text/css" href="design/frontend-elements/blog.css">
    <link rel="stylesheet" type="text/css" href="design/frontend-elements/cta-box.css">
    <link rel="stylesheet" type="text/css" href="design/frontend-elements/feature-box.css">
    <link rel="stylesheet" type="text/css" href="design/frontend-elements/footer.css">
    <link rel="stylesheet" type="text/css" href="design/frontend-elements/hero-box.css">
    <link rel="stylesheet" type="text/css" href="design/frontend-elements/icon-box.css">
    <link rel="stylesheet" type="text/css" href="design/frontend-elements/portfolio-navigation.css">
    <link rel="stylesheet" type="text/css" href="design/frontend-elements/pricing-table.css">
    <link rel="stylesheet" type="text/css" href="design/frontend-elements/sliders.css">
    <link rel="stylesheet" type="text/css" href="design/frontend-elements/testimonial-box.css">

    <!-- ICONS -->
    <link rel="stylesheet" type="text/css" href="design/icons/fontawesome/fontawesome.css">
    <link rel="stylesheet" type="text/css" href="design/icons/linecons/linecons.css">
    <link rel="stylesheet" type="text/css" href="design/icons/spinnericon/spinnericon.css">

    <!-- WIDGETS -->
    <link rel="stylesheet" type="text/css" href="design/widgets/accordion-ui/accordion.css">
    <link rel="stylesheet" type="text/css" href="design/widgets/calendar/calendar.css">
    <link rel="stylesheet" type="text/css" href="design/widgets/carousel/carousel.css">
    <link rel="stylesheet" type="text/css" href="design/widgets/charts/justgage/justgage.css">
    <link rel="stylesheet" type="text/css" href="design/widgets/charts/morris/morris.css">
    <link rel="stylesheet" type="text/css" href="design/widgets/charts/piegage/piegage.css">
    <link rel="stylesheet" type="text/css" href="design/widgets/chosen/chosen.css">
    <link rel="stylesheet" type="text/css" href="design/widgets/colorpicker/colorpicker.css">
    <link rel="stylesheet" type="text/css" href="design/widgets/datatable/datatable.css">
    <link rel="stylesheet" type="text/css" href="design/widgets/datepicker/datepicker.css">
    <link rel="stylesheet" type="text/css" href="design/widgets/datepicker-ui/datepicker.css">
    <link rel="stylesheet" type="text/css" href="design/widgets/daterangepicker/daterangepicker.css">
    <link rel="stylesheet" type="text/css" href="design/widgets/dialog/dialog.css">
    <link rel="stylesheet" type="text/css" href="design/widgets/dropdown/dropdown.css">
    <link rel="stylesheet" type="text/css" href="design/widgets/dropzone/dropzone.css">
    <link rel="stylesheet" type="text/css" href="design/widgets/file-input/fileinput.css">
    <link rel="stylesheet" type="text/css" href="design/widgets/input-switch/inputswitch.css">
    <link rel="stylesheet" type="text/css" href="design/widgets/input-switch/inputswitch-alt.css">
    <link rel="stylesheet" type="text/css" href="design/widgets/ionrangeslider/ionrangeslider.css">
    <link rel="stylesheet" type="text/css" href="design/widgets/jcrop/jcrop.css">
    <link rel="stylesheet" type="text/css" href="design/widgets/jgrowl-notifications/jgrowl.css">
    <link rel="stylesheet" type="text/css" href="design/widgets/loading-bar/loadingbar.css">
    <link rel="stylesheet" type="text/css" href="design/widgets/maps/vector-maps/vectormaps.css">
    <link rel="stylesheet" type="text/css" href="design/widgets/markdown/markdown.css">
    <link rel="stylesheet" type="text/css" href="design/widgets/modal/modal.css">
    <link rel="stylesheet" type="text/css" href="design/widgets/multi-select/multiselect.css">
    <link rel="stylesheet" type="text/css" href="design/widgets/multi-upload/fileupload.css">
    <link rel="stylesheet" type="text/css" href="design/widgets/nestable/nestable.css">
    <link rel="stylesheet" type="text/css" href="design/widgets/noty-notifications/noty.css">
    <link rel="stylesheet" type="text/css" href="design/widgets/popover/popover.css">
    <link rel="stylesheet" type="text/css" href="design/widgets/pretty-photo/prettyphoto.css">
    <link rel="stylesheet" type="text/css" href="design/widgets/progressbar/progressbar.css">
    <link rel="stylesheet" type="text/css" href="design/widgets/range-slider/rangeslider.css">
    <link rel="stylesheet" type="text/css" href="design/widgets/slider-ui/slider.css">
    <link rel="stylesheet" type="text/css" href="design/widgets/summernote-wysiwyg/summernote-wysiwyg.css">
    <link rel="stylesheet" type="text/css" href="design/widgets/tabs-ui/tabs.css">
    <link rel="stylesheet" type="text/css" href="design/widgets/theme-switcher/themeswitcher.css">
    <link rel="stylesheet" type="text/css" href="design/widgets/timepicker/timepicker.css">
    <link rel="stylesheet" type="text/css" href="design/widgets/tocify/tocify.css">
    <link rel="stylesheet" type="text/css" href="design/widgets/tooltip/tooltip.css">
    <link rel="stylesheet" type="text/css" href="design/widgets/touchspin/touchspin.css">
    <link rel="stylesheet" type="text/css" href="design/widgets/uniform/uniform.css">
    <link rel="stylesheet" type="text/css" href="design/widgets/wizard/wizard.css">
    <link rel="stylesheet" type="text/css" href="design/widgets/xeditable/xeditable.css">

    <!-- FRONTEND WIDGETS -->
    <link rel="stylesheet" type="text/css" href="design/widgets/layerslider/layerslider.css">
    <link rel="stylesheet" type="text/css" href="design/widgets/owlcarousel/owlcarousel.css">
    <link rel="stylesheet" type="text/css" href="design/widgets/fullpage/fullpage.css">

    <!-- SNIPPETS -->
    <link rel="stylesheet" type="text/css" href="design/snippets/chat.css">
    <link rel="stylesheet" type="text/css" href="design/snippets/files-box.css">
    <link rel="stylesheet" type="text/css" href="design/snippets/login-box.css">
    <link rel="stylesheet" type="text/css" href="design/snippets/notification-box.css">
    <link rel="stylesheet" type="text/css" href="design/snippets/progress-box.css">
    <link rel="stylesheet" type="text/css" href="design/snippets/todo.css">
    <link rel="stylesheet" type="text/css" href="design/snippets/user-profile.css">
    <link rel="stylesheet" type="text/css" href="design/snippets/mobile-navigation.css">

    <!-- Frontend theme -->
    <link rel="stylesheet" type="text/css" href="design/themes/frontend/layout.css">
    <link rel="stylesheet" type="text/css" href="design/themes/frontend/color-schemes/default.css">

    <!-- Components theme -->
    <link rel="stylesheet" type="text/css" href="design/themes/components/default.css">
    <link rel="stylesheet" type="text/css" href="design/themes/components/border-radius.css">

    <!-- Frontend responsive -->
    <link rel="stylesheet" type="text/css" href="design/helpers/responsive-elements.css">
    <link rel="stylesheet" type="text/css" href="design/helpers/frontend-responsive.css">

    <!-- JS Core -->
    <script type="text/javascript" src="design/js-core/jquery-core.js"></script>
    <script type="text/javascript" src="design/js-core/jquery-ui-core.js"></script>
    <script type="text/javascript" src="design/js-core/jquery-ui-widget.js"></script>
    <script type="text/javascript" src="design/js-core/jquery-ui-mouse.js"></script>
    <script type="text/javascript" src="design/js-core/jquery-ui-position.js"></script>
    <script type="text/javascript" src="design/js-core/transition.js"></script>
    <script type="text/javascript" src="design/js-core/modernizr.js"></script>
    <script type="text/javascript" src="design/js-core/jquery-cookie.js"></script>

    <script src="js/main.js"></script>
        
    <link rel="stylesheet" href="css/Styles.css" />

    <style>
        /* Loading Spinner */
        .spinner{margin:0;width:70px;height:18px;margin:35px 0 0 9px;position:absolute;top:50%;left:50%;text-align:center}.spinner div{width:18px;height:18px;background-color:#333;border-radius:100%;display:inline-block;-webkit-animation:bouncedelay 1.4s infinite ease-in-out;animation:bouncedelay 1.4s infinite ease-in-out;-webkit-animation-fill-mode:both;animation-fill-mode:both}.spinner .bounce1{-webkit-animation-delay:-.32s;animation-delay:-.32s}.spinner .bounce2{-webkit-animation-delay:-.16s;animation-delay:-.16s}@-webkit-keyframes bouncedelay{0%,80%,100%{-webkit-transform:scale(0.0)}40%{-webkit-transform:scale(1.0)}}@keyframes bouncedelay{0%,80%,100%{transform:scale(0.0);-webkit-transform:scale(0.0)}40%{transform:scale(1.0);-webkit-transform:scale(1.0)}}
        .validationMessage { color: #f00; }
        .customMessage { color: #FFA500; }    
    </style>


    <script>
        $(window).load(function () {
            setTimeout(function () {
                $('#loading').fadeOut(400, "linear");
            }, 300);
        });
    </script>

    <link rel="stylesheet" href="css/module.css" />
    <link rel="stylesheet" href="lib/bootstrap/dist/css/bootstrap.min.css" />
    
	<script src="lib/jquery/dist/jquery.min.js"></script>
   <script src="lib/knockout/dist/knockout.js"></script>    
     
</head>

<body class="main-header-fixed">

    <div id="loading">
        <div class="spinner">
            <div class="bounce1"></div>
            <div class="bounce2"></div>
            <div class="bounce3"></div>
        </div>
    </div>

<div id="page-wrapper">
    <div class="main-header bg-header header-resizable wow bounceInDown" data-0="padding: 0px 0;" data-250="padding: 0px 0;">
        <div class="container">
            <a class="header-logo" title="EHEALTH" href=""></a><!-- .header-logo -->
            <div class="right-header-btn">
                <div id="mobile-navigation">
                    <button id="nav-toggle" class="collapsed" data-toggle="collapse" data-target=".header-nav"><span></span></button>
                </div>
            </div>
            <ul class="header-nav collapse">
                <li class="top_btn text-center">
                    <button onclick="window.location.href='CreateLegalEntity.php'" class="btn btn-alt btn-hover btn-bright-green" title="Реєстрація Лікувального закладу в центральному компоненті e-Health МОЗ України">
                        <span>Реєстрація Лікувального закладу</span>
                        <i class="glyph-icon icon-arrow-right"></i>
                    </button>
                </li>
             
					<!--<li class="top_btn text-center">
                    <button onclick="window.location.href='../index.php'" class="btn btn-alt btn-hover btn-bright-red" title="Вийти">
                        <span>Вийти</span>
                        <i class="glyph-icon icon-arrow-right"></i>
                    </button>					
					</li>-->
				
            </ul><!-- .header-nav -->
        </div><!-- .container -->
    </div><!-- .main-header -->

    <?php include 'include/notification_modal.php'; ?>

    <div id="page-content-wrapper" style="min-height: calc(100vh - 170px);">
        <div class="hero-box full-bg-2">
    <div class="container">
	
        <h1 class="hero-heading fadeInDown" data-wow-duration="0.6s">
            <br/>
            Вхід до eHealth
        </h1>
		
		<p class="hero-text bounceInUp" data-wow-duration="0.9s" data-wow-delay="0.2s">
            ПОШУК: 1) Знайти медзаклад за ЄДРПОУ. 2) Знайти користувача за адресою e-mail.
		</p>

       <br />
		
      <!-- ==============================  Надавач медичних послуг ============================ -->  
		<form class="form-horizontal">
            <div class="form-group">
                <label class="col-md-5 control-label">
                    Надавач медичних послуг
                </label>
                <div class="col-md-4">
                    <input id="edrpou_input" class="col-md-3 form-control single" type="search"
                           data-bind="value: edrpou"
                           placeholder="ЄДРПОУ закладу">
                </div>
                <div class="col-md-2 action_icons">
                    <button type="submit" class="btn btn-blue-alt" data-bind="enable: $root.legalEntityTypes().length > 0, click: getOrgNames">
                        <span class="glyph-icon icon-search"></span>
                    </button>
                </div>
                <div class="col-md-4 col-md-offset-5">
                    <select id="edrpou__list" class="form-control single"
                            data-bind="options: orgNames,
                                       optionsText: 'text',
                                       optionsValue: 'value',
                                       value: orgId"></select>
                </div>
            </div>
        </form>
		
      <!-- ==============================  E-mail користувача ============================ -->  
        <form class="form-horizontal">
            <div class="form-group">
                <label class="col-md-5 control-label">
                    E-mail користувача
                </label>
                <div class="col-md-4">
                    <input id="email_input" class="col-md-3 form-control single" type="text"
                           placeholder="e-mail" data-bind="value: emplEmail">
                </div>
                <div class="col-md-2 action_icons">
                    <button type="submit" id="searchEmplNames" class="btn btn-blue-alt" 
                            data-bind="click: getEmplNames, enable: orgId">
                        <span class="glyph-icon icon-search"></span>
                    </button>
                </div>
                <div class="col-md-4 col-md-offset-5">
                    <select id="emplNames__list" class="form-control single"
                            data-bind="options: emplData,
                                       optionsText: 'name',
                                       optionsValue: 'id',
                                       value: emplId">
                    </select>
                    </div>
            </div>
        </form>

		   <!-- ========================  Вхід ======================== -->
         <form class="form-horizontal" id="sending_form">
            <div>
              <label class="col-md-5 control-label"></label>               
				<div class="col-md-4 text-center">
                    <input type="submit" class="btn btn-primary btn-lg btn-block" title="" 
                           data-bind="enable: emplId, click: logIntoCabinet" value="Вхід" />
						   
							<?php include 'include/demo.php'; ?>

                </div>
            </div>

        </form>

    </div><!-- container -->

</div><!-- hero-box -->

<div class="hero-box full-bg-2 fixed-bg hero-box-smaller pad5T">
    <div class="container">

            <script>
                function testAnim(x) {
                    $('#animationSandbox')
                        .removeClass()
                        .addClass(x + ' animated')
                        .one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', 
                            function(){$(this).removeClass();});
                };

                $(document).ready(function(){
                    $('.js--triggerAnimation').click(function(){
                        var anim = $('.js--animations').val();
                        testAnim(anim);
                    });

                    $('.js--animations').change(function(){
                        var anim = $(this).val();
                        testAnim(anim);
                    });
                });
            </script>
        </div>
    </div>
</div>

</div>

</div>

<?php include 'include/footer.php'; ?>

	<script src="lib/knockout/dist/knockout.js"></script>
	<script src="lib/bootstrap/dist/js/bootstrap.min.js"></script>
	<script src="js/main.js"></script>

    <script>
        (function ($) { // Avoid conflicts with other libraries
            'use strict';
            $(function() {
                var settings = {
                        min: 160,
                        scrollSpeed: 400
                    },
                    toTop = $('.scroll-btn'),
                    toTopHidden = true;
                $(window).scroll(function() {
                    var pos = $(this).scrollTop();
                    if (pos > settings.min && toTopHidden) {
                        toTop.stop(true, true).fadeIn();
                        toTopHidden = false;
                    } else if (pos <= settings.min && !toTopHidden) {
                        toTop.stop(true, true).fadeOut();
                        toTopHidden = true;
                    }
                });
                toTop.bind('click touchstart',
                    function() {
                        $('html, body').animate({
                                scrollTop: 0
                            },
                            settings.scrollSpeed);
                    });
            });
        })(jQuery)
    </script>
    
    <script src="js/signing/base.js"></script>
    <script src="js/viewmodels/login.js"></script>
	
    <script type="text/javascript">
        var model = new LoginViewModel();
        ko.applyBindings(model);
    </script>
   
   <script type="text/JavaScript">
        document.getElementById('sending_form').onsubmit = function() {
            logIntoCabinet();
        };
    </script>

    <!-- FRONTEND ELEMENTS -->
    <script type="text/javascript" src="design/tether/js/tether.js"></script>
    <script type="text/javascript" src="design/widgets/skrollr/skrollr.js"></script>

    <!-- Owl carousel -->
    <script type="text/javascript" src="design/widgets/owlcarousel/owlcarousel.js"></script>
    <script type="text/javascript" src="design/widgets/owlcarousel/owlcarousel-demo.js"></script>

    <!-- HG sticky -->
    <script type="text/javascript" src="design/widgets/sticky/sticky.js"></script>

    <!-- WOW -->
    <script type="text/javascript" src="design/widgets/wow/wow.js"></script>

    <!-- VideoBG -->
    <script type="text/javascript" src="design/widgets/videobg/videobg.js"></script>
    <script type="text/javascript" src="design/widgets/videobg/videobg-demo.js"></script>

    <!-- Mixitup -->
    <script type="text/javascript" src="design/widgets/mixitup/mixitup.js"></script>
    <script type="text/javascript" src="design/widgets/mixitup/isotope.js"></script>

    <!-- Superclick -->
    <script type="text/javascript" src="design/widgets/superclick/superclick.js"></script>

    <!-- Input switch alternate -->
    <script type="text/javascript" src="design/widgets/input-switch/inputswitch-alt.js"></script>

    <!-- Slim scroll -->
    <script type="text/javascript" src="design/widgets/slimscroll/slimscroll.js"></script>

    <!-- Content box -->
    <script type="text/javascript" src="design/widgets/content-box/contentbox.js"></script>

    <!-- Overlay -->
    <script type="text/javascript" src="design/widgets/overlay/overlay.js"></script>

    <!-- Widgets init for demo -->
    <script type="text/javascript" src="design/js-init/widgets-init.js"></script>
    <script type="text/javascript" src="design/js-init/frontend-init.js"></script>

    <!-- Theme layout -->
    <script type="text/javascript" src="design/themes/frontend/layout.js"></script>

    <!-- Theme switcher -->
    <script type="text/javascript" src="design/widgets/theme-switcher/themeswitcher.js"></script>

</body>
</html>




    <!-- navbar-fixed-top-->
    <nav class="header-navbar navbar navbar-with-menu navbar-fixed-top navbar-semi-dark navbar-shadow">
      <div class="navbar-wrapper">
        <div class="navbar-header">
          <ul class="nav navbar-nav">
            <li class="nav-item mobile-menu hidden-md-up float-xs-left"><a class="nav-link nav-menu-main menu-toggle hidden-xs"><i class="icon-menu5 font-large-1"></i></a></li>
            <li class="nav-item"><a href="index.html" class="navbar-brand nav-link"><img alt="branding logo" src="../app-assets/images/logo/robust-logo-light-big.png" data-expand="../app-assets/images/logo/robust-logo-light-big.png" data-collapse="../app-assets/images/logo/robust-logo-small.png" class="brand-logo"></a></li>
            <li class="nav-item hidden-md-up float-xs-right"><a data-toggle="collapse" data-target="#navbar-mobile" class="nav-link open-navbar-container"><i class="icon-ellipsis pe-2x icon-icon-rotate-right-right"></i></a></li>
          </ul>
        </div>
        <div class="navbar-container content container-fluid">
          <div id="navbar-mobile" class="collapse navbar-toggleable-sm">
            <ul class="nav navbar-nav">
              <li class="nav-item hidden-sm-down"><a class="nav-link nav-menu-main menu-toggle hidden-xs"><i class="icon-menu5">         </i></a></li>
              <li class="nav-item hidden-sm-down"><a href="#" class="nav-link nav-link-expand"><i class="ficon icon-expand2"></i></a></li>
            </ul>
            <ul class="nav navbar-nav float-xs-right">
              <li class="dropdown dropdown-language nav-item"><a id="dropdown-flag" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="dropdown-toggle nav-link"><i class="flag-icon flag-icon-gb"></i><span class="selected-language">English</span></a>
                <div aria-labelledby="dropdown-flag" class="dropdown-menu"><a href="#" class="dropdown-item"><i class="flag-icon flag-icon-gb"></i> English</a><a href="#" class="dropdown-item"><i class="flag-icon flag-icon-cn"></i> Chinese</a></div>
              </li>
              <li class="dropdown dropdown-notification nav-item"><a href="#" data-toggle="dropdown" class="nav-link nav-link-label"><i class="ficon icon-bell4"></i><span class="tag tag-pill tag-default tag-danger tag-default tag-up">0</span></a>
                <ul class="dropdown-menu dropdown-menu-media dropdown-menu-right">
                  <li class="dropdown-menu-header">
                    <h6 class="dropdown-header m-0"><span class="grey darken-2">Notifications</span><span class="notification-tag tag tag-default tag-danger float-xs-right m-0">0 New</span></h6>
                  </li>
                  <li class="list-group scrollable-container"><a href="javascript:void(0)" class="list-group-item">
                      
                  <li class="dropdown-menu-footer"><a href="javascript:void(0)" class="dropdown-item text-muted text-xs-center">Read all notifications</a></li>
                </ul>
              </li>
              <li class="dropdown dropdown-notification nav-item"><a href="#" data-toggle="dropdown" class="nav-link nav-link-label"><i class="ficon icon-mail6"></i><span class="tag tag-pill tag-default tag-info tag-default tag-up">0</span></a>
                <ul class="dropdown-menu dropdown-menu-media dropdown-menu-right">
                  <li class="dropdown-menu-header">
                    <h6 class="dropdown-header m-0"><span class="grey darken-2">Messages</span><span class="notification-tag tag tag-default tag-info float-xs-right m-0">0 New</span></h6>
                  </li>
                  <li class="list-group scrollable-container"><a href="javascript:void(0)" class="list-group-item">
                      
                  <li class="dropdown-menu-footer"><a href="javascript:void(0)" class="dropdown-item text-muted text-xs-center">Read all messages</a></li>
                </ul>
              </li>
              <li class="dropdown dropdown-user nav-item"><a href="#" data-toggle="dropdown" class="dropdown-toggle nav-link dropdown-user-link"><span class="avatar avatar-online"><img src="../app-assets/images/portrait/small/avatar-s-1.png" alt="avatar"><i></i></span>
              <span class="user-name">
                <!--John Doe --> <?php echo $_SESSION['user_data']['username']; ?>
              </span></a>
                <div class="dropdown-menu dropdown-menu-right"><a href="#" class="dropdown-item"><i class="icon-head"></i> Edit Profile</a><a href="#" class="dropdown-item"><i class="icon-mail6"></i> My Inbox</a><a href="#" class="dropdown-item"><i class="icon-clipboard2"></i> Task</a><a href="#" class="dropdown-item"><i class="icon-calendar5"></i> Calender</a>
                  <div class="dropdown-divider"></div>
                  <a href="<?php echo ROOT_URL; ?>/users/logout" class="dropdown-item"><i class="icon-power3">
                    
                  </i> Logout</a>
                </div>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </nav>

    <!-- ////////////////////////////////////////////////////////////////////////////-->


    <!-- main menu-->
    <div data-scroll-to-active="true" class="main-menu menu-fixed menu-dark menu-accordion menu-shadow">
        <!-- main menu header-->
        <div class="main-menu-header">
          <input type="text" placeholder="Search" class="menu-search form-control round"/>
        </div>
        <!-- / main menu header-->
        <!-- main menu content-->
        <div class="main-menu-content">
          <ul id="main-menu-navigation" data-menu="menu-navigation" class="navigation navigation-main">
            <li class=" nav-item"><a href="dashboard.html"><i class="icon-home3"></i><span data-i18n="nav.dash.main" class="menu-title">Dashboard</span></a>
              
            </li>
            <li class=" nav-item"><a href="#"><i class="icon-stack-2"></i><span data-i18n="nav.page_layouts.main" class="menu-title">Students</span></a>
              <ul class="menu-content">
                <li><a href="add-student.html" data-i18n="nav.page_layouts.1_column" class="menu-item">Add Student</a>
                </li>
                <li><a href="edit-student.html" data-i18n="nav.page_layouts.2_columns" class="menu-item">Edit Student</a>
                </li>
                <li><a href="remove-student.html" data-i18n="nav.page_layouts.boxed_layout" class="menu-item">Remove student</a>
                </li>
                
                
                
              </ul>
            </li>
            <li class="disabled nav-item"><a href="#"><i class="icon-briefcase4"></i><span data-i18n="nav.project.main" class="menu-title">Teachers</span></a>
              <ul class="menu-content">
                <li><a href="add-teacher.html" data-i18n="nav.invoice.invoice_template" class="menu-item">Add Teacher</a>
                </li>
                <li><a href="edit-teacher.html" data-i18n="nav.gallery_pages.gallery_grid" class="menu-item">Edit Teacher</a>
                </li>
                <li><a href="remove-teacher.html" data-i18n="nav.search_pages.search_page" class="menu-item">Delete Teacher</a>
                </li>
                
              
              </ul>
            </li>
            <li class=" nav-item"><a href="#"><i class="icon-ios-albums-outline"></i><span data-i18n="nav.cards.main" class="menu-title">Assessments</span></a>
              <ul class="menu-content">
                  <li><a href="check-assessment.html" data-i18n="nav.cards.card_actions" class="menu-item">Check Assessment</a>
                  </li>
                <li><a href="add-assessment.html" data-i18n="nav.cards.card_bootstrap" class="menu-item">Add Assessment</a>
                </li>
                <li><a href="edit-assessment.html" data-i18n="nav.cards.card_actions" class="menu-item">Edit Assessment</a>
                </li>
                <li><a href="remove-assessment.html" data-i18n="nav.cards.card_actions" class="menu-item">Remove Assesment</a>
                </li>
              </ul>
            </li>
            <li class="disabled nav-item"><a href="#"><i class="icon-whatshot"></i><span data-i18n="nav.advance_cards.main" class="menu-title">Courses</span></a>
              <ul class="menu-content">
                <li><a href="architecture.html" data-i18n="nav.cards.card_statistics" class="menu-item">Architecture</a>
                </li>
                <li><a href="inventing.html" data-i18n="nav.cards.card_charts" class="menu-item">Inventing and Prototyping</a>
                </li>
                <li><a href="horticulture.html" data-i18n="nav.cards.card_charts" class="menu-item">Horticulture</a>
                </li>
                <li><a href="programming.html" data-i18n="nav.cards.card_charts" class="menu-item">Programming</a>
                </li>
              </ul>
            </li>
           
            
          </ul>
        </div>
        <!-- /main menu content-->
        <!-- main menu footer-->
        <!-- include includes/menu-footer-->
        <!-- main menu footer-->
      </div>
      <!-- / main menu-->

    <div class="app-content content container-fluid">
      <div class="content-wrapper">
        <div class="content-header row">
        </div>
        <div class="content-body"><!-- stats -->
<div class="row">


</div>
<!--/ stats -->
<!--/ project charts -->
<div class="row">
    
</div>
<!--/ project charts -->

<div class="row match-height">
    
    
    
</div>

        </div>
      </div>
    </div>
    <!-- ////////////////////////////////////////////////////////////////////////////-->


    <footer class="footer footer-static footer-light navbar-border">
      <p class="clearfix text-muted text-sm-center mb-0 px-2"><span class="float-md-left d-xs-block d-md-inline-block">Copyright  &copy; 2018 <a href="https://huobo.org" target="_blank" class="text-bold-800 grey darken-2">Huobo University </a>, All rights reserved. </span></p>
    </footer>

  
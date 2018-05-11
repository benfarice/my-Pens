<body data-open="click" data-menu="vertical-menu" data-col="2-columns" class="vertical-layout vertical-menu 2-columns  fixed-navbar">

  <!-- navbar-fixed-top-->
  <nav class="header-navbar navbar navbar-with-menu navbar-fixed-top navbar-semi-dark navbar-shadow">
    <div class="navbar-wrapper">
      <div class="navbar-header">
        <ul class="nav navbar-nav">
          <li class="nav-item mobile-menu hidden-md-up float-xs-left">
            <a class="nav-link nav-menu-main menu-toggle hidden-xs">
              <i class="icon-menu5 font-large-1"></i>
            </a>
          </li>
          <li class="nav-item">
            <a href="index.html" class="navbar-brand nav-link">
              <img alt="branding logo" src="<?php echo ROOT_URL; ?>/app-assets/images/logo/robust-logo-light-big.png" data-expand="<?php echo ROOT_URL; ?>/app-assets/images/logo/robust-logo-light-big.png"
                data-collapse="<?php echo ROOT_URL; ?>/app-assets/images/logo/robust-logo-small.png" class="brand-logo">
            </a>
          </li>
          <li class="nav-item hidden-md-up float-xs-right">
            <a data-toggle="collapse" data-target="#navbar-mobile" class="nav-link open-navbar-container">
              <i class="icon-ellipsis pe-2x icon-icon-rotate-right-right"></i>
            </a>
          </li>
        </ul>
      </div>
      <div class="navbar-container content container-fluid">
        <div id="navbar-mobile" class="collapse navbar-toggleable-sm">
          <ul class="nav navbar-nav">
            <li class="nav-item hidden-sm-down">
              <a class="nav-link nav-menu-main menu-toggle hidden-xs">
                <i class="icon-menu5"> </i>
              </a>
            </li>
            <li class="nav-item hidden-sm-down">
              <a href="#" class="nav-link nav-link-expand">
                <i class="ficon icon-expand2"></i>
              </a>
            </li>
          </ul>
          <ul class="nav navbar-nav float-xs-right">
            <li class="dropdown dropdown-language nav-item">
              <a id="dropdown-flag" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="dropdown-toggle nav-link">
                <i class="flag-icon flag-icon-gb"></i>
                <span class="selected-language">English</span>
              </a>
              <div aria-labelledby="dropdown-flag" class="dropdown-menu">
                <a href="#" class="dropdown-item">
                  <i class="flag-icon flag-icon-gb"></i> English</a>
                <a href="#" class="dropdown-item">
                  <i class="flag-icon flag-icon-cn"></i> Chinese</a>
              </div>
            </li>
            <li class="dropdown dropdown-notification nav-item">
              <a href="#" data-toggle="dropdown" class="nav-link nav-link-label">
                <i class="ficon icon-bell4"></i>
                <span class="tag tag-pill tag-default tag-danger tag-default tag-up">0</span>
              </a>
              <ul class="dropdown-menu dropdown-menu-media dropdown-menu-right">
                <li class="dropdown-menu-header">
                  <h6 class="dropdown-header m-0">
                    <span class="grey darken-2">Notifications</span>
                    <span class="notification-tag tag tag-default tag-danger float-xs-right m-0">0 New</span>
                  </h6>
                </li>
                <li class="list-group scrollable-container">
                  <a href="javascript:void(0)" class="list-group-item">

                    <li class="dropdown-menu-footer">
                      <a href="javascript:void(0)" class="dropdown-item text-muted text-xs-center">Read all notifications</a>
                    </li>
              </ul>
              </li>
              <li class="dropdown dropdown-notification nav-item">
                <a href="#" data-toggle="dropdown" class="nav-link nav-link-label">
                  <i class="ficon icon-mail6"></i>
                  <span class="tag tag-pill tag-default tag-info tag-default tag-up">0</span>
                </a>
                <ul class="dropdown-menu dropdown-menu-media dropdown-menu-right">
                  <li class="dropdown-menu-header">
                    <h6 class="dropdown-header m-0">
                      <span class="grey darken-2">Messages</span>
                      <span class="notification-tag tag tag-default tag-info float-xs-right m-0">0 New</span>
                    </h6>
                  </li>
                  <li class="list-group scrollable-container">
                    <a href="javascript:void(0)" class="list-group-item">

                      <li class="dropdown-menu-footer">
                        <a href="javascript:void(0)" class="dropdown-item text-muted text-xs-center">Read all messages</a>
                      </li>
                </ul>
                </li>
                <li class="dropdown dropdown-user nav-item">
                  <a href="#" data-toggle="dropdown" class="dropdown-toggle nav-link dropdown-user-link">
                    <span class="avatar avatar-online">
                      <img src="<?php echo ROOT_URL; ?>/app-assets/images/portrait/small/avatar-s-1.png" alt="avatar">
                      <i></i>
                    </span>
                    <span class="user-name">
                       <!--John Doe--><?php echo $_SESSION['user_data']['username']?>
                    </span>
                  </a>
                  <div class="dropdown-menu dropdown-menu-right">
                    <a href="#" class="dropdown-item">
                      <i class="icon-head"></i> Edit Profile</a>
                    <a href="#" class="dropdown-item">
                      <i class="icon-mail6"></i> My Inbox</a>
                    <a href="#" class="dropdown-item">
                      <i class="icon-clipboard2"></i> Task</a>
                    <a href="#" class="dropdown-item">
                      <i class="icon-calendar5"></i> Calender</a>
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item">
                      <i class="icon-power3"></i> Logout</a>
                  </div>
                </li>
          </ul>
        </div>
      </div>
    </div>
  </nav>
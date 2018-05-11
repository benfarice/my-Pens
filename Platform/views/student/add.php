
  <!-- ////////////////////////////////////////////////////////////////////////////  -->


  <!-- main menu-->
 



  <div class="app-content content container-fluid">
    <div class="content-wrapper">
      <div class="content-header row">

      </div>
      <div class="content-body">
        <!-- stats -->
        <section id="basic-form-layouts">
          <div class="row">
            <div class="col-md-12">
              <div class="card">
                <div class="card-header">
                  <h4 class="card-title" id="basic-layout-card-center">Add a student</h4>
                  <a class="heading-elements-toggle">
                    <i class="icon-ellipsis font-medium-3"></i>
                  </a>
                  <div class="heading-elements">
                    <ul class="list-inline mb-0">
                      <li>
                        <a data-action="collapse">
                          <i class="icon-minus4"></i>
                        </a>
                      </li>
                      <li>
                        <a data-action="reload">
                          <i class="icon-reload"></i>
                        </a>
                      </li>
                      <li>
                        <a data-action="expand">
                          <i class="icon-expand2"></i>
                        </a>
                      </li>
                      <li>
                        <a data-action="close">
                          <i class="icon-cross2"></i>
                        </a>
                      </li>
                    </ul>
                  </div>
                </div>
                <div class="card-body collapse in">
                  <div class="card-block">
                    <div class="card-text">
                      <p></p>
                    </div>
                    <form class="form" action="<?php $_SERVER['PHP_SELF']; ?>" novalidate method="post">
                      <div class="form-body">

                        <div class="form-group">
                          <label for="eventRegInput1">Full Name</label>
                          <input type="text" id="eventRegInput1" class="form-control" placeholder="full name" name="fullname">
                        </div>

                        <div class="form-group">
                          <label>Gender</label>
                          <div class="input-group">
                            <label class="display-inline-block custom-control custom-radio ml-1">
                              <input type="radio" name="student_gender" value="male" class="custom-control-input">
                              <span class="custom-control-indicator"></span>
                              <span class="custom-control-description ml-0">Male</span>
                            </label>
                            <label class="display-inline-block custom-control custom-radio">
                              <input type="radio" name="student_gender" value="female" checked class="custom-control-input">
                              <span class="custom-control-indicator"></span>
                              <span class="custom-control-description ml-0">Female</span>
                            </label>
                          </div>

                        </div>

                        <div class="form-group">

                          <p>Parent</p>
                        </div>

                        <div class="form-group">
                          <label for="eventRegInput3">Full Name</label>
                          <input type="text" id="eventRegInput3" class="form-control" placeholder="full name" name="parent_name">
                        </div>



                        <div class="form-group">
                          <label for="eventRegInput4">Email</label>
                          <input type="email" id="eventRegInput4" class="form-control" placeholder="email" name="email">
                        </div>

                        <div class="form-group">
                          <label for="eventRegInput5">Phone Number</label>
                          <input type="tel" id="eventRegInput5" class="form-control" name="phone_number" placeholder="contact number">
                        </div>

                        <div class="form-group">
                          <label for="eventRegInput3">Wechat ID</label>
                          <input type="text" id="eventRegInput3" class="form-control" placeholder="wechat ID" name="Wechat">
                        </div>

                        <div class="form-group">
                          <label>Existing Customer</label>
                          <div class="input-group">
                            <label class="display-inline-block custom-control custom-radio ml-1">
                              <input type="radio" name="Existing_Customer" value="yes" class="custom-control-input">
                              <span class="custom-control-indicator"></span>
                              <span class="custom-control-description ml-0">Yes</span>
                            </label>
                            <label class="display-inline-block custom-control custom-radio">
                              <input type="radio" name="Existing_Customer" value="no" checked class="custom-control-input">
                              <span class="custom-control-indicator"></span>
                              <span class="custom-control-description ml-0">No</span>
                            </label>
                          </div>
                        </div>
                      </div>

                      <div class="form-actions center">
                        <button type="button" class="btn btn-warning mr-1">
                          <i class="icon-cross2"></i> Cancel
                        </button>
                        <button type="submit" name="submit" class="btn btn-primary">
                          <i class="icon-check2"></i> Save
                        </button>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>
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
    <p class="clearfix text-muted text-sm-center mb-0 px-2">
      <span class="float-md-left d-xs-block d-md-inline-block">Copyright &copy; 2018
        <a href="https://huobo.org" target="_blank" class="text-bold-800 grey darken-2">Huobo University </a>, All rights reserved. </span>
    </p>
  </footer>


</body>
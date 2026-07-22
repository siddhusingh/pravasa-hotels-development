<aside class="main-sidebar">
   <!-- sidebar-->
   <section class="sidebar position-relative">
      <div class="multinav">
         <div class="multinav-scroll" style="height: 99%;">
            <!-- sidebar menu-->
            <ul class="sidebar-menu" data-widget="tree">
               <li class="<?php if ($this->uri->segment('1') == 'hotel-admin-dashbaord') {
                              echo "treeview active menu-open";
                           } ?>">
                  <a href="<?php echo base_url('hotel-admin-dashbaord') ?>">
                     <i class="fa fa-home" aria-hidden="true"></i>
                     <span>Dashboard</span>
                  </a>
               </li>

               <li class="<?php if ($this->uri->segment('1') == 'view-staff-admin') {
                              echo "treeview active menu-open";
                           } ?>">
                  <a href="<?php echo base_url('view-staff-admin') ?>">
                     <i class="fa fa-users" aria-hidden="true"></i>
                     <span>Staff Management </span>
                  </a>
               </li>



               <li class="<?php if ($this->uri->segment('1') == 'view-leads' || $this->uri->segment('1') == 'add-lead-admin') {
                              echo "treeview active menu-open";
                           } ?>">
                  <a href="<?php echo base_url('view-leads') ?>">
                     <i class="fa fa-phone" aria-hidden="true"></i>
                     <span>Leads Management </span>
                  </a>
               </li>


               <li class="<?php if ($this->uri->segment('1') == 'view-followups-admin') {
                              echo "treeview active menu-open";
                           } ?>">
                  <a href="<?php echo base_url('view-followups-admin') ?>">
                     <i class="fa fa-bell" aria-hidden="true"></i>
                     <span>Followups </span>
                  </a>
               </li>




               <li class="<?php if ($this->uri->segment('1') == 'hotel-admin-profile') {
                              echo "treeview active menu-open";
                           } ?>">
                  <a href="<?php echo base_url('hotel-admin-profile') ?>">
                     <i class="fa fa-user" aria-hidden="true"></i>
                     <span>My Profile </span>
                  </a>
               </li>

               <li class="<?php if ($this->uri->segment('1') == 'guest-contact-book-admin') {
                              echo "treeview active menu-open";
                           } ?>">
                  <a href="<?php echo base_url('guest-contact-book-admin') ?>">
                     <i class="fa fa-address-book" aria-hidden="true"></i>

                     <span>Guest Contact Book</span>
                  </a>
               </li>

               <li class="treeview <?php if ($this->uri->segment('1') == 'hotel-admin' && $this->uri->segment('2') == 'manage-roomtypes') {
                                       echo "active menu-open";
                                    } ?>">
                  <a href="#">
                     <i class="fa fa-cogs" aria-hidden="true"></i>
                     <span>Master Settings</span>
                     <span class="pull-right-container">
                        <i class="fa fa-angle-right pull-right"></i>
                     </span>
                  </a>
                  <ul class="treeview-menu">
                     <li class="<?php if ($this->uri->segment('1') == 'hotel-admin' && $this->uri->segment('2') == 'manage-roomtypes') {
                                    echo "active";
                                 } ?>">
                        <a href="<?php echo base_url('hotel-admin/manage-roomtypes') ?>">
                           <i class="fa fa-th-list" aria-hidden="true"></i> Room Types
                        </a>
                     </li>
                  </ul>
               </li>

               <li class="treeview <?php if (($this->uri->segment('1') == 'hotel-admin' && in_array($this->uri->segment('2'), ['manage-restaurants', 'manage-banquet', 'manage-departments', 'manage-table-categories', 'manage-time-slots'], true)) || in_array($this->uri->segment('1'), ['restaurants', 'view-departments'], true)) {
                                       echo "active menu-open";
                                    } ?>">
                  <a href="#">
                     <i class="fa fa-hotel" aria-hidden="true"></i>
                     <span>Property Management</span>
                     <span class="pull-right-container">
                        <i class="fa fa-angle-right pull-right"></i>
                     </span>
                  </a>
                  <ul class="treeview-menu">
                     <li class="<?php if (($this->uri->segment('1') == 'hotel-admin' && $this->uri->segment('2') == 'manage-restaurants') || $this->uri->segment('1') == 'restaurants') {
                                    echo "active";
                                 } ?>">
                        <a href="<?php echo base_url('hotel-admin/manage-restaurants') ?>">
                           <i class="fa fa-cutlery" aria-hidden="true"></i> Restaurants
                        </a>
                     </li>
                     <li class="<?php if ($this->uri->segment('1') == 'hotel-admin' && $this->uri->segment('2') == 'manage-banquet') {
                                    echo "active";
                                 } ?>">
                        <a href="<?php echo base_url('hotel-admin/manage-banquet') ?>">
                           <i class="fa fa-building" aria-hidden="true"></i> Banquets
                        </a>
                     </li>
                     <li class="<?php if (($this->uri->segment('1') == 'hotel-admin' && $this->uri->segment('2') == 'manage-departments') || $this->uri->segment('1') == 'view-departments') {
                                    echo "active";
                                 } ?>">
                        <a href="<?php echo base_url('hotel-admin/manage-departments') ?>">
                           <i class="fa fa-sitemap" aria-hidden="true"></i> Departments
                        </a>
                     </li>
                     <li class="<?php if ($this->uri->segment('1') == 'hotel-admin' && $this->uri->segment('2') == 'manage-table-categories') {
                                    echo "active";
                                 } ?>">
                        <a href="<?php echo base_url('hotel-admin/manage-table-categories') ?>">
                           <i class="fa fa-list-alt" aria-hidden="true"></i> Tables Categories
                        </a>
                     </li>
                     <li class="<?php if ($this->uri->segment('1') == 'hotel-admin' && $this->uri->segment('2') == 'manage-time-slots') {
                                    echo "active";
                                 } ?>">
                        <a href="<?php echo base_url('hotel-admin/manage-time-slots') ?>">
                           <i class="fa fa-clock-o" aria-hidden="true"></i> Time Slots
                        </a>
                     </li>
                  </ul>
               </li>

               <li class="treeview <?php if ($this->uri->segment('1') == 'reports') {
                                       echo "treeview active menu-open";
                                    } ?>">
                  <a href="#">
                     <i class="fa fa-upload" aria-hidden="true"></i>

                     <span>View Reports</span>
                     <span class="pull-right-container">
                        <i class="fa fa-angle-right pull-right"></i>
                     </span>
                  </a>
                  <ul class="treeview-menu <?php if ($this->uri->segment('1') == 'reports-admin') {
                                                echo "treeview active menu-open";
                                             } ?>">


                     <li>
                        <!-- Trigger modal -->
                        <a href="<?php echo base_url('admin-reports-lead-by-dispositions') ?>">
                           <i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Stage -wise Lead
                        </a>
                     </li>

                     <li>
                        <!-- Trigger modal -->
                        <a href="<?php echo base_url('admin-reports-lead-by-source') ?>">
                           <i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Source-wise Lead
                        </a>
                     </li>
                     <li>
                        <!-- Trigger modal -->
                        <a href="<?php echo base_url('admin-reports-lead-by-departments') ?>">
                           <i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Department-wise Lead
                        </a>

                     </li>

                     <li>
                        <!-- Trigger modal -->
                        <a href="<?php echo base_url('admin-reports-property-materialized') ?>">
                           <i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Property Materialization
                        </a>
                     </li>
                     <li>
                        <!-- Trigger modal -->
                        <a href="<?php echo base_url('reports-admin') ?>">
                           <i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i> Custom Report
                        </a>
                     </li>
                     <li>
                        <!-- Trigger modal -->
                        <a href="<?php echo base_url('admin-reports-summary') ?>">
                           <i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Summary Report
                        </a>
                     </li>
                  </ul>
               </li>




               <li class="">
                  <a href="<?php echo base_url('hotel-admin-sign-out') ?>">
                     <i class="fa fa-sign-out" aria-hidden="true"></i>

                     <span>Sign-out</span>
                  </a>
               </li>



            </ul>
         </div>
      </div>
   </section>
</aside>

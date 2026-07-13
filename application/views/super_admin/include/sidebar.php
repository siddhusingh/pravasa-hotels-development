<aside class="main-sidebar">
   <section class="sidebar position-relative">
      <div class="multinav">
         <div class="multinav-scroll" style="height:99%;">

            <ul class="sidebar-menu" data-widget="tree">

               <!-- ================= DASHBOARD ================= -->
               <li class="<?php if ($this->uri->segment(1) == 'super-admin-dashbaord') echo 'active'; ?>">
                  <a href="<?= base_url('super-admin-dashbaord') ?>">
                     <i class="fa fa-home"></i>
                     <span>Dashboard</span>
                  </a>
               </li>


               <?php if ($this->session->userdata('user_role') == 1) { ?>


                  <!-- ================= LOCATION SETUP ================= -->
                  <li class="treeview <?php if (in_array($this->uri->segment(1), ['manage-countries', 'manage-states', 'manage-cities', 'manage-areas'])) echo 'active menu-open'; ?>">
                     <a href="#">
                        <i class="fa fa-globe"></i>
                        <span>Location Setup</span>
                        <i class="fa fa-angle-right pull-right"></i>
                     </a>

                     <ul class="treeview-menu">
                        <li class="<?php if ($this->uri->segment(1) == 'manage-countries') echo 'active'; ?>">
                           <a href="<?= base_url('manage-countries') ?>"><i class="fa fa-flag"></i> Country Management</a>
                        </li>

                        <li class="<?php if ($this->uri->segment(1) == 'manage-states') echo 'active'; ?>">
                           <a href="<?= base_url('manage-states') ?>"><i class="fa fa-map"></i> State Management</a>
                        </li>

                        <li class="<?php if ($this->uri->segment(1) == 'manage-cities') echo 'active'; ?>">
                           <a href="<?= base_url('manage-cities') ?>"><i class="fa fa-area-chart"></i> City Management</a>
                        </li>

                        <li class="<?php if ($this->uri->segment(1) == 'manage-areas') echo 'active'; ?>">
                           <a href="<?= base_url('manage-areas') ?>"><i class="fa fa-location-arrow"></i> Area Management</a>
                        </li>
                     </ul>
                  </li>


                  <!-- ================= MASTER SETTINGS ================= -->
                  <li class="treeview <?php if (in_array($this->uri->segment(1), ['manage-roomtypes', 'manage-ratetypes', 'manage-mealplans', 'manage-designations', 'manage-team-group', 'manage-travel-modes', 'manage-slot-types', 'whatsapp-template-management'])) echo 'active menu-open'; ?>">
                     <a href="#">
                        <i class="fa fa-cogs"></i>
                        <span>Master Settings</span>
                        <i class="fa fa-angle-right pull-right"></i>
                     </a>

                     <ul class="treeview-menu">

                        <li class="<?php if ($this->uri->segment(1) == 'manage-roomtypes') echo 'active'; ?>">
                           <a href="<?= base_url('manage-roomtypes') ?>"><i class="fa fa-th-list"></i> Room Types</a>
                        </li>

                        <li class="<?php if ($this->uri->segment(1) == 'manage-ratetypes') echo 'active'; ?>">
                           <a href="<?= base_url('manage-ratetypes') ?>"><i class="fa fa-tags"></i> Rate Types</a>
                        </li>

                        <li class="<?php if ($this->uri->segment(1) == 'manage-mealplans') echo 'active'; ?>">
                           <a href="<?= base_url('manage-mealplans') ?>"><i class="fa fa-cutlery"></i> Meal Plans</a>
                        </li>

                        <li class="<?php if ($this->uri->segment(1) == 'promotional-offers') echo 'active'; ?>">
                           <a href="<?= base_url('promotional-offers') ?>">
                              <i class="fa fa-bullhorn"></i> Promotional Offers
                           </a>
                        </li>

                        <li class="<?php if ($this->uri->segment(1) == 'manage-designations') echo 'active'; ?>">
                           <a href="<?= base_url('manage-designations') ?>"><i class="fa fa-id-badge"></i> Designations</a>
                        </li>

                        <li class="<?php if ($this->uri->segment(1) == 'manage-team-group') echo 'active'; ?>">
                           <a href="<?= base_url('manage-team-group') ?>"><i class="fa fa-steam-square"></i> Team Groups</a>
                        </li>

                        <li class="<?php if ($this->uri->segment(1) == 'manage-travel-modes') echo 'active'; ?>">
                           <a href="<?= base_url('manage-travel-modes') ?>"><i class="fa fa-plane"></i> Travel Modes</a>
                        </li>

                        <li class="<?php if ($this->uri->segment(1) == 'manage-slot-types') echo 'active'; ?>">
                           <a href="<?= base_url('manage-slot-types') ?>"><i class="fa fa-clock"></i> Slot Type</a>
                        </li>

                        <li class="<?php if ($this->uri->segment(1) == 'whatsapp-template-management') echo 'active'; ?>">
                           <a href="<?= base_url('whatsapp-template-management') ?>">
                              <i class="fa fa-whatsapp"></i> WhatsApp Templates
                           </a>
                        </li>

                     </ul>
                  </li>


                  <!-- ================= COMPANY & CORPORATE ================= -->
                  <li class="treeview <?php if (in_array($this->uri->segment(1), ['manage-company-group', 'manage-area-users', 'manage-company-contacts', 'manage-companies', 'manage-agencies'])) echo 'active menu-open'; ?>">
                     <a href="#">
                        <i class="fa fa-building"></i>
                        <span>Company & Corporate</span>
                        <i class="fa fa-angle-right pull-right"></i>
                     </a>

                     <ul class="treeview-menu">

                        <li class="<?php if ($this->uri->segment(1) == 'manage-company-group') echo 'active'; ?>">
                           <a href="<?= base_url('manage-company-group') ?>"><i class="fa fa-object-group"></i> Company Groups</a>
                        </li>

                        <li class="<?php if ($this->uri->segment(1) == 'manage-area-users') echo 'active'; ?>">
                           <a href="<?= base_url('manage-area-users') ?>"><i class="fa fa-users"></i> Area Users</a>
                        </li>

                        <li class="<?php if ($this->uri->segment(1) == 'manage-company-contacts') echo 'active'; ?>">
                           <a href="<?= base_url('manage-company-contacts') ?>"><i class="fa fa-building"></i> Company Contacts</a>
                        </li>

                        <li class="<?php if ($this->uri->segment(1) == 'manage-companies') echo 'active'; ?>">
                           <a href="<?= base_url('manage-companies') ?>"><i class="fa fa-building"></i> Companies</a>
                        </li>

                        <li class="<?php if ($this->uri->segment(1) == 'manage-agencies') echo 'active'; ?>">
                           <a href="<?= base_url('manage-agencies') ?>"><i class="fa fa-handshake"></i> Agency Management</a>
                        </li>

                     </ul>
                  </li>


                  <!-- ================= PROPERTY MANAGEMENT ================= -->
                  <li class="treeview <?php if (in_array($this->uri->segment(1), ['manage-hotels', 'manage-hotel-admins', 'manage-hotel-restaurants', 'manage-banquet', 'manage-departments'])) echo 'active menu-open'; ?>">
                     <a href="#">
                        <i class="fa fa-hotel"></i>
                        <span>Property Management</span>
                        <i class="fa fa-angle-right pull-right"></i>
                     </a>

                     <ul class="treeview-menu">

                        <li class="<?php if ($this->uri->segment(1) == 'manage-hotels') echo 'active'; ?>">
                           <a href="<?= base_url('manage-hotels') ?>"><i class="fa fa-hotel"></i> Hotels Management</a>
                        </li>



                        <li class="<?php if ($this->uri->segment(1) == 'manage-hotel-restaurants') echo 'active'; ?>">
                           <a href="<?= base_url('manage-hotel-restaurants') ?>"><i class="fa fa-fire"></i> Restaurants Management</a>
                        </li>

                        <li class="<?php if ($this->uri->segment(1) == 'manage-banquet') echo 'active'; ?>">
                           <a href="<?= base_url('manage-banquet') ?>"><i class="fa fa-building"></i> Banquet Management</a>
                        </li>

                        <li class="<?php if ($this->uri->segment(1) == 'manage-departments') echo 'active'; ?>">
                           <a href="<?= base_url('manage-departments') ?>"><i class="fa fa-sitemap"></i> Department Management</a>
                        </li>

                        <li class="<?php if ($this->uri->segment(1) == 'manage-table-categories') echo 'active'; ?>">
                           <a href="<?= base_url('manage-table-categories') ?>">
                              <i class="fa fa-list-alt"></i> Table Categories
                           </a>
                        </li>

                        <li class="<?php if ($this->uri->segment(1) == 'manage-tables') echo 'active'; ?>">
                           <a href="<?= base_url('manage-tables') ?>">
                              <i class="fa fa-table"></i> Tables
                           </a>
                        </li>

                        <li class="<?php if ($this->uri->segment(1) == 'manage-time-slots') echo 'active'; ?>">
                           <a href="<?= base_url('manage-time-slots') ?>">
                              <i class="fa fa-clock"></i> Time Slots
                           </a>
                        </li>

                     </ul>
                  </li>


               <?php } ?>


               <!-- ================= LEADS & GUEST MANAGEMENT ================= -->
               <li class="<?php if (in_array($this->uri->segment(1), ['manage-leads', 'add-lead'])) echo 'active'; ?>">
                  <a href="<?= base_url('manage-leads') ?>">
                     <i class="fa fa-phone-volume"></i>
                     <span>Leads Management</span>
                  </a>
               </li>

               <li class="<?php if ($this->uri->segment(1) == 'followups') echo 'active'; ?>">
                  <a href="<?= base_url('followups') ?>">
                     <i class="fa fa-bell"></i>
                     <span>Followups</span>
                  </a>
               </li>

               <!-- <li>
                  <a href="javascript:void(0)" id="openAvailabilityPopup">
                     <i class="fa fa-bed"></i>
                     <span>Check Room Availability</span>
                  </a>
               </li> -->

               <li class="<?php if ($this->uri->segment(1) == 'guest-contact-book') echo 'active'; ?>">
                  <a href="<?= base_url('guest-contact-book') ?>">
                     <i class="fa fa-address-book"></i>
                     <span>Guest Contact Book</span>
                  </a>
               </li>

               <li class="<?php if ($this->uri->segment(1) == 'activity-logs') echo 'active'; ?>">
                  <a href="<?= base_url('activity-logs') ?>">
                     <i class="fa fa-history"></i>
                     <span>Access & Activity Logs</span>
                  </a>
               </li>

               <li class="treeview <?php
                                    if (
                                       $this->uri->segment(1) == 'banquet-feedback-list' ||
                                       $this->uri->segment(1) == 'room-feedback-list' ||
                                       $this->uri->segment(1) == 'restaurant-feedback-list'
                                    ) echo 'active menu-open';
                                    ?>">

                  <a href="#">
                     <i class="fa fa-commenting"></i>
                     <span>Feedback</span>
                     <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                     </span>
                  </a>

                  <ul class="treeview-menu" style="<?php
                                                   if (
                                                      $this->uri->segment(1) == 'banquet-feedback-list' ||
                                                      $this->uri->segment(1) == 'room-feedback-list' ||
                                                      $this->uri->segment(1) == 'restaurant-feedback-list'
                                                   ) echo 'display:block;';
                                                   ?>">

                     <li class="<?php if ($this->uri->segment(1) == 'banquet-feedback-list') echo 'active'; ?>">
                        <a href="<?= base_url('banquet-feedback-list') ?>">
                           <i class="fa fa-circle-o"></i>
                           Banquet Feedback
                        </a>
                     </li>

                     <li class="<?php if ($this->uri->segment(1) == 'room-feedback-list') echo 'active'; ?>">
                        <a href="<?= base_url('room-feedback-list') ?>">
                           <i class="fa fa-circle-o"></i>
                           Room Feedback
                        </a>
                     </li>

                     <li class="<?php if ($this->uri->segment(1) == 'restaurant-feedback-list') echo 'active'; ?>">
                        <a href="<?= base_url('restaurant-feedback-list') ?>">
                           <i class="fa fa-circle-o"></i>
                           Restaurant Feedback
                        </a>
                     </li>

                  </ul>
               </li>



               <!-- ================= SALES OPERATIONS ================= -->


               <?php if ($this->session->userdata('user_role') == 1) { ?>

                  <li class="treeview <?php if (in_array($this->uri->segment(1), ['add-sales-visit', 'sales-visits-history', 'weekly-planner'])) echo 'active menu-open'; ?>">
                     <a href="#">
                        <i class="fa fa-map-marker"></i>
                        <span>Sales Operations</span>
                        <i class="fa fa-angle-right pull-right"></i>
                     </a>

                     <ul class="treeview-menu">

                        <li class="<?php if ($this->uri->segment(1) == 'add-sales-visit') echo 'active'; ?>">
                           <a href="<?= base_url('add-sales-visit') ?>"><i class="fa fa-plus-circle"></i> Add Visit</a>
                        </li>

                        <li class="<?php if ($this->uri->segment(1) == 'sales-visits-history') echo 'active'; ?>">
                           <a href="<?= base_url('sales-visits-history') ?>"><i class="fa fa-history"></i> Visit History</a>
                        </li>

                        <li class="<?php if ($this->uri->segment(1) == 'weekly-planner') echo 'active'; ?>">
                           <a href="<?= base_url('weekly-planner') ?>"><i class="fa fa-lightbulb-o"></i> Weekly Planner</a>
                        </li>

                     </ul>
                  </li>


                  <!-- ================= USER MANAGEMENT ================= -->
                  <li class="treeview <?php if (in_array($this->uri->segment(1), ['manage-super-admin', 'regional-managers', 'manage-sales-users', 'manage-staff'])) echo 'active menu-open'; ?>">
                     <a href="#">
                        <i class="fa fa-users"></i>
                        <span>User Management</span>
                        <i class="fa fa-angle-right pull-right"></i>
                     </a>

                     <ul class="treeview-menu">

                        <li class="<?php if ($this->uri->segment(1) == 'manage-super-admin') echo 'active'; ?>">
                           <a href="<?= base_url('manage-super-admin') ?>"><i class="fa fa-user-secret"></i> Super Administrators</a>
                        </li>



                        <!-- <li class="<?php if ($this->uri->segment(1) == 'regional-managers') echo 'active'; ?>">
                           <a href="<?= base_url('regional-managers') ?>"><i class="fa fa-users"></i> Regional Managers</a>
                        </li> -->

                        <li class="<?php if ($this->uri->segment(1) == 'manage-hotel-admins') echo 'active'; ?>">
                           <a href="<?= base_url('manage-hotel-admins') ?>"><i class="fa fa-user-secret"></i> Hotel Administrators</a>
                        </li>

                        <li class="<?php if ($this->uri->segment(1) == 'manage-sales-users') echo 'active'; ?>">
                           <a href="<?= base_url('manage-sales-users') ?>"><i class="fa fa-male"></i> Sales Executives</a>
                        </li>

                        <li class="<?php if ($this->uri->segment(1) == 'manage-staff') echo 'active'; ?>">
                           <a href="<?= base_url('manage-staff') ?>"><i class="fa fa-users"></i> Staff Management</a>
                        </li>

                     </ul>
                  </li>

                  <li class="<?php if ($this->uri->segment(1) == 'software-settings') echo 'active'; ?>">
                     <a href="<?= base_url('software-settings') ?>">
                        <i class="fa fa-cogs"></i>
                        <span>Software Settings</span>
                     </a>
                  </li>

               <?php } ?>






               <!-- ================= REPORTS ================= -->
               <li class="treeview <?php if ($this->uri->segment('1') == 'Reports') echo 'active menu-open'; ?>">
                  <a href="#">
                     <i class="fa fa-upload"></i>
                     <span>Reports</span>
                     <i class="fa fa-angle-right pull-right"></i>
                  </a>

                  <ul class="treeview-menu">

                     <li><a href="<?= base_url('reports-lead-by-dispositions') ?>">Stage-wise Lead</a></li>
                     <li><a href="<?= base_url('reports-lead-by-source') ?>">Source-wise Lead</a></li>
                     <li><a href="<?= base_url('reports-lead-by-departments') ?>">Department-wise Lead</a></li>
                     <li><a href="<?= base_url('Reports') ?>">Custom Report</a></li>
                     <li><a href="<?= base_url('reports-property-materialized') ?>">Property Materialization</a></li>
                     <li><a href="<?= base_url('reports-summary') ?>">Summary Report</a></li>

                  </ul>
               </li>


               <!-- ================= ACCOUNT ================= -->
               <li class="<?php if ($this->uri->segment(1) == 'hotel-admin-profile') echo 'active'; ?>">
                  <a href="<?= base_url('super-admin-profile') ?>">
                     <i class="fa fa-user-circle"></i>
                     <span>My Profile</span>
                  </a>
               </li>

               <li>
                  <a href="<?= base_url('super-admin-sign-out') ?>">
                     <i class="fa fa-sign-out"></i>
                     <span>Sign Out</span>
                  </a>
               </li>

            </ul>

         </div>
      </div>
   </section>
</aside>
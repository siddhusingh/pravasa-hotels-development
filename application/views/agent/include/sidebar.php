<aside class="main-sidebar">
   <!-- sidebar-->
   <section class="sidebar position-relative">
      <div class="multinav">
         <div class="multinav-scroll" style="height: 99%;">
            <!-- sidebar menu-->
            <ul class="sidebar-menu" data-widget="tree">
               <li class="<?php if ($this->uri->segment('1') == 'agent-dashboard') {
                              echo "treeview active menu-open";
                           } ?>">
                  <a href="<?php echo base_url('agent-dashboard') ?>">
                     <i class="fa fa-home" aria-hidden="true"></i>
                     <span>Dashboard</span>
                  </a>
               </li>



               <li class="<?php if ($this->uri->segment('1') == 'view-agents-leads' || $this->uri->segment('1') == 'add-lead-agents') {
                              echo "treeview active menu-open";
                           } ?>">
                  <a href="<?php echo base_url('view-agents-leads') ?>">
                     <i class="fa fa-users" aria-hidden="true"></i>
                     <span>Leads Management </span>
                  </a>
               </li>


               <li class="<?php if ($this->uri->segment('1') == 'view-followups-agent') {
                              echo "treeview active menu-open";
                           } ?>">
                  <a href="<?php echo base_url('view-followups-agent') ?>">
                     <i class="fa fa-bell" aria-hidden="true"></i>
                     <span>Followups </span>
                  </a>
               </li>


               <li class="<?php if ($this->uri->segment('1') == 'agent-profile') {
                              echo "treeview active menu-open";
                           } ?>">
                  <a href="<?php echo base_url('agent-profile') ?>">
                     <i class="fa fa-user" aria-hidden="true"></i>
                     <span>My Profile </span>
                  </a>
               </li>

               <li class="treeview <?php if ($this->uri->segment('1') == 'reports-agent') {
                                       echo "treeview active menu-open";
                                    } ?>">
                  <a href="#">
                     <i class="fa fa-upload" aria-hidden="true"></i>

                     <span>View Reports</span>
                     <span class="pull-right-container">
                        <i class="fa fa-angle-right pull-right"></i>
                     </span>
                  </a>
                  <ul class="treeview-menu <?php if ($this->uri->segment('1') == 'reports-agent') {
                                                echo "treeview active menu-open";
                                             } ?>">
                     <li>
                        <!-- Trigger modal -->
                        <a href="<?php echo base_url('reports-agent') ?>">
                           <i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i> Custom Report
                        </a>
                     </li>
                  </ul>
               </li>



               <li class="<?php if ($this->uri->segment('1') == 'agent-sign-out') {
                              echo "treeview active menu-open";
                           } ?>">
                  <a href="<?php echo base_url('agent-sign-out') ?>">
                     <i class="fa fa-sign-out" aria-hidden="true"></i>

                     <span>Sign-out</span>
                  </a>
               </li>



            </ul>
         </div>
      </div>
   </section>
</aside>
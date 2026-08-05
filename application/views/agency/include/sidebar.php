<?php
$currentRoute = $this->uri->segment(1);
$isDashboardActive = $currentRoute === 'agency-dashboard';
$isLeadsActive = in_array($currentRoute, ['view-agency-leads', 'add-lead-agency'], true);
$isProfileActive = $currentRoute === 'agency-profile';
$isReportsActive = $currentRoute === 'reports-agency';
?>

<aside class="main-sidebar">
   <section class="sidebar position-relative">
      <div class="multinav">
         <div class="multinav-scroll" style="height: 99%;">
            <ul class="sidebar-menu" data-widget="tree">
               <li class="header fs-10 m-0 text-uppercase">Dashboard</li>

               <li class="<?= $isDashboardActive ? 'active' : ''; ?>">
                  <a href="<?= base_url('agency-dashboard'); ?>">
                     <i class="fa fa-home" aria-hidden="true"></i>
                     <span>Dashboard</span>
                  </a>
               </li>

               <li class="<?= $isLeadsActive ? 'active' : ''; ?>">
                  <a href="<?= base_url('view-agency-leads'); ?>">
                     <i class="fa fa-users" aria-hidden="true"></i>
                     <span>Leads Management</span>
                  </a>
               </li>

               <li class="<?= $isProfileActive ? 'active' : ''; ?>">
                  <a href="<?= base_url('agency-profile'); ?>">
                     <i class="fa fa-user" aria-hidden="true"></i>
                     <span>My Profile</span>
                  </a>
               </li>

               <li class="treeview<?= $isReportsActive ? ' active menu-open' : ''; ?>">
                  <a href="#" aria-expanded="<?= $isReportsActive ? 'true' : 'false'; ?>">
                     <i class="fa fa-upload" aria-hidden="true"></i>
                     <span>View Reports</span>
                     <span class="pull-right-container">
                        <i class="fa fa-angle-right pull-right"></i>
                     </span>
                  </a>
                  <ul class="treeview-menu">
                     <li class="<?= $isReportsActive ? 'active' : ''; ?>">
                        <a href="<?= base_url('reports-agency'); ?>">
                           <i class="icon-Commit">
                              <span class="path1"></span><span class="path2"></span>
                           </i>
                           Custom Report
                        </a>
                     </li>
                  </ul>
               </li>

               <li>
                  <a href="<?= base_url('agency-sign-out'); ?>">
                     <i class="fa fa-sign-out" aria-hidden="true"></i>
                     <span>Sign-out</span>
                  </a>
               </li>
            </ul>
         </div>
      </div>
   </section>
</aside>

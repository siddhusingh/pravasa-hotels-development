<?php
$salesSection = $this->uri->segment(1);
$salesPage = $this->uri->segment(2);
$salesAction = $this->uri->segment(3);
?>

<aside class="main-sidebar">
   <section class="sidebar position-relative">
      <div class="multinav">
         <div class="multinav-scroll" style="height: 99%;">
            <ul class="sidebar-menu" data-widget="tree">
               <li class="<?= ($salesSection === 'sales' && $salesPage === 'dashboard') ? 'active' : '' ?>">
                  <a href="<?= base_url('sales/dashboard') ?>">
                     <i class="fa fa-home" aria-hidden="true"></i>
                     <span>Dashboard</span>
                  </a>
               </li>

               <?php if ($is_sales_manager): ?>
                  <li class="treeview <?= ($salesSection === 'sales' && $salesPage === 'executives') ? 'active menu-open' : '' ?>">
                     <a href="#">
                        <i class="fa fa-users" aria-hidden="true"></i>
                        <span>Sales Executives</span>
                        <span class="pull-right-container">
                           <i class="fa fa-angle-right pull-right"></i>
                        </span>
                     </a>
                     <ul class="treeview-menu">
                        <li class="<?= ($salesPage === 'executives' && empty($salesAction)) ? 'active' : '' ?>">
                           <a href="<?= base_url('sales/executives') ?>">
                              <i class="fa fa-list" aria-hidden="true"></i>
                              View Executives
                           </a>
                        </li>
                        <li class="<?= ($salesPage === 'executives' && $salesAction === 'add') ? 'active' : '' ?>">
                           <a href="<?= base_url('sales/executives/add') ?>">
                              <i class="fa fa-user-plus" aria-hidden="true"></i>
                              Add Executive
                           </a>
                        </li>
                     </ul>
                  </li>

                  <li class="<?= ($salesSection === 'sales' && $salesPage === 'visits') ? 'active' : '' ?>">
                     <a href="<?= base_url('sales/visits') ?>">
                        <i class="fa fa-map-marker" aria-hidden="true"></i>
                        <span>All Sales Visits</span>
                     </a>
                  </li>
               <?php endif; ?>

               <?php if ($is_sales_executive): ?>
                  <li class="treeview <?= ($salesSection === 'sales' && in_array($salesPage, ['visits', 'weekly-planner'], true)) ? 'active menu-open' : '' ?>">
                     <a href="#">
                        <i class="fa fa-map-marker" aria-hidden="true"></i>
                        <span>Sales Operations</span>
                        <span class="pull-right-container">
                           <i class="fa fa-angle-right pull-right"></i>
                        </span>
                     </a>
                     <ul class="treeview-menu">
                        <li class="<?= ($salesPage === 'visits' && $salesAction === 'add') ? 'active' : '' ?>">
                           <a href="<?= base_url('sales/visits/add') ?>">
                              <i class="fa fa-plus-circle" aria-hidden="true"></i>
                              Add Visit
                           </a>
                        </li>
                        <li class="<?= ($salesPage === 'visits' && empty($salesAction)) ? 'active' : '' ?>">
                           <a href="<?= base_url('sales/visits') ?>">
                              <i class="fa fa-history" aria-hidden="true"></i>
                              Visit History
                           </a>
                        </li>
                        <li class="<?= ($salesPage === 'weekly-planner') ? 'active' : '' ?>">
                           <a href="<?= base_url('sales/weekly-planner') ?>">
                              <i class="fa fa-lightbulb-o" aria-hidden="true"></i>
                              Weekly Planner
                           </a>
                        </li>
                     </ul>
                  </li>
               <?php endif; ?>

               <?php if ($is_sales_executive): ?>
                  <li class="treeview <?= ($salesSection === 'sales' && in_array($salesPage, ['company-groups', 'area-users', 'company-contacts', 'companies', 'agencies'], true)) ? 'active menu-open' : '' ?>">
                     <a href="#">
                        <i class="fa fa-building" aria-hidden="true"></i>
                        <span>Company</span>
                        <span class="pull-right-container">
                           <i class="fa fa-angle-right pull-right"></i>
                        </span>
                     </a>
                     <ul class="treeview-menu">
                        <li class="<?= ($salesPage === 'companies') ? 'active' : '' ?>">
                           <a href="<?= base_url('sales/companies') ?>">
                              <i class="fa fa-building-o" aria-hidden="true"></i>
                              Companies
                           </a>
                        </li>

                        <li class="<?= ($salesPage === 'company-contacts') ? 'active' : '' ?>">
                           <a href="<?= base_url('sales/company-contacts') ?>">
                              <i class="fa fa-address-book-o" aria-hidden="true"></i>
                              Contacts
                           </a>
                        </li>
                     </ul>
                  </li>
               <?php endif; ?>

               <?php if ($is_sales_executive): ?>
                  <li class="<?= ($salesSection === 'sales' && $salesPage === 'profile') ? 'active' : '' ?>">
                     <a href="<?= base_url('sales/profile') ?>">
                        <i class="fa fa-user" aria-hidden="true"></i>
                        <span>My Profile</span>
                     </a>
                  </li>
               <?php endif; ?>

               <li>
                  <a href="<?= base_url('sales/sign-out') ?>">
                     <i class="fa fa-sign-out" aria-hidden="true"></i>
                     <span>Sign-out</span>
                  </a>
               </li>
            </ul>
         </div>
      </div>
   </section>
</aside>

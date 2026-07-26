<?php
defined('BASEPATH') OR exit('No direct script access allowed');


// HEAD Routes
$route['sales'] = 'Sales/Auth';
$route['sales/sign-in'] = 'Sales/Auth/sign_in';
$route['sales/sign-in-auth'] = 'Sales/Auth/login_auth';
$route['sales/sign-out'] = 'Sales/Auth/logout';

$route['sales/dashboard'] = 'Sales/Dashboard/index';

$route['sales/profile'] = 'Sales/Profile/index';
$route['sales/profile/update'] = 'Sales/Profile/update';

$route['sales/company-contacts'] = 'Sales/CompanyContacts/index';
$route['sales/company-contacts/table'] = 'Sales/CompanyContacts/get_contacts_table';
$route['sales/company-contacts/save'] = 'Sales/CompanyContacts/save';
$route['sales/company-contacts/details'] = 'Sales/CompanyContacts/getDetails';
$route['sales/company-contacts/delete'] = 'Sales/CompanyContacts/delete';



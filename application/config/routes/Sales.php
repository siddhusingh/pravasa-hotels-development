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

$route['sales/companies'] = 'Sales/Companies/index';
$route['sales/companies/table'] = 'Sales/Companies/get_companies_table';
$route['sales/companies/save'] = 'Sales/Companies/save';
$route['sales/companies/details'] = 'Sales/Companies/getDetails';
$route['sales/companies/delete'] = 'Sales/Companies/delete';

$route['sales/visits'] = 'Sales/Visits/index';
$route['sales/visits/add'] = 'Sales/Visits/add';
$route['sales/visits/create'] = 'Sales/Visits/insert';
$route['sales/visits/calendar'] = 'Sales/Visits/getCalendarVisits';
$route['sales/visits/details'] = 'Sales/Visits/getVisitDetails';
$route['sales/visits/company-contacts'] = 'Sales/Visits/get_company_contacts';
$route['sales/visits/restaurants'] = 'Sales/Visits/get_restaurants';
$route['sales/visits/slot-types'] = 'Sales/Visits/get_slot_types';



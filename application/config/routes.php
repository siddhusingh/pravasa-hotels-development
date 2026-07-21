<?php
defined('BASEPATH') or exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding 
to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/

// Siddhu Singh Changes

$route['default_controller'] = 'Home';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;


// super admin routes starts from here
$route['super-admin-login'] = 'superAdmin/login/index';
$route['super-admin-dashbaord'] = 'superAdmin/main/index';
$route['super-admin-profile'] = 'superAdmin/main/profile';
$route['super-admin-sign-out'] = 'superAdmin/login/logout';
$route['forget-password-super-admin'] = 'superAdmin/login/forget_password';
$route['update-super-admin-profile'] = 'superAdmin/main/update_profile';
$route['super-admin-account-settings'] = 'superAdmin/main/account_settings';

$route['feedback-list'] = 'Feedback/feedback_list_view';
$route['restaurant-feedback-list'] = 'Feedback/restaurant_feedback_list_view';
$route['banquet-feedback-list'] = 'Feedback/banquet_feedback_list_view';
$route['room-feedback-list'] = 'Feedback/room_feedback_list_view';

$route['set-password/(:any)'] = 'User_profile/set_password/$1';
$route['admin-dashboard'] = 'admin/main';
$route['sign-out'] = 'admin/login/logout';
$route['admin-dashboard'] = 'admin/main';
$route['profile'] = 'admin/main/profile';
$route['update_profile'] = 'admin/main/update_profile';
$route['check_email'] = 'admin/Login/check_email';
$route['change_password/(:any)'] = 'admin/Login/change_password/$1';
$route['confirm_password'] = 'admin/Login/confirm_password';


// manage country routes

$route['manage-countries'] = 'superAdmin/CountryManagment/index';
$route['insert-country'] = 'superAdmin/CountryManagment/insert';
$route['delete-country'] = 'superAdmin/CountryManagment/delete';
$route['edit-country'] = 'superAdmin/CountryManagment/edit';
$route['update-country'] = 'superAdmin/CountryManagment/update';
$route['countries-data-table'] = 'superAdmin/CountryManagment/get_countries_table';

// manage roomtype routes

$route['manage-roomtypes'] = 'superAdmin/RoomTypeManagment/index';
$route['insert-roomtype'] = 'superAdmin/RoomTypeManagment/insert';
$route['delete-roomtype'] = 'superAdmin/RoomTypeManagment/delete';
$route['edit-roomtype'] = 'superAdmin/RoomTypeManagment/edit';
$route['update-roomtype'] = 'superAdmin/RoomTypeManagment/update';
$route['get-roomtypes-table'] = 'superAdmin/RoomTypeManagment/get_roomtypes_table';

$route['manage-ratetypes'] = 'superAdmin/RateTypeManagment/index';
$route['insert-ratetype'] = 'superAdmin/RateTypeManagment/insert';
$route['delete-ratetype'] = 'superAdmin/RateTypeManagment/delete';
$route['edit-ratetype'] = 'superAdmin/RateTypeManagment/edit';
$route['update-ratetype'] = 'superAdmin/RateTypeManagment/update';
$route['get-ratetypes-table'] = 'superAdmin/RateTypeManagment/get_ratetypes_table';


// manage state routes

$route['manage-states'] = 'superAdmin/StateManagment/index';
$route['insert-state'] = 'superAdmin/StateManagment/insert';
$route['delete-state'] = 'superAdmin/StateManagment/delete';
$route['edit-state'] = 'superAdmin/StateManagment/edit';
$route['update-state'] = 'superAdmin/StateManagment/update';
$route['get-states-table'] = 'superAdmin/StateManagment/get_states_table';

// manage city routes

$route['manage-cities'] = 'superAdmin/CityManagment/index';
$route['insert-city'] = 'superAdmin/CityManagment/insert';
$route['delete-city'] = 'superAdmin/CityManagment/delete';
$route['edit-city'] = 'superAdmin/CityManagment/edit';
$route['update-city'] = 'superAdmin/CityManagment/update';
$route['get-cities-table'] = 'superAdmin/CityManagment/get_cities_table';
$route['city/get-states-by-country'] = 'superAdmin/CityManagment/get_states_by_country';

// manage hotel routes

$route['manage-hotels'] = 'superAdmin/HotelManagment/index';
$route['insert-hotel'] = 'superAdmin/HotelManagment/insert';
$route['delete-hotel'] = 'superAdmin/HotelManagment/delete';
$route['edit-hotel'] = 'superAdmin/HotelManagment/edit';
$route['update-hotel'] = 'superAdmin/HotelManagment/update';
$route['get-hotels-table'] = 'superAdmin/HotelManagment/get_hotels_table';
$route['hotel/get-states-by-country'] = 'superAdmin/HotelManagment/get_states_by_country';
$route['hotel/get-cities-by-state'] = 'superAdmin/HotelManagment/get_cities_by_state';


// manage departments routes

$route['manage-departments'] = 'superAdmin/DepartmentManagment/index';
$route['insert-department'] = 'superAdmin/DepartmentManagment/insert';
$route['delete-department'] = 'superAdmin/DepartmentManagment/delete';
$route['edit-department'] = 'superAdmin/DepartmentManagment/edit';
$route['update-department'] = 'superAdmin/DepartmentManagment/update';
$route['get-departments-table'] = 'superAdmin/DepartmentManagment/get_departments_table';

// manage  - staff routes

$route['manage-staff'] = 'superAdmin/Managestaff/index';
$route['insert-staff'] = 'superAdmin/Managestaff/insert';
$route['delete-staff'] = 'superAdmin/Managestaff/delete';
$route['edit-staff'] = 'superAdmin/Managestaff/edit';
$route['update-staff'] = 'superAdmin/Managestaff/update';
$route['get-staff-table'] = 'superAdmin/Managestaff/get_staff_table';
$route['view-staff'] = 'superAdmin/Managestaff/get_mapping_details';
$route['get_staff_details'] = 'superAdmin/Managestaff/get_staff_details';






// manage manage-seniors routes

$route['manage-seniors'] = 'superAdmin/ManageSeniors/index';
$route['insert-seniors'] = 'superAdmin/ManageSeniors/insert';
$route['delete-seniors'] = 'superAdmin/ManageSeniors/delete';
$route['edit-seniors'] = 'superAdmin/ManageSeniors/edit';
$route['update-seniors'] = 'superAdmin/ManageSeniors/update';


$route['regional-managers'] = 'superAdmin/RegionalManager/index';
$route['insert-regional-managers'] = 'superAdmin/RegionalManager/insert';
$route['delete-regional-managers'] = 'superAdmin/RegionalManager/delete';
$route['edit-regional-managers'] = 'superAdmin/RegionalManager/edit';
$route['update-regional-managers'] = 'superAdmin/RegionalManager/update';




// ============= End Super admin Login Password Route ===============

// hotle admin routes start from here

$route['hotel-admin-login'] = 'hotelAdmin/login/index';
$route['hotel-admin-dashbaord'] = 'hotelAdmin/main/index';
$route['hotel-admin-profile'] = 'hotelAdmin/main/profile';
$route['hotel-admin-sign-out'] = 'hotelAdmin/login/logout';
$route['forget-password-hotel-admin'] = 'hotelAdmin/login/forget_password';
$route['update-hotel-admin-profile'] = 'hotelAdmin/main/update_profile';
$route['hotel-admin-account-settings'] = 'hotelAdmin/main/account_settings';

// routes for room anylysis

$route['room_analysis'] = 'hotelAdmin/ManageRoom_Analysis/index';
$route['insert-room_analysis'] = 'hotelAdmin/ManageRoom_Analysis/insert';
$route['delete-room_analysis'] = 'hotelAdmin/ManageRoom_Analysis/delete';
$route['edit-room_analysis'] = 'hotelAdmin/ManageRoom_Analysis/edit';
$route['update-room_analysis'] = 'hotelAdmin/ManageRoom_Analysis/update';

// routes for restarants anylysis

$route['restaurants'] = 'hotelAdmin/ManageRestaurants/index';
$route['insert-restaurants'] = 'hotelAdmin/ManageRestaurants/insert';
$route['delete-restaurants'] = 'hotelAdmin/ManageRestaurants/delete';
$route['edit-restaurants'] = 'hotelAdmin/ManageRestaurants/edit';
$route['update-restaurants'] = 'hotelAdmin/ManageRestaurants/update';


// routes for restarants anylysis

$route['other-revenue'] = 'hotelAdmin/ManageOtherRevenue/index';
$route['insert-other-revenue'] = 'hotelAdmin/ManageOtherRevenue/insert';
$route['delete-other-revenue'] = 'hotelAdmin/ManageOtherRevenue/delete';
$route['edit-other-revenue'] = 'hotelAdmin/ManageOtherRevenue/edit';
$route['update-other-revenue'] = 'hotelAdmin/ManageOtherRevenue/update';



$route['add-hotel-dsr'] = 'hotelAdmin/ManageDSR/index';
$route['view-hotel-dsr'] = 'hotelAdmin/ManageDSR/viewDSR';
$route['insert-hotel-dsr'] = 'hotelAdmin/ManageDSR/insertDSR';
$route['view-hotel-dsr-details/(:any)'] = 'hotelAdmin/ManageDSR/viewDSRDetails/$1';


$route['super-admin-view-hotel-dsr/(:any)'] = 'superAdmin/ManageDSR/viewDSR/$1';
$route['super-admin-view-hotel-dsr-details/(:any)/(:any)'] = 'superAdmin/ManageDSR/viewDSRDetails/$1/$2';

$route['airtel-api'] = 'superAdmin/AirtelApi/makeCall';
$route['all-calls'] = 'superAdmin/AirtelApi/allCalls';
$route['manage-leads'] = 'LeadController/view_leads';
$route['update-lead-super-admin'] = 'LeadController/update_lead';
$route['update-lead-admin'] = 'hotelAdmin/Leads/update_lead';
$route['update-lead-agent'] = 'agent/Leads/update_lead';




$route['view-departments'] = 'hotelAdmin/Departments/index';
$route['view-staff-admin'] = 'hotelAdmin/Staff/index';
$route['view-leads'] = 'hotelAdmin/Leads/index';
$route['add-lead-admin'] = 'hotelAdmin/Leads/add_lead';
$route['insert-lead-admin'] = 'hotelAdmin/Leads/insert_lead';
$route['add-lead'] = 'LeadController/add_lead';
$route['manage-super-admin'] = 'superAdmin/SuperAdminController/index';
$route['get-super-admins-table'] = 'superAdmin/SuperAdminController/get_super_admins_table';
$route['insert-super-admin'] = 'superAdmin/SuperAdminController/addAdmin';
$route['edit-super-admin'] = 'superAdmin/SuperAdminController/editAdmin';
$route['update-super-admin'] = 'superAdmin/SuperAdminController/updateAdmin';
$route['delete-super-admin'] = 'superAdmin/SuperAdminController/deleteAdmin';
$route['manage-agencies'] = 'superAdmin/Agency/index';
$route['get-agencies-table'] = 'superAdmin/Agency/get_agencies_table';
$route['agency-save'] = 'superAdmin/Agency/save';
$route['agency-details'] = 'superAdmin/Agency/getDetails';
$route['agency-delete'] = 'superAdmin/Agency/delete';
$route['manage-facebook-forms/(:any)'] = 'superAdmin/FacebookformController/manage/$1';
$route['facebook-forms-data-table'] = 'superAdmin/FacebookformController/get_forms_table';
$route['facebook-form-add'] = 'superAdmin/FacebookformController/addForm';
$route['facebook-form-details'] = 'superAdmin/FacebookformController/getFormDetails';
$route['facebook-form-update'] = 'superAdmin/FacebookformController/updateForm';
$route['facebook-form-delete'] = 'superAdmin/FacebookformController/deleteForm';
$route['agency-login'] = 'AgencyLogin/index';
$route['agency-dashboard'] = 'agency/main/index';
$route['agency-profile'] = 'agency/main/profile';
$route['agency-sign-out'] = 'agency/login/logout';
$route['update-agency-profile'] = 'agency/main/update_profile';
$route['view-agency-leads'] = 'agency/Leads/index';
$route['add-lead-agency'] = 'agency/Leads/add_lead';
$route['insert-lead-agency'] = 'agency/Leads/insert_lead';
$route['add-lead-agency'] = 'agency/Leads/add_lead';
$route['reports-agency'] = 'agency/Reports/index';


$route['reports-lead-by-dispositions'] = 'Reports/lead_by_dispostion';
$route['reports-lead-by-source'] = 'Reports/lead_by_source';
$route['reports-lead-by-departments'] = 'Reports/lead_by_department';
$route['reports-property-materialized'] = 'Reports/hotel_department_status_report';
$route['reports-summary'] = 'Reports/summary_report';


$route['admin-reports-lead-by-dispositions'] = 'hotelAdmin/Reports/lead_by_dispostion';
$route['admin-reports-lead-by-source'] = 'hotelAdmin/Reports/lead_by_source';
$route['admin-reports-lead-by-departments'] = 'hotelAdmin/Reports/lead_by_department';
$route['admin-reports-property-materialized'] = 'hotelAdmin/Reports/hotel_department_status_report';
$route['admin-reports-summary'] = 'hotelAdmin/Reports/summary_report';






// agent dashboard routes


$route['agent-login'] = 'agent/login/index';
$route['select-hotel'] = 'agent/login/select_hotel';
$route['select-hotel-regional-manager'] = 'RegionalManager/select_hotel';

$route['agent-dashboard'] = 'agent/main/index';
$route['agent-profile'] = 'agent/main/profile';
$route['agent-sign-out'] = 'agent/login/logout';
$route['update-agent-profile'] = 'agent/main/update_profile';
$route['view-agents-leads'] = 'agent/Leads/index';
$route['add-lead-agents'] = 'agent/Leads/add_lead';
$route['insert-lead-agents'] = 'agent/Leads/insert_lead';
$route['add-lead-agents'] = 'agent/Leads/add_lead';
$route['download-sample-file'] = 'LeadController/download_sample_file';
$route['import-leads'] = 'LeadController/import_leads_data';
$route['view-lead-details/(:any)'] = 'LeadController/view_lead_details/$1';
$route['view-lead-details-admin/(:any)'] = 'LeadController/view_lead_details_admin/$1';
$route['view-lead-details-agent/(:any)'] = 'LeadController/view_lead_details_agent/$1';



$route['customer-lead-history/(:any)'] = 'LeadController/customer_lead_history/$1';
$route['customer-lead-history-admin/(:any)'] = 'hotelAdmin/Leads/customer_lead_history_hotel/$1';
$route['customer-lead-history-agent/(:any)'] = 'agent/Leads/customer_lead_history_agent/$1';
$route['get-lead-by-form/(:any)/(:any)'] = 'LeadController/get_lead_by_form/$1/$2';

$route['guest-contact-book'] = 'superAdmin/Guestcontactbook/index';
$route['activity-logs'] = 'superAdmin/ActivityLogs/index';
$route['activity-logs-data-table'] = 'superAdmin/ActivityLogs/get_activity_logs_table';
$route['guest-contact-book-admin'] = 'hotelAdmin/Guestcontactbook/index';
$route['reports-admin'] = 'hotelAdmin/Reports/index';
$route['reports-agent'] = 'agent/Reports/index';

$route['regional-manager-login'] = 'RegionalManager/index';

$route['manage-company-group'] = 'superAdmin/CompanyGroup/manage';
$route['get-company-groups-table'] = 'superAdmin/CompanyGroup/get_company_groups_table';
$route['company-group-add'] = 'superAdmin/CompanyGroup/add';
$route['company-group-update'] = 'superAdmin/CompanyGroup/update';
$route['company-group-delete'] = 'superAdmin/CompanyGroup/delete';
$route['company-group-details'] = 'superAdmin/CompanyGroup/getDetails';
$route['manage-travel-modes'] = 'superAdmin/TravelModes/manage';
$route['get-travel-modes-table'] = 'superAdmin/TravelModes/get_travel_modes_table';
$route['travel-modes-add'] = 'superAdmin/TravelModes/add';
$route['travel-modes-update'] = 'superAdmin/TravelModes/update';
$route['travel-modes-delete'] = 'superAdmin/TravelModes/delete';
$route['travel-modes-details'] = 'superAdmin/TravelModes/getDetails';
$route['manage-team-group'] = 'superAdmin/TeamGroup/manage';
$route['get-team-groups-table'] = 'superAdmin/TeamGroup/get_team_groups_table';
$route['team-group-add'] = 'superAdmin/TeamGroup/add';
$route['team-group-update'] = 'superAdmin/TeamGroup/update';
$route['team-group-delete'] = 'superAdmin/TeamGroup/delete';
$route['team-group-details'] = 'superAdmin/TeamGroup/getDetails';
$route['manage-companies'] = 'superAdmin/Company/manage';
$route['get-companies-table'] = 'superAdmin/Company/get_companies_table';
$route['company-save'] = 'superAdmin/Company/save';
$route['company-details'] = 'superAdmin/Company/getDetails';
$route['company-delete'] = 'superAdmin/Company/delete';
$route['manage-designations'] = 'superAdmin/Designation/manage';
$route['get-designations-table'] = 'superAdmin/Designation/get_designations_table';
$route['designation-add'] = 'superAdmin/Designation/add';
$route['designation-update'] = 'superAdmin/Designation/update';
$route['designation-delete'] = 'superAdmin/Designation/delete';
$route['designation-details'] = 'superAdmin/Designation/getDetails';
$route['manage-sales-users'] = 'superAdmin/Designation/manage';
$route['manage-area-users'] = 'superAdmin/Areas/manage';
$route['get-area-users-table'] = 'superAdmin/Areas/get_areas_table';
$route['area-users-add'] = 'superAdmin/Areas/add';
$route['area-users-update'] = 'superAdmin/Areas/update';
$route['area-users-delete'] = 'superAdmin/Areas/delete';
$route['area-users-details'] = 'superAdmin/Areas/getDetails';
$route['manage-hotel-restaurants'] = 'superAdmin/Restaurants/manage';
$route['get-restaurants-table'] = 'superAdmin/Restaurants/get_restaurants_table';
$route['manage-slot-types'] = 'superAdmin/SlotType/manage';
$route['get-slot-types-table'] = 'superAdmin/SlotType/get_slot_types_table';
$route['slot-types-add'] = 'superAdmin/SlotType/add';
$route['slot-types-update'] = 'superAdmin/SlotType/update';
$route['slot-types-delete'] = 'superAdmin/SlotType/delete';
$route['slot-types-details'] = 'superAdmin/SlotType/getDetails';



$route['manage-sales-users'] = 'superAdmin/SalesUsers/index';
$route['insert-sales-users'] = 'superAdmin/SalesUsers/insert';
$route['delete-sales-users'] = 'superAdmin/SalesUsers/delete';
$route['edit-sales-users'] = 'superAdmin/SalesUsers/edit';
$route['update-sales-users'] = 'superAdmin/SalesUsers/update';
$route['get-sales-users-table'] = 'superAdmin/SalesUsers/get_sales_users_table';

$route['manage-company-contacts'] = 'superAdmin/CompanyContacts/index';
$route['get-company-contacts-table'] = 'superAdmin/CompanyContacts/get_contacts_table';
$route['company-contact-save'] = 'superAdmin/CompanyContacts/save';
$route['company-contact-details'] = 'superAdmin/CompanyContacts/getDetails';
$route['company-contact-delete'] = 'superAdmin/CompanyContacts/delete';



$route['sales-visits-history'] = 'superAdmin/SalesVisits/index';
$route['add-sales-visit'] = 'superAdmin/SalesVisits/add';
$route['insert-sales-visit'] = 'superAdmin/SalesVisits/insert';
$route['delete-sales-visit'] = 'superAdmin/SalesVisits/delete';
$route['edit-sales-visit/(:any)'] = 'superAdmin/SalesVisits/edit/$1';
$route['update-sales-visit'] = 'superAdmin/SalesVisits/update';

$route['weekly-planner']            = 'superAdmin/WeeklyPlanner/manage';
$route['weekly-planner-fetch']      = 'superAdmin/WeeklyPlanner/fetch';

$route['add-weekly-planner']        = 'superAdmin/WeeklyPlanner/add';
$route['insert-weekly-planner']     = 'superAdmin/WeeklyPlanner/add';

$route['edit-weekly-planner']       = 'superAdmin/WeeklyPlanner/getDetails';
$route['update-weekly-planner']     = 'superAdmin/WeeklyPlanner/update';

$route['delete-weekly-planner']     = 'superAdmin/WeeklyPlanner/delete';

$route['whatsapp-template-management']     = 'superAdmin/Whatsapp_templates/manage';
$route['get-whatsapp-templates-table']     = 'superAdmin/Whatsapp_templates/get_whatsapp_templates_table';
$route['whatsapp-templates-add']     = 'superAdmin/Whatsapp_templates/add';
$route['whatsapp-templates-update']     = 'superAdmin/Whatsapp_templates/update';
$route['whatsapp-templates-delete']     = 'superAdmin/Whatsapp_templates/delete';
$route['whatsapp-templates-details']     = 'superAdmin/Whatsapp_templates/getDetails';
$route['software-settings']     = 'superAdmin/Software_settings/index';
$route['software-settings/upload-branding'] = 'superAdmin/Software_settings/upload_branding';
$route['software-settings/update-basic'] = 'superAdmin/Software_settings/update_basic';
$route['software-settings/update-smtp'] = 'superAdmin/Software_settings/update_smtp';
$route['software-settings/update-airtel'] = 'superAdmin/Software_settings/update_airtel';

$route['manage-hotel-admins']     = 'superAdmin/HotelAdmins/index';
$route['insert-hotel-admin']     = 'superAdmin/HotelAdmins/insert';
$route['delete-hotel-admin']     = 'superAdmin/HotelAdmins/delete';
$route['edit-hotel-admin']     = 'superAdmin/HotelAdmins/edit';
$route['update-hotel-admin']     = 'superAdmin/HotelAdmins/update';
$route['update-hotel-admin-status']     = 'superAdmin/HotelAdmins/update_status';
$route['get-hotel-admins-table']     = 'superAdmin/HotelAdmins/get_hotel_admins_table';


// Banquet Management Routes

$route['manage-banquet'] = 'superAdmin/BanquetManagment/index';

$route['insert-banquet'] = 'superAdmin/BanquetManagment/insert';

$route['edit-banquet'] = 'superAdmin/BanquetManagment/edit';

$route['update-banquet'] = 'superAdmin/BanquetManagment/update';

$route['delete-banquet'] = 'superAdmin/BanquetManagment/delete';
$route['get-banquets-table'] = 'superAdmin/BanquetManagment/get_banquets_table';

$route['manage-mealplans'] = 'superAdmin/MealPlan/manage';
$route['get-mealplans-table'] = 'superAdmin/MealPlan/get_mealplans_table';
$route['mealplans-add'] = 'superAdmin/MealPlan/add';
$route['mealplans-update'] = 'superAdmin/MealPlan/update';
$route['mealplans-delete'] = 'superAdmin/MealPlan/delete';
$route['mealplans-details'] = 'superAdmin/MealPlan/getDetails';


$route['promotional-offers'] = 'superAdmin/PromotionalOffers/manage';
$route['get-promotional-offers-table'] = 'superAdmin/PromotionalOffers/get_promotional_offers_table';
$route['promotional-offers-add'] = 'superAdmin/PromotionalOffers/add';
$route['promotional-offers-update'] = 'superAdmin/PromotionalOffers/update';
$route['promotional-offers-delete'] = 'superAdmin/PromotionalOffers/delete';
$route['promotional-offers-details'] = 'superAdmin/PromotionalOffers/getDetails';

$route['lead/get-restaurants'] = 'LeadController/getRestaurantsByHotel';
$route['lead/get-banquets'] = 'LeadController/getBanquetsByHotel';
$route['lead/get-meal-plans'] = 'LeadController/getMealPlans';
$route['lead/get-promotional-offers'] = 'LeadController/getPromotionalOffersByDepartment';
$route['lead/get-room-types'] = 'LeadController/getRoomTypesByHotel';
$route['lead/get-slot-types'] = 'LeadController/getSlotTypes';
$route['manage-table-categories'] = 'superAdmin/Table_categories/manage';
$route['get-table-categories-table'] = 'superAdmin/Table_categories/get_table_categories_table';
$route['table-categories-add'] = 'superAdmin/Table_categories/add';
$route['table-categories-update'] = 'superAdmin/Table_categories/update';
$route['table-categories-delete'] = 'superAdmin/Table_categories/delete';
$route['table-categories-details'] = 'superAdmin/Table_categories/getDetails';
$route['manage-tables'] = 'superAdmin/Tables/manage';
$route['get-tables-table'] = 'superAdmin/Tables/get_tables_table';
$route['tables-add'] = 'superAdmin/Tables/add';
$route['tables-update'] = 'superAdmin/Tables/update';
$route['tables-delete'] = 'superAdmin/Tables/delete';
$route['tables-details'] = 'superAdmin/Tables/getDetails';
$route['manage-time-slots'] = 'superAdmin/Time_slots/manage';
$route['get-time-slots-table'] = 'superAdmin/Time_slots/get_time_slots_table';
$route['time-slots-add'] = 'superAdmin/Time_slots/add';
$route['time-slots-update'] = 'superAdmin/Time_slots/update';
$route['time-slots-delete'] = 'superAdmin/Time_slots/delete';
$route['time-slots-details'] = 'superAdmin/Time_slots/getDetails';
$route['lead/get-time-slots'] = 'LeadController/getTimeSlots';
$route['lead/get-table-categories'] = 'LeadController/getTableCategories';

$route['lead/get-tables'] = 'LeadController/getTables';

// Property APIs
$route['api/property-list'] = 'API/property_list';

// Department APIs
$route['api/department-list'] = 'API/department_list';

// Restaurant APIs
$route['api/restaurant-list'] = 'API/restaurant_list_by_hotel';

// Time Slot APIs
$route['api/time-slots'] = 'API/time_slots';

$route['api/save-lead'] = 'API/save_lead';


$route['followups'] = 'LeadController/followups';
$route['view-followups-admin'] = 'hotelAdmin/Leads/followups';
$route['view-followups-agent'] = 'agent/Leads/followups';





//$route['superAdmin/tables/manage'] = 'Tables/manage';

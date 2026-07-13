<?php 

$config = array(
    'username' => array
    (
                        array(
                                'field' => 'username',
                                'label' => 'User Name',
                                'rules' => 'required|valid_email|is_unique[admin.admin_email]'
                        ),array(
                        'field'=>'password',
                        'label'=>'Password',
                        'rules'=>'required'
                        )

    ),
       'footer' => array
    (
        array(
                'field' => 'facebook',
                'label' => 'Facebook',
                'rules' => 'required'
        ),
          array(
                'field' => 'twitter',
                'label' => 'Twitter',
                'rules' => 'required'
        ),
            array(
                'field' => 'instagram',
                'label' => 'Instagram',
                'rules' => 'required'
        ),
              array(
                'field' => 'email',
                'label' => 'Email',
                'rules' => 'required'
        ),
    ),
    'shipping_valueform' => array
    (
        array(
                'field' => 'shipping_value',
                'label' => 'Shipping Amount',
                'rules' => 'required'
        )
    ),
 
     'refund_days' => array
    (
        array(
                'field' => 'refund_day_limit',
                'label' => 'Days',
                'rules' => 'required'
        )
    ),
         'tags' => array
    (
        array(
                'field' => 'name',
                'label' => 'Name (en)',
                'rules' => 'required|is_unique[tags.name]'
        ),
        array(
                'field' => 'ar_name',
                'label' => 'Name(ar)',
                'rules' => 'required'
        )
    ),

     'profile' => array
    (
        array(
                'field' => 'cust_fname',
                'label' => 'First Name',
                'rules' => 'required'
        ),
        array(
                'field' => 'cust_lname',
                'label' => 'Last Name',
                'rules' => 'required'
        ),
         array(
                'field' => 'cust_email',
                'label' => 'Email',
                'rules' => 'required|valid_email'
        ),
        // array('field' => 'cpassword',
        // 'label' => 'Password',
        // 'rules' => 'matches[password]',
        //   "errors" => [
        //     'matches' => $this->lang->line('password_not_match'),
        // ],
        // ),
        // array('field' => 'cpassword',
        //     'label' => 'Confirm Password',
        //     'rules' => 'min_length[8]|matches[password]'
        // ),
    ),
    'tags1' => array
    (
        array(
                'field' => 'name',
                'label' => 'Name (en)',
                'rules' => 'required'
        ),
        array(
                'field' => 'ar_name',
                'label' => 'Name(ar)',
                'rules' => 'required'
        )
    ),
    
    'driver'=> array
    (
         array('field'=>'email',
            'label'=>'Email ID',
            'rules'=>'required|valid_email|is_unique[driver.email]'),
         array('field'=>'name',
            'label'=>'Name',
            'rules'=>'required'),
         array(
            'field'=>'password',
            'label'=>'Password',
            'rules'=>'required|min_length[8]'
            ),
          array(
            'field'=>'phone',
            'label'=>'phone',
            'rules'=>'required|is_unique[driver.mobile]'
            )

    ),
    'driver1'=> array
    (
         array('field'=>'email',
            'label'=>'Email ID',
            'rules'=>'required'),
         array('field'=>'name',
            'label'=>'Name',
            'rules'=>'required')
      

    ),
    'size'=>array
    ( 
        array(
            'field' => 'size_name',
            'label' => 'Size Name(en)',
            'rules' => 'required|is_unique[sizes.size_name]'
        ),
        // array(
        //     'field' => 'ar_size_name',
        //     'label' => 'Size Name(ar)',
        //     'rules' => 'required'
        // )
    ),
    'size1'=>array
    ( 
        array(
            'field' => 'size_name',
            'label' => 'Size Name(en)',
            'rules' => 'required|is_unique[sizes.size_name]'
        ),
         array(
            'field' => 'ar_size_name',
            'label' => 'Size Name(ar)',
            'rules' => 'required|is_unique[sizes.ar_size_name]'
        )
    ),
    'colors' => array(
            array(
                    'field' => 'color_name',
                    'label' => 'Color Name(en)',
                    'rules' => 'required'
            ),
            array(
                    'field' => 'ar_color_name',
                    'label' => 'Color Name(ar)',
                    'rules' => 'required'
            ),
            array(
                    'field' => 'color_value',
                    'label' => 'Color Value',
                    'rules' => 'required'
            ),
            array(
                    'field' => 'color_cat',
                    'label' => 'Color Product Category',
                    'rules' => 'required'
            ),
            array(
                    'field' => 'color_subcat',
                    'label' => 'Color Product Sub Category',
                    'rules' => 'required'
            )
    ),
    'colors1' => array(
            array(
                    'field' => 'color_name',
                    'label' => 'Color Name(en)',
                    'rules' => 'required'
            ),
            array(
                    'field' => 'ar_color_name',
                    'label' => 'Color Name(ar)',
                    'rules' => 'required'
            ),
            array(
                    'field' => 'color_value',
                    'label' => 'Color Value',
                    'rules' => 'required'
            )
    ),
    'brands' => array
    (
        array(
                'field' => 'brand_name',
                'label' => 'Brand Name (en)',
                'rules' => 'required|is_unique[brands.brand_name]'
        ),
        // array(
        //         'field' => 'ar_brand_name',
        //         'label' => 'Brand Name(ar)',
        //         'rules' => 'required'
        // )
    ),
    'brands1' => array
    (
        array(
                'field' => 'brand_name',
                'label' => 'Brand Name (en)',
                'rules' => 'required|is_unique[brands.brand_name]'
        ),
        array(
                'field' => 'ar_brand_name',
                'label' => 'Brand Name(ar)',
                'rules' => 'required|is_unique[brands.ar_brand_name]'
        )
    ),
     'update_brand' => array
    (
        array(
                'field' => 'brand_name',
                'label' => 'Brand Name (en)',
                'rules' => 'required'
        ),
        array(
                'field' => 'ar_brand_name',
                'label' => 'Brand Name(ar)',
                'rules' => 'required'
        )
    ),
    'pro_txt' => array
    (
        array(
                'field' => 'msg',
                'label' => 'Title (en)',
                'rules' => 'required'
        ),
        array(
                'field' => 'ar_msg',
                'label' => 'Title(ar)',
                'rules' => 'required'
        )
    ),
    'slider'=>array(
                    array(
                        'field' => 'enname',
                        'label' => 'Slider Name (en)',
                        'rules' => 'required|is_unique[slider.slide_name]'
                    ),
                    // array(
                    //     'field' => 'arname',
                    //     'label' => 'Slider Name (ar)',
                    //     'rules' => 'required|is_unique[slider.slide_name_ar]'
                    // ),                    
                    // array(
                    //     'field' => 'arlink',
                    //     'label' => 'Slider Link (ar)',
                    //     'rules' => 'required'
                    // ),                    
                    // array(
                    //     'field' => 'enlink',
                    //     'label' => 'Slider Link (en)',
                    //     'rules' => 'required'
                    // )


                     ),
    'updateslider'=>array(
                    array(
                        'field' => 'enname',
                        'label' => 'Slider Name (en)',
                        'rules' => 'required'
                    ),
                    // array(
                    //     'field' => 'arname',
                    //     'label' => 'Slider Name (ar)',
                    //     'rules' => 'required'
                    // ),                    
                    // array(
                    //     'field' => 'arlink',
                    //     'label' => 'Slider Link (ar)',
                    //     'rules' => 'required'
                    // ),                    
                    // array(
                    //     'field' => 'enlink',
                    //     'label' => 'Slider Link (en)',
                    //     'rules' => 'required'
                    // )


                     ),
    'category'=>array(
                    array(
                        'field' => 'enname',
                        'label' => 'Category Name (en)',
                        'rules' => 'required|is_unique[categories.category_name]'
                    ),
                    array(
                        'field' => 'arname',
                        'label' => 'Category Name (ar)',
                        'rules' => 'required|is_unique[categories.ar_category_name]'
                    )

                     ),
    'country'=>array(
                    array(
                        'field' => 'enname',
                        'label' => 'Country Name (en)',
                        'rules' => 'required|is_unique[countries.country_name]'
                    ),
                    array(
                        'field' => 'arname',
                        'label' => 'Country Name (ar)',
                        'rules' => 'required|is_unique[countries.ar_country_name]'
                    )

                     ),
    'update_country'=>array(
                    array(
                        'field' => 'enname',
                        'label' => 'Country Name (en)',
                        'rules' => 'required'
                    ),
                    array(
                        'field' => 'ar_country_name',
                        'label' => 'Country Name (ar)',
                        'rules' => 'required'
                    )

                     ),
    'region'=>array(
                    array(
                        'field' => 'region_name',
                        'label' => 'Region Name (en)',
                        'rules' => 'required'
                    ),
                    array(
                        'field' => 'ar_region_name',
                        'label' => 'Region Name (ar)',
                        'rules' => 'required'
                    )
         

                     ),
        'update_region'=>array(
                    array(
                        'field' => 'region_name',
                        'label' => 'Region Name (en)',
                        'rules' => 'required'
                    ),
                    array(
                        'field' => 'ar_region_name',
                        'label' => 'Region Name (ar)',
                        'rules' => 'required'
                    )
         

                     ),                 
    'updatecategory'=>array(
                    array(
                        'field' => 'enname',
                        'label' => 'Category Name (en)',
                        'rules' => 'required'
                    ),
                    array(
                        'field' => 'ar_category_name',
                        'label' => 'Category Name (ar)',
                        'rules' => 'required'
                    )
            
                     ),
     'subcategory'=>array(
                    array(
                        'field' => 'subcategory_name',
                        'label' => 'Sub-Category Name (en)',
                        'rules' => 'required|is_unique[subcategories.subcategory_name]'
                    ),
                    array(
                        'field' => 'ar_subcategory_name',
                        'label' => 'Sub-Category Name (ar)',
                        'rules' => 'required|is_unique[subcategories.ar_subcategory_name]'
                    )
         

                     ),
    'pages' => array(
        
        array(
            'field'=>'page_name',
            'label'=>'Page Name(en)',
            'rules'=>'required|is_unique[pages.page_name]'        
            
            ),
        array(
            'field'=>'page_disc',
            'label'=>'Page Description(en)',
            'rules'=>'required'
            ),
        //  array(
        //     'field'=>'page_name_ar',
        //     'label'=>'Page Name(ar)',
        //     'rules'=>'required'
        //     ),
        // array(
        //     'field'=>'page_disc_ar',
        //     'label'=>'Page Description(ar)',
        //     'rules'=>'required'
        //     )
        
        
        
        ),
    'pages1' => array(
        
        array(
            'field'=>'page_name',
            'label'=>'Page Name(en)',
            'rules'=>'required'        
            
            ),
        array(
            'field'=>'page_disc',
            'label'=>'Page Description(en)',
            'rules'=>'required'
            ),
        //  array(
        //     'field'=>'page_name_ar',
        //     'label'=>'Page Name(ar)',
        //     'rules'=>'required'
        //     ),
        // array(
        //     'field'=>'page_disc_ar',
        //     'label'=>'Page Description(ar)',
        //     'rules'=>'required'
        //     )
        
        
        
        ),
        'faq' => array(
        
        array(
            'field'=>'faq_title',
            'label'=>'Faq Title (en)',
            'rules'=>'required'        
            
            ),
        array(
            'field'=>'ar_faq_title',
            'label'=>'Faq Title (ar)',
            'rules'=>'required'        
            
            ),
         array(
            'field'=>'faq_desc',
            'label'=>'Faq Description(en)',
            'rules'=>'required'
            ),
        array(
            'field'=>'ar_faq_desc',
            'label'=>'Faq Description(ar)',
            'rules'=>'required'
            )
        
        
        
        ),
    'coupan' => array(
            array(
                    'field' => 'cup_name',
                    'label' => 'Coupon Name',
                    'rules' => 'required|is_unique[coupons.cup_name]'
            ),
            array(
                    'field' => 'cup_value',
                    'label' => 'Coupon Value',
                    'rules' => 'required'
            ),
            //array(
            //        'field' => 'cup_qty',
            //        'label' => 'Coupon Quantity',
            //        'rules' => 'required'
            //),
            array(
                    'field' => 'cup_type',
                    'label' => 'Coupon Type',
                    'rules' => 'required'
            )
           
    ),
    'coupan1' => array(
            array(
                    'field' => 'cup_name',
                    'label' => 'Coupon Name',
                    'rules' => 'required|is_unique[coupons.cup_name]'
            ),
            array(
                    'field' => 'cup_value',
                    'label' => 'Coupon Value',
                    'rules' => 'required'
            ),
            // array(
            //         'field' => 'cup_qty',
            //         'label' => 'Coupon Quantity',
            //         'rules' => 'required'
            // ),
            array(
                    'field' => 'cup_type',
                    'label' => 'Coupon Type',
                    'rules' => 'required'
            )
           
    ),
    'update_coupon' => array(
            array(
                    'field' => 'cup_name',
                    'label' => 'Coupon Name',
                    'rules' => 'required'
            ),
            array(
                    'field' => 'cup_value',
                    'label' => 'Coupon Value',
                    'rules' => 'required'
            ),
            // array(
            //         'field' => 'cup_qty',
            //         'label' => 'Coupon Quantity',
            //         'rules' => 'required'
            // ),
            array(
                    'field' => 'cup_type',
                    'label' => 'Coupon Type',
                    'rules' => 'required'
            )
           
    ),
    'update_banner'=>array
        (
            array(
                    'field' => 'banner_title',
                    'label' => 'Title',
                    'rules' => 'required'
                )
                        
           
        ),
    'update_fcat'=>array
        (
            array(
                    'field' => 'title',
                    'label' => 'Title',
                    'rules' => 'required'
                ),
            array(
                    'field' => 'description',
                    'label' => 'Text',
                    'rules' => 'required'
                )
         ),
        'products' => array(
            array(
                    'field' => 'product_title',
                    'label' => 'Product Name',
                    'rules' => 'required|is_unique[products.product_title]'
            ),
            array(
                    'field' => 'product_cat',
                    'label' => 'Product Category',
                    'rules' => 'required'
            ),
             array(
                    'field' => 'product_brand',
                    'label' => 'Product Brand',
                    'rules' => 'required'
            ),
            array(
                    'field' => 'product_sdisc',
                    'label' => 'Short Description',
                    'rules' => 'required'
            ),
        
       
            array(
                    'field' => 'product_cat',
                    'label' => 'product_cat',
                    'rules' => 'required|callback_check_default'
            )
            ,
            array(
                    'field' => 'product_brand',
                    'label' => 'Brand',
                    'rules' => 'required|callback_check_default'
            ),
            array(
                    'field' => 'product_subcat',
                    'label' => 'sub category',
                    'rules' => 'required|callback_check_default'
            )

            
    ),
 'products1' => array(
            array(
                    'field' => 'product_title',
                    'label' => 'Product Name',
                    'rules' => 'required'
            ),
            array(
                    'field' => 'product_cat',
                    'label' => 'Product Category',
                    'rules' => 'required'
            ),
             array(
                    'field' => 'product_brand',
                    'label' => 'Product Brand',
                    'rules' => 'required'
            ),
            array(
                    'field' => 'product_sdisc',
                    'label' => 'Short Description',
                    'rules' => 'required'
            ),
            array(
                    'field' => 'product_cat',
                    'label' => 'product_cat',
                    'rules' => 'required|callback_check_default'
            )
            ,
            array(
                    'field' => 'product_brand',
                    'label' => 'Brand',
                    'rules' => 'required|callback_check_default'
            ),
            array(
                    'field' => 'product_subcat',
                    'label' => 'sub categorie',
                    'rules' => 'required|callback_check_default'
            )
    ),
 'image' => array(
     array('field' => 'prid',
            'label' => 'id',
            'rules' => 'required'
         )

 ),

  'vendor_update' => array(
        
        array(
            'field'=>'fname',
            'label'=>'First Name',
            'rules'=>'required'        
            ),
      
            array(
            'field'=>'phone',
            'label'=>'Phone',
            'rules'=>'required|max_length[8]'
            ),
            //  array(
            // 'field'=>'email',
            // 'label'=>'Email ID',
            // 'rules'=>'required|valid_email'
            // ),        
        ),

    'vendors' => array(
        
        array(
            'field'=>'fname',
            'label'=>'First Name',
            'rules'=>'required'        
            ),

             array(
            'field'=>'email',
            'label'=>'Email ID',
            'rules'=>'required|valid_email|is_unique[admin.admin_email]'
            ),
             array(
            'field'=>'phone',
            'label'=>'Phone',
            'rules'=>'required|max_length[8]'
            ),
            
             array(
            'field'=>'password',
            'label'=>'Password',
            'rules'=>'required'
            )
        
        ),

      'adminuser' => array(
            array(
                    'field' => 'admin_fullname',
                    'label' => 'Full Name',
                    'rules' => 'required'
            ),
            array(
                    'field' => 'admin_pass',
                    'label' => 'Password',
                    'rules' => 'required'
            ),
            array(
                    'field' => 'admin_email',
                    'label' => 'Email',
                    'rules' => 'required|is_unique[admin.admin_email]'
            )
    ),

 'adminuserupdate' => array(
            array(
                    'field' => 'admin_fullname',
                    'label' => 'Full Name',
                    'rules' => 'required'
            ),
    ),


)



?>
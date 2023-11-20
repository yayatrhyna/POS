<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Lproduct
{

    //Product Details Page Load Here
    public function product_details($p_id)
    {
        $CI =& get_instance();
        $CI->load->model('website/Products_model');
        $CI->load->model('website/Homes');
        $CI->load->model('web_settings');
        $CI->load->model('Soft_settings');
        $CI->load->model('Blocks');
        $CI->load->model('Themes');
        $theme = $CI->Themes->get_theme();

        $pro_category_list = $CI->Homes->category_list();
        $parent_category_list = $CI->Homes->parent_category_list();
        $best_sales = $CI->Homes->best_sales();
        $footer_block = $CI->Homes->footer_block();
        $slider_list = $CI->web_settings->slider_list();
        $block_list = $CI->Blocks->block_list();
        $product_info = $CI->Products_model->product_info($p_id);
        $stock_report_single_item = $CI->Products_model->stock_report_single_item_by_store($p_id);
        $best_sales_category = $CI->Products_model->best_sales_category($p_id);
        $product_gallery_img = $CI->Products_model->product_gallery_img($p_id);
        $category_id = $product_info->category_id;
        $product_id = $product_info->product_id;
//        $review_list 			= $CI->Products_model->review_list($p_id);
        $review_list 			= $CI->Products_model->review_list_with_customer($p_id);
        $related_product = $CI->Products_model->related_product($category_id, $product_id);
        $currency_details = $CI->Soft_settings->retrieve_currency_info();
        $Soft_settings = $CI->Soft_settings->retrieve_setting_editdata();
        $languages = $CI->Homes->languages();
        $currency_info = $CI->Homes->currency_info();
        $selected_currency_info = $CI->Homes->selected_currency_info();
        $select_details_adds = $CI->Homes->select_details_adds();
        $data = array(
            'title' => display('product_details'),
            'category_list' => $parent_category_list,
            'slider_list' => $slider_list,
            'block_list' => $block_list,
            'best_sales' => $best_sales,
            'footer_block' => $footer_block,
            'product_name' => $product_info->product_name,
            'brand_name' => $product_info->brand_name,
            'brand_id' => $product_info->brand_id,
            'product_id' => $product_info->product_id,
            'product_details' => $product_info->product_details,
            'product_model' => $product_info->product_model,
            'type' => $product_info->type,
            'price' => $product_info->price,
            'onsale' => $product_info->onsale,
            'onsale_price' => $product_info->onsale_price,
            'image_thumb' => $product_info->image_thumb,
            'variant' => $product_info->variants,
            'default_variant' => $product_info->default_variant,
            'category_name' => $product_info->category_name,
            'video' => $product_info->video,
            'category_id' => $category_id,
            'stok' => $stock_report_single_item,
            'related_product' => $related_product,
            'image_large_details' => $product_info->image_large_details,
            'product_gallery_img' => $product_gallery_img,
            'review' => $product_info->review,
            'description' => $product_info->description,
            'tag' => $product_info->tag,
            'specification' => $product_info->specification,
            'pro_category_list' => $pro_category_list,
            'Soft_settings' => $Soft_settings,
            'languages' => $languages,
            'currency_info' => $currency_info,
            'selected_cur_id' => (($selected_currency_info->currency_id) ? $selected_currency_info->currency_id : ""),
            'currency' => $currency_details[0]['currency_icon'],
            'position' => $currency_details[0]['currency_position'],
            'best_sales_category' => $best_sales_category,
            'review_list' 			=> $review_list,
            'select_details_adds' => $select_details_adds
        );
        $HomeForm = $CI->parser->parse('website/themes/' . $theme . '/details', $data, true);
        return $HomeForm;
    }


    //Brand product
    public function brand_product($brand_id=null,$price_range=null,$size=null,$sort=null,$rate=null)
    {
        $CI =& get_instance();
        $CI->load->model('website/Products_model');
        $CI->load->model('website/Categories');
        $CI->load->model('website/Homes');
        $CI->load->model('web_settings');
        $CI->load->model('Soft_settings');
        $CI->load->model('Blocks');
        $CI->load->model('Companies');
        $CI->load->model('Variants');
        $CI->load->model('Themes');
        $theme = $CI->Themes->get_theme();



        $brand_product 	  = $CI->Products_model->retrieve_brand_product($brand_id,$price_range,$size,$sort,$rate);

        //        $top_category_list = $CI->Homes->top_category_list();
        $brand_info 	  = $CI->Products_model->select_brand_info($brand_id);
        $categoryList 	  = $CI->Homes->parent_category_list();
        $pro_category_list= $CI->Homes->category_list();
        $best_sales 	  = $CI->Homes->best_sales();
        $footer_block 	  = $CI->Homes->footer_block();
        $block_list 	  = $CI->Blocks->block_list();
        $currency_details = $CI->Soft_settings->retrieve_currency_info();
        $soft_settings 	  = $CI->Soft_settings->retrieve_setting_editdata();
        $web_settings 	  = $CI->web_settings->retrieve_setting_editdata();
        $languages 		  = $CI->Homes->languages();
        $currency_info 	  = $CI->Homes->currency_info();
        $selected_currency_info = $CI->Homes->selected_currency_info();
        $company_info  	  = $CI->Companies->company_list();
        $selected_default_currency_info = $CI->Homes->selected_default_currency_info();
        $variant_list  	  = $CI->Variants->variant_list();

        $max_value   	 = $CI->Products_model->get_max_value_of_pro($brand_id);
        $min_value   	 = $CI->Products_model->get_min_value_of_pro($brand_id);
//dd($max_value);
        if ($max_value->price == $min_value->price) {
            $max = $max_value->price;
            $min = 0;
        }else{
            $max = $max_value->price;
            $min = $min_value->price;
        }

        $from_price = 0;
        $to_price 	= 0;
        if (!(empty($price_range))) {
            $ex = explode("-", $price_range);
            $from_price = $ex[0];
            $to_price   = $ex[1];
        }

        $data = array(
            'title' 		=> display('brand_product'),
            'brand_product' => $brand_product,
//            'top_category_list' => $top_category_list,
            'brand_id' 		=> $brand_id,
            'brand_name' 	=> $brand_info->brand_name,
            'pro_category_list' => $pro_category_list,
            'category_list' => $categoryList,
            'block_list' 	=> $block_list,
            'best_sales' 	=> $best_sales,
            'footer_block' 	=> $footer_block,
            'languages' 	=> $languages,
            'currency_info' => $currency_info,
            'selected_cur_id' => (($selected_currency_info->currency_id)?$selected_currency_info->currency_id:""),
            'selected_currency_icon'  => $selected_currency_info->currency_icon,
            'selected_currency_name'  => $selected_currency_info->currency_name,
            'web_settings'  => $web_settings,
            'soft_settings' => $soft_settings,
            'logo' 			=> $web_settings[0]['logo'],
            'favicon' 		=> $web_settings[0]['favicon'],
            'footer_text'   => $web_settings[0]['footer_text'],
            'company_name'  => $company_info[0]['company_name'],
            'email'  		=> $company_info[0]['email'],
            'address'  		=> $company_info[0]['address'],
            'mobile'  		=> $company_info[0]['mobile'],
            'website'  		=> $company_info[0]['website'],
            'currency' 		=> $currency_details[0]['currency_icon'],
            'position' 		=> $currency_details[0]['currency_position'],
            'links' 		=> '',
            'max_value' 	=> $max,
            'min_value' 	=> $min,
            'from_price' 	=> $from_price,
            'to_price' 		=> $to_price,
            'default_currency_icon'  => $selected_default_currency_info->currency_icon,
            'variant_list'  => $variant_list,
        );
        $categoryList = $CI->parser->parse('website/themes/' . $theme .'/brand_product',$data,true);
        return $categoryList;
    }
}

?>
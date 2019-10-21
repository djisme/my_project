<?php
/**
 * [get_city_info 获取地区信息(地区id组合，地区中文组合)]
 */
function get_city_info($ids){
    if(!$ids) return array('district'=>'','district_cn'=>'');
    $city_cate = F('city_search_cate','',APP_PATH . 'Data/');
    $spell['spell'] = F('city_cate_list_spell','',APP_PATH . 'Data/');
    $spell['id'] = F('city_cate_list_id','',APP_PATH . 'Data/');
    foreach(explode(',',$ids) as $val) {
        $a = array_filter(explode('_',$city_cate[$val]));
        if(!count($a)) continue;
        foreach($a as $key=>$v){
            $s[] = $spell['id'][$v]['spell'];
        }
        $district[] = implode('.',$a);
        $district_spell[] = implode('.',$s);
    }
    return array('district'=>implode(',',$district),'district_spell'=>implode(',',$district_spell));
}
?>
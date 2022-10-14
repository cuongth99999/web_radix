<?php

// Lấy link theo module
function getLinkModule($module, $id, $table=null, $filed=null) {
    $prefixUrl = getPrefixLinkService($module);

    if (empty($table)) {
        $table = $module;
    }
    if (empty($filed)) {
        $filed = 'slug';
    }
    $sql = "SELECT $filed FROM $table WHERE id=$id";

    $moduleDetail = firstRaw($sql);

    if (!empty($moduleDetail)) {
        $link = _WEB_HOST_ROOT.'/'.$prefixUrl.'/'.$moduleDetail[$filed].'-'.$id.'.html';
        return $link;
    }
    return  false;
}

function getPrefixLinkService($module='') {
    $prefixArr = [
        'services' => 'dich-vu',
        'pages' => 'thong-tin',
        'portfolios' => 'du-an',
        'blog_categoris' => 'danh-muc',
        'blog' => 'blog'
    ];

    if (!empty($prefixArr[$module])) {
        return $prefixArr[$module];
    }

    return false;
}
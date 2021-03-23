<?php


if (!function_exists('modify_url_query')) {

     function modify_url_query($url, $mod){

        $purl = parse_url($url);
        $params = [];

        if (($query_str=$purl['query']))
        {
            parse_str($query_str, $params);

            foreach($params as $name => $value)
            {
                if (isset($mod[$name]))
                {
                    $params[$name] = $mod[$name];
                    unset($mod[$name]);
                }
            }
        }

        $params = array_merge($params, $mod);
//        dd($params);
        $ret = "";

        if ($purl['scheme'])
        {
            $ret = $purl['scheme'] . "://";
        }

        if ($purl['host'])
        {
            $ret .= $purl['host'];
        }

        if ($purl['path'])
        {
            $ret .= $purl['path'];
        }

        if ($params)
        {
             http_build_query($params);
            $ret .= '?' . http_build_query($params);
        }

        return $ret;

    }


}

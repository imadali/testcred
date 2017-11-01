<?php

//Loop through the groupings to find include/javascript/sugar_grp7.min.js
foreach ($js_groupings as $key => $groupings)
{
    foreach  ($groupings as $file => $target)
    {
        if ($target == 'include/javascript/sugar_grp7.min.js')
        {
            //append the custom helper file
            $js_groupings[$key]['custom/JavaScript/my-handlebar-helpers.js'] = 'include/javascript/sugar_grp7.min.js';
        }

        break;
    }
}
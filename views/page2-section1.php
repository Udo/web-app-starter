<?php

    URL::$page_type = 'blank';

    print(microtime(true) . ' - Page 2 Section 1 loaded');

    print('<pre>Can haz ODT? ');
    print_r(ODT::check_requirements());
    print('</pre>');
<?php
echo '<html>' . "\n";
echo '<head><title>Processing Payment...</title></head>' . "\n";
echo '<body onLoad="document.forms[\'paypal_auto_form\'].submit();">' . "\n";
echo '<p>Please wait, your order is being processed and you will be redirected to the paypal website.</p>' . "\n";
echo $this->paypal_form('paypal_auto_form');
echo '</body></html>';
?>
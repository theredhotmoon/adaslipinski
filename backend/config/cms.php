<?php

return [
    /*
     * Comma-separated list of emails allowed to access the admin CMS.
     * First login with a whitelisted email auto-creates the account.
     */
    'admin_emails' => array_values(array_filter(
        array_map('trim', explode(',', env('ADMIN_EMAILS', 'admin@example.com')))
    )),
];
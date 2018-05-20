<?php

return [
    'btn-next' => 'Next',
    'database' => [
        'success' => 'Database have been created and filled in. It\'s almost done!',
        'title' => 'Database',
    ],
    'environment' => [
        'content' => 'First you need to provide basic script configuration.',
        'db' => 'Database connection',
        'db-host' => 'Host',
        'db-host-help' => 'Usually it\'s just <code>localhost</code>',
        'db-name' => 'Database name',
        'db-password' => 'Password',
        'db-user' => 'Database user',
        'db-prefix' => 'Table prefix',
        'db-prefix-help' => 'Recommended. Helps to avoid conflict other installations witinin the same database.',
        'other' => 'Other',
        'timezone' => 'Timezone',
        'title' => 'Script configuration',
    ],
    'final' => [
        'do-unlink' => 'Codice has been installed! Please remove <code>storage/app/.install-pending</code> file.',
        'success' => 'Codice has been installed!',
    ],
    'requirements' => [
        'available' => 'Available',
        'content' => 'Quick check of script requirements. If everything is fine, click the button at the bottom.',
        'directory' => 'Directory',
        'error-extensions' => 'Some of required extensions have not been found.',
        'error-directories' => 'Some of required directories are not writable.',
        'error-software' => 'Installed PHP version does not meet the requirements.',
        'extension' => 'Extension',
        'software' => 'Software',
        'status' => 'Status',
        'status-dir-error' => 'Not writable',
        'status-dir-ok' => 'Writable',
        'title' => 'Requirements',
        'unavailable' => 'Unavailable',
    ],
    'step' => 'Step',
    'title' => 'Codice installer',
    'user' => [
        'content' => 'Finally, we need to create your account in the system',
        'email' => 'E-mail address',
        'password' => 'Password',
        'password-confirmation' => 'Password (confirm)',
        'title' => 'User account',
    ],
    'welcome' => [
        'title' => 'Welcome',
        'para1' => 'Welcome to the Codice installer!',
        'para2' => 'Go through next few steps to install a script on your server.',
    ],
    // Name of the label assigned to welcome note
    'welcome-note-label' => 'Important',
];

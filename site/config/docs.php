<?php

/*
|--------------------------------------------------------------------------
| Documentation pages
|--------------------------------------------------------------------------
|
| Each entry maps a URL slug to a markdown file from the Kimi SEO
| repository root (one level above this Laravel app). The files are
| rendered on the fly — the markdown stays in the repo, nothing is
| copied into blade views.
|
*/

return [
    'pages' => [
        'getting-started' => [
            'title' => 'Getting Started',
            'path' => base_path('../docs/GETTING-STARTED.md'),
        ],
        'overview' => [
            'title' => 'Overview',
            'path' => base_path('../README.md'),
        ],
        'installation' => [
            'title' => 'Installation',
            'path' => base_path('../docs/INSTALLATION.md'),
        ],
        'commands' => [
            'title' => 'Commands',
            'path' => base_path('../docs/COMMANDS.md'),
        ],
        'architecture' => [
            'title' => 'Architecture',
            'path' => base_path('../docs/ARCHITECTURE.md'),
        ],
        'mcp-integration' => [
            'title' => 'MCP Integration',
            'path' => base_path('../docs/MCP-INTEGRATION.md'),
        ],
        'troubleshooting' => [
            'title' => 'Troubleshooting',
            'path' => base_path('../docs/TROUBLESHOOTING.md'),
        ],
        'workflow' => [
            'title' => 'Workflow',
            'path' => base_path('../docs/WORKFLOW-public-private.md'),
        ],
    ],
];

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
            'description' => 'Install Kimi SEO and run your first full SEO audit in minutes — the quick-start guide for the Kimi Code CLI SEO suite.',
        ],
        'overview' => [
            'title' => 'Overview',
            'path' => base_path('../README.md'),
            'description' => 'What Kimi SEO is: a free, open-source SEO analysis plugin for Kimi Code CLI with 25 skills, 18 subagents and 53 Python scripts.',
        ],
        'installation' => [
            'title' => 'Installation',
            'path' => base_path('../docs/INSTALLATION.md'),
            'description' => 'Install the Kimi SEO plugin for Kimi Code CLI — requirements, setup steps and API credential configuration.',
        ],
        'commands' => [
            'title' => 'Commands',
            'path' => base_path('../docs/COMMANDS.md'),
            'description' => 'Every Kimi SEO command explained: site audits, page analysis, technical SEO, schema, sitemaps, GEO, backlinks and more.',
        ],
        'architecture' => [
            'title' => 'Architecture',
            'path' => base_path('../docs/ARCHITECTURE.md'),
            'description' => 'How Kimi SEO is built: the orchestrator skill, specialist subagents, Python execution scripts and MCP extensions.',
        ],
        'mcp-integration' => [
            'title' => 'MCP Integration',
            'path' => base_path('../docs/MCP-INTEGRATION.md'),
            'description' => 'Connect Kimi SEO to MCP extensions for live data — DataForSEO, Firecrawl, Ahrefs, Bing Webmaster and more.',
        ],
        'troubleshooting' => [
            'title' => 'Troubleshooting',
            'path' => base_path('../docs/TROUBLESHOOTING.md'),
            'description' => 'Fix common Kimi SEO issues: runtime setup, missing credentials, script errors and environment problems.',
        ],
        'workflow' => [
            'title' => 'Workflow',
            'path' => base_path('../docs/WORKFLOW-public-private.md'),
            'description' => 'Public and private workflows in Kimi SEO — how the analysis pipeline fits together from crawl to action plan.',
        ],
    ],
];

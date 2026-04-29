<?php

header('Content-Type: application/xml; charset=UTF-8');

$pages = [
    [
        'loc' => url('/'),
        'lastmod' => date('c', max(
            filemtime(APP_ROOT . '/app/views/landing.php') ?: time(),
            filemtime(APP_ROOT . '/routes.php') ?: time()
        )),
        'changefreq' => 'weekly',
        'priority' => '1.0',
    ],
];

echo '<?xml version="1.0" encoding="UTF-8"?>';
?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
<?php foreach ($pages as $page): ?>
    <url>
        <loc><?= esc($page['loc']) ?></loc>
        <lastmod><?= esc($page['lastmod']) ?></lastmod>
        <changefreq><?= esc($page['changefreq']) ?></changefreq>
        <priority><?= esc($page['priority']) ?></priority>
    </url>
<?php endforeach; ?>
</urlset>

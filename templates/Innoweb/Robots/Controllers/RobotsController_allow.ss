<% if $GoogleSitemapURL %>Sitemap: {$GoogleSitemapURL}<% end_if %>
User-agent: *
Disallow: /dev/
Disallow: /admin/
Disallow: /Security/<% if $DisallowedPages %><% loop $DisallowedPages %>
Disallow: $Link<% end_loop %><% end_if %>

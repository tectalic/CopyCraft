/* global page */

const { visitAdminPage } = require('@wordpress/e2e-test-utils');

describe('Plugins Screen', () => {
  it('CopyCraft listed in active plugins', async () => {
    expect.assertions(1);

    await visitAdminPage('plugins.php', 'plugin_status=active');
    await expect(page).toElementEquals('tr[data-slug="copycraft"] .plugin-title strong', 'CopyCraft');
  });
});

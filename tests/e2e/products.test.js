/* global describe, expect, it, jestPuppeteer, page */

const { visitAdminPage } = require('@wordpress/e2e-test-utils');

describe('Product Management', () => {
  beforeEach(async () => {
    await jestPuppeteer.resetPage();
    await visitAdminPage('post-new.php', 'post_type=product');
  });
  it('Can create a new draft product', async () => {
    expect.assertions(4);

    // Enter a Product Title
    await expect(page).toFill('#title', 'Draft Product');
    // Click "Save Draft" button.
    await page.evaluate((selector) => document.querySelector(selector).click(), '#save-post');
    // Wait for page to reload.
    await page.waitForNavigation({ waitUntil: 'load' });
    // Verify new page saved.
    await expect(page).toMatchElement('#wpbody-content > div.wrap > h1', 'Edit product');
    await expect(page).toMatchElement('#title', 'Draft Product');
    await expect(page).toMatch('Product draft updated.');
  });
  it('Can create a new published product', async () => {
    expect.assertions(4);

    // Enter a Product Title
    await expect(page).toFill('#title', 'Published Product');
    // Click "Publish" button.
    await page.evaluate((selector) => document.querySelector(selector).click(), '#publish');
    // Wait for page to reload.
    await page.waitForNavigation({ waitUntil: 'load' });
    // Verify new page saved.
    await expect(page).toMatchElement('#wpbody-content > div.wrap > h1', 'Edit product');
    await expect(page).toMatchElement('#title', 'Published Product');
    await expect(page).toMatch('Product published.');
  });
});

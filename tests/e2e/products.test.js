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
  it('Modal displays error message when adding an empty new product', async () => {
    expect.assertions(7);

    // Click "CopyCraft" button in the main description editor.
    await page.evaluate((selector) => document.querySelector(selector).click(), '#wp-content-wrap .copycraft-open-modal-button');

    // Wait for Modal to load.
    await page.waitForSelector('#TB_window');

    // Modal Title.
    await expect(page).toMatchElement('#TB_window #TB_ajaxWindowTitle', 'CopyCraft');
    // Loading message displays while AJAX call is made.
    await expect(page).toMatchElement('#copycraft-modal-contents p.loading', 'Generating description, please wait ...');

    // Verify error message is shown and buttons are not shown.
    await page.waitForSelector('#copycraft-modal-contents p.error');
    await expect(page).toMatchElement('#copycraft-modal-contents p.error', 'Please enter a product name and try again.');
    await expect(page).not.toMatchElement('#replace');
    await expect(page).not.toMatchElement('#insert');
    await expect(page).not.toMatchElement('#refresh');
    await expect(page).not.toMatchElement('#discard');
  });
  it('Can display a Modal when CopyCraft button clicked', async () => {
    expect.assertions(5);

    // Enter a Product Title
    await expect(page).toFill('#title', 'A new Product');
    // Click "CopyCraft" button in the main description editor.
    await page.evaluate((selector) => document.querySelector(selector).click(), '#wp-content-wrap .copycraft-open-modal-button');

    // Wait for Modal to load.
    await page.waitForSelector('#TB_window');

    // Modal Title.
    await expect(page).toMatchElement('#TB_window #TB_ajaxWindowTitle', 'CopyCraft');
    // Loading message displays while AJAX call is made.
    await expect(page).toMatchElement('#copycraft-modal-contents p.loading', 'Please wait ...');

    // Verify new Post ID exists and is used in the AJAX request URL.
    await expect(page).toMatchElement('#post_ID', { value: /^\d+$/ });
    const newPostId = parseInt(await page.$eval('#post_ID', el => el.value));
    expect(newPostId).toBeGreaterThan(0);

    // TODO: Verify that the AJAX request URL is correct and containts the newPostId.
    // Wait for AJAX call to complete.
    // await page.waitForSelector('#copycraft-modal-contents #description');
    // await expect(page).toMatchElement('#copycraft-modal-contents #description', { value: /\w+/ });

  });
});

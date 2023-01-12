/* global copycraft, describe, expect, it, jestPuppeteer, page */

const { visitAdminPage } = require('@wordpress/e2e-test-utils');

describe('Product Management', () => {
  beforeEach(async () => {
    await jestPuppeteer.resetPage();
    await visitAdminPage('post-new.php', 'post_type=product');
  });
  it('Can create a new draft product', async () => {
    expect.assertions(4);

    // Enter a Product Title
    await expect(page).toFillElement('#title', 'Draft Product');
    // Click "Save Draft" button.
    await page.evaluate((selector) => document.querySelector(selector).click(), '#save-post');
    // Wait for page to reload.
    await page.waitForNavigation({ waitUntil: 'load' });
    // Verify new page saved.
    await expect(page).toElementEquals('#wpbody-content > div.wrap > h1', 'Edit product');
    await expect(page).toElementEquals('#title', 'Draft Product');
    await expect(page).toIncludes('Product draft updated.');
  });
  it('Can create a new published product', async () => {
    expect.assertions(5);

    // Enter a Product Title
    await expect(page).toFillElement('#title', 'Published Product');
    // Click "Publish" button.
    await page.evaluate((selector) => document.querySelector(selector).click(), '#publish');
    // Wait for page to reload.
    await page.waitForNavigation({ waitUntil: 'load' });
    // Verify new page saved.
    await expect(page).toElementEquals('#wpbody-content > div.wrap > h1', 'Edit product');
    await expect(page).toElementEquals('#title', 'Published Product');
    await expect(page).toIncludes('Product draft updated.');
    await expect(page).toMatch('Product published.');
  });
  it('Page load correctly', async () => {
    expect.assertions(3);

    // Make sure "CopyCraft" button exist on the page.
    await expect(page).toElementEquals('#wp-content-wrap .copycraft-open-modal-button', 'CopyCraft');

    // Click "CopyCraft" button in the main description editor.
    await page.evaluate(
      (selector) => document.querySelector(selector).click(),
      '#wp-content-wrap .copycraft-open-modal-button'
    );

    // Wait for Modal to load.
    await page.waitForSelector('#TB_window');

    // Modal Title.
    await expect(page).toElementEquals('#TB_window #TB_ajaxWindowTitle', 'CopyCraft');
    // Loading message displays while AJAX call is made.
    await expect(page).toElementEquals('#copycraft-modal-contents p.loading', 'Generating description, please wait ...');
  });
  it('Modal displays error message when adding an empty new product', async () => {
    expect.assertions(5);

    // Click "CopyCraft" button in the main description editor.
    await page.evaluate(
      (selector) => document.querySelector(selector).click(),
      '#wp-content-wrap .copycraft-open-modal-button'
    );

    // Wait for Modal to load.
    await page.waitForSelector('#TB_window');

    // Verify error message is shown and buttons are not shown.
    await page.waitForSelector('#copycraft-modal-contents p.error');
    await expect(page).toElementEquals(
      '#copycraft-modal-contents p.error',
      'Please enter a product name and try again.'
    );
    await expect(page).not.toHasElement('#replace');
    await expect(page).not.toHasElement('#insert');
    await expect(page).not.toHasElement('#refresh');
    await expect(page).not.toHasElement('#discard');
  });
  it('Modal displays and ajax request running', async () => {
    expect.assertions(2);

    // Enter a Product Title
    await expect(page).toFillElement('#title', 'A new Product');
    // Click "CopyCraft" button in the main description editor.
    await page.evaluate(
      (selector) => document.querySelector(selector).click(),
      '#wp-content-wrap .copycraft-open-modal-button'
    );

    // Wait for Modal to load.
    await page.waitForSelector('#TB_window');

    // Verify new Post ID exists and is used in the AJAX request URL.
    const newPostId = await page.$eval('#post_ID', (el) => el.value);
    const IsMatch = await page.evaluate((postID) => {
      return copycraft.events.ajaxParams.post_id === postID;
    }, newPostId);

    expect(IsMatch).toBe(true);
  });
  it('Modal displays error because missing OpenAI API key', async () => {
    expect.assertions(2);

    // Enter a Product Title
    await expect(page).toFillElement('#title', 'A new Product');
    // Click "CopyCraft" button in the main description editor.
    await page.evaluate(
      (selector) => document.querySelector(selector).click(),
      '#wp-content-wrap .copycraft-open-modal-button'
    );

    // Wait for Modal to load.
    await page.waitForSelector('#TB_window');

    // Verify error message is shown and buttons are not shown.
    await page.waitForSelector('#copycraft-modal-contents p.error');
    await expect(page).toElementEquals(
      '#copycraft-modal-contents p.error',
      'Please enter a product name and try agadadsvavdssain.'
    );
  });
});

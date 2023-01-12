'use strict';

module.exports = {
  preset: 'jest-puppeteer',
  setupFilesAfterEnv: ['./tests/setup.js'],
  testTimeout: 20_000 // 20 second
};

# CopyCraft End-to-End Tests

Automated end-to-end (e2e) tests for CopyCraft. Based on https://github.com/woocommerce/woocommerce-e2e-boilerplate/.

## Pre-requisites

### Install Node.js

Follow [instructions on the node.js site](https://nodejs.org/en/download/) to install Node.js.

### Install Docker

Install Docker Desktop if you don't have it installed:

- [Docker Desktop for Mac](https://docs.docker.com/docker-for-mac/install/)
- [Docker Desktop for Windows](https://docs.docker.com/docker-for-windows/install/)

Once installed, you should see the `🟢 Docker Desktop is running` message, indicating that everything is working as expected.
Note that if you install Docker through other methods, such as homebrew, your steps to set it up can be different. Please consult your package manager's documentation.

## Running tests

If you are using Windows, we recommend using [Windows Subsystem for Linux (WSL)](https://docs.microsoft.com/en-us/windows/wsl/) for End-to-end testing. Follow the [WSL Setup Instructions](./WSL_SETUP_INSTRUCTIONS.md) before proceeding with the steps below.

### Build environment for tests

- `cd` to the **CopyCraft** plugin folder
- Run `npm install`
- Run `npm run wp-env:start` will build the test site using Docker.
- Run `npm run wp-env:initialise` - it will run the initialisation script.
- Run `docker ps` - to confirm that the Docker containers were built and running. You should see output similar to the one below, indicating that everything had been built as expected:

```
CONTAINER ID   IMAGE       COMMAND                  CREATED      STATUS      PORTS                     NAMES
6c034f997b23   wordpress   "docker-entrypoint.s…"   3 days ago   Up 3 days   0.0.0.0:8889->80/tcp      dadda7d60d675512286455124fa59347-tests-wordpress-1
2b77cc5c5b12   wordpress   "docker-entrypoint.s…"   3 days ago   Up 3 days   0.0.0.0:8888->80/tcp      dadda7d60d675512286455124fa59347-wordpress-1
36d344f3473d   mariadb     "docker-entrypoint.s…"   3 days ago   Up 3 days   0.0.0.0:50938->3306/tcp   dadda7d60d675512286455124fa59347-tests-mysql-1
5cf1d90e2e1f   mariadb     "docker-entrypoint.s…"   3 days ago   Up 3 days   0.0.0.0:50923->3306/tcp   dadda7d60d675512286455124fa59347-mysql-1
```

This plugin uses the [@wordpress/env](https://www.npmjs.com/package/@wordpress/env) (`wp-env` for sort) npm package to build the e2e test environment. The main config file is `.wp-env.json`.

The `wp-env` creates two sets of WordPress Environments, one for development and one for testing.

- Navigate to `http://localhost:8888/` for the development environment
- Navigate to `http://localhost:8889/` for the testing environment

For both environments, use the following Admin user details to log in:

```
Username: admin
PW: password
```

- Run `npm run wp-env:update` when you modify setting.
- Run `npm run wp-env:stop` when you are done with running e2e tests.

Note that running `npm run wp-env:stop` and then `npm run wp-env:start` does not re-initialise it. Instead, use `npm run wp-env:destroy` and `npm run wp-env:start` to start anew.

### Run e2e tests

```bash
npm run test:e2e
```

### Run tests in debug mode

For Puppeteer debugging, follow [Google's documentation](https://developers.google.com/web/tools/puppeteer/debugging).

https://github.com/WordPress/gutenberg/tree/trunk/packages/e2e-test-utils

### Run a Test individually

To run an individual test, just call `jest` directly. For example:

```bash
jest tests/e2e/admin.test.js
```

## Writing e2e tests

We use the following tools to write e2e tests:

- Use [Jest](https://jestjs.io) to write test files. The [jest-puppeteer](https://github.com/smooth-code/jest-puppeteer) package provides all required configurations to run Jest using Puppeteer.
- Use WordPress' [E2E Test Utils](https://www.npmjs.com/package/@wordpress/e2e-test-utils) API, to navigate between pages. It is pre-configured to work seamlessly with `wp-env`.
- Use [Puppeteer](https://github.com/GoogleChrome/puppeteer) directly interact with the page.

Note: Do not use [expect-puppeteer](https://github.com/smooth-code/jest-puppeteer/tree/master/packages/expect-puppeteer) API. The time of writing appears to be broken, even with `puppeteer@17` installed.

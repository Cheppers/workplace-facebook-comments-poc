# Workplace by Facebook Comments PoC Code

Quick proof of concept code to test interacting with comments in a Workplace by Facebook group using the Facebook SDK v5 for PHP.

* [Workplace by Facebook developer documentation](https://developers.facebook.com/docs/workplace)
* [Facebook SDK v5 for PHP](https://developers.facebook.com/docs/reference/php/)
* [Graph API documentation](https://developers.facebook.com/docs/graph-api)

From the [Graphs API reference for Workplace](https://developers.facebook.com/docs/workplace/reference/graph-api):

> The Graph API for Workplace allows for a subset of functionality of the Graph API for Facebook. This functionality is limited to interactions with a Workplace community and may differ in some cases for better performance or usability.

You won't find certain details in the Workplace developer docs, i.e. Comments. In these cases, refer to the regular Graph API documentation.

## How to run the example code

1. Open the **Integrations** tab in the **Admin panel** of your Workplace.
1. Create a custom integration and take note of the app ID, app secret and access token.
1. Grant the *Read group content* and *Manage group content* permissions to your integration.
1. Clone this repository and create a file named `.env` in the root folder of the project with the following content (replacing the values):

        APP_ID={app id}
        APP_SECRET={app secret}
        ACCESS_TOKEN={access token}

1. Install packages using Composer:

        $ composer install

1. Simply run the `index.php` script in your command line:

        $ php index.php

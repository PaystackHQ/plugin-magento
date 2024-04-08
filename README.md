# Paystack Inline

A plugin that allows Magento store owners accept payments using Paystack

## :warning: **Deprecation Notice** 

We regret to inform you that the Paystack Magento Plugin is now deprecated and will no longer be actively maintained or supported.

**Reasons for deprecation**:
- Compatibility issues with the latest software versions
- Security vulnerabilities that cannot be addressed sufficiently.
- Obsolete functionality that is no longer relevant

To ensure a seamless experience, we recommend exploring the Paystack Integrations Directory for [alternative plugins](https://paystack.com/gh/integrations?category=app-builder&id=magento) that are actively maintained and supported.

## Requirements

- Existing Magento installation on your web server. The Paystack Magento module is compatible
with version 1.9.1 of Magento Community edition onwards. The installation procedure
described here has been tested on Magento Community version 1.9.1 and 1.9.2
- Supported Web Servers: Apache and Nginx
- PHP (5.3.19 or more recent) and extensions, MySQL and web browser
- cURL (7.34.0 or more recent)
- OpenSSL v1.0.1 or more recent
- For further details of PHP compatibility, MySQL, supported Web servers and other
requirements,refer to the Magento website:[Magento Requirements][link-magento-requirements]

## Prepare

- Before you can start taking payments through Paystack, you will first need to sign up at:
[https://dashboard.paystack.co/#/signup][link-signup]. To receive live payments, you should request a Go-live after
you are done with configuration and have successfully made a test payment.
- Either: Download a released `.tgz` file from the [releases page][link-releases]
- OR: <del>Locate this plugin on [Magento Connect][link-magento-connect] and copy the extension key.</del> Pending Approval.

## Install

- Login to your Store Admin
- Navigate to **System** > **Magento Connect** > **Magento Connect Manager**.

### Using the downloaded release

- Scroll to **Direct package file upload**

![Direct package file upload](.github/screenshots/direct-package-upload.png?raw=true "Direct package file upload")
- Choose the package file you downloaded from our [releases page][link-releases].
- Click `Upload`. Magento will attempt to install the plugin.

### Using Magento Connect

-
- Scroll to **Install New Extensions**

![Install New Extensions](.github/screenshots/install-new-extensions.png?raw=true "Install New Extensions")
- Enter the extension key you copied from [Magento Connect][link-magento-connect].
- Click `Install`. The Magento will download the plugin and show details of whether your system is ready to install.

![Preinstall Confirmation](.github/screenshots/preinstall-confirmation.png?raw=true "Preinstall Confirmation")
- Click `Proceed`.

### Wrapping up install

- If all went well in both cases, you should see a message similar to this:
![Successful Install](.github/screenshots/successful-install.png?raw=true "Successful Install")


## Configure

- In the Magento Admin panel, navigate to the **System** > **Configuration** section
and select **Payment Methods** from the Sales section of the left‐hand Configuration menu.
- Find `Paystack Inline Module` and click the title to expand if not already expanded.
- Provide your **Test keys** and **Live Keys** as made available on the
[Paystack Dashboard][link-keys]. Note that the dashboard has no live keys until a successful
_Go-live_. Not to worry, you can  test the installation by setting `Test Mode` to `Yes`
and providing only your **Test keys**.
- You may also change the title of the module specifying what you'd want your customers to
see when choosing to pay via Paystack.
- Set your Webhook URL on your [Paystack Dashboard][link-keys] to _//your/magento/site/root/paystack/webhook/handler_.

## I'm ready!

- Request `Go-live` on the Paystack Dashboard.

## Note

- Paystack currently only accepts `NGN`. Kindly set your website currency in Magento thus.
- Any order paid for via Paystack will not open if you uninstall the module in case you do not need it anymore.
Rather you should disable by setting `Enabled` to `No`.

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email `support@paystack.com` instead of using the issue tracker.

## Credits

- [Paystack Support][link-author]
- [Ibrahim Lawal][link-author2]
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.

[link-releases]: https://github.com/paystackhq/paystack-magento/releases
[link-magento-requirements]: http://magento.com/resources/system‐requirements
[link-magento-connect]: https://www.magentocommerce.com/
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[link-author]: https://github.com/paystackhq
[link-signup]: https://dashboard.paystack.co/#/signup
[link-keys]: https://dashboard.paystack.co/#/settings/developer
[link-author2]: https://github.com/ibrahimlawal
[link-contributors]: ../../contributors

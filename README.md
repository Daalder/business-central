[![MIT Licensed](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/daalder/business-central/run-tests?label=tests)
[![Quality Score](https://img.shields.io/scrutinizer/g/Daalder/business-central.svg?style=flat-square)](https://scrutinizer-ci.com/g/Daalder/business-central)

# Microsoft Dynamics 365 Business Central + Daalder
[Daalder](https://daalder.io) package for Microsoft Dynamics 365 Business Central.<br/>
For questions about this package or Daalder contact: [info@pionect.nl](mailto:info@pionect.nl)

## Current state
| Endpoints        | Status           | Remarks  |
| ------------- |:-------------| :-----|
| Customer      | Implemented | Customers in Daalder |
| Dimension      | *Partial*      | One parent dimension is configurable |
| DimensionValue | Implemented      | Revenue groups in Daalder |
| Item | Implemented      | [Products](https://daalder.io/docs/) in Daalder |
| Item category | Implemented      | [Product attribute set](https://daalder.io/docs/) in Daalder |
| Sales order | Implemented | [Orders](https://daalder.io/docs/) in Daalder |
| Sales order line | Implemented | [Order rows](https://daalder.io/docs/) in Daalder |

For a complete list of endpoints visit the [Microsoft reference](https://docs.microsoft.com/en-us/dynamics365/business-central/dev-itpro/api-reference/v2.0/)

## Migrations
Tables are generated to store references to Business Central records for the various resources mentioned above.
## Configuration
In the `.env` file of Daalder `BC_ENDPOINT` needs to be set.<br/>
For example https://api.businesscentral.dynamics.com/v2.0/production/api/v2.0/

[Here](https://docs.microsoft.com/en-us/dynamics365/business-central/dev-itpro/api-reference/v2.0/endpoints-apis-for-dynamics) you can find more information on the various endpoints.
## Daalder events
### Products
In [EventServiceProvider](/src/Providers/EventServiceProvider.php) there is and [ProductObserver](/src/Observers/ProductObserver.php) registered to the Daalder product model.
This observer handles create, update and delete.
### Orders
In Daalder the event ```OrderPaymentConfirmed``` is fired when an order is confirmed. In [EventServiceProvider](/src/Providers/EventServiceProvider.php) we register the listener [PushOrderToBusinessCentral](/src/Listeners/PushOrderToBusinessCentral.php).
When the event is triggered this listener is put on the queue specified.
## Jobs
## Error reporting
## Commands
## Customization and extending
## Future updates
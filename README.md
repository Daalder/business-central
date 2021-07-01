[![MIT Licensed](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/daalder/business-central/run-tests?label=tests)
[![Quality Score](https://img.shields.io/scrutinizer/g/Daalder/business-central.svg?style=flat-square)](https://scrutinizer-ci.com/g/Daalder/business-central)

# business-central
Daalder package for Microsoft Dynamics 365 Business Central


## File structure

## Migrations
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

- Events waarop ingehaakt moet worden Daalder.
    - Product
        - Updated model event product
    - Order
        - Created model event
        - Updated model event
        - Order status change los event
- Job afhandeling
- Error reporting
- Commands die beschikbaar zijn
- Custom inhaak opties.
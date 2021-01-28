# Translators

## Concept

The concept of translators is quite simple - they are built to translate objects from Business Central and vice versa.

Now translations are performed within `NotifyController` after application receives notification from Business Central.
The process of translation is sophisticated, as it requires origin and destination repositories and, in case of,
products, also intermediate model (`Daalder` product model).

Other benefits of emplyoing Translators include avoiding code duplication (DRY), unified interfaces for translation
of particular resources (SOLID), and separation translation logic from controllers themselves (KISS).

## Expected usage

Given the current `NotifyController` logic, the expected usage of translators is as following:

```php
<?php

// ...

public function updateItem(Request $request)
{
    try {
        TranslatorFactory::fromBusinessCentral($request->path())->update($request->all());
    } catch (Exception $e) {
        // exception handling
    }   

    // or

    $product = TranslatorFactory::fromBusinessCentral($request->path())->prepare($request->all());
    // handling of product update logic
}
```

## Logic

The logic of translators is as following. They use combined *Factory* design patterns, with Laravel-ish way
of using *Chain of responsibility* pattern.

1. `TranslatorFactory` provides two main static methods: `fromBusinessCentral` and `fromBackOffice`. Those methods
   should receive `$translationBase` as a single parameter. Translation base is the minimal data payload, necessary
   for the `TranslatorFactory` to return expected `TranslatorContract`-compatible class.
   
   For Business Central to BackOffice translation, a `$translationBase` is a remote resource URL, and `TranslatorFactory`
   handles parsing of the URL and resource type by passing those data to a particular `Translator`.
   
2. `Translator` is a class implementing `TranslatorContract` interface, thus, providing few common methods, like
   `update`, `create` or `delete`. `Translator` gets information on the translation direction from the `TranslatorFactory`,
   so all you have to do in the controller is call `fromBusinessCentral` method of the `TranslatorFactory` and
   `update`, `create`, `prepare` or `delete` method of returned `Translator`. 

3. Since validation of received payload will be handled both in the `NotificationController` (from request), and
   within the `SubscriptionNoticeService` while processing received subscription notices' payloads, it (validation)
   will be handled by particular translators, and not controllers or services.
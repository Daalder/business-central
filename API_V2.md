# BusinessCentral API v2

## Models

### Subscription

`Subsciption` is a model, representing BusinessCentral API v2 webhook subscriptions.

A single `Subscription` regards single application model, e.g. `Product`, and can contain multiple operations,
like `create`, `update`, `delete`, etc.

### SubscriptionNotice

`SubscriptionNotice` is a model, representing payload, received from BusinessCentral API v2, regarding single
operation specified for a particular model (which a `Subscription` is related to).

When a `Product` gets first created within BusinessCentral API, and then updated almost immediately, application
would receive two `SubscriptionNotice` objects. One representing `create` operation, and a second one, representing
`update` operation.

## Flow

In order to register application webhooks, a specific flow was implemented:

1. **`Subscription` is created**

   When a `Subscription` is created (f.e. when a `Product` is created), a `SubscriptionCreated` event is fired,
   via `SubscriptionObserver`, registered within `EventServiceProvider` residing in the API namespace
   of the `BusinessCentral\API`.
   
   *Note: API-specific `EventServiceProvider` is not registered in `config/app.php` file, but within the main 
   `EventServiceProvider` of the BusinessCentral package.*
   
   Before fresh `Subscription` model is created, two listeners assign randomly generated `clientState` property
   and `notificationUrl` which equals to the particular route.
   
   Creation of a fresh `Subscription` triggers also dispatching of queued listener `SubscriptionRegister`, using 
   `HttpClient` to register `Subscription` within the API.
   
2. **A handshake is received**

   When a `Subscription` gets created within the API, a POST request is directed to the `SubscriptionController`
   `index()` method with `validationToken` passed as the query parameter.
   
   If handshake is valid, i.e. a `validationToken` was passed, and received `clientState` is equal to `Subscription`
   `clientState` parameter, a `text/plain` response is generated with `validationToken` contents as body, and 200
   as HTTP status.
   
   Before response is returned, application calls API's subscription endpoint to fetch `expirationDateTime` property
   of the subscription and, if it's in the future, `Subscription` gets registered by setting `expirationDateTime` and
   `isRegistered` properties.
   
3. **`Subscription` gets registered**

   When a `Subscription` gets registered successfully, a `SubscriptionRegistered` event is triggered, with queued
   listener `SubscriptionRenew` attached to.
   
   `SubscriptionRenew` listener is delayed for 24 hours after `Subscription` registration, and it produces a PATCH
   request to the BusinessCentral API in order to renew the `Subscription`.
   
   `Subscription` renewal is similar to its registration. After application receives handshake, it fetches data from
   the API to update the `expirationDateTime` property. However, instead of `SubscriptionRegistered`, event, a
   `SubscriptionRenewed` event is trigger and the very same `SubscriptionRenew` listener is dispatched.
   
   *In order for the `SubscriptionRenew` listener to listen to two event classes, a `SubscriptionRegisteredRenewedContract`
   interface was created, and the `SubscriptionRenew` listener receives it instead of a particular event class.*
   
4. **A webhook is received**

   Application listens to webhooks on the same route, that the handshakes are performed. However, if no `validationToken`
   was provided to that route URL, a `readPayload` method of the `SubscriptionNoticeService` is called, and subscription 
   registration/renewal is ignored.
   
5. **Webhook payload parsing**

   A `readPayload` method parses received JSON body, and creates `SubscriptionNotice` models of it, validating `clientState`
   value on-the-fly.
   
6. **A `SubscriptionNotice` is created**

    When the `SubscriptionNotice` gets created, a `SubscriptionNoticeCreated` event is fired, and related
    `SubscriptionNoticeProcess` listener gets dispatched.
    
    Logic of processing new `SubscriptionNotice` lies within the `SubscriptionNoticeService` class. At first, it verifies
    whether processing of `SubscriptionNotice` should start. When following conditions are not met, processing would not start:
    
    - `SubscriptionNotice` should not be expired,
    - `SubscriptionNotice` should not be already processed.
    
    Moreover, a code to check whether given `SubscriptionNotice` contains data about the **latest** modification to the
    resource, but it is currently turned off.
    
    Processing of a `SubscriptionNotice` triggers API call for a given resource, using `HttpClient` class. When
    processing ends succesfully, `SubscriptionNotice` gets flagged as processed (`isProcessed` set to `true`).
   
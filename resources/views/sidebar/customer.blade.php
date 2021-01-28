@if($customer->businessCentral)
    @include('backoffice::sidebars.partial.open-in', ['link' => "https://businesscentral.dynamics.com/?company=NuBuiten&page=21&filter='Id' IS '{$customer->businessCentral->business_central_id}'"])

    <p>
        <strong>Beschikbaar in Business Central 365</strong>
        <small>Aangemaakt op: {{$customer->businessCentral->created_at->format('d-m-Y H:i:s')}}</small>
    </p>
@else
    <p>
        Niet beschikbaar in Business Central 365
    </p>
@endif
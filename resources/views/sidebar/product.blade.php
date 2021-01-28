@if($product->businessCentral)
    @include('backoffice::sidebars.partial.open-in', ['link' => "https://businesscentral.dynamics.com/?company=NuBuiten&page=30&filter='Id' IS '{$product->businessCentral->business_central_id}'"])

    <p>
        <strong>Beschikbaar in Business Central 365</strong>
    </p>

    <table class="table table-bordered">
        <tr>
            <td>ID</td>
            <td>{{$product->id}}</td>
        </tr>
        <tr>
            <td>Business Central ID</td>
            <td>{{$product->businessCentral->business_central_id}}</td>
        </tr>
    </table>

    <p>
        <small>Aangemaakt op: {{$product->businessCentral->created_at->format('d-m-Y H:i:s')}}</small>
    </p>
@else
    <p>
        Niet beschikbaar in Business Central 365
    </p>
@endif

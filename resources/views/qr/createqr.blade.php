<style>
  .buttons-modal {
    display: flex;
    flex-direction: row;
    gap: 1rem;
    align-items: center;
    justify-content: center;
  }
</style>

@extends('layouts.app')
@section('content')

<div class="container marketing">
@if(session('error'))
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
  <h1>
    Crea el seguimiento de un producto
  </h1>
  <br>
  <table class="table table-striped" id="manage-qrs-table">
    <thead>
      <tr>
        <th scope="col">#</th>
        <th scope="col">Imagen</th>
        <th scope="col">Nombre</th>
        <th scope="col">Categoría</th>
        <th scope="col">Marca</th>
        <th scope="col">Descripción</th>
        <th scope="col" >Editar</th>
        <th scope="col" >Generar QR</th>
      </tr>
    </thead>
    <tbody>
      @foreach($products as $product)
        <tr>
          <td>{{$product->id}}</td>
          <td> <img src="{{ asset("products/".$product->pic) }}" alt=""  style="max-width: 100px; max-height: 100px;"> </td>
          <td>{{$product->name}}</td>
          <td>{{$product->category}}</td>
          <td>{{$product->marca}}</td>
          <td>{{$product->description}}</td>
          <td>
            @if($qrs->where('product_id', $product->id)->count() > 0)
              <button type="button" class="btn btn-link bi bi-pencil-square" data-bs-toggle="modal" data-bs-target="#qrModal{{$product->id}}" style="color:light-blue;"></button>
            @endif
          </td>
          <td>
            <form action="{{ route('createqr') }}" method="POST" style="display:inline;">
              @csrf
              <input type="hidden" name="id" value="{{ $product->id }}">
              <button type="submit" class="btn btn-link bi bi-plus-square" style="color:green;"></button>
            </form>
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>

  <!-- Modal para mostrar todos los QR de un producto -->
  @foreach($products as $product)
  <div class="modal fade" id="qrModal{{$product->id}}" tabindex="-1" aria-labelledby="qrModalLabel{{$product->id}}" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="qrModalLabel{{$product->id}}">QRs para {{$product->name}}</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <table class="table">
            <thead>
              <tr>
                <th scope="col">ID</th>
                <th scope="col">End</th>
                <th scope="col">Distancia</th>
                <th scope="col">Puntuación</th>
                <th scope="col">Acciones</th>
              </tr>
            </thead>
            <tbody>
              @foreach($qrs as $qr)
                @if($qr->product_id == $product->id)
                  <tr>
                    <td>{{$qr->id}}</td>
                    <td>{{$qr->end ? '1' : '0'}}</td>
                    <td>{{$qr->end ? $qr->distancia : '-'}}</td>
                    <td>{{$qr->end ? $qr->puntuacion : '-'}}</td>
                    <td>
                      <button type="button" class="btn btn-primary" onclick="reloadAndShowQrModal('{{ $qr->id }}')">Ver QR</button>
                    </td>
                  </tr>
                @endif
              @endforeach
            </tbody>
          </table>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </div>
  </div>
  @endforeach

  <!-- Modal para generar QR de un producto -->
  <div class="modal fade" id="qrCodeModal" tabindex="-1" aria-labelledby="qrCodeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="qrCodeModalLabel">QR Generado</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body text-center">
          <img id='qr' src="{{ isset($qrImage) ? asset('qr/qr_'.$qrImage.'.png') : '' }}" class="center" >
          <div class="buttons-modal">
            <div>
              <button onclick="saveImage()" class="btn btn-primary" style="margin:auto;display:block;">Guardar QR</button>
            </div>
            <div>
            <button onclick="window.location.href='https://eco2.netshiba.com/viewqr?id={{ $qrImage }}'" class="btn btn-primary" style="margin:auto;display:block;">
              Ver QR
            </button>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </div>
  </div>

  @if(isset($qrImage))
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      $('#qrCodeModal').modal('show');
    });

    function saveImage() {
      var imgElement = document.getElementById('qr');
      var link = document.createElement('a');
      link.href = imgElement.src;
      link.download = 'qr.jpg';
      link.click();
    }
  </script>
  @endif
</div>


<script>
    $(document).ready(function() {
        $('#manage-qrs-table').DataTable();
    });
</script>
<script>
    $(document).ready(function() {
        $('#manage-qrs-table').DataTable();
    });

    function reloadAndShowQrModal(qrId) {
        // Recargar la página con el parámetro de consulta qrImage
        window.location.href = window.location.pathname + '?qrImage=' + qrId;
    }

    // @if(isset($qrImage))
    //   document.addEventListener('DOMContentLoaded', function() {
    //     $('#qrCodeModal').modal('show');
    //   });
    // @endif

    function saveImage() {
      var imgElement = document.getElementById('qr');
      var link = document.createElement('a');
      link.href = imgElement.src;
      link.download = 'qr.jpg';
      link.click();
    }
</script>

@endsection

@extends('app')

@section('panel')

    @if($uploads->isNotEmpty())
    <table class="table table-hover">
        <thead>
        <tr>
            <th>UUID</th>
            <th>Nazwa użytkownika</th>
            <th>Name</th>
            <th>Extension</th>
            <th>Size (GB)</th>
            <th>Status</th>
            <th>Updated at</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($uploads as $upload)
            <tr id="{{$upload->uuid.'.'.$upload->extension}}" data-typ="{{ $upload->extension }}">
                <td>{{ $upload->uuid }}</td>
                <td>{{ $upload->user->name }}</td>
                <td>{{ $upload->original_name }}</td>
                <td>{{ $upload->extension }}</td>
                <td>{{ $upload->size }}</td>
                <td>@if($upload->status === 'completed') <span class="badge text-bg-success">Ukończono</span> @elseif($upload->status == 'awaiting') <span class="badge text-bg-warning">Oczekuje</span> @elseif($upload->status == 'rejected') <span class="badge text-bg-warning">Odrzucono</span> @elseif($upload->status === 'failed') <span class="badge text-bg-danger">Błąd</span> @endif</td>
                <td>{{ $upload->updated_at }}</td>
                <td>
                    <i class="fa-solid fa-video" data-bs-toggle="modal" data-bs-target="#video"></i>
                    <a href="{{ route('videos.accept', $upload->uuid) }}"><i class="fa-solid fa-check text-success"></i></a>
                    <a href="{{ route('videos.reject', $upload->uuid) }}"><i class="fa-solid fa-check text-danger"></i></a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    @else
        <h3>Brak danych do wyświetlenia</h3>
    @endif

    <div class="modal fade" id="video" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-body">

                    <video id="video" width="100%" controls>

                    </video>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Zamknij</button>
                </div>
            </div>
        </div>
    </div>


@endsection


@push('scripts')

    <script>

        $(function() {

            $('table tbody tr').on('click', function () {
                const uuid = $(this).attr('id');
                const base = "{{ asset('storage/video') }}";
                const typ = $(this).data('typ');
                $('video').html(`
                <source src="${base}/${uuid}" type="video/${typ}">
            `);
            });

        });

    </script>

@endpush

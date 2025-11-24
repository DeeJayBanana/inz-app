@extends('app')

@section('panel')

        <div id="upload" class="dropzone"></div>


        <table class="table table-hover">
            <thead>
                <tr>
                    <th>UUID</th>
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
                        <td>{{ $upload->original_name }}</td>
                        <td>{{ $upload->extension }}</td>
                        <td>{{ $upload->size }}</td>
                        <td>@if($upload->status === 'completed') <span class="badge text-bg-success">Ukończono</span> @elseif($upload->status === 'failed') <span class="badge text-bg-danger">Błąd</span> @endif</td>
                        <td>{{ $upload->updated_at }}</td>
                        <td>
                            <i class="fa-solid fa-video" data-bs-toggle="modal" data-bs-target="#video"></i>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

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

        Dropzone.autoDiscover = false;

        $(function() {

            const dz = new Dropzone('#upload', {
                autoProcessQueue: false,
                url: "/video/upload",
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                chunking: true,
                forceChunking: true,
                chunkSize: 5 * 1024 * 1024,
                parallelChunkUploads: false,
                retryChunks: true,
                retryChunksLimit: 3,
                maxFilesize: 10000,
            });

            dz.on('addedfile', function(file) {
                $.ajax({
                    url: "/video/store",
                    method: 'POST',
                    data: {fileName: file.name},
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    success: function(res) {
                        file.uploadId = res.uuid;
                        dz.processFile(file);
                    }
                });
            });

            dz.on('sending', function(file, xhr, formData) {
                formData.append('dzuuid', file.uploadId);
            });

            dz.on('queuecomplete', function() {
               dz.files.forEach(function(file) {
                  $.ajax({
                      url: "/video/finalize",
                      method: "POST",
                      headers: {
                          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                      },
                      data: {
                          dzuuid: file.uploadId,
                          dztotalchunkcount: file.upload.totalChunkCount,
                      },
                      success: function(res) {
                          console.log(res);
                      },
                      error: function(err) {
                          console.log(err);
                      }
                  });
               });
            });

        });

        $('table tbody tr').on('click', function() {
            const uuid = $(this).attr('id');
            const base = "{{ asset('storage/video') }}";
            const typ = $(this).data('typ');
            $('video').html(`
                <source src="${base}/${uuid}" type="video/${typ}">
            `);
        });


    </script>

@endpush

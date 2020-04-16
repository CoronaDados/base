<form role="form" id="diagnostic_form">
    <input type="hidden" name="person_id" class="person_id">

    @csrf

    <div class="row">
        <div class="col-md-6">
            <h3>Teste</h3>

            @foreach($tests as $test)
                <div class="form-group">
                    <div class="custom-control custom-radio mb-3">
                        <input name="status_test" class="custom-control-input" id="test-{{$loop->index}}" value={{ $test }} type="radio">
                        <label class="custom-control-label" for="test-{{$loop->index}}">{{ $test }}</label>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="col-md-6">
            <h3>Situação atual</h3>

            @foreach($status as $s)
                <div class="form-group">
                    <div class="custom-control custom-radio mb-3">
                        <input name="status_covid" class="custom-control-input" id="status-{{$loop->index}}" value={{ $s }} type="radio">
                        <label class="custom-control-label" for="status-{{$loop->index}}">{{ $s }}</label>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="col-12 text-right p-0">
        <button type="submit" class="btn btn-primary my-4 btn-diagnostic">Salvar</button>
    </div>
</form>

@push('js')
    <script>
        $(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('.btn-diagnostic').on('click', function (e) {
                e.preventDefault();

                $(this).html('Atualizando...').prop('disabled', true);

                let person_id = $('.person_id').val();

                $.ajax({
                    data: $('#diagnostic_form').serialize(),
                    url: "{{ route('casesPerson.store') }}",
                    type: "POST",
                    dataType: 'json',
                    success: function (data) {
                        table.ajax.reload();

                        $('.save').html('Salvar').prop('disabled', false);
                        $('#ajaxModel').modal('hide');

                        Swal.fire({
                            title: 'Sucesso!',
                            text: data.message,
                            icon: 'success',
                            confirmButtonText: 'Fechar'
                        });
                    },
                    error: function (e) {
                        $('#ajaxModel').modal('hide');
                        $('.save').html('Salvar').prop('disabled', false);

                        Swal.fire({
                            title: 'Erro!',
                            text: 'Erro ao atualizar os dados.',
                            icon: 'error',
                            confirmButtonText: 'Fechar'
                        });
                    }
                });
            });
        });

    </script>
@endpush

@extends('company.layouts.app',['class' => 'bg-gradient-success'])

@section('content')

    <div class="container-fluid pb-8 pt-3 pt-md-7">
        <div class="card">
            <div class="card-header">
                <h3>Cadastrar do Colaborador</h3>
            </div>

            <div class="card-body">
                @include('person.partials.form', ['isRequired' => true, 'route' => route('person.store')])
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        let c = 1;
        var addP = (count) =(function (count) {

            console.log('count' + count)
            console.log('c' + c)
            if(count != c)
                return true;
            count++;
            c =count;
            var newInput = $(document.createElement('div'))
                .attr("class", 'row');

            newInput.after().html(
                '<div class="col-6 p-sm-1">\n' +
                '    <div class="form-group">\n' +
                '        <label>Nome</label>\n' +
                '        <input type="text" name="related_persons['+count+'][name]" placeholder="Nome"\n' +
                '            onchange="addP('+count+')" class="form-control"/>\n' +
                '    </div>\n' +
                '</div>\n' +
                '<div class="col-3 p-sm-1">\n' +
                '    <div class="form-group">\n' +
                '        <label>Telefone</label>\n' +
                '        <input type="tel" name="related_persons['+count+'][phone]" placeholder="Telefone"\n' +
                '               class="form-control phone"/>\n' +
                '    </div>\n' +
                '</div>\n' +
                '<div class="col-3 p-sm-1">\n' +
                '    <div class="form-group">\n' +
                '        <label>CPF</label>\n' +
                '        <input type="text" name="related_persons['+count+'][cpf]" placeholder="CPF"\n' +
                '               class="form-control cpf"/>\n' +
                '    </div>\n' +
                '</div>');

            newInput.appendTo("#related_persons");
            handleMasks();
        });
    </script>
@endpush

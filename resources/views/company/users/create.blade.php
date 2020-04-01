@extends('company.layouts.app',['class' => 'bg-gradient-success'])

@section('content')
    {{--    @include('layouts.headers.cards')--}}
    <div class="container-fluid pb-8 pt-3 pt-md-7">
        <div class="card">
            <div class="card-header text-center">
                <h3>Cadastrar Usuário</h3>
                <h5>Essa pessoa será monitorada por ela mesma</h5>
            </div>
            <div class="card-body">
                <form role="form" method="POST" action="{{ route('company.users.store') }}">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">Nome Completo</label>
                                <input type="text" class="form-control" required id="name" name="name"
                                       placeholder="Nome Completo">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" required id="email" name="email"
                                       placeholder="Email">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="phone">Telefone (WhatsApp)</label>
                                <input type="tel" class="form-control phone" required name="phone" id="phone"
                                       placeholder="Telefone (WhatsApp)">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="cpf">CPF</label>
                                <input type="text" class="form-control cpf" required id="cpf" name="cpf"
                                       placeholder="CPF">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="department">Departamento</label>
                                <select name="department" id="department" required class="custom-select">
                                    <option disabled selected>Setor</option>
                                    <option value="Operacional">Operacional</option>
                                    <option value="Escritório">Escritório</option>
                                    <option value="Almoxerifado">Almoxerifado</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="cep">CEP</label>
                                <input type="text" class="form-control cep" required name="cep" id="cep"
                                       placeholder="CEP">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="birthday">Data de Nascimento</label>
                                <div class="input-group input-group-alternative">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="ni ni-calendar-grid-58"></i></span>
                                    </div>
                                    <input class="form-control" required placeholder="Data de Nascimento" id="birthday"
                                           name="birthday" type="date">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="type_transport">Como vai ao trabalho</label>
                                <select name="type_transport" id="type_transport" required class="custom-select"
                                        onchange="TypeTransport()">
                                    <option disabled selected>Como vai ao trabalho</option>
                                    <option value="1">Onibus</option>
                                    <option value="2">Carro</option>
                                    <option value="3">A pé</option>
                                    <option value="3">Bicicleta</option>
                                    <option value="4">Outros</option>
                                </select>
                            </div>
                        </div>
                        <div id="show_type_transport" class="col-md-4">
                            <div class="form-group">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 p-1">
                            <div class="card p-1">
                                <div class="card-header text-center">
                                    <h3>Relacionamento com pessoas</h3>
                                    <h5>Liste todas as pessoas em sua residencia, meio de transporte, meio de convivio
                                        etc</h5>
                                </div>
                                <div class="card-body" id="related_persons">
                                    <div class="row">
                                        <div class="col-6 p-1">
                                            <div class="form-group">
                                                <label>Nome</label>
                                                <input type="text" name="related_persons[1][name]" placeholder="Nome"
                                                   onchange="addP(1)"    class="form-control"/>
                                            </div>
                                        </div>
                                        <div class="col-3 p-1">
                                            <div class="form-group">
                                                <label>Telefone</label>
                                                <input type="tel" name="related_persons[1][phone]" placeholder="Telefone"
                                                       class="form-control phone"/>
                                            </div>
                                        </div>
                                        <div class="col-3 p-1">
                                            <div class="form-group">
                                                <label>CPF</label>
                                                <input type="text" name="related_persons[1][cpf]" placeholder="CPF"
                                                       class="form-control cpf"/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary my-4">{{ __('Cadastrar') }}</button>
                    </div>
                </form>
            </div>
        </div>
        <div>

        </div>


    </div>
@endsection

@push('js')
    <script>
        var handleMasks = function (){
            var SPMaskBehavior = function (val) {
                    return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
                },
                spOptions = {
                    onKeyPress: function(val, e, field, options) {
                        field.mask(SPMaskBehavior.apply({}, arguments), options);
                    }
                };

            $('.phone').mask(SPMaskBehavior, spOptions);
            $('.cpf').mask('000.000.000-00', {reverse: true});
        };
        var TypeTransport = (function () {
            let type = $('#type_transport').val();
            let template = null;
            switch (type) {
                case '1': {
                    template =
                        '<label for="bus_line">Número da linha de onibus</label>\n' +
                        '<input type="text" class="form-control" name="bus_line"  id="bus_line"\n' +
                        'placeholder="Número da linha de onibus">'
                }
            }
            console.log(template)
            if (template) {
                $('#show_type_transport > div').html(template)
                $('#show_type_transport').show()
            } else {
                $('#show_type_transport').hide()
            }
        })
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
        })
    </script>
@endpush

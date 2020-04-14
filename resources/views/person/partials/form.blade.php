<form role="form"  {{ $route ? 'method=POST action=' . $route : ''}} id="person_form">
    @if(!$isRequired)
        <input type="hidden" name="person_id" id="person_id">
    @endif

    @csrf
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="name">Nome Completo {{ $isRequired ? '*' : '' }}</label>
                <input type="text" class="form-control form-control-alternative" required id="name" name="name" value="{{ $companyUser->person->name ?? '' }}"
                       placeholder="Nome Completo {{ $isRequired ? '(obrigatório)' : '' }}" {{ $isRequired ? 'required' : '' }}/>

            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label for="email">E-mail {{ $isRequired ? '*' : '' }}</label>
                <input type="email" class="form-control form-control-alternative" required id="email" name="email" value="{{ $companyUser->email ?? '' }}"
                       placeholder="Email {{ $isRequired ? '(obrigatório)' : '' }}" {{ $isRequired ? 'required' : '' }}/>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3">
            <div class="form-group">
                <label for="phone">Telefone (WhatsApp) {{ $isRequired ? '*' : '' }}</label>
                <input type="tel" class="form-control form-control-alternative phone" required name="phone" id="phone" value="{{ $companyUser->person->phone ?? '' }}"
                       placeholder="Telefone (WhatsApp) {{ $isRequired ? '(obrigatório)' : '' }}" {{ $isRequired ? 'required' : '' }}/>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label for="cpf">CPF {{ $isRequired ? '*' : '' }}</label>
                <input type="text" class="form-control form-control-alternative cpf" required id="cpf" name="cpf" value="{{ $companyUser->person->cpf ?? '' }}"
                       placeholder="CPF {{ $isRequired ? '(obrigatório)' : '' }}" {{ $isRequired ? 'required' : '' }}/>
            </div>
            {{-- <div class="form-group{{ $errors->has('cpf') ? ' has-danger' : '' }}">
                <label for="cpf">CPF {{ $isRequired ? '*' : '' }}</label>
                <input type="text" class="form-control form-control-alternative cpf{{ $errors->has('cpf') ? ' is-invalid' : '' }}" name="cpf" value="{{ $companyUser->person->cpf ?? '' }}"
                    placeholder="CPF {{ $isRequired ? '(obrigatório)' : '' }}" {{ $isRequired ? 'required' : '' }}>
                @if ($errors->has('cpf'))
                    <span class="invalid-feedback" style="display: block;" role="alert">
                        <strong>{{ $errors->first('cpf') }}</strong>
                    </span>
                @endif
            </div> --}}
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label for="sector">Setor</label>
                <select name="sector" id="sector" class="custom-select form-control-alternative">
                    <option value="">Setor</option>
                    @foreach($sectors as $k => $v)
                        <option value="{{ $v }}" {{ isset($companyUser->person->sector) ? ($companyUser->person->sector == $v ? 'selected': '') : '' }}>{{ $v }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label for="cep">CEP {{ $isRequired ? '*' : '' }}</label>
                <input type="text" class="form-control form-control-alternative cep-person" {{ $isRequired ? 'required' : '' }} name="cep" value="{{ $companyUser->person->cep ?? '' }}"
                       id="cep" placeholder="CEP {{ $isRequired ? '(obrigatório)' : '' }}" {{ $isRequired ? 'required' : '' }}/>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label for="birthday">Data de Nascimento {{ $isRequired ? '*' : '' }}</label>
                <input class="form-control form-control-alternative birthday" {{ $isRequired ? 'required' : '' }} placeholder="Data de Nascimento" id="birthday"
                name="birthday" value="{{ $companyUser->person->birthday_formatted ?? '' }}" type="text">
            </div>
            {{-- <div class="form-group{{ $errors->has('birthday') ? ' has-danger' : '' }}">
                <label for="birthday">Data de Nascimento {{ $isRequired ? '*' : '' }}</label>
                <input class="form-control form-control-alternative birthday{{ $errors->has('cpf') ? ' is-invalid' : '' }}" type="text" name="birthday" value="{{ $companyUser->person->birthday_formatted ?? '' }}"
                    placeholder="Data de Nascimento" {{ $isRequired ? 'required' : '' }}>
                @if ($errors->has('birthday'))
                    <span class="invalid-feedback" style="display: block;" role="alert">
                        <strong>{{ $errors->first('birthday') }}</strong>
                    </span>
                @endif
            </div> --}}
        </div>
        <div class="col-md-2">
            <div class="form-group">
                <label>Gênero {{ $isRequired ? '*' : '' }}</label>
                <div class="custom-control custom-radio">
                    <input type="radio" id="masculino" {{ $isRequired ? 'required' : '' }} name="gender" value="M" class="custom-control-input" {{ isset($companyUser->person->gender) ? ($companyUser->person->gender == 'M' ? 'checked': '') : '' }}>
                    <label class="custom-control-label" for="masculino">Masculino</label>
                </div>
                <div class="custom-control custom-radio">
                    <input type="radio" id="feminino" {{ $isRequired ? 'required' : '' }} name="gender" value="F" class="custom-control-input" {{ isset($companyUser->person->gender) ? ($companyUser->person->gender == 'F' ? 'checked': '') : '' }}>
                    <label class="custom-control-label" for="feminino">Feminino</label>
                </div>
            </div>
        </div>

        @php
            $isAdmin = auth()->user()->hasRole('Admin');
            $col = ($isAdmin) ? 'col-md-4' : 'col-md-6'
        @endphp

        @if(!isset($companyUser))
            <div class={{ $col }}>
                <div class="form-group">
                    <label for="risk_group">Grupo de Risco {{ $isRequired ? '*' : '' }}</label>
                    <select name="risk_group" id="risk_group" {{ $isRequired ? 'required' : '' }} class="custom-select form-control-alternative risk_group">
                        <option value="">Grupo de Risco {{ $isRequired ? '(obrigatório)' : '' }}</option>
                        @foreach($riskGroups as $k => $v)
                            <option value="{{ $v }}" {{ isset($companyUser->person->risk_group) ? ($companyUser->person->risk_group == $v ? 'selected': '') : '' }}>{{ $v }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            @if($isAdmin)
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="role">Perfil {{ $isRequired ? '*' : '' }}</label>
                        <select name="role" id="role" class="custom-select form-control-alternative role" {{ $isRequired ? 'required' : '' }}>
                            <option value="" disabled selected>Perfil {{ $isRequired ? '(obrigatório)' : '' }}</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->name }}" {{ isset($companyUser->roles[0]) ? ($companyUser->roles[0]->name == $role->name ? 'selected': '') : '' }}>{{ $role->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            @endif

        @endif
    </div>

    @if($isAdmin and !isset($companyUser))
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="leader">Líder responsável {{ $isRequired ? '*' : '' }}</label>
                    <select name="leader" id="leader" class="custom-select form-control-alternative leader" {{ $isRequired ? 'required' : '' }}>
                        <option value="" disabled selected>Líder responsável {{ $isRequired ? '(obrigatório)' : '' }}</option>
                        @foreach($leaders as $l)
                            <option value="{{ $l->id }}" {{ isset($leader) ? ($leader == $l->id ? 'selected': '') : '' }}>{{ $l->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    @endif

{{--    <div class="row">--}}
{{--        <div class="col-md-4">--}}
{{--            <div class="form-group">--}}
{{--                <label for="type_transport">Como vai ao trabalho</label>--}}
{{--                <select name="type_transport" id="type_transport" required class="custom-select form-control-alternative"--}}
{{--                        onchange="TypeTransport()">--}}
{{--                    <option disabled selected>Como vai ao trabalho</option>--}}
{{--                    <option value="1">Ônibus</option>--}}
{{--                    <option value="2">Carro</option>--}}
{{--                    <option value="3">A pé</option>--}}
{{--                    <option value="3">Bicicleta</option>--}}
{{--                    <option value="4">Outros</option>--}}
{{--                </select>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--        <div id="show_type_transport" class="col-md-4">--}}
{{--            <div class="form-group">--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}

    @if(isset($companyUser))
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label" for="password">Senha</label>
                    <input type="password" id="password" name="password" class="form-control form-control-alternative">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label" for="confirm_password">Confirme a senha</label>
                    <input type="password" id="confirm_password" name="confirm_password" class="form-control form-control-alternative">
                </div>
            </div>
        </div>
    @endif

{{--    @if($isRequired)--}}
{{--    <div class="row">--}}
{{--        <div class="col-12 p-1">--}}
{{--            <div class="card p-1">--}}
{{--                <div class="card-header text-center">--}}
{{--                    <h3>Relacionamento com pessoas</h3>--}}
{{--                    <h5>Liste todas as pessoas em sua residência, meio de transporte, meio de convívio etc</h5>--}}
{{--                </div>--}}
{{--                <div class="card-body" id="related_persons">--}}
{{--                    <div class="row">--}}
{{--                        <div class="col-6 p-1">--}}
{{--                            <div class="form-group">--}}
{{--                                <label>Nome</label>--}}
{{--                                <input type="text" name="related_persons[1][name]" placeholder="Nome"--}}
{{--                                       onchange="addP(1)" class="form-control form-control-alternative"/>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                        <div class="col-3 p-1">--}}
{{--                            <div class="form-group">--}}
{{--                                <label>Telefone</label>--}}
{{--                                <input type="tel" name="related_persons[1][phone]"--}}
{{--                                       placeholder="Telefone"--}}
{{--                                       class="form-control form-control-alternative phone"/>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                        <div class="col-3 p-1">--}}
{{--                            <div class="form-group">--}}
{{--                                <label>CPF</label>--}}
{{--                                <input type="text" name="related_persons[1][cpf]" placeholder="CPF"--}}
{{--                                       class="form-control form-control-alternative cpf"/>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--    @endif--}}

    <div class="col-12 text-right p-0">
        <button type="submit" class="btn btn-primary my-4 save">{{ ($isRequired and !isset($companyUser)) ? __('Cadastrar') : __('Salvar') }}</button>
    </div>
</form>

@push('js')
    <script>
        let calculateAge = function(dateString) {
            let today = new Date(),
                birthDate = new Date(dateString);
            age = today.getFullYear() - birthDate.getFullYear();
            month = today.getMonth() - birthDate.getMonth();

            if (month < 0 || (month === 0 && today.getDate() < birthDate.getDate())) {
                age--;
            }

            return age;
        };

        let formattedDateToDB = function(dateString) {
            let day  = dateString.split("/")[0],
                month  = dateString.split("/")[1],
                year = dateString.split("/")[2];

            return year + '-' + ("0"+month).slice(-2) + '-' + ("0"+day).slice(-2);
        }


        let formattedDateFromDB = function(dateString) {
            const options = { year: 'numeric', month: 'numeric', day: 'numeric' };

            let dateParts = dateString.split("-"),
                date = new Date(dateParts[0], dateParts[1] - 1, dateParts[2].substr(0,2));

            return date.toLocaleDateString('pt-BR', options);
        }

        let optionsBirthday =  {
            onComplete: function(birthday) {
                let newDate = formattedDateToDB(birthday),
                    $option = $(".risk_group option:contains('Acima de 60 anos')");

                if(calculateAge(newDate) >= 60) {
                    $option.prop("selected", true);
                } else {
                    $option.prop("selected", false);
                }
            }
        },
        SPMaskBehavior = function (val) {
            return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
        },
        spOptions = {
            onKeyPress: function(val, e, field, options) {
                field.mask(SPMaskBehavior.apply({}, arguments), options);
            }
        };

        let handleMasks = function () {
            $('.phone').mask(SPMaskBehavior, spOptions);
            $('.cep-person').mask('00000-000');
            $('.cpf').mask('000.000.000-00');
            $('.birthday').mask('00/00/0000', optionsBirthday);
        };

        handleMasks();

        let TypeTransport = (function () {
            let type = $('#type_transport').val();
            let template = null;
            switch (type) {
                case '1': {
                    template =
                        '<label for="bus_line">Número da linha de ônibus</label>\n' +
                        '<input type="number" class="form-control form-control-alternative" name="bus_line"  id="bus_line"\n' +
                        'placeholder="Número da linha de ônibus">'
                }
            }
            if (template) {
                $('#show_type_transport > div').html(template)
                $('#show_type_transport').show()
            } else {
                $('#show_type_transport').hide()
            }
        });

        $(function () {
            // $("#leaders").select2();
        });
    </script>
@endpush

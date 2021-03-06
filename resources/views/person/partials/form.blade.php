<form role="form"  {{ $route ? 'method=POST action=' . $route : ''}} id="person_form">
    @if(!$isRequired or isset($companyUser))
        <input type="hidden" name="person_id" class="person_id" value="{{ $companyUser->person->id ?? '' }}">
    @endif

    @csrf
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="name">Nome Completo {{ $isRequired ? '*' : '' }}</label>
                <input type="text" class="form-control form-control-alternative" required id="name" name="name" value="{{ old('name') ?? $companyUser->person->name ?? '' }}"
                       placeholder="Nome Completo {{ $isRequired ? '(obrigatório)' : '' }}" {{ $isRequired ? 'required' : '' }}/>

            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group @error('email') has-danger @enderror">
                <label for="email">E-mail {{ $isRequired ? '*' : '' }}</label>
                <input type="email" class="form-control form-control-alternative @error('email') is-invalid @enderror" required id="email" name="email" value="{{ old('email') ?? $companyUser->email ?? '' }}"
                       placeholder="Email {{ $isRequired ? '(obrigatório)' : '' }}" {{ $isRequired ? 'required' : '' }}/>

                @error('email')
                    <div class="invalid-feedback" role="alert">
                        {{ $errors->first('email') }}
                    </div>
                @enderror
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3">
            <div class="form-group @error('phone') has-danger @enderror" >
                <label for="phone">Telefone (WhatsApp) {{ $isRequired ? '*' : '' }}</label>
                <input type="tel" class="form-control form-control-alternative phone @error('phone') is-invalid @enderror" name="phone" id="phone" value="{{ old('phone') ?? $companyUser->person->phone ?? '' }}"
                       data-mask="(00) 0000-00009" placeholder="Telefone (WhatsApp) {{ $isRequired ? '(obrigatório)' : '' }}" {{ $isRequired ? 'required' : '' }}/>

                @error('phone')
                    <div class="invalid-feedback" role="alert">
                        {{ $errors->first('phone') }}
                    </div>
                @enderror
            </div>
        </div>

        <div class="col-md-3">
             <div class="form-group @error('cpf') has-danger @enderror">
                <label for="cpf">CPF {{ $isRequired ? '*' : '' }}</label>
                <input type="text" class="form-control form-control-alternative cpf @error('cpf') is-invalid @enderror" name="cpf" id="cpf" value="{{ old('cpf') ?? old('cpf') ?? $companyUser->person->cpf ?? '' }}"
                    placeholder="CPF {{ $isRequired ? '(obrigatório)' : '' }}" {{ $isRequired ? 'required' : '' }}  data-mask="000.000.000-00">

                @error('cpf')
                    <div class="invalid-feedback" role="alert">
                        {{ $errors->first('cpf') }}
                    </div>
                @enderror
            </div>
        </div>

        <div class="col-md-3">
            <div class="form-group">
                <label for="sector">Setor</label>
                <select name="sector" id="sector" class="custom-select form-control-alternative">
                    <option value="">Setor</option>
                    @foreach($sectors as $sector)
                        @if(old('sector') == $sector)
                            <option value="{{ $sector }}" selected>{{ $sector }}</option>
                        @else
                            <option value="{{ $sector }}" {{ isset($companyUser->person->sector) ? ($companyUser->person->sector == $sector ? 'selected': '') : '' }}>{{ $sector }}</option>
                        @endif
                    @endforeach
                </select>
            </div>
        </div>

        <div class="col-md-3">
            <div class="form-group">
                <label for="cep">CEP {{ $isRequired ? '*' : '' }}</label>
                <input type="text" class="form-control form-control-alternative cep-person" {{ $isRequired ? 'required' : '' }} name="cep" value="{{ old('cep') ?? $companyUser->person->cep ?? '' }}"
                       data-mask="00000-000" id="cep" placeholder="CEP {{ $isRequired ? '(obrigatório)' : '' }}" {{ $isRequired ? 'required' : '' }}/>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
             <div class="form-group @error('birthday') has-danger @enderror">
                <label for="birthday">Data de Nascimento {{ $isRequired ? '*' : '' }}</label>
                <input class="form-control form-control-alternative birthday @error('birthday') is-invalid @enderror" type="text" id="birthday" name="birthday" value="{{ old('birthday') ?? $companyUser->person->birthday_formatted ?? '' }}"
                    placeholder="Data de Nascimento" {{ $isRequired ? 'required' : '' }}>

                 @error('birthday')
                    <div class="invalid-feedback" role="alert">
                        {{ $errors->first('birthday') }}
                    </div>
                @enderror
            </div>
        </div>

        <div class="col-md-2">
            <div class="form-group">
                <label>Gênero {{ $isRequired ? '*' : '' }}</label>
                <div class="custom-control custom-radio">
                    <input type="radio" id="masculino" {{ $isRequired ? 'required' : '' }} name="gender" value="M" class="custom-control-input"
                        {{ isset($companyUser->person->gender) ? ($companyUser->person->gender == 'M' ? 'checked': '') : '' }}
                        {{ old('gender') == 'M' ? 'checked' : ''  }}>
                    <label class="custom-control-label" for="masculino">Masculino</label>
                </div>
                <div class="custom-control custom-radio">
                    <input type="radio" id="feminino" {{ $isRequired ? 'required' : '' }} name="gender" value="F" class="custom-control-input"
                        {{ isset($companyUser->person->gender) ? ($companyUser->person->gender == 'F' ? 'checked': '') : '' }}
                        {{ old('gender') == 'F' ? 'checked' : ''  }}>
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
                    <label for="risk_group">Grupo de Risco</label>

                    @foreach($riskGroupsType as $k => $v)
                        @if($v !== "Não")
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" id="risk_group-{{ $loop->index }}" name="risk_groups[]" value="{{ $v }}"
                                       {{ (is_array(old('risk_groups')) and in_array($v, old('risk_groups'))) ? ' checked' : '' }} class="custom-control-input risk-groups">
                                <label class="custom-control-label" for="risk_group-{{ $loop->index }}">{{ $v }}</label>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>

            @if($isAdmin)
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="role">Perfil {{ $isRequired ? '*' : '' }}</label>
                        <select name="role" id="role" class="custom-select form-control-alternative role" {{ $isRequired ? 'required' : '' }}>
                            <option value="" disabled selected>Perfil {{ $isRequired ? '(obrigatório)' : '' }}</option>
                            @foreach($roles as $role)
                                @if(old('role') == $role->name)
                                    <option value="{{ $role->name }}" selected>{{ $role->name }}</option>
                                @else
                                    <option value="{{ $role->name }}" {{ isset($companyUser->roles[0]) ? ($companyUser->roles[0]->name == $role->name ? 'selected': '') : '' }}>{{ $role->name }}</option>
                                @endif
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
                            @if(old('leader') == $l->id)
                                <option value="{{ $l->id }}" selected>{{ $l->name }}</option>
                            @else
                                <option value="{{ $l->id }}" {{ isset($leader) ? ($leader == $l->id ? 'selected': '') : '' }}>{{ $l->name }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    @endif

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
                    $option = $('.risk-groups:checkbox[value="Acima de 60 anos"]');

                if(calculateAge(newDate) >= 60) {
                    $option.prop("checked", true);
                } else {
                    $option.prop("checked", false);
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
            $('.birthday').mask('00/00/0000', optionsBirthday);
            $('.cpf').mask('000.000.000-00');
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
    </script>
@endpush
